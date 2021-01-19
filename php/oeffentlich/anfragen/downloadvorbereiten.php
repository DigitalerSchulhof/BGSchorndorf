<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();
$_SESSION['DOWNLOADPFAD'] = '';
$_SESSION['DOWNLOADNAME'] = '';
$_SESSION['DOWNLOADGROESSE'] = '';

// Variablen einlesen, falls übergeben
if (isset($_POST['datei'])) {$datei = $_POST['datei'];} else {$datei = '';}

if (substr($datei, 0, 16) == 'dateien/website/') {
	if (is_file("../../../".$datei)) {
		$name = explode('/', $datei);
		$_SESSION['DOWNLOADPFAD'] = $datei;
		$_SESSION['DOWNLOADNAME'] = $name[count($name)-1];
		$_SESSION['DOWNLOADGROESSE'] = filesize("../../../".$datei);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
