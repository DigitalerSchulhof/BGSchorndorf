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

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte freigeben'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		if ($art == 'editroren') {$sql = "neu = aktuell";}
		else if ($art == 'downloads') {$sql = "pfadneu = pfadaktuell, titelneu = titelaktuell, beschreibungneu = beschreibungaktuell, dateinamealt = dateinameaktuell, dateigroessealt = dateigroesseaktuell";}
		else if ($art == 'boxenaussen') {
			$sql = "UPDATE boxen SET titelneu = titelaktuell, inhaltneu = inhaltaktuell, styleneu = styleaktuell WHERE boxaussen = '$id'";
			$dbs->query($sql);
			$sql = "ausrichtungneu = ausrichtungaktuell, breiteneu = breiteaktuell";
		}
		else if ($art == 'eventuebersichten') {$sql = "termineneu = termineaktuell, termineanzahlneu = termineanzahlaktuell, blogneu = blogaktuell, bloganzahlneu = bloganzahlaktuell, galerieneu = galerieaktuell, galerieanzahlneu = galerieanzahlaktuell";}
		$sql = "UPDATE $art SET ".$sql."  WHERE id = $id";
		$dbs->query($sql);
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