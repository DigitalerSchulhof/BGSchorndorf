<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vtext'])) {$vtext = $_POST['vtext'];} else {echo "FEHLER"; exit;}
$vtext = cms_texttrafo_e_db($vtext);

if ($art == 'entfall') {
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
}
else if (($art == 'zusatzstunde') || ($art == 'aenderung')) {
	if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['bs'])) {$bs = $_POST['bs'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['bm'])) {$bm = $_POST['bm'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['es'])) {$es = $_POST['es'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['em'])) {$em = $_POST['em'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
	$beginn = mktime($bs,$bm,0,$monat,$tag,$jahr);
	$ende = mktime($es,$em,0,$monat,$tag,$jahr);

	//echo date("d.m.Y H:i", $beginn)." - ".date("d.m.Y H:i", $ende);
	if ($art == 'zusatzstunde') {
		if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
	}
	else if ($art == 'aenderung') {
		if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
		if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
	}
}
else {
	echo "BASTLER";
	exit;
}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	if ($art == 'entfall') {
		$sql = "UPDATE tagebuch_$schuljahr SET entfall = 1, vertretungsplan = 1, zusatstunde = 0, vertretungstext = AES_ENCRYPT('$vtext', '$CMS_SCHLUESSEL'), tbeginn = beginn, tende = ende, tlehrkraft = lehrkraft, traum = raum, tstunde = stunde WHERE id = $id";
		$dbs->query($sql);
		echo "ERFOLG";
	}
	else {
		// Gemeinsames Prüfen
		$fehler = false;

		// Gibt es den Lehrer
		$flehrer = false;
		$sql = "SELECT COUNT(*) AS anzahl FROM lehrer WHERE id = $lehrer";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] == 1) {$flehrer = true;}
			}
			$anfrage->free();
		}
		if (!$flehrer) {echo "LEHRER"; $fehler = true;}

		// Gibt es den Raum
		$fraum = false;
		$sql = "SELECT COUNT(*) AS anzahl FROM raeume WHERE id = $raum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] == 1) {$fraum = true;}
			}
			$anfrage->free();
		}
		if (!$fraum) {echo "RAUM"; $fehler = true;}

		if ($art == 'zusatzstunde') {
			// Schuljahr der Zusatzstunde ermitteln
			$schuljahr = '-';
			$sql = "SELECT id FROM schuljahre WHERE $beginn BETWEEN beginn AND ende";
			if ($anfrage = $dbs->query($sql)) {
				if ($daten = $anfrage->fetch_assoc()) {
					$schuljahr = $daten['id'];
				}
				$anfrage->free();
			}
			if ($schuljahr == '-') {echo "ZSCHULJAHR"; $fehler = true;}

			if (!$fehler) {
				// Gibt es den Kurs in diesem Schuljahr
				$fkurs = false;
				$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT klassenstufe FROM kurse WHERE id = $kurs) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id WHERE schuljahr = $schuljahr";
				if ($anfrage = $dbs->query($sql)) {
					if ($daten = $anfrage->fetch_assoc()) {
						if ($daten['anzahl'] == 1) {$fkurs = true;}
					}
					$anfrage->free();
				}
				if (!$fkurs) {echo "ZKURS"; $fehler = true;}
			}

			// Zusatzstunde eintragen
			if (!$fehler) {
				// Versuche eine Schulstunde zu finden
				$stunde = NULL;
				$sql = "SELECT x.id AS stunde FROM (SELECT id, zeitraum FROM schulstunden WHERE beginnstd = AES_ENCRYPT('$bs', '$CMS_SCHLUESSEL') AND beginnmin = AES_ENCRYPT('$bm', '$CMS_SCHLUESSEL') AND endestd = AES_ENCRYPT('$es', '$CMS_SCHLUESSEL') AND endemin = AES_ENCRYPT('$em', '$CMS_SCHLUESSEL')) AS x JOIN zeitraeume ON x.zeitraum = zeitraeume.id WHERE zeitraeume.schuljahr = $schuljahr";
				if ($anfrage = $dbs->query($sql)) {
					if ($daten = $anfrage->fetch_assoc()) {
						$stunde = $daten['stunde'];
					}
					$anfrage->free();
				}

				$id = cms_generiere_kleinste_id("tagebuch_".$schuljahr);
				$sql = "UPDATE tagebuch_$schuljahr SET tbeginn = $beginn, tende = $ende, tlehrkraft = $lehrer, traum = $raum, tstunde = $stunde, kurs = $kurs, entfall = 0, zusatzstunde = 1, vertretungsplan = 1, vertretungstext = AES_ENCRYPT('$vtext', '$CMS_SCHLUESSEL') WHERE id = $id";
				$dbs->query($sql);
				echo "ERFOLG";
			}
		}
		else if ($art == 'aenderung') {
			// Änderung vornehmen eintragen
			if (!$fehler) {
				// Versuche eine Schulstunde zu finden
				$stunde = NULL;
				$sql = "SELECT x.id AS stunde FROM (SELECT id, zeitraum FROM schulstunden WHERE beginnstd = AES_ENCRYPT('$bs', '$CMS_SCHLUESSEL') AND beginnmin = AES_ENCRYPT('$bm', '$CMS_SCHLUESSEL') AND endestd = AES_ENCRYPT('$es', '$CMS_SCHLUESSEL') AND endemin = AES_ENCRYPT('$em', '$CMS_SCHLUESSEL')) AS x JOIN zeitraeume ON x.zeitraum = zeitraeume.id WHERE zeitraeume.schuljahr = $schuljahr";
				if ($anfrage = $dbs->query($sql)) {
					if ($daten = $anfrage->fetch_assoc()) {
						$stunde = $daten['stunde'];
					}
					$anfrage->free();
				}

				$sql = "UPDATE tagebuch_$schuljahr SET tbeginn = $beginn, tende = $ende, tlehrkraft = $lehrer, traum = $raum, tstunde = $stunde, entfall = 0, zusatzstunde = 0, vertretungsplan = 1, vertretungstext = AES_ENCRYPT('$vtext', '$CMS_SCHLUESSEL') WHERE id = $id";
				$dbs->query($sql);
				echo "ERFOLG";
			}
		}

	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
