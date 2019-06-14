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
if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {$bereich = '';}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {$pfad = '';}
if (isset($_POST['feldid'])) {$feldid = $_POST['feldid'];} else {$feldid = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$CMS_BENUTZERID = $_SESSION['BENUTZERID'];
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$dbs = cms_verbinden('s');
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

$zugriff = false;
$fehler = false;

$pfadteile = explode('/', $pfad);
if ($bereich == "website") {
	$gruppenrechte = cms_websitedateirechte_laden();
	if ($pfad[0] == "website") {$fehler = true;}
}
else if ($bereich == "titelbilder") {
	$gruppenrechte = cms_titelbilderdateirechte_laden();
	if ($pfad[0] == "titelbilder") {$fehler = true;}
}
else if ($bereich == "schulhof") {
	// Gruppe ermitteln
	if (count($pfadteile) < 4) {$fehler = true;}
	else {
		$gruppe = strtoupper(substr($pfadteile[2],0,1)).substr($pfadteile[2],1);
		if ($gruppe == "Sonstigegruppen") {$gruppe = "Sonstige Gruppen";}
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		if ($pfadteile[3] != $id) {$fehler = true;}
	}
}
else {$fehler = true;}

$zugriff = $gruppenrechte['sichtbar'] || $gruppenrechte['mitglied'];

if ($angemeldet && $zugriff) {
	if (!$fehler) {
		echo cms_dateisystem_ordner_ausgeben($pfad, 's', $bereich, $id, $gruppenrechte, $feldid, true);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
