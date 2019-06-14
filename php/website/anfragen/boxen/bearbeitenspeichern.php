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
if (isset($_SESSION['ELEMENTPOSITION'])) {$altposition = $_SESSION['ELEMENTPOSITION'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTSPALTE'])) {$spalte = $_SESSION['ELEMENTSPALTE'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ELEMENTID'])) {$id = $_SESSION['ELEMENTID'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte bearbeiten'];


if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (($aktiv != 0) && ($aktiv != 1)) {$fehler = true;}
	if (!cms_check_ganzzahl($position,0)) {$fehler = true;}
	if (($ausrichtung != 'u') && ($ausrichtung != 'n')) {$fehler = true;}
	if (!cms_check_ganzzahl($breite,0)) {$fehler = true;}

	if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$aktiv = 0;}

	$dbs = cms_verbinden('s');
	$maxpos = cms_maxpos_spalte($dbs, $spalte);
	if ($position > $maxpos+1) {$fehler = true;}

	// Boxen überprüfen
	$boxen = array();
	if ($boxenanzahl > 0) {
		$bids = explode('|', $boxenids);
		$sqlwhere = substr(implode(' OR ', $bids), 4);
		for ($i=1; $i<count($bids); $i++) {
			$boxen[$i]['id'] = $bids[$i];
			if (isset($_POST["baktiv_".$bids[$i]])) {$boxen[$i]['aktiv'] = $_POST["baktiv_".$bids[$i]];} else {echo "FEHLER"; exit;}
			if (($boxen[$i]['aktiv'] != '0') && ($boxen[$i]['aktiv'] != '1')) {$fehler = true; }
			if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$boxen[$i]['aktiv'] = 0;}

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
		$dbs = cms_verbinden('s');
		cms_elemente_verschieben_aendern($dbs, $spalte, $altposition, $position);
		if (!$CMS_RECHTE['Website']['Inhalte freigeben']) {$sql = "UPDATE boxenaussen SET position = $position, ausrichtungneu = '$ausrichtung', breiteneu = '$breite' WHERE id = $id";}
		else {$sql = "UPDATE boxenaussen SET position = $position, ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = '$ausrichtung', ausrichtungneu = '$ausrichtung', breitealt = breiteaktuell, breiteaktuell = '$breite', breiteneu = '$breite', aktiv = '$aktiv' WHERE id = $id";}
		$anfrage = $dbs->query($sql);


		$eingetragen = array();
		// Boxen eintragen
		$position = 1;
		for ($i=1; $i<=count($boxen); $i++) {
			$boxen[$i]['titel'] = cms_texttrafo_e_db($boxen[$i]['titel']);
			$boxen[$i]['inhalt'] = cms_texttrafo_e_db($boxen[$i]['inhalt']);
			if (substr($bids[$i],0,4) == 'temp') {
				$bid = cms_generiere_kleinste_id('boxen');
				$sql = "UPDATE boxen SET boxaussen = $id, position = $position, aktiv = '".$boxen[$i]['aktiv']."', ";
				$sql .= "titelalt = '".$boxen[$i]['titel']."', titelaktuell = '".$boxen[$i]['titel']."', titelneu = '".$boxen[$i]['titel']."', ";
				$sql .= "inhaltalt = '".$boxen[$i]['inhalt']."', inhaltaktuell = '".$boxen[$i]['inhalt']."', inhaltneu = '".$boxen[$i]['inhalt']."', ";
				$sql .= "stylealt = '".$boxen[$i]['style']."', styleaktuell = '".$boxen[$i]['style']."', styleneu = '".$boxen[$i]['style']."' ";
				$sql .= "WHERE id = $bid";
				array_push($eingetragen, $bid);
			}
			else {
				if ($CMS_RECHTE['Website']['Inhalte freigeben']) {
					$sql = "UPDATE boxen SET position = $position, aktiv = '".$boxen[$i]['aktiv']."', ";
					$sql .= "titelalt = titelaktuell, titelaktuell = '".$boxen[$i]['titel']."', titelneu = '".$boxen[$i]['titel']."', ";
					$sql .= "inhaltalt = inhaltaktuell, inhaltaktuell = '".$boxen[$i]['inhalt']."', inhaltneu = '".$boxen[$i]['inhalt']."', ";
					$sql .= "stylealt = styleaktuell, styleaktuell = '".$boxen[$i]['style']."', styleneu = '".$boxen[$i]['style']."' ";
					$sql .= "WHERE id = ".$boxen[$i]['id']." AND boxaussen = $id";
				}
				else {
					$sql = "UPDATE boxen SET position = $position, ";
					$sql .= "titelneu = '".$boxen[$i]['titel']."', ";
					$sql .= "inhaltneu = '".$boxen[$i]['inhalt']."', ";
					$sql .= "styleneu = '".$boxen[$i]['style']."' ";
					$sql .= "WHERE id = ".$boxen[$i]['id']." AND boxaussen = $id";
				}
				array_push($eingetragen, $boxen[$i]['id']);
			}
			$dbs->query($sql);
			$position++;
		}
		// Lösche Boxen, die nicht mehr dazu gehören
		$sqlwhere = "";
		foreach ($eingetragen as $e) {
			$sqlwhere .= " AND id != ".$e;
		}
		$sql = "DELETE FROM boxen WHERE boxaussen = $id".$sqlwhere;
		$dbs->query();

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