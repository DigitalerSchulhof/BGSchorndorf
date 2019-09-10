<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($id,0) && ($id !== '-')) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION['VERTRETUNGSPLANUNGRAUM'] = $id;
	$_SESSION['VERTRETUNGSPLANUNGSTUNDE'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGOPTION'] = 'x';
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
