<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

$CMS_BENUTZERART = $_SESSION['BENUTZERART'];

if (isset($_POST['ziel'])) {$ziel = $_POST['ziel'];} else {echo "FEHLER"; exit;}

// Zugriffssteuerung je nach Gruppe
$zugriff = false;

if (cms_r("artikel.galerien.anlegen")) {
	$zugriff = true;
}

if (cms_angemeldet() && $zugriff) {
	$_SESSION["GALERIEID"] = '-';
  $_SESSION["GALERIEZIEL"] = $ziel;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
