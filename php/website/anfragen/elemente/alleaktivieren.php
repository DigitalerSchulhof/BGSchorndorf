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
	  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten');

		$dbs = cms_verbinden('s');
		// Alle Spalten der Seite
		$sql = "SELECT id FROM spalten WHERE seite = '$seite'";
		if ($anfrage = $dbs->query($sql)) {
			while ($spalten = $anfrage->fetch_assoc()) {
				// Alle Elemente dieser Spalte aktivieren
				foreach ($elemente as $e) {
					$sql = "UPDATE $e SET aktiv = '1' WHERE spalte = '".$spalten['id']."'";
					$dbs->query($sql);
					if ($e == 'boxenaussen') {
						$sql = "UPDATE boxen SET aktiv = '1' WHERE boxaussen IN (SELECT id AS boxaussen FROM boxenaussen WHERE spalte = '".$spalten['id']."')";
						$dbs->query($sql);
					}
				}
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
