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
if (isset($_POST['namealt'])) {$namealt = $_POST['namealt'];} else {$namealt = '';}
if (isset($_POST['nameneu'])) {$nameneu = $_POST['nameneu'];} else {$nameneu = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

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
		$dbs = cms_verbinden('s');
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		cms_trennen($dbs);
		if ($pfadteile[3] != $id) {$fehler = true;}
	}
}
else {$fehler = true;}

$zugriff = $gruppenrechte['dateiumbenennen'];


if ($angemeldet && $zugriff) {

	if (strlen($namealt) < 1) {
		$fehler = true;
	}

	if ($namealt == $nameneu) {
		$fehler = true;
	}

	if (preg_match("/^([a-zA-Z0-9_-])*.([a-zA-Z0-9])+$/", $nameneu) < 1) {
		$fehler = true;
	}

	$dbs = cms_verbinden('s');
	// Dateiendungen laden und prüfen
	$erlaubteendungen = array();
	$sql = "SELECT AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung FROM zulaessigedateien WHERE zulaessig = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			array_push($erlaubteendungen, $daten['endung']);
		}
	}
	else {$fehler = false;}
	cms_trennen($dbs);

	//Neue Endung
	$neueteile = explode('.', $nameneu);
	$neueendung = $neueteile[count($neueteile)-1];
	if (!in_array($neueendung, $erlaubteendungen)) {
		$fehler = true;
		echo "ENDUNG";
	}

	if (!$fehler) {
		$pfad = "../../../dateien/".$pfad;
		if (is_file($pfad."/".$namealt)) {
			if (file_exists($pfad."/".$nameneu)) {
				$existiert = true;
			}
			else {
				chmod($pfad."/".$namealt, 0777);
				$fehler = !rename($pfad."/".$namealt, $pfad."/".$nameneu);
				chmod($pfad."/".$nameneu, 0775);
			}
		}
		else {
			$fehler = true;
		}
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
?>
