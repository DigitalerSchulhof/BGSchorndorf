<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER";exit;}
if (isset($_POST['postfach'])) {$postfach = $_POST['postfach'];} else {echo "FEHLER";exit;}
if (isset($_POST['leer'])) {$leer = $_POST['leer'];} else {echo "FEHLER";exit;}
if (isset($_POST['eltern'])) {$eltern = $_POST['eltern'];} else {echo "FEHLER";exit;}
if (isset($_POST['kinder'])) {$kinder = $_POST['kinder'];} else {echo "FEHLER";exit;}
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER";exit;}
if (isset($_POST['reli'])) {$reli = $_POST['reli'];} else {echo "FEHLER";exit;}
if (isset($_POST['adresse'])) {$adresse = $_POST['adresse'];} else {echo "FEHLER";exit;}
if (isset($_POST['kontaktdaten'])) {$kontaktdaten = $_POST['kontaktdaten'];} else {echo "FEHLER";exit;}
if (isset($_POST['geburtsdatum'])) {$geburtsdatum = $_POST['geburtsdatum'];} else {echo "FEHLER";exit;}
if (isset($_POST['konfession'])) {$konfession = $_POST['konfession'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();

$zugriff = false;
if ($art == 'Schüler') {$zugriff = $CMS_RECHTE['Personen']['Schülerliste sehen']; $art = 's';}
if ($art == 'Lehrer') {$zugriff = $CMS_RECHTE['Personen']['Lehrerliste sehen']; $art = 'l';}
if ($art == 'Eltern') {$zugriff = $CMS_RECHTE['Personen']['Elternliste sehen']; $art = 'e';}
if ($art == 'Verwaltung') {$zugriff = $CMS_RECHTE['Personen']['Verwaltungsliste sehen']; $art = 'v';}
if ($art == 'Externe') {$zugriff = $CMS_RECHTE['Personen']['Externenliste sehen']; $art = 'x';}
if ($art == 'Elternvertreter') {$zugriff = $CMS_RECHTE['Personen']['Elternvertreter sehen']; $art = 'ev';}
if ($art == 'Schülervertreter') {$zugriff = $CMS_RECHTE['Personen']['Schülervertreter sehen']; $art = 'sv';}

if (!cms_check_toggle($postfach)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($leer)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($eltern)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($kinder)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($klassen)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($reli)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($adresse)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($kontaktdaten)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($geburtsdatum)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($konfession)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();



	include_once('../../schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
  $schreibenpool = cms_postfach_empfaengerpool_generieren($dbs);

  include_once('../../schulhof/seiten/listen/listenausgeben.php');
  echo cms_listen_personenliste_ausgeben($dbs, $schreibenpool, $art, $postfach, $leer, $eltern, $kinder, $klassen, $reli,
	                                       $adresse, $kontaktdaten, $geburtsdatum, $konfession);
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
