<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['aktiv'])) {$aktiv = $_POST['aktiv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['position'])) {$position = $_POST['position'];} else {echo "FEHLER"; exit;}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {echo "FEHLER"; exit;}
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beschreibung'])) {$beschreibung = $_POST['beschreibung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['dateiname'])) {$dateiname = $_POST['dateiname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['dateigroesse'])) {$dateigroesse = $_POST['dateigroesse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.elemente.download.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (($dateiname != 0) && ($dateiname != 1)) {$fehler = true;}
	if (($dateigroesse != 0) && ($dateigroesse != 1)) {$fehler = true;}
	if (strlen($titel) < 1) {$fehler = true;}
	if (strlen($pfad) < 1) {$fehler = true;}
	if (!is_file("../../../".$pfad)) {
		echo "DATEI";
		$fehler = true;
	}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos) {$fehler = true;}

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);
		$titel = cms_texttrafo_e_db($titel);
		$beschreibung = cms_texttrafo_e_db($beschreibung);
		if (!cms_r("website.freigeben")) {
			$sql = $dbs->prepare("UPDATE downloads SET position = ?, pfadneu = ?, titelneu = ?, beschreibungneu = ?, dateinameneu = ?, dateigroesseneu = ? WHERE id = ?");
			$sql->bind_param("isssssi", $position, $pfad, $titel, $beschreibung, $dateiname, $dateigroesse, $id);
		}
		else {
			$sql = $dbs->prepare("UPDATE downloads SET position = ?, aktiv = ?, pfadalt = pfadaktuell, pfadaktuell = ?, pfadneu = ?, titelalt = titelaktuell, titelaktuell = ?, titelneu = ?, beschreibungalt = beschreibungaktuell, beschreibungaktuell = ?, beschreibungneu = ?, dateinamealt = dateinameaktuell, dateinameaktuell = ?, dateinameneu = ?, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = ?, dateigroesseneu = ? WHERE id = ?");
			$sql->bind_param("isssssssssssi", $position, $aktiv, $pfad, $pfad, $titel, $titel, $beschreibung, $beschreibung, $dateiname, $dateiname, $dateigroesse, $dateigroesse, $id);
		}
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
