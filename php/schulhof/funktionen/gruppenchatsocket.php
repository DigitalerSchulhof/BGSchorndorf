<?php
set_time_limit(0);
set_include_path(dirname(__FILE__)."/../../../");
date_default_timezone_set("CET");
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/check.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("php/allgemein/funktionen/sql.php");

define('HOST_NAME', $CMS_SOCKET_IP);
define('PORT', 			$CMS_SOCKET_PORT);
$null = NULL;

$debug = false;
$limit = 20;
$nachlimit = 20;

/*
* Server->Client
*  Generell:
*   -2: Berechtigung
*   -1: Ungültige Anfrage
*	 Authentifikation:
*   -3: Fehler, erneut versuchen
*    0: Authentifikation kann beginnen
*    1: Authentifikation erfolgreich, Daten folgen
*    2: Daten (Nachrichten, Rechte etc.)
*  Betrieb:
*    3:	Neue Nachricht (ausgehend)
*    4: Nachricht gelöscht
*		 5: Stummschaltung
*		 6: Antwort an c->s#2
*		 7: Antwort an c->s#4
*
*	Client->Server
*  Authentifikation:
*    0: Antwort an s->c#0
*  Betrieb:
*    1: Neue Nachricht (eingehend)
*    2: Nachrichten Nachladen
*		 3: Nachricht löschen
*		 4: Nutzer stummschalten
*/
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
echo "Starte Socket auf ".HOST_NAME.":".PORT."\n\n";
while (true) {
	$updatePruefenSockets = $clientSockets;
	@socket_select($updatePruefenSockets, $null, $null, 0, 10);

	if (in_array($socket, $updatePruefenSockets)) {
		$neuerClient = socket_accept($socket);

		// Header ausm Socket lesen (Nicht mehr als 1,024 Bytes?)
		$header = socket_read($neuerClient, 1024);

		$clientSockets[] = $neuerClient;
		$unbekannt[] = array("socket" => $neuerClient, "verbunden" => time());
		// Handshake machen
		$socketHandler->doHandshake($header, $neuerClient, HOST_NAME, PORT);
		nachricht($neuerClient, "0", "Bereit für Authentifikation");
		// Hax
		$index = array_search($socket, $updatePruefenSockets);
		unset($updatePruefenSockets[$index]);
		debug("Neuer Client verbunden");
	}

	foreach ($updatePruefenSockets as $clientSocket) {
		while(socket_recv($clientSocket, $socketData, 1024, 0) >= 1){
			$socketMessage = $socketHandler->unseal($socketData);
			debug("Neue Nachricht: »{$socketMessage}«");
			$nachricht = json_decode($socketMessage, true);

			if($nachricht == null || !isset($nachricht["status"]))
				break 2;

			switch ($nachricht["status"]) {
				case "0":
					authentifizieren($nachricht, $clientSocket) || fehler($clientSocket, "-3");
					break;
				case "1":
					nachrichtSenden($nachricht, $clientSocket) || fehler($clientSocket, "-1");
					break;
				case "2":
					nachrichtenNachladen($nachricht, $clientSocket) || fehler($clientSocket, "-1");
					break;
				case "3":
					nachrichtLoeschen($nachricht, $clientSocket) || fehler($clientSocket, "-1");
					break;
				case "4":
					chatterBannen($nachricht, $clientSocket) || fehler($clientSocket, "-1");
					break;
				default:
					fehler($clientSocket, "-1");
					break;
			}
			break 2;
		}

		$socketData = @socket_read($clientSocket, 1024, PHP_NORMAL_READ);
		if ($socketData === false) {
			$kickClientIndex = array_search($clientSocket, $clientSockets);
			if(isset($socketInfos[intval($clientSocket)])) {
				$infos = $socketInfos[intval($clientSocket)];
				isset($infos["id"]) ? debug("Nutzer »".$infos["id"]."« getrennt") : debug("Client getrennt");
				if(($index = array_search($clientSocket, $verbunden[$infos["g"]][$infos["gid"]][$infos["id"]])) !== false)
					unset($verbunden[$infos["g"]][$infos["gid"]][$infos["id"]][$index]);
				unset($verbunden[$infos["g"]][$infos["gid"]][$infos["id"]]);
				unset($socketInfos[intval($clientSocket)]);
			}
			unset($clientSockets[$kickClientIndex]);
		}
	}

	foreach($unbekannt as $k => $v) {
		if(time() > $v["verbunden"] + 60) {	// 60 Sekunden für Authentifikation
			debug("Timeout bei Authentifikation");
			clientSchmeissen($v["socket"]);
			unset($unbekannt[$k]);
		}
	}
}
echo "Schließe Socket\n";
// Programm irgendwie gestorben, Socket schließen
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
		"WebSocket-Location: wss://$host_name:$port/schulhof/gruppenchat/socket.php\r\n".
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

function clientDaten($clientSocket) {
	global $socketInfos;
	$infos = $socketInfos[intval($clientSocket)] ?? false;
	if($infos)
		return array("g" => $infos["g"], "gid" => $infos["gid"], "id" => $infos["id"]);
	return null;
}

function clientSchmeissen($clientSocket) {
	global $clientSockets, $verbunden;
	$index = array_search($clientSocket, $clientSockets);
	unset($clientSockets[$index]);

	@socket_close($clientSocket);

	if(($daten = clientDaten($clientSocket)) !== null) {
		unset($verbunden[$infos["g"]][$infos["gid"]][$infos["id"]]);
		debug("War g: »{$infos['g']}« - gid: »{$infos['gid']}« - id: »{$infos['id']}«");
	} else {
		debug("War nicht authentifiziert");
	}
	debug("Client geschmissen");
}

function anGruppeSenden($g, $gid, $d, $i, $id, $name, $nid, $cid) {
	global $verbunden;
	if(!isset($verbunden[$g][$gid]))
		return;
	debug("Sende Nachricht an »$g#{$gid}«");
	foreach($verbunden[$g][$gid] as $nuid => $sockets) {
		foreach($sockets as $k => $s) {
			debug("Nachricht an Nutzer »$nuid#${k}« gesandt");
			$n = array(
				"id" => $nid,
				"person" => $id,
				"tag" => cms_tagnamekomplett(date("w", $d)) . ", den " . date("d", $d) . " " . cms_monatsnamekomplett(date("n", $d)),
				"zeit" => date("H:i", $d),
				"inhalt" => $i,
				"name" => $name,
				"eigen" => $nuid == $id);
			if($n["eigen"])
				$n["cid"] = $cid;
			senden($s, "3", $n);
		}
	}
}

function debug($string) {
	global $debug;
	if($debug)
		echo date("H:i:s")." - ".$string."\n";
}

function anGruppeSendenR($g, $gid, $status, $daten) {
	global $verbunden;
	if(!isset($verbunden[$g][$gid]))
		return;
	foreach($verbunden[$g][$gid] as $nuid => $sockets) {
		foreach($sockets as $k => $s) {
			senden($s, $status, $daten);
		}
	}
}

function authentifizieren($nachricht, $socket) {
	global $unbekannt, $verbunden, $socketInfos, $dbs, $CMS_SCHLUESSEL, $limit;
	// Übermittelte Daten lesen
	$g = $nachricht["g"];
	$gid = $nachricht["gid"];
	$sessid = $nachricht["sessid"];

	debug("Starte Authentifikation");
	debug("g: »{$g}« - gid: »{$gid}« - sessid: »{$sessid}«");

	$angemeldet = false;
	foreach($unbekannt as $i => $v) {
		if($v["socket"] == $socket) {
			$angemeldet = true;
		}
	}
	if(!$angemeldet)
		return false;
	if(is_null($g) || is_null($gid) || is_null($sessid))
		return false;
	if(!cms_valide_gruppe($g))
		return false;

	// Daten aus db lesen
	$sql = "SELECT id FROM nutzerkonten WHERE sessionid = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("s", $sessid);
	$sql->bind_result($id);
	if(!$sql->execute() || !$sql->fetch()) {
		debug("Authentifikation fehlgeschlagen!");
		return false;
	}
	$sql->close();
	foreach($unbekannt as $i => $v) {
		if($v["socket"] == $socket) {
			unset($unbekannt[$i]);
		}
	}

	// Berechtigung prüfen
	$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);

	$gk = cms_textzudb($g);

	$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
	$sql->bind_param("i", $gid);
	$sql->bind_result($chataktiv);
	$sql->execute();
	if(!$sql->fetch())
		return false;
	$sql->close();

	if(!$rechte["mitglied"] || !$chataktiv) {
		fehler($socket, "-2", "Nicht berechtigt");
		return true;
	}

	// Socket->Gruppe, Id einspeichern
	$verbunden[$g][$gid][$id][] = $socket;
	$socketInfos[intval($socket)] = array("id" => $id, "g" => $g, "gid" => $gid);

	nachricht($socket, "1", "Authentifikation erfolgreich");

	// Daten prüfen

	// Stummschaltung prüfen
	$sql = "SELECT chatbannbis FROM $gk"."mitglieder WHERE person = ? AND gruppe = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $id, $gid);
	$sql->bind_result($bannbis);
	$sql->execute();
	$sql->fetch();
	$sql->close();

	$daten = array();
	$daten["nachrichten"] = array();
	$sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL') as inhalt, chat.meldestatus, chat.loeschstatus, AES_DECRYPT(sender.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.titel, '$CMS_SCHLUESSEL'), meldungen.melder IS NOT NULL FROM $gk"."chat as chat JOIN personen as sender ON sender.id = chat.person LEFT OUTER JOIN $gk"."chatmeldungen as meldungen ON meldungen.nachricht = chat.id AND meldungen.melder = ? WHERE chat.gruppe = $gid AND chat.fertig = 1 ORDER BY chat.id DESC LIMIT ".($limit+1);
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $id);
	$sql->bind_result($nid, $p, $d, $i, $m, $ls, $v, $n, $t, $sm);
	$sql->execute();
	while($sql->fetch()) {
		$daten["nachrichten"][] = array(
			"id" => $nid,
			"person" => $p,
			"tag" => cms_tagnamekomplett(date("w", $d)) . ", den " . date("d", $d) . " " . cms_monatsnamekomplett(date("n", $d)),
			"zeit" => date("H:i", $d),
			"inhalt" => $ls ? "<img src=\"res/icons/klein/geloescht.png\" height=\"10\"> Vom Administrator gelöscht" : $i,
			"selbstgemeldet" => $sm,
			"name" => cms_generiere_anzeigename($v, $n, $t),
			"geloescht" => !!$ls,	// (bool)
			"eigen" => $p == $id);
	}
	$daten["loeschen"] = $rechte["nachrichtloeschen"];
	$daten["stummschalten"] = $rechte["nutzerstummschalten"];
	$daten["stumm"] = $bannbis;
	$daten["leer"] = !count($daten["nachrichten"]);
	$daten["mehr"] = false;
	if(count($daten["nachrichten"]) > $limit) {
		$daten["mehr"] = true;
		array_pop($daten["nachrichten"]);	// Es wird Eine zu viel in der SQL geladen, um zu prüfen, ob noch Nachrichten nachzuladen sind (Anzahl an Nachrchten > $limit)
	}
	$daten["nachrichten"] = array_reverse($daten["nachrichten"]);
	senden($socket, "2", $daten);

	debug("Authentifikation für Nutzer »{$id}« erfolgreich");

	return true;
}

function nachrichtSenden($nachricht, $socket) {
	global $dbs, $CMS_SCHLUESSEL;
	if(($daten = clientDaten($socket)) === null) {
		clientSchmeissen($socket);
		return true;
	}
	$inhalt = $nachricht["nachricht"];
	$id = $daten["id"];
	$g = $daten["g"];
	$gk = cms_textzudb($g);
	$gid = $daten["gid"];

	debug("Eingehende Nachricht");
	debug("g: »{$g}« - gid: »{$gid}« - id: »{$id}«");

	$inhalt = htmlentities($inhalt);

	// Stummschaltung aktualisieren
	$sql = "UPDATE $gk"."mitglieder SET chatbannbis = 0 WHERE chatbannbis < ".time();
	$sql = $dbs->prepare($sql);
	$sql->execute();

	$gebannt = 1;
	// Stummschaltung prüfen
	$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ? AND (chatbannbis < ".time()." OR chatbannbis IS NULL)";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $id, $gid);
	$sql->bind_result($gebannt);
	$sql->execute();
	$sql->fetch();
	$sql->close();
	$gebannt = !$gebannt;		// Umkehrung, weil bei abgelaufener Banndauer (bannbis == 0) 1 gegeben wird.

	$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $id, $gid);
	$sql->bind_result($istda);
	$sql->execute();
	$sql->fetch();
	$sql->close();
	$gebannt = $gebannt && $istda;

	// Rechtecheck
	$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);
	$gk = cms_textzudb($g);
	$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
	$sql->bind_param("i", $gid);
	$sql->bind_result($chataktiv);
	$sql->execute();
	if(!$sql->fetch())
		return false;
	$sql->close();

	if(!$rechte["mitglied"] || !$rechte["chatten"] || $gebannt || !$chataktiv) {
		fehler($socket, "-2", "Nicht berechtigt");
		return true;
	}

	$nid = cms_generiere_kleinste_id($gk."chat", "s", $id);
	$sql = "UPDATE $gk"."chat SET gruppe = ?, person = ?, datum = ?, inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), meldestatus = 0, loeschstatus = 0, fertig = 1 WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$jetzt = time();
	$sql->bind_param("iiisi", $gid, $id, $jetzt, $inhalt, $nid);
	$sql->execute();

	$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $id);
	$sql->bind_result($v, $n, $t);
	$sql->execute();
	$sql->fetch();
	$sql->close();

	anGruppeSenden($g, $gid, $jetzt, $inhalt, $id, cms_generiere_anzeigename($v, $n, $t), $nid, $nachricht["cid"]);
	debug("Eingehende Nachricht ok");
	return true;
}

function nachrichtenNachladen($nachricht, $socket) {
	global $dbs, $CMS_SCHLUESSEL, $nachlimit;
	if(($daten = clientDaten($socket)) === null) {
		clientSchmeissen($socket);
		return true;
	}
	if(!isset($nachricht["lid"]) || !cms_check_ganzzahl($nachricht["lid"]))
		return false;

	$lid = $nachricht["lid"];

	$id = $daten["id"];
	$g = $daten["g"];
	$gk = cms_textzudb($g);
	$gid = $daten["gid"];

	debug("Nachrichten Nachladen");
	debug("g: »{$g}« - gid: »{$gid}« - id: »{$id}« - lid: »{$lid}«");

	// Rechtecheck
	$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);
	$gk = cms_textzudb($g);
	$sql = $dbs->prepare("SELECT chataktiv FROM $gk WHERE id = ?");
	$sql->bind_param("i", $gid);
	$sql->bind_result($chataktiv);
	$sql->execute();
	if(!$sql->fetch())
		return false;
	$sql->close();

	if(!$rechte["mitglied"] || !$chataktiv) {
		fehler($socket, "-2", "Nicht berechtigt");
		return true;
	}

	$daten = array();
	$daten["nachrichten"] = array();
	$sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL') as inhalt, chat.meldestatus, chat.loeschstatus, AES_DECRYPT(sender.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.titel, '$CMS_SCHLUESSEL'), meldungen.melder IS NOT NULL FROM $gk"."chat as chat JOIN personen as sender ON sender.id = chat.person LEFT OUTER JOIN $gk"."chatmeldungen as meldungen ON meldungen.nachricht = chat.id AND meldungen.melder = ? WHERE chat.gruppe = $gid AND chat.fertig = 1 AND chat.id < $lid ORDER BY chat.id DESC LIMIT ".($nachlimit+1);
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $id);
	$sql->bind_result($nid, $p, $d, $i, $m, $ls, $v, $n, $t, $sm);
	$sql->execute();
	while($sql->fetch())
		$daten["nachrichten"][] = array(
			"id" => $nid,
			"person" => $p,
			"tag" => cms_tagnamekomplett(date("w", $d)) . ", den " . date("d", $d) . " " . cms_monatsnamekomplett(date("n", $d)),
			"zeit" => date("H:i", $d),
			"inhalt" => $ls ? "<img src=\"res/icons/klein/geloescht.png\" height=\"10\"> Vom Administrator gelöscht" : $i,
			"selbstgemeldet" => $sm,
			"name" => cms_generiere_anzeigename($v, $n, $t),
			"geloescht" => !!$ls,	// (bool)
			"eigen" => $p == $id);

	$daten["mehr"] = count($daten["nachrichten"]) > $nachlimit;
	array_pop($daten["nachrichten"]);	// Es wird Eine zu viel in der SQL geladen, um zu prüfen, ob noch Nachrichten nachzuladen sind (Anzahl an Nachrchten > $nachlimit)

	senden($socket, "6", $daten);

	debug("Nachrichten nachgeladen\n");
	return true;
}

function nachrichtLoeschen($nachricht, $socket) {
	global $dbs, $CMS_SCHLUESSEL;
	if(($daten = clientDaten($socket)) === null) {
		clientSchmeissen($socket);
		return true;
	}
	if(!isset($nachricht["lid"]) || !cms_check_ganzzahl($nachricht["lid"]))
		return false;

	$lid = $nachricht["lid"];
	if(!cms_check_ganzzahl($lid))
		return false;

	$id = $daten["id"];
	$g = $daten["g"];
	$gk = cms_textzudb($g);
	$gid = $daten["gid"];

	debug("Nachricht löschen");
	debug("g: »{$g}« - gid: »{$gid}« - id: »{$id}« - lid: »{$lid}«");

	// Rechtecheck
	$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);
	$gk = cms_textzudb($g);

	if(!$rechte["nachrichtloeschen"]) {
		fehler($socket, "-2", "Nicht berechtigt");
		return true;
	}

	$sql = "UPDATE $gk"."chat SET loeschstatus = 2 WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $lid);
	$sql->execute();

	$sql = "DELETE FROM $gk"."chatmeldungen WHERE nachricht = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $lid);
	$sql->execute();

	anGruppeSendenR($g, $gid, "4", array("inhalt" => "<img src=\"res/icons/klein/geloescht.png\" height=\"10\"> Vom Administrator gelöscht", "lid" => $lid));
	debug("Nachricht gelöscht");
	return true;
}

function chatterBannen($nachricht, $socket) {
	global $dbs, $CMS_SCHLUESSEL, $verbunden;
	if(($daten = clientDaten($socket)) === null) {
		clientSchmeissen($socket);
		return true;
	}
	if(!isset($nachricht["lid"]) || !cms_check_ganzzahl($nachricht["lid"]))
		return false;
	$nid = $nachricht["lid"];

	$bannbis = -1;
	if(isset($nachricht["bannbis"]) && cms_check_ganzzahl($nachricht["bannbis"], 0))
		$bannbis = $nachricht["bannbis"];
	if(isset($nachricht["banndauer"]) && cms_check_ganzzahl($nachricht["banndauer"], 0))
		$bannbis = time() + $nachricht["banndauer"];

	if($bannbis == -1)
		return false;

	$id = $daten["id"];
	$g = $daten["g"];
	$gk = cms_textzudb($g);
	$gid = $daten["gid"];

	debug("Nutzer stummschalten");
	debug("g: »{$g}« - gid: »{$gid}« - id: »{$id}« - nid: »{$nid}« - bannbis: »{$bannbis}«");

	// Rechtecheck
	$rechte = cms_gruppenrechte_laden($dbs, $g, $gid, $id);
	$gk = cms_textzudb($g);

	if(!$rechte["nutzerstummschalten"]) {
		fehler($socket, "-2", "Nicht berechtigt");
		return true;
	}

	$uid = -1;
  // Sender
  $sql = "SELECT person FROM $gk"."chat WHERE id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $nid);
  $sql->bind_result($uid);
  if(!$sql->execute() || !$sql->fetch() || $uid == -1)
    return false;

	$sql = "UPDATE $gk"."mitglieder SET chatbannbis = ?, chatbannvon = ? WHERE person = ? AND gruppe = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("iiii", $bannbis, $id, $uid, $gid);
	$sql->execute();

	if(isset($verbunden[$g][$gid][$id]))
		foreach($verbunden[$g][$gid][$id] as $k => $v) {
			senden($v, "7", array());
		}

	if(isset($verbunden[$g][$gid][$uid]))
		foreach($verbunden[$g][$gid][$uid] as $k => $v) {
			senden($v, "5", array("stumm" => $bannbis));
		}

	debug("Nutzer stummgeschalten");

	return true;
}
?>
