<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

if (isset($_POST['tag']))   {$tag = $_POST['tag'];} else {echo "FEHLER";exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER";exit;}
if (isset($_POST['jahr']))  {$jahr = $_POST['jahr'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

session_start();

// Variablen einlesen, falls übergeben
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION['VERTRETUNGSPLANUNGSTUFEN'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGKLASSEN'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGKURSE'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGLEHRER'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGRAUM'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGSTUNDE'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGOPTION'] = 'x';
	$_SESSION['VERTRETUNGSPLANUNGTAG'] = $tag;
	$_SESSION['VERTRETUNGSPLANUNGMONAT'] = $monat;
	$_SESSION['VERTRETUNGSPLANUNGJAHR'] = $jahr;
	$_SESSION['VERTRETUNGSPLANUNGVOLLBILD'] = false;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
