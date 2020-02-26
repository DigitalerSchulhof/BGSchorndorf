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



if(!cms_check_ganzzahl($spalte))
	die("FEHLER");

if (cms_angemeldet() && cms_r("website.elemente.editor.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (strlen($inhalt) < 1) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}


	if (!cms_r("website.freigeben")) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// Prüfen, ob es eine übergeordnete Seite gibt
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM spalten WHERE id = ?");
		$sql->bind_param("i", $spalte);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl == 0) {
					$fehler = true;
					echo "ZUORDNUNG";
				}
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
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
		$sql = $dbs->prepare("UPDATE editoren SET spalte = ?, position = ?, alt = ?, aktuell = ?, neu = ?, aktiv = ? WHERE id = ?");
		$sql->bind_param("iissssi", $spalte, $position, $inhalt, $inhalt, $inhalt, $aktiv, $id);
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
