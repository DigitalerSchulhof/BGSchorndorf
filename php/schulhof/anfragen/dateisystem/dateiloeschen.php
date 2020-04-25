<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {$bereich = '';}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {$pfad = '';}
if (isset($_POST['datei'])) {$datei = $_POST['datei'];} else {$datei = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$zugriff = false;
$fehler = false;
$existiert = false;

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
		$dbs = cms_verbinden('s');
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		cms_trennen($dbs);
		if ($pfadteile[3] != $id) {$fehler = true;}
	}
}
else {$fehler = true;}

$zugriff = $gruppenrechte['dateiloeschen'];


if (cms_angemeldet() && $zugriff) {

	if (strlen($datei) < 1) {
		$fehler = true;
	}


	if (!$fehler) {
		$pfad = "../../../dateien/".$pfad;
		if (is_dir($pfad)) {
			if (is_file($pfad."/".$datei)) {
				$fehler = !unlink($pfad."/".$datei);
			}
			else {
				$fehler = true;
			}
		}
		else {
			$fehler = true;
		}
	}

	if (!$fehler) {
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
