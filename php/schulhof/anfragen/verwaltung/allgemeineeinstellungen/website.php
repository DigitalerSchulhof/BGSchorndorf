<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

$gruppen[0] = 'termine';
$gruppen[1] = 'blog';
$gruppen[2] = 'galerien';

$personen[0] = 'lehrer';
$personen[1] = 'verwaltungsangestellte';
$personen[2] = 'schueler';
$personen[3] = 'eltern';
$personen[4] = 'externe';

foreach ($personen as $p) {
	foreach ($gruppen as $g) {
		if (!isset($_POST[$p.$g.'vorschlagen'])) {echo "FEHLER";exit;}
	}
}

// Variablen einlesen, falls übergeben
if (isset($_POST['menueseitenweiterleiten'])) {$menueseitenweiterleiten = $_POST['menueseitenweiterleiten'];} else {echo "FEHLER";exit;}
if (isset($_POST['fehlermeldungaktiv'])) {$fehlermeldungaktiv = $_POST['fehlermeldungaktiv'];} else {echo "FEHLER";exit;}
if (isset($_POST['fehlermeldungangemeldet'])) {$fehlermeldungangemeldet = $_POST['fehlermeldungangemeldet'];} else {echo "FEHLER";exit;}
if (isset($_POST['feedbackaktiv'])) {$feedbackaktiv = $_POST['feedbackaktiv'];} else {echo "FEHLER";exit;}
if (isset($_POST['feedbackangemeldet'])) {$feedbackangemeldet = $_POST['feedbackangemeldet'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.verwaltung.einstellungen")) {
	$fehler = false;

	if (!cms_check_toggle($menueseitenweiterleiten)) {$fehler = true;}
	if (!cms_check_toggle($fehlermeldungaktiv)) {$fehler = true;}
	if (!cms_check_toggle($fehlermeldungangemeldet)) {$fehler = true;}
	if (!cms_check_toggle($feedbackaktiv)) {$fehler = true;}
	if (!cms_check_toggle($feedbackangemeldet)) {$fehler = true;}

	foreach ($personen as $p) {
		foreach ($gruppen as $g) {
			if (!cms_check_toggle($_POST[$p.$g.'vorschlagen'])) {$fehler = true;}
		}
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	  foreach ($personen as $p) {
			if ($p == "schueler") {$pg = "Schüler";}
			else {$pg = cms_vornegross($p);}
			foreach ($gruppen as $g) {
				if ($g == "blog") {$gg = "Blogeinträge";}
				else {$gg = cms_vornegross($g);}
				$einstellungsname = "$pg dürfen $gg vorschlagen";
				$sql->bind_param("ss", $_POST[$p.$g.'vorschlagen'], $einstellungsname);
			  $sql->execute();
			}
		}
		$einstellungsname = "Menüseiten weiterleiten";
		$sql->bind_param("ss", $menueseitenweiterleiten, $einstellungsname);
		$sql->execute();
		$einstellungsname = "Fehlermeldung aktiv";
		$sql->bind_param("ss", $fehlermeldungaktiv, $einstellungsname);
		$sql->execute();
		$einstellungsname = "Fehlermeldung Anmeldung notwendig";
		$sql->bind_param("ss", $fehlermeldungangemeldet, $einstellungsname);
		$sql->execute();
		$einstellungsname = "Feedback aktiv";
		$sql->bind_param("ss", $feedbackaktiv, $einstellungsname);
		$sql->execute();
		$einstellungsname = "Feedback Anmeldung notwendig";
		$sql->bind_param("ss", $feedbackangemeldet, $einstellungsname);
		$sql->execute();
	  $sql->close();

		cms_trennen($dbs);
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
