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

if (cms_angemeldet() && r("website.freigeben")) {
	$fehler = false;

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Alle Spalten der Seite
		$sql = "SELECT id FROM spalten WHERE seite = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $seite);
		if ($sql->execute()) {
			$sql->bind_result($sid);
			while ($sql->fetch()) {
				// Alle Elemente dieser Spalte aktivieren
				$sql = "UPDATE editoren SET alt = aktuell, aktuell = neu WHERE spalte = '$sid'";
				$dbs->query($sql);	// Safe weil interne ID
				$sql = "UPDATE downloads SET pfadalt = pfadaktuell, pfadaktuell = pfadneu, titelalt = titelaktuell, titelaktuell = titelneu, beschreibungalt = beschreibungaktuell, beschreibungaktuell = beschreibungneu, dateinamealt = dateinameaktuell, dateinameaktuell = dateinameneu, dateigroessealt = dateigroesseaktuell, dateigroesseaktuell = dateigroesseneu WHERE spalte = '$sid'";
				$dbs->query($sql);	// Safe weil interne ID
				$sql = "UPDATE boxenaussen SET ausrichtungalt = ausrichtungaktuell, ausrichtungaktuell = ausrichtungalt, breitealt = breiteaktuell, breiteaktuell = breiteneu WHERE spalte = '$sid'";
				$dbs->query($sql);	// Safe weil interne ID
				$sql = "UPDATE boxen SET titelalt = titelaktuell, titelaktuell = titelneu, inhaltalt = inhaltaktuell, inhaltaktuell = inhaltneu, stylealt = styleaktuell, styleaktuell = styleneu WHERE boxaussen IN (SELECT id AS boxaussen FROM boxenaussen WHERE spalte = '$sid')";
				$dbs->query($sql);	// Safe weil interne ID
				$sql = "UPDATE eventuebersichten SET terminealt = termineaktuell, termineaktuell = termineneu, termineanzahlalt = termineanzahlaktuell, termineanzahlaktuell = termineanzahlneu, blogalt = blogaktuell, blogaktuell = blogneu, bloganzahlalt = bloganzahlaktuell, bloganzahlaktuell = bloganzahlneu, galeriealt = galerieaktuell, galerieaktuell = galerieneu, galerieanzahlalt = galerieanzahlaktuell, galerieanzahlaktuell = galerieanzahlneu WHERE spalte = '$sid'";
				$dbs->query($sql);	// Safe weil interne ID
			}
			$anfrage->free();
		}
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
