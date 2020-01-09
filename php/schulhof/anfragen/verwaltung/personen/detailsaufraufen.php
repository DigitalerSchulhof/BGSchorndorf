<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.personen.daten")) {
	$fehler = false;
	$_SESSION["PERSONENDETAILS"] = $id;

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
