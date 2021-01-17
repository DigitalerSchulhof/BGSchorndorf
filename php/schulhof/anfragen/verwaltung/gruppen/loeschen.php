<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['name'])) {$art = $_POST['name'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($art)) {echo "FEHLER"; exit;}



$zugriff = cms_r("schulhof.gruppen.$art.löschen");

$artk = cms_textzudb($art);
$artg = cms_vornegross($art);

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("DELETE FROM $artk WHERE id = ?");
  $sql->bind_param("i", $id);
  $sql->execute();
  $sql->close();

	$sql = $dbs->prepare("DELETE FROM notifikationen WHERE gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND gruppenid = ?");
  $sql->bind_param("si", $artk, $id);
  $sql->execute();
  $sql->close();

	$pfad = '../../../dateien/schulhof/gruppen/'.$artk."/".$id;
	if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}

	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
