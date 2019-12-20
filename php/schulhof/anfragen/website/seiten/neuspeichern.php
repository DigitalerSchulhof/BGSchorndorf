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
if (!cms_check_ganzzahl($position, 0)) {echo "FEHLER";exit;}
if (isset($_POST['sidebar'])) {$sidebar = $_POST['sidebar'];} else {echo "FEHLER"; exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {echo "FEHLER"; exit;}
if (isset($_POST['styles'])) {$styles = $_POST['styles'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zuordnung'])) {$zuordnung = $_POST['zuordnung'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Seiten anlegen'];

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

	if ($zuordnung != '-') {if (!cms_check_ganzzahl($zuordnung,0)) {$fehler = true;}}

	if (($art == 'm') && ($status == 's')) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);
	$beschreibung = cms_texttrafo_e_db($beschreibung);
	$styles = cms_texttrafo_e_db($styles);
	$klassen = cms_texttrafo_e_db($klassen);

	$dbs = cms_verbinden('s');
	if (!$fehler && ($zuordnung != '-')) {
		// Prüfen, ob es eine übergeordnete Seite gibt
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM seiten WHERE id = ?");
	  $sql->bind_param("i", $zuordnung);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl == 0) {echo "ZUORDNUNG"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {
		// Prüfen, ob die Position nicht über max+1 liegt
		if ($zuordnung != '-') {
			$sql = $dbs->prepare("SELECT MAX(position) AS maxpos FROM seiten WHERE zuordnung = ?");
		  $sql->bind_param("i", $zuordnung);
		}
		else {
			$sql = $dbs->prepare("SELECT MAX(position) AS maxpos FROM seiten WHERE zuordnung IS NULL");
		}

	  if ($sql->execute()) {
	    $sql->bind_result($maxpos);
	    if ($sql->fetch()) {if ($position > $maxpos+1) {echo "MAXPOS"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}

	// Prüfen, ob es bereits eine Seite mit dieser Bezeichnung gibt
	if (!$fehler) {
		if ($zuordnung != '-') {
			$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM seiten WHERE zuordnung = ? AND bezeichnung = ?");
		  $sql->bind_param("is", $zuordnung, $bezeichnung);
		}
		else {
			$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM seiten WHERE zuordnung IS NULL AND bezeichnung = ?");
		  $sql->bind_param("s", $bezeichnung);
		}
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
		$id = cms_generiere_kleinste_id('seiten');
		$spid = cms_generiere_kleinste_id('spalten');

		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		// Nachfolgende Seiten aufrutschen lassen
		if ($zuordnung != '-') {
			$sql = $dbs->prepare("UPDATE seiten SET position = position + 1 WHERE zuordnung = ? AND position >= ?");
		  $sql->bind_param("ii", $zuordnung, $position);
		}
		else {
			$sql = $dbs->prepare("UPDATE seiten SET position = position + 1 WHERE zuordnung IS NULL AND position >= ?");
			$sql->bind_param("i", $position);
		}
	  $sql->execute();
	  $sql->close();

		// Falls es eine neue Startseite gibt, alte Startseite auf aktiv setzen
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
		if ($zuordnung != '-') {
			$sql = $dbs->prepare("UPDATE seiten SET bezeichnung = ?, beschreibung = ?, art = ?, position = ?, zuordnung = ?, sidebar = ?, status = ?, styles = ?, klassen = ? WHERE id = ?");
			$sql->bind_param("sssiissssi", $bezeichnung, $beschreibung, $art, $position, $zuordnung, $sidebar, $status, $styles, $klassen, $id);
		}
		else {
			$sql = $dbs->prepare("UPDATE seiten SET bezeichnung = ?, beschreibung = ?, art = ?, position = ?, zuordnung = NULL, sidebar = ?, status = ?, styles = ?, klassen = ? WHERE id = ?");
			$sql->bind_param("sssissssi", $bezeichnung, $beschreibung, $art, $position, $sidebar, $status, $styles, $klassen, $id);
		}

		$sql->execute();
		$sql->close();

		// Neue Spalte einfügen
		$sql = $dbs->prepare("UPDATE spalten SET seite = ?, zeile = 1, position = 1 WHERE id = ?");
		$sql->bind_param("ii", $id, $spid);
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
