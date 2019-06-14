<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden löschen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	$dbs = cms_verbinden('s');
	// Zeitraum laden
	if (!$fehler) {
		$sql = "SELECT schuljahr FROM zeitraeume WHERE id = $zeitraum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$schuljahr = $daten['schuljahr'];
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	if (!$fehler) {
		$code = "";

		$sql = "DELETE FROM stunden WHERE lehrkraft = $lehrer AND raum = $raum AND kurs = $kurs AND zeitraum = $zeitraum AND tag = $tag AND stunde = $stunde";
		$dbs->query($sql);

		$jetzt = time();

		$sql = "DELETE FROM tagebuch_$schuljahr WHERE lehrkraft = $lehrer AND raum = $raum AND kurs = $kurs AND zeitraum = $zeitraum AND tag = $tag AND stunde = $stunde AND beginn > $jetzt";
		$dbs->query($sql);
		echo "ERFOLG";
	}
	else {echo "FEHLER";}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
