<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rechte'])) {$rechte = $_POST['rechte'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["ROLLEBEARBEITEN"])) {$id = $_SESSION["ROLLEBEARBEITEN"];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Rollen bearbeiten'];

if (cms_angemeldet() && $zugriff) {

	// Zusammenbauen der Bedingung
	$sqlwhere = '';
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($bezeichnung) == 0) {$fehler = true;}
	if (($art != 's') && ($art != 'l') && ($art != 'e') && ($art != 'v') && ($art != 'x')) {$fehler = true;}
	if (strlen($rechte) == 0) {$fehler = true;}
	if ($id == '') {$fehler = true;}
	// Administrator darf nicht verändert werden
	if ($id == 0) {$fehler = true;}


	$dbs = cms_verbinden('s');

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);
	// Prüfen, ob es bereits eine Rolle mit dieser Bezeichnung gibt
	$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM rollen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
  $sql->bind_param("si", $bezeichnung, $id);
	if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();
	cms_trennen($dbs);


	if (!$fehler) {
		// ROLLE ÄNDERN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE rollen SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), personenart = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
	  $sql->bind_param("ssi", $bezeichnung, $art, $id);
	  $sql->execute();
	  $sql->close();

		// Rechte der Rolle zuordnen
		// Alle Rechte der Rolle löschen
		$sql = $dbs->prepare("DELETE FROM rollenrechte WHERE rolle = ?;");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		$rechte = explode("|", $rechte);
		$sql = $dbs->prepare("INSERT INTO rollenrechte (rolle, recht) VALUES (?, ?);");
		for ($i = 1; $i <count($rechte); $i++) {
			// EINSTELLUNGEN DER PERSON EINTRAGEN
			$sql->bind_param("ii", $id, $rechte[$i]);
		  $sql->execute();
		}
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
