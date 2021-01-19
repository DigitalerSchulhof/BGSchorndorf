<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER";exit;}

if (cms_angemeldet()) {
	$_SESSION["TAGBEARBEITEN"] = $id;

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
