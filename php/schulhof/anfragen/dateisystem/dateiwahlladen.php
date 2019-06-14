<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {echo "FEHLER"; exit;}
if (isset($_POST['feldid'])) {$feldid = $_POST['feldid'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

$zugriff = false;
$fehler = false;

$pfadteile = explode('/', $pfad);

if (($bereich == "website") && ($pfadteile[0] == 'website')) {
	$zugriff = true;
}
else if ($bereich == "Stundenplan") {
	$zugriff = $CMS_RECHTE['Gruppen']['Klassen anlegen'] || $CMS_RECHTE['Gruppen']['Klassen bearbeiten'] || $CMS_RECHTE['Gruppen']['Kurse anlegen'] || $CMS_RECHTE['Gruppen']['Kurse bearbeiten'] || $CMS_RECHTE['Organisation']['Räume anlegen'] || $CMS_RECHTE['Organisation']['Räume bearbeiten'];
	$zugriff = $zugriff || $CMS_RECHTE['Personen']['Lehrerkürzel ändern'];
}
else if ($bereich == "Vertretungsplan") {
	$zugriff = $CMS_RECHTE['Administration']['Allgemeine Einstellungen vornehmen'];
}
else if ($bereich == "gruppe") {
	if (count($pfadteile) < 4) {$fehler = true;}
	else {
		$dbs = cms_verbinden('s');
		$gruppe = strtoupper(substr($pfadteile[2],0,1)).substr($pfadteile[2],1);
		if ($gruppe == "Sonstigegruppen") {$gruppe = "Sonstige Gruppen";}
		$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		if ($pfadteile[3] != $id) {$fehler = true;}
		cms_trennen($dbs);
		$zugriff = $gruppenrechte['blogeintraege'] || $gruppenrechte['termine'];
	}
}
else {$fehler = true;}

$bereichlaenge = strlen($bereich);
$idlaenge = strlen($id);

if ($angemeldet && $zugriff) {
	if (!$fehler) {
		echo cms_dateiwaehler_ordner($pfad, 's', $bereich, $id, $feldid, $art, true);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
