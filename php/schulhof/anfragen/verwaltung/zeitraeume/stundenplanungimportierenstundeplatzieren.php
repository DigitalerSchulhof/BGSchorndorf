<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nr'])) {$nr = $_POST['nr'];} else {echo "FEHLER1"; exit;}
if (isset($_POST['schulstunde'])) {$schulstunde = $_POST['schulstunde'];} else {echo "FEHLER2"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER3"; exit;}
if (isset($_POST['rythmus'])) {$rythmus = $_POST['rythmus'];} else {echo "FEHLER4"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER5"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER6"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER7"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER8"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER9"; exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;
	if (!cms_check_ganzzahl($nr, 0)) {$fehler = true; echo "|10";}
	if (!cms_check_ganzzahl($SCHULJAHR, 0)) {$fehler = true; echo "|11";}
	if (!cms_check_ganzzahl($ZEITRAUM, 0)) {$fehler = true; echo "|12";}
	if (!cms_check_ganzzahl($schulstunde, 0)) {$fehler = true; echo "|13";}
	if (!cms_check_ganzzahl($tag, 1,7)) {$fehler = true; echo "|14";}
	if (!cms_check_ganzzahl($rythmus, 0,26)) {$fehler = true; echo "|15";}
	if (!cms_check_ganzzahl($lehrer, 0)) {$fehler = true; echo "|16";}
	if (!cms_check_ganzzahl($raum, 0)) {$fehler = true; echo "|17";}

	$dbs = cms_verbinden('s');
	if (!$fehler) {
		// Schuljahr und Zeitraum und Schulstunde checken
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schuljahre WHERE id = ?");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($sjanzahl);
			if ($sql->fetch()) {
				if ($sjanzahl != '1') {$fehler = true; echo "|18";}
			} else {$fehler = true; echo "|19";}
		} else {$fehler = true; echo "|20";}
		$sql->close();
		$sql = $dbs->prepare("SELECT COUNT(*), rythmen FROM zeitraeume WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $ZEITRAUM, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl, $zrythmen);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true; echo "|21";}
				if ($rythmus > $zrythmen) {$fehler = true; echo "|22";}
			} else {$fehler = true; echo "|23";}
		} else {$fehler = true; echo "|24";}
		$sql->close();
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schulstunden WHERE id = ? AND zeitraum = ?");
		$sql->bind_param("ii", $schulstunde, $ZEITRAUM);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true; echo "|25";}
			} else {$fehler = true; echo "|26";}
		} else {$fehler = true; echo "|27";}
		$sql->close();
		// Existenz des Lehrers prüfen
		$sql = $dbs->prepare("SELECT COUNT(*) FROM lehrer WHERE id = ?");
		$sql->bind_param("i", $lehrer);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true; echo "|28";}
			} else {$fehler = true; echo "|29";}
		} else {$fehler = true; echo "|30";}
		$sql->close();
		// Existenz des Raumes prüfen
		$sql = $dbs->prepare("SELECT COUNT(*) FROM raeume WHERE id = ?");
		$sql->bind_param("i", $raum);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true; echo "|31";}
			} else {$fehler = true; echo "|32";}
		} else {$fehler = true; echo "|33";}
		$sql->close();
		// Existenz des Kurses prüfen
		$sql = $dbs->prepare("SELECT COUNT(*), id FROM kurse WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
		$sql->bind_param("si", $kurs, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl, $kursid);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true; echo $kurs."|34";}
				else {$kurs = $kursid;}
			} else {$fehler = true; echo "|35";}
		} else {$fehler = true; echo "|36";}
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
