<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("aktiv", "position", "titel", "bildanzahl", "bildids"));
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.elemente.diashow.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (!cms_check_titel($titel)) {$fehler = true;}

	if (!cms_r("website.freigeben")) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	$dbs = cms_verbinden('s');

	$bilder = array();
  if ($bildanzahl > 0) {
		$bildids = explode('|', $bildids);

		for ($i=1; $i<count($bildids); $i++) {
      $dd = array();
			if (isset($_POST["bbeschreibung_".$bildids[$i]])) {$dd['beschreibung'] = $_POST["bbeschreibung_".$bildids[$i]];} else {echo "FEHLER"; exit;}
			if (isset($_POST["bid_".$bildids[$i]])) {$dd['id'] = $_POST["bid_".$bildids[$i]];} else {echo "FEHLER"; exit;}

			if (isset($_POST["bpfad_".$bildids[$i]])) {$dd['pfad'] = $_POST["bpfad_".$bildids[$i]];} else {echo "FEHLER"; exit;}
			if (!is_file('../../../'.$dd['pfad'])) {$fehler = true; }
			$dd["beschreibung"] = cms_texttrafo_e_db($dd["beschreibung"]);

      array_push($bilder, $dd);
		}
	}

	if (!$fehler) {
		$dbs = cms_verbinden("s");
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);

		if (!cms_r("website.freigeben")) {
			$sql = "UPDATE diashows SET spalte = ?, position = ?, aktiv = ?, titelneu = ? WHERE id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("iiisi", $spalte, $position, $aktiv, $titel, $id);
			$sql->execute();

			// Ohne neue Daten "löschen"
			$sql = $dbs->prepare("UPDATE diashowbilder SET pfadneu = '' WHERE diashow = ?");
			$sql->bind_param("i", $id);
			$sql->execute();

			$sql = $dbs->prepare("UPDATE diashowbilder SET diashow = ?, pfadneu = ?, beschreibungneu = ? WHERE id = ?");
			$sql->bind_param("issi", $id, $pfad, $beschreibung, $bid);
			foreach ($bilder as $b) {
				$pfad = $b["pfad"];
				$beschreibung = $b["beschreibung"];
				$bid = $b["id"];
				if($bid == -1) {
					$bid = cms_generiere_kleinste_id('diashowbilder');
				}
	      $sql->execute();
			}
	    $sql->close();
		} else {
			$sql = "UPDATE diashows SET spalte = ?, position = ?, aktiv = ?, titelalt = ?, titelaktuell = ?, titelneu = ? WHERE id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("iiisssi", $spalte, $position, $aktiv, $titel, $titel, $titel, $id);
			$sql->execute();

			// Ohne neue Daten "löschen"
			$sql = $dbs->prepare("UPDATE diashowbilder SET pfadalt = '', pfadaktuell = '', pfadneu = '' WHERE diashow = ?");
			$sql->bind_param("i", $id);
			$sql->execute();

			$sql = $dbs->prepare("UPDATE diashowbilder SET diashow = ?, pfadalt = ?, pfadaktuell = ?, pfadneu = ?, beschreibungalt = ?, beschreibungaktuell = ?, beschreibungneu = ? WHERE id = ?");
			$sql->bind_param("issssssi", $id, $pfad, $pfad, $pfad, $beschreibung, $beschreibung, $beschreibung, $bid);
			foreach ($bilder as $b) {
				$pfad = $b["pfad"];
				$beschreibung = $b["beschreibung"];
				$bid = $b["id"];
				if($bid == -1) {
					$bid = cms_generiere_kleinste_id('diashowbilder');
				}
	      $sql->execute();
			}
	    $sql->close();
		}

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
