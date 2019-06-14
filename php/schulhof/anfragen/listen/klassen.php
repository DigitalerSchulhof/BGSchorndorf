<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["KLASSENLISTE"] = $id;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
