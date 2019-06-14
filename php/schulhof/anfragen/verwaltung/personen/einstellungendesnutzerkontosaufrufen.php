<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet() && ($CMS_RECHTE['Personen']['Persönliche Einstellungen ändern'])) {
	$_SESSION["PERSONENDETAILS"] = $id;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
