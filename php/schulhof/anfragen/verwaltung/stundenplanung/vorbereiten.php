<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['sjid'])) {$sjid = $_POST['sjid'];} else {echo "FEHLER";exit;}
if (isset($_POST['zrid'])) {$zrid = $_POST['zrid'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($sjid, 0)) {echo "FEHLER";exit;}
if (!(cms_check_ganzzahl($zrid, 0) || $zrid == '-')) {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$_SESSION["STUNDENPLANUNGSCHULJAHR"] = $sjid;
  $_SESSION["STUNDENPLANUNGZEITRAUM"] = $zrid;
	$_SESSION['STUNDENPLANUNGSTUFEN'] = 'x';
	$_SESSION['STUNDENPLANUNGKLASSEN'] = 'x';
	$_SESSION['STUNDENPLANUNGKURSE'] = 'x';
	$_SESSION['STUNDENPLANUNGLEHRER'] = 'x';
	$_SESSION['STUNDENPLANUNGRAUM'] = 'x';
	$_SESSION['STUNDENPLANUNGRYTHMUS'] = '0';
	$_SESSION['STUNDENPLANUNGMODUS'] = 'P';
	$_SESSION['STUNDENPLANUNGVOLLBILD'] = false;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
