<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();
// Variablen einlesen, falls übergeben
postLesen("base", "lehrer", "vpn", "hosts", "hostl", "socketip", "socketport", "offizielle_version");
$github_benutzer = "";
$github_repo = "";
$github_oauth = "";
postLesen("github_benutzer", "github_repo", "github_oauth", false);

if (strlen($base) == 0) 										{echo "FEHLER";exit;}
if (strlen($lehrer) == 0) 									{echo "FEHLER";exit;}
if (!cms_check_toggle($vpn)) 								{echo "FEHLER";exit;}
if (strlen($hosts) == 0) 										{echo "FEHLER";exit;}
if (strlen($hostl) == 0) 										{echo "FEHLER";exit;}
if (!cms_check_toggle($offizielle_version)) {echo "FEHLER";exit;}
if($offizielle_version == 0) {
	if(!strlen($github_benutzer))							{echo "FEHLER";exit;}
	if(!strlen($github_repo))									{echo "FEHLER";exit;}
}
if (cms_angemeldet() && cms_r("technik.server.netze")) {
	// Übrige Werte in die Datenbank schreiben
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	$inhalt = "Netze Basisverzeichnis";
	$sql->bind_param("ss", $base, $inhalt); $sql->execute();
	$inhalt = "Netze Lehrerserver";
	$sql->bind_param("ss", $lehrer, $inhalt); $sql->execute();
	$inhalt = "Netze VPN-Anleitung";
	$sql->bind_param("ss", $vpn, $inhalt); $sql->execute();
	$inhalt = "Hosting Schülernetz";
	$sql->bind_param("ss", $hosts, $inhalt); $sql->execute();
	$inhalt = "Hosting Lehrernetz";
	$sql->bind_param("ss", $hostl, $inhalt); $sql->execute();
	$inhalt = "Netze Socket-IP";
	$sql->bind_param("ss", $socketip, $inhalt); $sql->execute();
	$inhalt = "Netze Socket-Port";
	$sql->bind_param("ss", $socketport, $inhalt); $sql->execute();
	$inhalt = "Netze Ofizielle Version";
	$sql->bind_param("ss", $offizielle_version, $inhalt); $sql->execute();
	$inhalt = "Netze GitHub Benutzer";
	$sql->bind_param("ss", $github_benutzer, $inhalt); $sql->execute();
	$inhalt = "Netze GitHub Repository";
	$sql->bind_param("ss", $github_repo, $inhalt); $sql->execute();
	$inhalt = "Netze GitHub OAuth";
	$sql->bind_param("ss", $github_oauth, $inhalt); $sql->execute();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
