<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/grundfunktionen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/stundeauswaehlen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}

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

	if ($fehler) {echo "SCHULJAHR";}
	else {
		echo cms_vertretungsplan_stunde_auswaehlen($dbs, $schuljahr, $id);
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
