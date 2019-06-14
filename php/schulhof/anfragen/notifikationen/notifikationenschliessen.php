<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();

// Zugriffssteuerung je nach Gruppe
$zugriff = false;
$fehler = false;

if (cms_angemeldet()) {
	if (!$fehler) {
		// NOTIFIKATIONEN VERSCHICKEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("DELETE FROM notifikationen WHERE person = ?");
	  $sql->bind_param("i", $CMS_BENUTZERID);
	  $sql->execute();
	  $sql->close();
		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
