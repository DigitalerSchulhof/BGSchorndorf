<?php
set_time_limit(0);
set_include_path(dirname(__FILE__)."/../../");
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/check.php");
include_once("php/allgemein/funktionen/sql.php");

define('HOST_NAME', $CMS_DOMAIN);
define('PORT', "12345");
$null = NULL;

$dbs = cms_verbinden("s");

$socketHandler = new SocketHandler();
$chat = new Chat();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, 0, PORT);
socket_listen($socket);

/* Socket Tech Magie */
$clientSockets = array($socket);

// Nicht authentifizierte Sockets
$unbekannt = array();

// Anonyme Funktionen für die Übersicht
/*
*	BENÖTIGEN PHP 7+
*/
while (true) {
	// Authentifizierenden Timeout prüfen
	foreach($unbekannt as $k => $v) {
		if(time() > $v["verbunden"] + 60) {	// 60 Sekunden für Authentifikation
			$index = array_search($v["socket"], $clientSockets);
			socket_close($v["socket"]);
			unset($clientSockets[$index]);
			unset($unbekannt[$k]);
		}
	}

	// Ohne reference kopieren
	$updatePruefenSockets = $clientSockets;
	// Warten, bis sich was getan hat
	socket_select($updatePruefenSockets, $null, $null, 0, 10);
	// Neue Aktion im Socket oder so - Ich weiß nicht ganz.  -  [Neuer Client]
	if (in_array($socket, $updatePruefenSockets)) {
		// Neuer Client Socket
		$neuerClient = socket_accept($socket);
		// Neuen Client behandeln
		(function ($clientSocket) use ($updatePruefenSockets, $clientSockets, $unbekannt){
			// Header ausm Socket lesen (Nicht mehr als 1,024 Bytes?)
			$header = socket_read($clientSocket, 1024);
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
			$clientSockets[] = $clientSocket;
			$unbekannt[] = array("socket" => $clientSocket, "verbunden" => time());

			// Handshake machen
			$socketHandler->doHandshake($header, $clientSocket, HOST_NAME, PORT);

			$socketHandler->send(json_encode(array("status" => "0", "nachricht" => "Bereit für Authentifikation")), $neuerClient);
			// Hax
			$index = array_search($socket, $updatePruefenSockets);
			unset($updatePruefenSockets[$index]);
		})($neuerClient);
	}

	foreach ($updatePruefenSockets as $clientSocket) {
		// Daten lesen (Max 1,024 Bytes?)
		while(socket_recv($clientSocket, $socketData, 1024, 0) >= 1){
			// Daten entschlüsseln
			$socketMessage = $socketHandler->unseal($socketData);
			// TODO: An andere Weiterleiten, in db speichern
			$nachricht = json_decode($socketMessage, true);
			if($nachricht == null)
				break 2;
			switch ($nachricht["status"]) {
				case "0":
					$g = $nachricht["g"];
					$gid = $nachricht["gid"];
					$sessid = $nachricht["sessid"];
					if(is_null($g) || is_null($gid) || is_null($sessid)) {
						foreach($unbekannt as $i => $v) {
							if($v["socket"] == $clientSocket) {
								unset($unbekannt[$i]);
								$socketHandler->send(json_encode(array("status" => "-1", "nachricht" => "FEHLER")), $clientSocket);
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
					if(isset($verbunden[$g][$gid][$id]))
						$fehler = true;
					if(!$fehler) {
						// Berechtigung prüfen
						$verbunden[$g][$gid][$id] = $socket;
						$socketHandler->send(json_encode(array("status" => "1", "nachricht" => "Authentifikation erfolgreich")), $clientSocket);
					} else {
						$socketHandler->send(json_encode(array("status" => "-1", "nachricht" => "FEHLER")), $clientSocket);
					}
					break;
				default:
					// Neue Nachricht
					break;
			}
			// Aus beiden Schleifen ausbrechen
			break 2;
		}
		// socket_recv hat keine Daten erkannt?

		// Prüfen ob noch da
		$socketData = @socket_read($clientSocket, 1024, PHP_NORMAL_READ);
		if ($socketData === false) {
			// Aus Client Liste entfernen
			$kickClientIndex = array_search($clientSocket, $clientSockets);
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

class Chat {

}
?>
