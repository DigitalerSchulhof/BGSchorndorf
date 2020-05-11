<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();
// Variablen einlesen, falls übergeben
postLesen("bezeichnung", "beschreibung", "id");
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0) && $id != '-') {echo "FEHLER";exit;}
if(!cms_check_titel($bezeichnung)) {echo "FEHLER"; exit;}
$beschreibung = cms_texttrafo_e_db($beschreibung);

if (cms_angemeldet()) {
  $dbs = cms_verbinden('s');

  $sql = "SELECT id FROM todo WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND person = ? AND id != ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("sii", $bezeichnung, $CMS_BENUTZERID, $id);
  $sql->execute();
  if($sql->fetch()) {
    die("DOPPELT");
  }

  if($id == '-') {
    $id = cms_generiere_kleinste_id("todo");
    $sql = $dbs->prepare("UPDATE todo SET person = ? WHERE id = ?");
    $sql->bind_param("ii", $CMS_BENUTZERID, $id);
    $sql->execute();
    $sql->close();
  }
  $sql = "UPDATE todo SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ? AND person = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ssii", $bezeichnung, $beschreibung, $id, $CMS_BENUTZERID);
  $sql->execute();
  $sql->close();

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
