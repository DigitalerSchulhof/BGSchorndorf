<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/seiten/website/besucherstatistiken/website/auswerten.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['start'])) {$start = $_POST['start'];} else {echo "FEHLER";exit;}
if (isset($_POST['ende'])) {$ende = $_POST['ende'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {$modus = '';}
if (isset($_POST['typ'])) {$typ = $_POST['typ'];} else {echo "FEHLER";exit;}
if (isset($_POST['geloescht'])) {$geloescht = $_POST['geloescht'];} else {echo "FEHLER";exit;}
if (isset($_POST['startseite'])) {$startseite = $_POST['startseite'];} else {echo "FEHLER";exit;}
cms_rechte_laden();

if (cms_angemeldet()) {

  if(!r("statistik.besucher.website.seiten")) {
    echo "BERECHTIGUNG";
  } else {
		$gesamt = $modus == "gesamt";
		echo cms_besucherstatistik_website($typ, "gesamtaufrufe_linie", json_decode($start, true), json_decode($ende, true), $gesamt);
		echo cms_besucherstatistik_website($typ, "bereiche_balken", json_decode($start, true), json_decode($ende, true), $gesamt, $geloescht, $startseite);
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
