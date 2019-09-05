<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['sjid'])) {$sjid = $_POST['sjid'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($sjid, 0)) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden und Tagebücher erzeugen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["STUNDENERZEUGENSCHULJAHR"] = $sjid;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
