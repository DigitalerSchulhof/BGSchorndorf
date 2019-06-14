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
if (isset($_POST['name'])) {$name = $_POST['name'];} else {$name = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$CMS_BENUTZERID = $_SESSION['BENUTZERID'];
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$dbs = cms_verbinden('s');
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

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
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		if ($pfadteile[3] != $id) {$fehler = true;}
	}
}
else {$fehler = true;}


$zugriff = $gruppenrechte['dateiupload'];


if ($angemeldet && $zugriff) {

	if (preg_match("/^([a-zA-Z0-9_-])+$/", $name) < 1) {
		$fehler = true;
	}

	if (strlen($name) < 3) {$fehler = true;}


	if (!$fehler) {
		$pfad = "../../../dateien/".$pfad;
		if (!file_exists($pfad.'/'.$name)) {
			chmod($pfad, 0777);
			$fehler = !mkdir($pfad.'/'.$name, 0775);
			chmod($pfad, 0775);
		}
		else {$existiert = true;}
	}

	if (!$fehler) {
		if ($existiert) {
			echo "EXISTIERT";
		}
		else {
			echo "ERFOLG";
		}
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
