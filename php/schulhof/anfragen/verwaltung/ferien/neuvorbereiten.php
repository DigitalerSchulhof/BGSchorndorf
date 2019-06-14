<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_RECHTE = cms_rechte_laden();
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];

// Zugriffssteuerung je nach Gruppe
$zugriff = false;

if ($CMS_RECHTE['Organisation']['Ferien anlegen']) {
	$zugriff = true;
}

if (cms_angemeldet() && $zugriff) {
	$_SESSION["FERIENID"] = '-';
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
