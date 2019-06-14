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
$zugriff = $CMS_RECHTE['Planung']['Stunden anlegen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$code = "";

	$dbs = cms_verbinden('s');
	// Prüfe, ob der Kurs in diesen Zeitraum gehöhrt
	$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT id, klassenstufe FROM kurse WHERE kurse.id = $kurs) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id JOIN schuljahre ON klassenstufen.schuljahr = schuljahre.id JOIN zeitraeume ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = $zeitraum";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($daten['anzahl'] != 1) {$fehler = true;}
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}

	// Prüfen, ob der Raum existiert
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM raeume WHERE id = $raum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] != 1) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Prüfen, ob der Lehrer existiert
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id = $lehrer AND art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] != 1) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Prüfen, ob die Stunde im gewählten Zeitraum liegt
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM schulstunden WHERE id = $stunde AND zeitraum = $zeitraum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] != 1) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Prüfen, ob die Stunde genau so existiert
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM stunden WHERE lehrkraft = $lehrer AND raum = $raum AND kurs = $kurs AND tag = $tag AND stunde = $stunde";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] > 0) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Prüfen, ob Tag Schultag ist
	if (!$fehler) {
		$tagkurz = strtolower(cms_tagname($tag));
		$sql = "SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE $tagkurz = 1";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] != 1) {$fehler = true;}
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Zeitraum laden
	if (!$fehler) {
		$sql = "SELECT id, beginn, ende, schuljahr FROM zeitraeume WHERE id = $zeitraum";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$zeitraum = $daten;
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}

	// Stunde laden
	if (!$fehler) {
		$sql = "SELECT id, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE id = $stunde";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$stunde = $daten;
			} else {$fehler = true;}
			$anfrage->free();
		} else {$fehler = true;}
	}



	if ($fehler) {echo "BASTLER";}
	else {
		// Schulstunde eintragen
		$sql = "INSERT INTO stunden (lehrkraft, raum, kurs, zeitraum, tag, stunde) VALUES ($lehrer, $raum, $kurs, ".$zeitraum['id'].", $tag, ".$stunde['id'].")";
		$dbs->query($sql);

		// Stunde im gesamten Zeitraum eintragen
		$beginn = $zeitraum['beginn'];
		$ende = $zeitraum['ende'];
		$jetzt = time();
		$start = max($beginn, $jetzt);

		if ($start < $ende) {
			$startTnr = date('N', $start);
			$startT = date('j', $start);
			$startM = date('n', $start);
			$startJ = date('Y', $start);

			if ($startTnr < $tag) {$startT = $startT + ($tag-$startTnr);}
			else if ($tag < $startTnr) {$startT = $startT + 7 - ($startTnr-$tag);}

			$sbeginn = mktime($stunde['bs'], $stunde['bm'], 0, $startM, $startT, $startJ);
			$sende = mktime($stunde['es'], $stunde['em'], 0, $startM, $startT, $startJ);

			// Wochenabstand 60 sek = 1 min * 60 = 1 h * 24 = 1 d * 7 = 1 w
			$abstand = 60 * 60 * 24 * 7;

			if ($sbeginn < $jetzt) {
				$sbeginn += $abstand;
				$sende += $abstand;
			}
			while ($sende < $ende) {
				$id = cms_generiere_kleinste_id("tagebuch_".$zeitraum['schuljahr']);
				$sql = "UPDATE tagebuch_".$zeitraum['schuljahr']." SET lehrkraft = $lehrer, raum = $raum, tlehrkraft = $lehrer, traum = $raum, kurs = $kurs, tag = $tag, stunde = ".$stunde['id'].", tstunde = ".$stunde['id'].", beginn = $sbeginn, ende = $sende, tbeginn = $sbeginn, tende = $sende, zeitraum = ".$zeitraum['id']." WHERE id = $id";
				$dbs->query($sql);

				$sbeginn += $abstand;
				$sende += $abstand;
			}
		}
		echo "ERFOLG";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
