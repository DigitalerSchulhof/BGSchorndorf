<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kommentar'])) {$kommentar = cms_texttrafo_e_db($_POST['kommentar']);} else {echo "FEHLER"; exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETESTANDORT"])) {$standort = $_SESSION["GERAETESTANDORT"];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETEART"])) {$art = $_SESSION["GERAETEART"];} else {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($standort, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($status, 1,4)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.technik.geräte.verwalten || schulhof.technik.hausmeisteraufträge.erteilen")) {

	$dbs = cms_verbinden('s');
	if ($art == 'l') {$geraetetabelle = 'leihengeraete';}
	else if ($art == 'r') {$geraetetabelle = 'raeumegeraete';}

	// GERÄT ANPASSEN
	$sql = $dbs->prepare("UPDATE $geraetetabelle SET statusnr = ?, kommentar = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE standort = ? AND id = ?");
  $sql->bind_param("isii", $status, $kommentar, $standort, $id);
  $sql->execute();
  $sql->close();

	$_SESSION['GERAETEPROBLEMDELIGIERENID'] = $id;
	$_SESSION['GERAETEPROBLEMDELIGIERENART'] = $art;
	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
