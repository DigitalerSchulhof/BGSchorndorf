<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schueler'])) {$schueler = $_POST['schueler'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	$tagzeit = mktime(0,0,0,$monat,$tag,$jahr);
	// Gibt es bereits einen Vertretungstext für diesen Tag?
	$id = '-';
	$sql = "SELECT id FROM vertretungstexte WHERE beginn = $tagzeit";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
		}
		$anfrage->free();
	}

	$schueler = cms_texttrafo_e_db($schueler);
	$lehrer = cms_texttrafo_e_db($lehrer);

	if ($id != '-') {
		$sql = "UPDATE vertretungstexte SET textschueler = AES_ENCRYPT('$schueler', '$CMS_SCHLUESSEL'), textlehrer = AES_ENCRYPT('$lehrer', '$CMS_SCHLUESSEL') WHERE id = $id";
		$anfrage = $dbs->query($sql);
	}
	else {
		$id = cms_generiere_kleinste_id('vertretungstexte');
		$sql = "UPDATE vertretungstexte SET textschueler = AES_ENCRYPT('$schueler', '$CMS_SCHLUESSEL'), textlehrer = AES_ENCRYPT('$lehrer', '$CMS_SCHLUESSEL'), beginn = $tagzeit WHERE id = $id";
		$anfrage = $dbs->query($sql);
	}
	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
