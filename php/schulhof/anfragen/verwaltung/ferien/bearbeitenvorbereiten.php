<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {$fehler = true;}


// Zugriffssteuerung je nach Gruppe
$zugriff = false;
$fehler = false;

if (cms_angemeldet() && cms_r("schulhof.organisation.ferien.bearbeiten")) {
	$_SESSION["FERIENID"] = $id;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
