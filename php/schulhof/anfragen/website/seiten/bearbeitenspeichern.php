<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beschreibung'])) {$beschreibung = $_POST['beschreibung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['position'])) {$position = $_POST['position'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($position,0)) {echo "FEHLER"; exit;}
if (isset($_POST['sidebar'])) {$sidebar = $_POST['sidebar'];} else {echo "FEHLER"; exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {echo "FEHLER"; exit;}
if (isset($_POST['styles'])) {$styles = $_POST['styles'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['SEITENBEARBEITENZUORDNUNG'])) {$zuordnung = $_SESSION['SEITENBEARBEITENZUORDNUNG'];}
else {if (is_null($_SESSION['SEITENBEARBEITENZUORDNUNG'])) {$zuordnung = '-';} else {echo "FEHLER"; exit;}}
if (isset($_SESSION['SEITENBEARBEITENID'])) {$id = $_SESSION['SEITENBEARBEITENID'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Seiten bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	if (($art != 's') && ($art != 'm') && ($art != 'b') && ($art != 'g') && ($art != 't')) {$fehler = true;}

	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}

	if (($sidebar != 0) && ($sidebar != 1)) {$fehler = true;}

	if (($status != 'i') && ($status != 'a') && ($status != 's')) {$fehler = true;}
	if (($status == 's') && (!$CMS_RECHTE['Website']['Startseite festlegen'])) {$fehler = true;}
	if (($status != 'i') && (!$CMS_RECHTE['Website']['Inhalte freigeben'])) {$fehler = true;}

	if (($art == 'm') && ($status == 's')) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);
	$beschreibung = cms_texttrafo_e_db($beschreibung);
	$styles = cms_texttrafo_e_db($styles);
	$klassen = cms_texttrafo_e_db($klassen);

	$dbs = cms_verbinden('s');
	if (!$fehler) {
		if ($zuordnung == '-') {$zuordnung = "zuordnung IS NULL";}
		else if (cms_check_ganzzahl($zuordnung)) {$zuordnung = "zuordnung = $zuordnung";}
		else {$zuordnung = "";}
		// Prüfen, ob die Position nicht über max liegt
		$sql = $dbs->prepare("SELECT MAX(position) AS maxpos FROM seiten WHERE $zuordnung");
		if ($sql->execute()) {
			$sql->bind_result($maxpos);
			if ($sql->fetch()) {if ($position > $maxpos) {echo "MAXPOS"; $fehler = true;}}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		// Alte Position der Seite ermitteln
		$sql = $dbs->prepare("SELECT position FROM seiten WHERE id = ?");
	  $sql->bind_param("i", $id);
	  if ($sql->execute()) {
	    $sql->bind_result($altpos);
	    if (!$sql->fetch()) {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}

	// Prüfen, ob es bereits eine Seite mit dieser Bezeichnung gibt
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM seiten WHERE zuordnung = ? AND bezeichnung = ? AND id != ?");
	  $sql->bind_param("ssi", $zuordnung, $bezeichnung, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}
	cms_trennen($dbs);

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		if ($altpos < $position) {
			// Nachfolgende Seiten aufrutschen lassen
			$sql = $dbs->prepare("UPDATE seiten SET position = position - 1 WHERE zuordnung = ? AND position > ? AND position <= ?");
		  $sql->bind_param("sii", $zuordnung, $altpos, $position);
		  $sql->execute();
		  $sql->close();
		}
		else if ($altpos > $position) {
			// Nachfolgende Seiten aufrutschen lassen
			$sql = $dbs->prepare("UPDATE seiten SET position = position + 1 WHERE zuordnung = ? AND position >= ? AND position < ?");
		  $sql->bind_param("sii", $zuordnung, $position, $altpos);
		  $sql->execute();
		  $sql->close();
		}
		// Falls es eine neue Startseite gibt, alte Startseiten auf aktiv setzen
		if ($status == 's') {
			$sql = $dbs->prepare("UPDATE seiten SET status = 'a' WHERE status = 's'");
			$sql->execute();
		  $sql->close();
		}
		if (($art == 'b') || ($art == 't') || ($art == 'g')) {
			$sql = $dbs->prepare("UPDATE seiten SET art = 's' WHERE art = ?");
			$sql->bind_param("s", $art);
			$sql->execute();
		  $sql->close();
		}
		// Neue Seite einfügen
		$sql = $dbs->prepare("UPDATE seiten SET bezeichnung = ?, beschreibung = ?, art = ?, position = ?, sidebar = ?, status = ?, styles = ?, klassen = ? WHERE id = ?");
		$sql->bind_param("sssissssi", $bezeichnung, $beschreibung, $art, $position, $sidebar, $status, $styles, $klassen, $id);
		$sql->execute();
		$sql->close();

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
