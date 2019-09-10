<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

if (($id !== 'e') && ($id !== 'v') && ($id !== 'a') & ($id !== 's')) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION['VERTRETUNGSPLANUNGOPTION'] = $id;
	if ($id == 's') {
		$_SESSION['VERTRETUNGSPLANUNGSTUNDE'] = 'x';
	}
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
