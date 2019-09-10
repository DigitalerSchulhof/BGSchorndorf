<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['vollbild'])) {$vollbild = $_POST['vollbild'];} else {echo "FEHLER";exit;}

if (!cms_check_toggle($vollbild)) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION['VERTRETUNGSPLANUNGVOLLBILD'] = $vollbild;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
