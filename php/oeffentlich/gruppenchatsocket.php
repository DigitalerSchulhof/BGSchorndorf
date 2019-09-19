<?php
set_time_limit(0);
set_include_path(dirname(__FILE__)."/../../");
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/check.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("php/allgemein/funktionen/sql.php");

define('HOST_NAME', $CMS_DOMAIN);
define('PORT', "12345");
$null = NULL;

$limit = 20;

$dbs = cms_verbinden("s");

$socketHandler = new SocketHandler();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 0, PORT);
socket_listen($socket);

/* Socket Tech Magie */
$clientSockets = array($socket);

// Nicht authentifizierte Sockets
$unbekannt = array();

while (true) {
	foreach($unbekannt as $k => $v) {
		if(time() > $v["verbunden"] + 60) {	// 60 Sekunden für Authentifikation
			$index = array_search($v["socket"], $clientSockets);
			socket_close($v["socket"]);
			unset($clientSockets[$index]);
			unset($unbekannt[$k]);
		}
	}

	$updatePruefenSockets = $clientSockets;
	socket_select($updatePruefenSockets, $null, $null, 0, 10);

	if (in_array($socket, $updatePruefenSockets)) {
		$neuerClient = socket_accept($socket);

		// Header ausm Socket lesen (Nicht mehr als 1,024 Bytes?)
		$header = socket_read($neuerClient, 1024);
		// Session laden
		$headers = array();
		$lines = preg_split("/\r\n/", $header);
		foreach($lines as $line) {
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}
		$clientSockets[] = $neuerClient;
		$unbekannt[] = array("socket" => $neuerClient, "verbunden" => time());
		// Handshake machen
		$socketHandler->doHandshake($header, $neuerClient, HOST_NAME, PORT);
		nachricht($neuerClient, "0", "Bereit für Authentifikation");
		// Hax
		$index = array_search($socket, $updatePruefenSockets);
		unset($updatePruefenSockets[$index]);
	}

	foreach ($updatePruefenSockets as $clientSocket) {
		while(socket_recv($clientSocket, $socketData, 1024, 0) >= 1){
			$socketMessage = $socketHandler->unseal($socketData);
			$nachricht = json_decode($socketMessage, true);

			if($nachricht == null)
				break 2;
			switch ($nachricht["status"]) {
				case "0":
					// Authentifikation
					$g = $nachricht["g"];
					$gid = $nachricht["gid"];
					$sessid = $nachricht["sessid"];
					if(is_null($g) || is_null($gid) || is_null($sessid)) {
						foreach($unbekannt as $i => $v) {
							if($v["socket"] == $clientSocket) {
								unset($unbekannt[$i]);
								fehler($clientSocket, "-1");
								break 2;
							}
						}
					}

					// Daten aus db lesen
					$sql = "SELECT id FROM nutzerkonten WHERE sessionid = ?";
					$sql = $dbs->prepare($sql);
					$sql->bind_param("s", $sessid);
					$sql->bind_result($id);
					$fehler = false;
					if(!$sql->execute() || !$sql->fetch()) {
						$fehler = true;
					}
					$sql->close();
					foreach($unbekannt as $i => $v) {
						if($v["socket"] == $clientSocket) {
							unset($unbekannt[$i]);
						}
					}
					if(!cms_valide_gruppe($g))
						$fehler = true;
					if(!$fehler) {
						// Berechtigung prüfen
						$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);
						$gk = cms_textzudb($g);
						$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
						$sql->bind_param("i", $gid);
						$sql->bind_result($chataktiv);
						$sql->execute();
						if(!$sql->fetch())
							fehler($clientSocket, "-1");
						$sql->close();

						if(!$rechte["mitglied"] || !$chataktiv) {
							fehler($clientSocket, "-2", "Nicht berechtigt");
						}
						$verbunden[$g][$gid][$id][] = $clientSocket;
						$socketInfos[intval($clientSocket)] = array("id" => $id, "g" => $g, "gid" => $gid);
						nachricht($clientSocket, "1", "Authentifikation erfolgreich");
						// Erfolgreich eingetragen! :D
						// Was für ne Arbeit
						"😅";

						// Daten prüfen
						$gebannt = 1;
						// Stummschaltung prüfen
						$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ? AND chatbannbis = 0";
						$sql = $dbs->prepare($sql);
						$sql->bind_param("ii", $id, $gid);
						$sql->bind_result($gebannt);
						$sql->execute();
						$sql->fetch();
						$sql->close();
						$gebannt = !$gebannt;		// Umkehrung, weil bei abgelaufener Banndauer (bannbis == 0) 1 gegeben wird.

						$daten = array();

						$daten["nachrichten"] = array();
						$sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL') as inhalt, chat.meldestatus, chat.loeschstatus, AES_DECRYPT(sender.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.titel, '$CMS_SCHLUESSEL'), meldungen.melder IS NOT NULL FROM $gk"."chat as chat JOIN personen as sender ON sender.id = chat.person LEFT OUTER JOIN $gk"."chatmeldungen as meldungen ON meldungen.nachricht = chat.id AND meldungen.melder = ? WHERE chat.gruppe = $gid AND chat.fertig = 1 ORDER BY chat.id DESC LIMIT ".($limit+1);
						$sql = $dbs->prepare($sql);
						$sql->bind_param("i", $id);
						$sql->bind_result($nid, $p, $d, $i, $m, $gl, $v, $n, $t, $sm);
						$sql->execute();
						while($sql->fetch())
							$daten["nachrichten"][] = array(
								"id" => $nid,
								"person" => $p,
								"tag" => cms_tagnamekomplett(date("w", $d)) . ", den " . date("d", $d) . " " . cms_monatsnamekomplett(date("n", $d)),
								"inhalt" => $gl ? "<img src=\"res/icons/klein/geloescht.png\" height=\"10\"> Vom Administrator gelöscht" : $i,
								"gemeldet" => $m,
								"selbstgemeldet" => $sm,
								"name" => cms_generiere_anzeigename($v, $n, $t),
								"geloescht" => $gl,
								"eigen" => $p == $nid);

						$daten["nachrichtloeschen"] = $rechte["nachrichtloeschen"];
						$daten["nutzerstummschalten"] = $rechte["nutzerstummschalten"];
						$daten["stummgeschalten"] = $gebannt;
						$daten["leer"] = !count($daten["nachrichten"]);
						$daten["mehrladen"] = count($daten["nachrichten"]) > $limit;

						array_pop($daten["nachrichten"]);	// Es wird Eine zu viel in der SQL geladen, um zu prüfen, ob noch Nachrichten nachzuladen sind (Anzahl an Nachrchten > $limit)

						senden($clientSocket, "2", $daten);
					} else {
						fehler($clientSocket, "-1");
					}
					break;
				default:

					break;
			}
			break 2;
		}
		$socketData = @socket_read($clientSocket, 1024, PHP_NORMAL_READ);
		if ($socketData === false) {
			$kickClientIndex = array_search($clientSocket, $clientSockets);
			$infos = $socketInfos[intval($clientSocket)];
			if(($index = array_search($clientSocket, $verbunden[$infos["g"]][$infos["gid"]][$infos["id"]])) !== false)
				unset($verbunden[$infos["g"]][$infos["gid"]][$infos["id"]][$index]);
			unset($verbunden[$infos["g"]][$infos["gid"]][$infos["id"]]);
			unset($socketInfos[intval($clientSocket)]);
			unset($clientSockets[$kickClientIndex]);
		}
	}
}
// Programm vorbei, Socket schließen
socket_close($socket);

class SocketHandler {
	function send($message, $socket) {
		$message = $this->seal($message);
		$messageLength = strlen($message);
		@socket_write($socket,$message,$messageLength);
		return true;
	}

	function unseal($socketData) {
		$length = ord($socketData[1]) & 127;
		if($length == 126) {
			$masks = substr($socketData, 4, 4);
			$data = substr($socketData, 8);
		}
		elseif($length == 127) {
			$masks = substr($socketData, 10, 4);
			$data = substr($socketData, 14);
		}
		else {
			$masks = substr($socketData, 2, 4);
			$data = substr($socketData, 6);
		}
		$socketData = "";
		for ($i = 0; $i < strlen($data); ++$i) {
			$socketData .= $data[$i] ^ $masks[$i%4];
		}
		return $socketData;
	}

	function seal($socketData) {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($socketData);

		if($length <= 125)
			$header = pack('CC', $b1, $length);
		elseif($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$socketData;
	}

	function doHandshake($received_header,$client_socket_resource, $host_name, $port) {
		$headers = array();
		$lines = preg_split("/\r\n/", $received_header);
		foreach($lines as $line) {
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}
		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		$buffer  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host_name\r\n" .
		"WebSocket-Location: ws://$host_name:$port/schulhof/gruppenchat/socket.php\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";

		socket_write($client_socket_resource,$buffer,strlen($buffer));
	}
}

function fehler($socket, $status, $nachricht = "Ein unbekannter Fehler ist aufgetreten") {
	global $socketHandler;
	return $socketHandler->send(json_encode(array("status" => $status, "nachricht" => $nachricht)), $socket);
}

function nachricht($socket, $status, $nachricht) {
	global $socketHandler;
	return $socketHandler->send(json_encode(array("status" => $status, "nachricht" => $nachricht)), $socket);
}

function senden($socket, $status, $daten) {
	global $socketHandler;
	if(is_array($daten))
		$daten["status"] = $status;
	else
		$daten = array("status" => $status, "daten" => $daten);
	return $socketHandler->send(json_encode($daten), $socket);
}
?>
