<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['zuordnung'])) {$zuordnung = $_POST['zuordnung'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Seiten anlegen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["SEITENNEUZUORDNUNG"] = $zuordnung;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
