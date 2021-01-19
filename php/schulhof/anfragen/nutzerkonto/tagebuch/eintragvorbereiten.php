<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['unterricht'])) {$unterricht = $_POST['unterricht'];} else {echo "FEHLER";exit;}
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];

if (!cms_check_ganzzahl($unterricht, 0)) {echo "FEHLER";exit;}

if (cms_angemeldet() && ($CMS_BENUTZERART == 'l')) {
	$_SESSION['TAGEBUCHEINTRAG'] = $unterricht;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
