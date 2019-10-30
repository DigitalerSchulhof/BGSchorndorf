<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

$CMS_RECHTE = cms_rechte_laden();
if (isset($_POST['zuordnen'])) {$zuordnen = $_POST['zuordnen'];} else {echo "FEHLER"; exit;}
if (!cms_check_toggle($zuordnen)) {echo "FEHLER"; exit;}

$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	// Schüler und Lehrer aufgrund der Klassenzusammensetzung und des Regelstundenplans zuordnen

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
