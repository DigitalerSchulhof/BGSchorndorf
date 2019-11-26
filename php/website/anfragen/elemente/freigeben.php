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

if(!cms_check_ganzzahl($id))
	die("FEHLER");

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		if ($art == 'editoren') {$sql = "alt = aktuell, aktuell = neu";}
		if ($art == 'downloads') {$sql = "pfadalt = pfadaktuell, pfadaktuell = pfadneu, titelalt = titelaktuell, titelaktuell = titelneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, dateinamealt = dateinameaktuell, dateinameaktuell = dateinameneu, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = dateigroesseneu";}
		if ($art == 'boxenaussen') {
			$sql = "UPDATE boxen SET titelalt = titelaktuell, titelaktuell = titelneu, inhaltalt = inhaltaktuell, inhaltaktuell = inhaltneu, stylealt = styleaktuell, styleaktuell = styleneu WHERE boxaussen = $id";
			$dbs->query($sql);	// Safe weil ID Check
			$sql = "ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = ausrichtungneu, breitealt = breiteaktuell, breiteaktuell = breiteneu";
		}
		if ($art == 'eventuebersichten') {$sql = "terminealt = termineaktuell, termineaktuell = termineneu, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = termineanzahlneu, blogalt = blogaktuell, blogaktuell = blogneu, bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = bloganzahlneu, galeriealt = galerieaktuell, galerieaktuell = galerieneu, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = galerieanzahlneu";}
		$sql = "UPDATE $art SET ".$sql." WHERE id = $id";
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
