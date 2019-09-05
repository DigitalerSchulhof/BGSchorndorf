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
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte anlegen'];


if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (strlen($inhalt) < 1) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}


	if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// Prüfen, ob es eine übergeordnete Seite gibt
		$sql = "SELECT COUNT(id) AS anzahl FROM spalten WHERE id = '$spalte'";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				if ($daten['anzahl'] == 0) {
					$fehler = true;
					echo "ZUORDNUNG";
				}
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		else {$fehler = true;}
	}

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('editoren');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_einfuegen($dbs, $spalte, $position);
		$inhalt = str_replace('<br></p>', '</p>', $inhalt);
		$inhalt = str_replace('<p></p>', '', $inhalt);
		$inhalt = cms_texttrafo_e_db($inhalt);
		$sql = "UPDATE editoren SET spalte = $spalte, position = $position, alt = '$inhalt', aktuell = '$inhalt', neu = '$inhalt', aktiv = '$aktiv' WHERE id = $id";
		$anfrage = $dbs->query($sql);
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
