<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['bez'])) {$bez = $_POST['bez'];} else {$bez = '';}

if (cms_angemeldet()) {
	$_SESSION["GRUPPEID"] = $id;
	$_SESSION["GRUPPEBEZEICHNUNG"] = $bez;
	$_SESSION["GRUPPE"] = $gruppe;
	$jetzt = time();
	$_SESSION['KALENDERANSICHT'] = "tag";
	$_SESSION['KALENDERZUGEHÖRIG'] = "gruppe";
	$_SESSION['KALENDERGEWAEHLTTAG'] = date('j', $jetzt);
	$_SESSION['KALENDERGEWAEHLTMONAT'] = date('n', $jetzt);
	$_SESSION['KALENDERGEWAEHLTJAHR'] = date('Y', $jetzt);
	$_SESSION['KALENDERUEBERSICHTMONAT'] = date('n', $jetzt);
	$_SESSION['KALENDERUEBERSICHTJAHR'] = date('Y', $jetzt);
	$_SESSION['KALENDERTERMINE'][0]['aktiv'] = 1;
	$_SESSION['KALENDERTERMINE'][1]['aktiv'] = 0;
	$_SESSION['KALENDERTERMINE'][2]['aktiv'] = 0;
	$_SESSION['KALENDERTERMINE'][0]['anzeigen'] = 1;
	$_SESSION['KALENDERTERMINE'][1]['anzeigen'] = 0;
	$_SESSION['KALENDERTERMINE'][2]['anzeigen'] = 0;

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
