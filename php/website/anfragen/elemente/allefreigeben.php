<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['seite'])) {$seite = $_POST['seite'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.freigeben")) {
	$fehler = false;

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Alle Spalten der Seite
		$sql = "SELECT id FROM spalten WHERE seite = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $seite);
		$SPALTEN = array();
		if ($sql->execute()) {
			$sql->bind_result($sid);
			while ($sql->fetch()) {
				array_push($SPALTEN, $sid);
			}
		}

		$sql = $dbs->prepare("UPDATE editoren SET alt = aktuell, aktuell = neu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE downloads SET pfadalt = pfadaktuell, pfadaktuell = pfadneu, titelalt = titelaktuell, titelaktuell = titelneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, dateinamealt = dateinameaktuell, dateinameaktuell = dateinameneu, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = dateigroesseneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE boxenaussen SET ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = ausrichtungalt, breitealt = breiteaktuell, breiteaktuell = breiteneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE boxen SET titelalt = titelaktuell, titelaktuell = titelneu, inhaltalt = inhaltaktuell, inhaltaktuell = inhaltneu, stylealt = styleaktuell, styleaktuell = styleneu WHERE boxaussen IN (SELECT id AS boxaussen FROM boxenaussen WHERE spalte = ?)");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE eventuebersichten SET terminealt = termineaktuell, termineaktuell = termineneu, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = termineanzahlneu, blogalt = blogaktuell, blogaktuell = blogneu, bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = bloganzahlneu, galeriealt = galerieaktuell, galerieaktuell = galerieneu, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = galerieanzahlneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE kontaktformulare SET betreffalt = betreffaktuell, betreffaktuell = betreffneu, kopiealt = kopieaktuell, kopieaktuell = kopieneu, anhangalt = anhangaktuell, anhangaktuell = anhangneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE wnewsletter SET bezeichnungalt = bezeichnungaktuell, bezeichnungaktuell = bezeichnungneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, typalt = typaktuell, typaktuell = typneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE diashows SET titelalt = titelaktuell, titelaktuell = titelneu WHERE spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE diashowbilder INNER JOIN diashows ON diashowbilder.diashow = diashows.id SET pfadalt = pfadaktuell, pfadaktuell = pfadneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu WHERE diashows.spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("DELETE diashowbilder FROM diashowbilder INNER JOIN diashows ON diashowbilder.diashow = diashows.id WHERE diashowbilder.pfadalt = '' AND diashowbilder.pfadaktuell = '' AND diashowbilder.pfadneu = '' AND diashows.spalte = ?");
		$sql->bind_param("i", $sid);
		foreach ($SPALTEN AS $sid) {
			$sql->execute();
		}
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
