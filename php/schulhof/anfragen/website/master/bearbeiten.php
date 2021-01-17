<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['fuss'])) {$fuss = cms_texttrafo_e_db($_POST['fuss']);} else {echo "FEHLER"; exit;}
if (isset($_POST['anmelden'])) {$anmelden = cms_texttrafo_e_db($_POST['anmelden']);} else {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("website.masterelemente")) {
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE master SET wert = ? WHERE inhalt = ?");
	$inhalt = 'Fußzeile';
	$sql->bind_param("ss", $fuss, $inhalt);
	$sql->execute();
	$inhalt = 'Anmelden';
	$sql->bind_param("ss", $anmelden, $inhalt);
	$sql->execute();
	$sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
