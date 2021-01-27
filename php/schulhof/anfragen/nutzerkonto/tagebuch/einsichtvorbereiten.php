<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppenart'])) {$gruppenart = $_POST['gruppenart'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER";exit;}
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];

if (!cms_check_ganzzahl($gruppenid, 0)) {echo "FEHLER";exit;}
if ($gruppenart != "klasse" && $gruppenart != "kurs") {echo "FEHLER";exit;}

if (cms_angemeldet() && ($CMS_BENUTZERART == 'l')) {
	$_SESSION['TAGEBUCHEINSEHENID'] = $gruppenid;
	$_SESSION['TAGEBUCHSEHENART'] = $gruppenart;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
