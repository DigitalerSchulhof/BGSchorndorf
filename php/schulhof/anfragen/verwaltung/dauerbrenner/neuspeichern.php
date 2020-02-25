<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarl'])) {$sichtbarl = $_POST['sichtbarl'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbars'])) {$sichtbars = $_POST['sichtbars'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbare'])) {$sichtbare = $_POST['sichtbare'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarv'])) {$sichtbarv = $_POST['sichtbarv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbarx'])) {$sichtbarx = $_POST['sichtbarx'];} else {echo "FEHLER"; exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);
$inhalt = cms_texttrafo_e_db($inhalt);



if (cms_angemeldet() && cms_r("schulhof.information.dauerbrenner.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}
	if (!cms_check_toggle($sichtbarl)) {$fehler = true;}
	if (!cms_check_toggle($sichtbars)) {$fehler = true;}
	if (!cms_check_toggle($sichtbare)) {$fehler = true;}
	if (!cms_check_toggle($sichtbarv)) {$fehler = true;}
	if (!cms_check_toggle($sichtbarx)) {$fehler = true;}


	if (!$fehler) {
		$dbs = cms_verbinden('s');

		// Prüfen, ob es ein Raum mit dieser Beezichnung vorliegt
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM dauerbrenner WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $bezeichnung);
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
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('dauerbrenner');
		// DAUERBRENNER EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE dauerbrenner SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbars = ?, sichtbarl = ?, sichtbare = ?, sichtbarv = ?, sichtbarx = ? WHERE id = ?");
	  $sql->bind_param("ssiiiiii", $bezeichnung, $inhalt, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $id);
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
