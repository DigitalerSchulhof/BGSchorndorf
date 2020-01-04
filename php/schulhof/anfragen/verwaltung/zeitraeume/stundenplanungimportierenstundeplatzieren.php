<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nr'])) {$nr = $_POST['nr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schulstunde'])) {$schulstunde = $_POST['schulstunde'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rythmus'])) {$rythmus = $_POST['rythmus'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;
	if (!cms_check_ganzzahl($nr, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($SCHULJAHR, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($ZEITRAUM, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($schulstunde, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($tag, 1,7)) {$fehler = true;}
	if (!cms_check_ganzzahl($rythmus, 0,26)) {$fehler = true;}
	if (!cms_check_ganzzahl($lehrer, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($raum, 0)) {$fehler = true;}

	$dbs = cms_verbinden('s');
	if (!$fehler) {
		// Schuljahr und Zeitraum und Schulstunde checken
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schuljahre WHERE id = ?");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($sjanzahl);
			if ($sql->fetch()) {
				if ($sjanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		$sql = $dbs->prepare("SELECT COUNT(*), rythmen FROM zeitraeume WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $ZEITRAUM, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl, $zrythmen);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
				if ($rythmus > $zrythmen) {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schulstunden WHERE id = ? AND zeitraum = ?");
		$sql->bind_param("ii", $schulstunde, $ZEITRAUM);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		// Existenz des Lehrers prüfen
		$sql = $dbs->prepare("SELECT COUNT(*) FROM lehrer WHERE id = ?");
		$sql->bind_param("i", $lehrer);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		// Existenz des Raumes prüfen
		$sql = $dbs->prepare("SELECT COUNT(*) FROM raeume WHERE id = ?");
		$sql->bind_param("i", $raum);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		// Existenz des Kurses prüfen
		$sql = $dbs->prepare("SELECT COUNT(*), id FROM kurse WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$sql->bind_param("s", $kurs);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl, $kursid);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
				else {$kurs = $kursid;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		if ($nr == '0') {
			$sql = $dbs->prepare("DELETE FROM regelunterricht WHERE schulstunde IN (SELECT id FROM schulstunden WHERE zeitraum = ?)");
			$sql->bind_param("i", $ZEITRAUM);
		  $sql->execute();
		  $sql->close();
		}
		$id = cms_generiere_kleinste_id('regelunterricht');
		$sql = $dbs->prepare("UPDATE regelunterricht SET schulstunde = ?, tag = ?, rythmus = ?, kurs = ?, lehrer = ?, raum = ? WHERE id = ?");
	  $sql->bind_param("iiiiiii", $schulstunde, $tag, $rythmus, $kurs, $lehrer, $raum, $id);
	  $sql->execute();
	  $sql->close();
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "FEHLER";
}
?>
