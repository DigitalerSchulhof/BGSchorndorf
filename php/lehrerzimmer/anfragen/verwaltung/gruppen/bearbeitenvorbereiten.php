<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation'][$gruppe.' bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["GRUPPEBEARBEITEN"] = $id;
	$_SESSION["GRUPPE"] = $gruppe;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
