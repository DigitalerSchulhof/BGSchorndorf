<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['aktiv'])) {$aktiv = $_POST['aktiv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bild'])) {$bild = $_POST['bild'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['position'])) {$position = $_POST['position'];} else {echo "FEHLER"; exit;}
if (isset($_POST['link'])) {$link = $_POST['link'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ziel'])) {$ziel = $_POST['ziel'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["AUSZEICHNUNGBEAREITENID"])) {$id = $_SESSION["AUSZEICHNUNGBEAREITENID"];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}
if (!cms_check_toggle($aktiv)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($position, 1)) {echo "FEHLER";exit;}
if (strlen($bild) == 0) {echo "FEHLER";exit;}
if (strlen($bezeichnung) == 0) {echo "FEHLER";exit;}
if (($ziel != '_self') && ($ziel != '_blank')) {echo "FEHLER";exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);
if (!preg_match("/^dateien\/website\/([\_\-a-zA-Z0-9]+\/)*[\_\-a-zA-Z0-9]+\.((tar\.gz)|([a-zA-Z0-9]{2,10}))$/", $bild)) {echo "FEHLER"; exit;}
if (!preg_match("/^(https?:\/\/)?[a-zA-ZÄÖÜäöü]+[\_\-a-zA-ZÄÖÜäöü\.\/]*$/", $link)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.auszeichnungen.anlegen")) {
	$fehler = false;
	$maxreihenfolge = null;

	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("SELECT MAX(reihenfolge) FROM auszeichnungen");
	if ($sql->execute()) {
		$sql->bind_result($maxreihenfolge);
		if ($sql->fetch()) {
			if ($maxreihenfolge === null) {$maxreihenfolge = 0;}
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();
	if ($maxreihenfolge === null) {$fehler = true;}

	if ($position > $maxreihenfolge) {$fehler = true;echo "POSITION";}

	// Alte Reihenfolge laden
	$altpos = null;
	$sql = $dbs->prepare("SELECT reihenfolge FROM auszeichnungen WHERE id = ?");
	$sql->bind_param("i", $id);
	if ($sql->execute()) {
		$sql->bind_result($altpos);
		$sql->fetch();
	} else {$fehler = true;}
	$sql->close();
	if ($altpos === null) {$fehler = true;}


	if (!$fehler) {
		if ($altpos != $position) {
			// Auszeichnungen ab der alten Position nach oben verschieben
			$sql = $dbs->prepare("UPDATE auszeichnungen SET reihenfolge = reihenfolge-1 WHERE reihenfolge >= ?");
			$sql->bind_param("i", $altpos);
			$sql->execute();
			$sql->close();
			// Auszeichnungen ab der neuen Position nach unten verschieben
			$sql = $dbs->prepare("UPDATE auszeichnungen SET reihenfolge = reihenfolge+1 WHERE reihenfolge >= ?");
			$sql->bind_param("i", $position);
			$sql->execute();
			$sql->close();
		}

		// Auszeichnung bearbeiten
		$sql = $dbs->prepare("UPDATE auszeichnungen SET bild = ?, bezeichnung = ?, link = ?, ziel = ?, reihenfolge = ?, aktiv = ? WHERE id = ?");
		$sql->bind_param("ssssiii", $bild, $bezeichnung, $link, $ziel, $position, $aktiv, $id);
		$sql->execute();
		$sql->close();
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
