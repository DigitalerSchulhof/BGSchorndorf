<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if(!cms_check_ganzzahl($id))
	die("FEHLER");

if (cms_angemeldet() && cms_r("website.freigeben")) {
	$fehler = false;

  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		if ($art == 'editroren') {$sql = "neu = aktuell";}
		else if ($art == 'downloads') {$sql = "pfadneu = pfadaktuell, titelneu = titelaktuell, beschreibungneu = beschreibungaktuell, dateinamealt = dateinameaktuell, dateigroessealt = dateigroesseaktuell";}
		else if ($art == 'boxenaussen') {
			$sql = "UPDATE boxen SET titelneu = titelaktuell, inhaltneu = inhaltaktuell, styleneu = styleaktuell WHERE boxaussen = '$id'";
			$dbs->query($sql);	// Safe weil ID Check
			$sql = "ausrichtungneu = ausrichtungaktuell, breiteneu = breiteaktuell";
		}
		else if ($art == 'eventuebersichten') {$sql = "termineneu = termineaktuell, termineanzahlneu = termineanzahlaktuell, blogneu = blogaktuell, bloganzahlneu = bloganzahlaktuell, galerieneu = galerieaktuell, galerieanzahlneu = galerieanzahlaktuell";}
		$sql = "UPDATE $art SET ".$sql."  WHERE id = $id";
		$dbs->query($sql);	// TODO: Irgendwie safe machen
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
