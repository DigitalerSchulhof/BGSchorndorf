<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/grundfunktionen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/tagladen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ziel'])) {$ziel = $_POST['ziel'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	if (($art != 'r') && ($art != 'l') && ($art != 'k')) {echo "FEHLER"; exit;}

	$dbs = cms_verbinden('s');
	// Schuljahr suchen
	$fehler = false;
	$beginn = mktime(0,0,0,$monat,$tag,$jahr);
	$sql = "SELECT id FROM schuljahre WHERE $beginn BETWEEN beginn AND ende";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$schuljahr = $daten['id'];
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}

	if ($fehler) {echo "SCHULJAHR";cms_trennen($dbs);exit;}

	$beginn = mktime(0,0,0,$monat,$tag,$jahr);
  $ende = mktime(23,59,59,$monat,$tag,$jahr);

  $code = "";
  $stunden = cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $ziel, $art, $beginn, $ende);
	$titel = array();
	if ($art == 'l') {for ($i=0; $i<count($stunden); $i++) {$titel[$i] = "Lehrerstundenplan";}}
	else if ($art == 'r') {for ($i=0; $i<count($stunden); $i++) {$titel[$i] = "Raumplan";}}
	else if ($art == 'k') {for ($i=0; $i<count($stunden); $i++) {$titel[$i] = "Klassenstundenplan";}}
  echo cms_vertretungsplan_tagesverlauf_ausgeben($stunden, $titel);

	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
