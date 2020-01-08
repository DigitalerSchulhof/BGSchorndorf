<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/seiten/website/besucherstatistiken/auswerten.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['start'])) {$start = $_POST['start'];} else {echo "FEHLER";exit;}
if (isset($_POST['ende'])) {$ende = $_POST['ende'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {$modus = '';}
cms_rechte_laden();

if (cms_angemeldet()) {

  if(!cms_r("statistik.besucher.schulhof.sehen"))) {
    echo "BERECHTIGUNG";
  } else {
		$gesamt = $modus == "gesamt";
		echo cms_besucherstatistik_schulhof("gesamtaufrufe_linie", json_decode($start, true), json_decode($ende, true), $gesamt);
		echo cms_besucherstatistik_schulhof("rollen_pie", json_decode($start, true), json_decode($ende, true), $gesamt);
		echo cms_besucherstatistik_schulhof("bereiche_balken", json_decode($start, true), json_decode($ende, true), $gesamt);
  }
}
else {
	echo "BERECHTIGUNG";
}
?>
