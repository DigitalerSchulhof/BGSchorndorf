<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER";exit;}
if (isset($_POST['kuerzel'])) {$kuerzel = $_POST['kuerzel'];} else {echo "FEHLER";exit;}
if (isset($_SESSION["FAECHERBEARBEITEN"])) {$id = $_SESSION["FAECHERBEARBEITEN"];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {$fehler = true;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Fächer bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($bezeichnung) == 0) {$fehler = true;}
	if (strlen($kuerzel) == 0) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	  $sql->bind_param("si", $bezeichnung, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTB"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	  $sql->bind_param("si", $kuerzel, $id);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl > 0) {echo "DOPPELTK"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		cms_trennen($dbs);
	}

	if (!$fehler) {
		// Fächer EINTRAGEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE faecher SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	  $sql->bind_param("ssi", $bezeichnung, $kuerzel, $id);
	  $sql->execute();
	  $sql->close();
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
