<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['seite'])) {$seite = $_POST['seite'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte freigeben'];

if (cms_angemeldet() && $zugriff) {
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
		foreach ($SPALTEN AS $sid) {
			$sql->bind_param("i", $sid);
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE downloads SET pfadalt = pfadaktuell, pfadaktuell = pfadneu, titelalt = titelaktuell, titelaktuell = titelneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, dateinamealt = dateinameaktuell, dateinameaktuell = dateinameneu, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = dateigroesseneu WHERE spalte = ?");
		foreach ($SPALTEN AS $sid) {
			$sql->bind_param("i", $sid);
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE boxenaussen SET ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = ausrichtungalt, breitealt = breiteaktuell, breiteaktuell = breiteneu WHERE spalte = ?");
		foreach ($SPALTEN AS $sid) {
			$sql->bind_param("i", $sid);
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE boxen SET titelalt = titelaktuell, titelaktuell = titelneu, inhaltalt = inhaltaktuell, inhaltaktuell = inhaltneu, stylealt = styleaktuell, styleaktuell = styleneu WHERE boxaussen IN (SELECT id AS boxaussen FROM boxenaussen WHERE spalte = ?)");
		foreach ($SPALTEN AS $sid) {
			$sql->bind_param("i", $sid);
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("UPDATE eventuebersichten SET terminealt = termineaktuell, termineaktuell = termineneu, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = termineanzahlneu, blogalt = blogaktuell, blogaktuell = blogneu, bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = bloganzahlneu, galeriealt = galerieaktuell, galerieaktuell = galerieneu, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = galerieanzahlneu WHERE spalte = ?");
		foreach ($SPALTEN AS $sid) {
			$sql->bind_param("i", $sid);
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
