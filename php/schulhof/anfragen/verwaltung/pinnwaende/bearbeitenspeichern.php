<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['beschreibung'])) {$beschreibung = $_POST['beschreibung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarl'])) {$sichtbarl = $_POST['sichtbarl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbars'])) {$sichtbars = $_POST['sichtbars'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbare'])) {$sichtbare = $_POST['sichtbare'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarv'])) {$sichtbarv = $_POST['sichtbarv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarx'])) {$sichtbarx = $_POST['sichtbarx'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schreibenl'])) {$schreibenl = $_POST['schreibenl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schreibens'])) {$schreibens = $_POST['schreibens'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schreibene'])) {$schreibene = $_POST['schreibene'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schreibenv'])) {$schreibenv = $_POST['schreibenv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schreibenx'])) {$schreibenx = $_POST['schreibenx'];} else {echo "FEHLER"; exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);
$beschreibung = cms_texttrafo_e_db($beschreibung);
if (isset($_SESSION["PINNWANDBEARBEITEN"])) {$id = $_SESSION["PINNWANDBEARBEITEN"];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.information.pinnwände.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sichtbarl)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sichtbars)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sichtbare)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sichtbarv)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($sichtbarx)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($schreibenl)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($schreibens)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($schreibene)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($schreibenv)) {echo "FEHLER"; exit;}
	if (!cms_check_toggle($schreibenx)) {echo "FEHLER"; exit;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		// Prüfen, ob es ein Raum mit dieser Bezeichnung vorliegt
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM pinnwaende WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	  $sql->bind_param("si", $bezeichnung, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
		cms_trennen($dbs);
	}

	if (!$fehler) {
		// PINNWAND EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE pinnwaende SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbars = ?, sichtbarl = ?, sichtbare = ?, sichtbarv = ?, sichtbarx = ?, schreibens = ?, schreibenl = ?, schreibene = ?, schreibenv = ?, schreibenx = ? WHERE id = ?");
	  $sql->bind_param("ssiiiiiiiiiii", $bezeichnung, $beschreibung, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $schreibens, $schreibenl, $schreibene, $schreibenv, $schreibenx, $id);
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
