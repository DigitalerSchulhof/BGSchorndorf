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
		$dbs->query($sql);	// TODO: Irgendwie safe machen
		echo "ERFOLG";
	}
	else {
		// Gemeinsames Prüfen
		$fehler = false;

		// Gibt es den Lehrer
		$flehrer = false;
		$sql = "SELECT COUNT(*) AS anzahl FROM lehrer WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $lehrer);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl == 1) {$flehrer = true;}
			}
			$sql->close();
		}
		if (!$flehrer) {echo "LEHRER"; $fehler = true;}

		// Gibt es den Raum
		$fraum = false;
		$sql = "SELECT COUNT(*) AS anzahl FROM raeume WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $raum);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl == 1) {$fraum = true;}
			}
			$sql->close();
		}
		if (!$fraum) {echo "RAUM"; $fehler = true;}

		if ($art == 'zusatzstunde') {
			// Schuljahr der Zusatzstunde ermitteln
			$schuljahr = '-';
			$sql = "SELECT id FROM schuljahre WHERE $beginn BETWEEN beginn AND ende";
			if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
				if ($daten = $anfrage->fetch_assoc()) {
					$schuljahr = $daten['id'];
				}
				$anfrage->free();
			}
			if ($schuljahr == '-') {echo "ZSCHULJAHR"; $fehler = true;}

			if (!$fehler) {
				// Gibt es den Kurs in diesem Schuljahr
				$fkurs = false;
				$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT klassenstufe FROM kurse WHERE id = ?) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id WHERE schuljahr = ?";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("ii", $kurs, $schuljahr);
				if ($sql->execute()) {
					$sql->bind_result($anzahl);
					if ($sql->fetch()) {
						if ($anzahl == 1) {$fkurs = true;}
					}
					$sql->close();
				}
				if (!$fkurs) {echo "ZKURS"; $fehler = true;}
			}

			// Zusatzstunde eintragen
			if (!$fehler) {
				// Versuche eine Schulstunde zu finden
				$stunde = NULL;
				$sql = "SELECT x.id AS stunde FROM (SELECT id, zeitraum FROM schulstunden WHERE beginnstd = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND beginnmin = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND endestd = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND endemin = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')) AS x JOIN zeitraeume ON x.zeitraum = zeitraeume.id WHERE zeitraeume.schuljahr = ?";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("iiiii", $bs, $bm, $es, $em, $schuljahr)
				if ($sql->execute()) {
					$sql->bind_param($stunde);
					$sql->fetch();
					$sql->close();
				}

				$id = cms_generiere_kleinste_id("tagebuch_".$schuljahr);
				$sql = "UPDATE tagebuch_$schuljahr SET tbeginn = $beginn, tende = $ende, tlehrkraft = $lehrer, traum = $raum, tstunde = $stunde, kurs = $kurs, entfall = 0, zusatzstunde = 1, vertretungsplan = 1, vertretungstext = AES_ENCRYPT('$vtext', '$CMS_SCHLUESSEL') WHERE id = $id";
				$dbs->query($sql);	// Schuljahr safe machen
				echo "ERFOLG";
			}
		}
		else if ($art == 'aenderung') {
			// Änderung vornehmen eintragen
			if (!$fehler) {
				// Versuche eine Schulstunde zu finden
				$stunde = NULL;
				$sql = "SELECT x.id AS stunde FROM (SELECT id, zeitraum FROM schulstunden WHERE beginnstd = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND beginnmin = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND endestd = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND endemin = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')) AS x JOIN zeitraeume ON x.zeitraum = zeitraeume.id WHERE zeitraeume.schuljahr = ?";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("iiiii", $bs, $bm, $es, $em, $schuljahr);
				if ($sql->execute()) {
					$sql->bind_result($stunde);
					$sql->fetch();
					$sql->close();
				}

				$sql = "UPDATE tagebuch_$schuljahr SET tbeginn = $beginn, tende = $ende, tlehrkraft = $lehrer, traum = $raum, tstunde = $stunde, entfall = 0, zusatzstunde = 0, vertretungsplan = 1, vertretungstext = AES_ENCRYPT('$vtext', '$CMS_SCHLUESSEL') WHERE id = $id";
				$sql = "UPDATE tagebuch_$schuljahr SET tbeginn = ?, tende = ?, tlehrkraft = ?, traum = ?, tstunde = ?, entfall = 0, zusatzstunde = 0, vertretungsplan = 1, vertretungstext = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("iiiiisi", $beginn, $ende, $lehrer, $raum, $stunde, $vtext, $id);
				$sql->execute();
				// $dbs->query($sql);	// TODO: Schuljahr safe machen!
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
