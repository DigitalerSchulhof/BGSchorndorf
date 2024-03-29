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



if(!cms_check_ganzzahl($id))
	die("FEHLER");

if (cms_angemeldet() && cms_r("website.freigeben")) {
	$fehler = false;

	$elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter', 'diashows');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		if ($art == 'editoren') {$sql = "alt = aktuell, aktuell = neu";}
		if ($art == 'downloads') {$sql = "pfadalt = pfadaktuell, pfadaktuell = pfadneu, titelalt = titelaktuell, titelaktuell = titelneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, dateinamealt = dateinameaktuell, dateinameaktuell = dateinameneu, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = dateigroesseneu";}
		if ($art == 'boxenaussen') {
			$sql = $dbs->prepare("UPDATE boxen SET titelalt = titelaktuell, titelaktuell = titelneu, inhaltalt = inhaltaktuell, inhaltaktuell = inhaltneu, stylealt = styleaktuell, styleaktuell = styleneu WHERE boxaussen = ?");
			$sql->bind_param("i", $id);
			$sql->execute();
			$sql->close();
			$sql = "ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = ausrichtungneu, breitealt = breiteaktuell, breiteaktuell = breiteneu";
		}
		if ($art == 'eventuebersichten') {$sql = "terminealt = termineaktuell, termineaktuell = termineneu, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = termineanzahlneu, blogalt = blogaktuell, blogaktuell = blogneu, bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = bloganzahlneu, galeriealt = galerieaktuell, galerieaktuell = galerieneu, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = galerieanzahlneu";}
		if ($art == 'kontaktformulare') {$sql = "betreffalt = betreffaktuell, betreffaktuell = betreffneu, kopiealt = kopieaktuell, kopieaktuell = kopieneu, anhangalt = anhangaktuell, anhangaktuell = anhangneu";}
		if ($art == 'wnewsletter') {$sql = "bezeichnungalt = bezeichnungaktuell, bezeichnungaktuell = bezeichnungneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, typalt = typaktuell, typaktuell = typneu";}
		if ($art == 'diashows') {
			$sql = $dbs->prepare("UPDATE diashowbilder INNER JOIN diashows ON diashowbilder.diashow = diashows.id SET pfadalt = pfadaktuell, pfadaktuell = pfadneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu WHERE diashows.id = ?");
			$sql->bind_param("i", $id);
			$sql->execute();
			$sql = $dbs->prepare("DELETE diashowbilder FROM diashowbilder INNER JOIN diashows ON diashowbilder.diashow = diashows.id WHERE diashowbilder.pfadalt = '' AND diashowbilder.pfadaktuell = '' AND diashowbilder.pfadneu = '' AND diashows.id = ?");
			$sql->bind_param("i", $id);
			$sql->execute();
			$sql = "titelalt = titelaktuell, titelaktuell = titelneu";
		}
		$sql = $dbs->prepare("UPDATE $art SET ".$sql." WHERE id = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();
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
