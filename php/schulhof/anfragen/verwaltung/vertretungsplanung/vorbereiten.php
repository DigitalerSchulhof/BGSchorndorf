<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

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
	$_SESSION['VERTRETUNGSPLANUNGTAG'] = date('d');
	$_SESSION['VERTRETUNGSPLANUNGMONAT'] = date('m');
	$_SESSION['VERTRETUNGSPLANUNGJAHR'] = date('Y');
	$_SESSION['VERTRETUNGSPLANUNGVOLLBILD'] = false;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
