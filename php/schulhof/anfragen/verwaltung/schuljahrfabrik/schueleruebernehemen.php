<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER";exit;}
if (isset($_POST['bestand'])) {$bestand = $_POST['bestand'];} else {echo "FEHLER";exit;}
if (isset($_POST['feldid'])) {$feldid = $_POST['feldid'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

$fehler = false;

$gk = cms_textzudb($gruppe);
if (!cms_valide_kgruppe($gk)) {$fehler = true; echo 1;}
if (!cms_check_idfeld($bestand)) {$fehler = true; echo 2;}

$dbs = cms_verbinden('s');

if (cms_angemeldet() && r("schulhof.planung.schuljahre.fabrik")) {

	if (!$fehler) {
		// Alte Mitglieder laden
		$sql = $dbs->prepare("SELECT person FROM $gk"."mitglieder JOIN $gk ON $gk"."mitglieder.gruppe = $gk.id JOIN personen ON person = personen.id WHERE gruppe = ? AND schuljahr = ? AND art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
		$sql->bind_param("ii", $gruppenid, $altschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($person);
			while ($sql->fetch()) {
				$bestand .= "|".$person;
			}
		}
		$sql->close();

		include_once('../../schulhof/seiten/personensuche/personensuche.php');
		echo cms_personensuche_personhinzu_generieren($dbs, $feldid, 's', $bestand);
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
