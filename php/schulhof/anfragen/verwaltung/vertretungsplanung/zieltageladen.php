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
if (isset($_POST['bs'])) {$bs = $_POST['bs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bm'])) {$bm = $_POST['bm'];} else {echo "FEHLER"; exit;}
if (isset($_POST['es'])) {$es = $_POST['es'];} else {echo "FEHLER"; exit;}
if (isset($_POST['em'])) {$em = $_POST['em'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {

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

	$ueberlappend['beginn'] = mktime($bs,$bm,0,$monat,$tag,$jahr);
	$ueberlappend['ende'] = mktime($es,$em,0,$monat,$tag,$jahr);

	// Suche alle zugehörigen Klassen
	$ktag = array();
	$ktitelnr = 0;
	$ktitel = array();
	if ($kurs != '-') {
		$sql = "SELECT * FROM (SELECT klasse AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM (SELECT klasse FROM kursklassen WHERE kurs = $kurs) AS x JOIN klassen ON x.klasse = klassen.id JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id) AS y ORDER BY klasse ASC";
		if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
			while ($daten = $anfrage->fetch_assoc()) {
				$katag = cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $daten['id'], 'k', $beginn, $ende);
				for ($i=0; $i<count($katag); $i++) {$ktitel[$ktitelnr] = "Klasse ".$daten['stufe'].$daten['klasse']; $ktitelnr++;}
				$ktag = array_merge ($ktag, $katag);
			}
			$anfrage->free();
		}
	}

  $code = "";
  $ltag = cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $lehrer, 'l', $beginn, $ende);
  $rtag = cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $raum, 'r', $beginn, $ende);

	$titel = array();
	$index = 0;
	for ($i=0; $i<count($ltag); $i++) {$titel[$index] = "Lehrerstundenplan"; $index++;}
	for ($i=0; $i<count($rtag); $i++) {$titel[$index] = "Raumplan"; $index++;}
	$titel = array_merge($titel, $ktitel);
	$stunden = array_merge ($ltag, $rtag, $ktag);
  echo cms_vertretungsplan_tagesverlauf_ausgeben($stunden, $titel, $ueberlappend, false);

	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
