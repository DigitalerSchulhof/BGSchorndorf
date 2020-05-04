<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['base'])) 					{$base = $_POST['base'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['lehrer'])) 				{$lehrer = $_POST['lehrer'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['vpn'])) 					{$vpn = $_POST['vpn'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['hosts'])) 				{$hosts = $_POST['hosts'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['hostl'])) 				{$hostl = $_POST['hostl'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['socketip'])) 			{$socketip = $_POST['socketip'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['socketport'])) 		{$socketport = $_POST['socketport'];} 		else {echo "FEHLER";exit;}
if (isset($_POST['githubsecret']))	{$githubsecret = $_POST['githubsecret'];} else {echo "FEHLER";exit;}

if (strlen($base) == 0) 				{echo "FEHLER";exit;}
if (strlen($lehrer) == 0) 			{echo "FEHLER";exit;}
if (!cms_check_toggle($vpn)) 		{echo "FEHLER";exit;}
if (strlen($hosts) == 0) 				{echo "FEHLER";exit;}
if (strlen($hostl) == 0) 				{echo "FEHLER";exit;}
if (strlen($githubsecret) == 0) {echo "FEHLER";exit;}

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
	$inhalt = "Netze GitHub";
	$sql->bind_param("ss", $githubsecret, $inhalt); $sql->execute();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
