<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
postLesen("bezeichnung", "rechte");
if (isset($_SESSION["ROLLEBEARBEITEN"])) {$id = $_SESSION["ROLLEBEARBEITEN"];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.rollen.bearbeiten")) {

	$rechte = explode(",", $rechte);
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($bezeichnung) == 0) {$fehler = true;}
	if(!count($rechte) || $rechte[0] == "") {
		die("ERFOLG");
	}
	// Administrator darf nicht verändert werden
	if ($id == 0) {$fehler = true;}


	$dbs = cms_verbinden('s');

	if(!cms_check_nametitel($bezeichnung))
		die("BEZEICHNUNG");
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
		$sql = $dbs->prepare("UPDATE rollen SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
	  $sql->bind_param("si", $bezeichnung, $id);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("DELETE FROM rollenrechte WHERE rolle = ?;");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		$sql = "INSERT INTO rollenrechte (`rolle`, `recht`) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $id, $recht);
		foreach($rechte as $recht) {
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
