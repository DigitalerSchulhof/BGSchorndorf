<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/funktionen/positionen.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Inhalte löschen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	$elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter', 'diashows');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Element laden
		$sql = "SELECT * FROM $art WHERE id = $id";
		if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
			if ($daten = $anfrage->fetch_assoc()) {
				cms_elemente_verschieben_loeschen($dbs, $daten['spalte'], $daten['position']);
			}
			$anfrage->free();
		}
		$sql = "DELETE FROM $art WHERE id = $id";
		$dbs->query($sql);	// Irgendwie safe machen
		if ($art == 'boxenaussen') {
			$sql = "DELETE FROM boxen WHERE boxaussen = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $id);
			$sql->execute();
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
