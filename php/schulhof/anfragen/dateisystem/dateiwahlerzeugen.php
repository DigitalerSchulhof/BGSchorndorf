<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {echo "FEHLER"; exit;}
if (isset($_POST['feldid'])) {$feldid = $_POST['feldid'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$angemeldet = cms_angemeldet();
cms_rechte_laden();

$zugriff = false;
$fehler = false;

if ($bereich == "website" || $bereich == "galerien") {
	$zugriff = true;
}
else if ($bereich == "Stundenplan") {
	$zugriff = cms_r("schulhof.gruppen.[|klassen,kurse].[|anlegen,bearbeiten] || schulhof.organisation.räume.[|anlegen,bearbeiten] || schulhof.verwaltung.lehrer.kürzel");
}
else if ($bereich == "Vertretungsplan") {
	$zugriff = cms_r("schulhof.verwaltung.einstellungen");
}
else if ($bereich == "gruppe") {
	$dbs = cms_verbinden('s');
	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
	$GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
	$zugriff = $GRUPPENRECHTE['blogeintraege'] || $GRUPPENRECHTE['termine'];
}
else {$fehler = true;}

if (($art != 'download') && ($art != 'bilder') && ($art != 'video') && ($art != 'linkdownload') && ($art != 'vorschaubild')) {$fehler = true;}

$bereichlaenge = strlen($bereich);
$idlaenge = strlen($id);


if ($angemeldet && $zugriff) {
	if (!$fehler) {
		if ($bereich == "website") {
			echo cms_dateiwaehler_generieren ('website', $pfad, $feldid, 's', $bereich, $id, $art, true);
		}
		else if ($bereich == "galerien") {
			echo cms_dateiwaehler_generieren ('galerien', $pfad, $feldid, 's', $bereich, $id, $art, true);
		}
		else if (($bereich == "Stundenplan") || ($bereich == "Vertretungsplan")) {
			echo cms_dateiwaehler_generieren ('schulhof/stundenplaene', $pfad, $feldid, 's', $bereich, $id, $art, true);
		}
		else if (($bereich == "gruppe")) {
			echo cms_dateiwaehler_generieren ("schulhof/gruppen/$gruppe/$gruppenid", $pfad, $feldid, 's', $bereich, $id, $art, true);
		}
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
