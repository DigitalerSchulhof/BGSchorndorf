<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['titel'])) {$titel = cms_texttrafo_e_db($_POST['titel']);} else {echo "FEHLER"; exit;}
if (isset($_POST['bild'])) {$bild = cms_texttrafo_e_db($_POST['bild']);} else {echo "FEHLER"; exit;}
if (isset($_POST['preis'])) {$preis = $_POST['preis'];} else {echo "FEHLER"; exit;}
if (isset($_POST['anzahl'])) {$anzahl = $_POST['anzahl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lieferzeit'])) {$lieferzeit = cms_texttrafo_e_db($_POST['lieferzeit']);} else {echo "FEHLER"; exit;}
if (isset($_POST['beschreibung'])) {$beschreibung = cms_texttrafo_e_db($_POST['beschreibung']);} else {echo "FEHLER"; exit;}

if (strlen($titel) < 1) {echo "FEHLER"; exit;}
if (strlen($titel) < 1) {echo "FEHLER"; exit;}
if (preg_match("/[0-9]+,[0-9]{2}$/", $preis) != 1) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($anzahl,1)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("shop.produkte.anlegen")) {
	$preis = str_replace(",", ".", $preis);
	$preis = $preis * 100;
	// NÄCHSTE FREIE ID SUCHEN
	$id = cms_generiere_kleinste_id('egeraete');
	// DAUERBRENNER EINTRAGEN
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE egeraete SET titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), bild = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), preis = ?, stk = ?, lieferzeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
  $sql->bind_param("sssiisi", $titel, $bild, $beschreibung, $preis, $anzahl, $lieferzeit, $id);
  $sql->execute();
  $sql->close();

	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
