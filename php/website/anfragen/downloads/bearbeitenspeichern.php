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

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte bearbeiten'];


if (cms_angemeldet() && $zugriff) {
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
		if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {
			$sql = "UPDATE downloads SET position = $position, pfadneu = '$pfad', titelneu = '$titel', beschreibungneu = '$beschreibung', dateinameneu = '$dateiname', dateigroesseneu = '$dateigroesse' WHERE id = $id";
		}
		else {$sql = "UPDATE downloads SET position = $position, aktiv = '$aktiv', pfadalt = pfadaktuell, pfadaktuell = '$pfad', pfadneu = '$pfad', titelalt = titelaktuell, titelaktuell = '$titel', titelneu = '$titel', beschreibungalt = beschreibungaktuell, beschreibungaktuell = '$beschreibung', beschreibungneu = '$beschreibung', dateinamealt = dateinameaktuell, dateinameaktuell = '$dateiname', dateinameneu = '$dateiname', dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = '$dateigroesse', dateigroesseneu = '$dateigroesse' WHERE id = $id";}
		$anfrage = $dbs->query($sql);	// TODO: Irgendwie safe machen
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
