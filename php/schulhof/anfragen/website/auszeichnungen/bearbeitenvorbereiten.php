<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("website.auszeichnungen.bearbeiten")) {
	$fehler = false;
	if (!$fehler) {
		$_SESSION["AUSZEICHNUNGBEAREITENID"] = $id;
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "FEHLER";
}
?>
