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
if (isset($_POST['ausrichtung'])) {$ausrichtung = $_POST['ausrichtung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['breite'])) {$breite = $_POST['breite'];} else {echo "FEHLER"; exit;}
if (isset($_POST['boxenanzahl'])) {$boxenanzahl = $_POST['boxenanzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['boxenids'])) {$boxenids = $_POST['boxenids'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.elemente.boxen.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (($ausrichtung != 'u') && ($ausrichtung != 'n')) {$fehler = true;}
	if (!cms_check_ganzzahl($breite,0)) {$fehler = true;}

	if (!cms_r("website.freigeben")) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	if (!$fehler) {
		// Prüfen, ob es eine übergeordnete Seite gibt
		$sql = "SELECT COUNT(id) AS anzahl FROM spalten WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $spalte);
		if ($sql->execute()) {
			$sql->bind_result($anz);
			if ($sql->fetch()) {
				if (!$anz) {
					$fehler = true;
					echo "ZUORDNUNG";
				}
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		else {$fehler = true;}
	}

	// Boxen überprüfen
	$boxen = array();
	if ($boxenanzahl > 0) {
		$bids = explode('|', $boxenids);
		$sqlwhere = substr(implode(' OR ', $bids), 4);
		for ($i=1; $i<count($bids); $i++) {
			if (isset($_POST["baktiv_".$bids[$i]])) {$boxen[$i]['aktiv'] = $_POST["baktiv_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (($boxen[$i]['aktiv'] != '0') && ($boxen[$i]['aktiv'] != '1')) {$fehler = true; }
			if (!cms_r("website.freigeben")) {$boxen[$i]['aktiv'] = 0;}

			if (isset($_POST["bstyle_".$bids[$i]])) {$boxen[$i]['style'] = $_POST["bstyle_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (($boxen[$i]['style'] != '1') && ($boxen[$i]['style'] != '2') && ($boxen[$i]['style'] != '3') && ($boxen[$i]['style'] != '4') &&
				  ($boxen[$i]['style'] != '5')) {$fehler = true;}

			if (isset($_POST["btitel_".$bids[$i]])) {$boxen[$i]['titel'] = $_POST["btitel_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (strlen($boxen[$i]['titel']) < 1) {$fehler = true;}

			if (isset($_POST["binhalt_".$bids[$i]])) {$boxen[$i]['inhalt'] = $_POST["binhalt_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (strlen($boxen[$i]['inhalt']) < 1) {$fehler = true;}
		}
	}


	if (!$fehler) {
		// Klassenstufe EINTRAGEN
		$dbs = cms_verbinden('s');
		$id = cms_generiere_kleinste_id('boxenaussen');
		cms_elemente_verschieben_einfuegen($dbs, $spalte, $position);
		$sql = "UPDATE boxenaussen SET spalte = ?, position = ?, aktiv = ?, ausrichtungalt = ?, ausrichtungaktuell = ?, ausrichtungneu = ?, breitealt = ?, breiteaktuell = ?, breiteneu = ? WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("iiisssiiii", $spalte, $position, $aktiv, $ausrichtung, $ausrichtung, $ausrichtung, $breite, $breite, $breite, $id);
		$sql->execute();

		// Boxen eintragen
		$position = 1;
		for ($i=1; $i<=count($boxen); $i++) {
			$boxen[$i]['titel'] = cms_texttrafo_e_db($boxen[$i]['titel']);
			$boxen[$i]['inhalt'] = cms_texttrafo_e_db($boxen[$i]['inhalt']);
			$bid = cms_generiere_kleinste_id('boxen');
			$sql = "UPDATE boxen SET boxaussen = $id, position = $position, aktiv = '".$boxen[$i]['aktiv']."', ";
			$sql .= "titelalt = '".$boxen[$i]['titel']."', titelaktuell = '".$boxen[$i]['titel']."', titelneu = '".$boxen[$i]['titel']."', ";
			$sql .= "inhaltalt = '".$boxen[$i]['inhalt']."', inhaltaktuell = '".$boxen[$i]['inhalt']."', inhaltneu = '".$boxen[$i]['inhalt']."', ";
			$sql .= "stylealt = '".$boxen[$i]['style']."', styleaktuell = '".$boxen[$i]['style']."', styleneu = '".$boxen[$i]['style']."' ";
			$sql .= "WHERE id = $bid";
			$dbs->query($sql);	// TODO: Irgendwie safe machen
			$position++;
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
