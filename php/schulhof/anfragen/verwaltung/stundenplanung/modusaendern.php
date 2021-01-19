<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}

if (($modus != 'L') && ($modus != 'P')) {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$_SESSION['STUNDENPLANUNGMODUS'] = $modus;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
