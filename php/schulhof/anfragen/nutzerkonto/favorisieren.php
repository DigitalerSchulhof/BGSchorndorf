<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['fid'])) {$fid = $_POST['fid'];} else {echo "FEHLER";exit;}
if (isset($_POST['seite'])) {$seite = $_POST['seite'];} else {echo "FEHLER";exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}
if (!cms_check_url($seite)) {echo "FEHLER";exit;}
if (!cms_check_toggle($status)) {echo "FEHLER";exit;}
if ($status == '0') {
  if (!cms_check_ganzzahl($fid,0)) {echo "FEHLER";exit;}
}

if (cms_angemeldet()) {
  $dbs = cms_verbinden("s");
  $seite = cms_texttrafo_e_db($seite);
  $sql = "";

  if ($status == '1') {
    $fid = cms_generiere_kleinste_id('favoritseiten');
    $sql = $dbs->prepare("UPDATE favoritseiten SET url = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), person = ? WHERE id = ?");
    $bez = explode("/", $seite);
    $bez = str_replace("_", " ", $bez[count($bez) - 1]);
    $sql->bind_param("ssii", $seite, $bez, $CMS_BENUTZERID, $fid);
    $sql->execute();
    $sql->close();
  }
  else {
    $sql = $dbs->prepare("DELETE FROM favoritseiten WHERE person = ? AND id = ?;");
    $sql->bind_param("ii", $CMS_BENUTZERID, $fid);
    $sql->execute();
    $sql->close();
  }

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
