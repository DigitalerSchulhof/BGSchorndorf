<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['seite'])) {$seite = $_POST['seite'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.freigeben")) {
	$fehler = false;

	if (!$fehler) {
		$elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter', 'diashows');

		$dbs = cms_verbinden('s');
		$SPALTEN = array();
		// Alle Spalten der Seite
		$sql = "SELECT id FROM spalten WHERE seite = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $seite);
		if ($sql->execute()) {
			$sql->bind_result($sid);
			while ($sql->fetch()) {
				array_push($SPALTEN, $sid);
			}
		}
		$sql->close();

		foreach ($SPALTE as $s) {
			// Alle Elemente dieser Spalte aktivieren
			foreach ($elemente as $e) {
				$sql = $dbs->prepare("UPDATE $e SET aktiv = '1' WHERE spalte = ?");
				$sql->bind_param("i", $sid);
				$sql->execute();
				$sql->close();

				if ($e == 'boxenaussen') {
					$sql = $dbs->prepare("UPDATE boxen SET aktiv = '1' WHERE boxaussen IN (SELECT id AS boxaussen FROM boxenaussen WHERE spalte = ?)");
					$sql->bind_param("i", $sid);
					$sql->execute();
					$sql->close();
				}
			}
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
