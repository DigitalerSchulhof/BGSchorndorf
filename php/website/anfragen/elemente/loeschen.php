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



$fehler = false;

$elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter');
if (!in_array($art, $elemente)) {$fehler = true;}

$rarten = array(
	"editoren" 	=> "editor",
	"downloads"	=> "download",
	"boxaussen"	=> "boxen",
	"eventuebersichten"	=> "eventübersicht",
	"kontaktformulare"	=> "kontaktformular",
	"wnewsletter"	=> "newsletter"
);

$rart = $rarten[$art];

if (cms_angemeldet() && cms_r("website.elemente.$rart.löschen")) {

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Element laden
		$sql = $dbs->prepare("SELECT * FROM $art WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
      $ergebnis = $sql->get_result();
      if ($daten = $ergebnis->fetch_assoc()) {
				cms_elemente_verschieben_loeschen($dbs, $daten['spalte'], $daten['position']);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM $art WHERE id = ?");
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
