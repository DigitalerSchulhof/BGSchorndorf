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
if (isset($_POST['bezeichnung'])) {$bezeichnung = cms_texttrafo_e_db($_POST['bezeichnung']);} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($fid, 0)) {echo "FEHLER";exit;}
if (strlen($bezeichnung) == 0) {echo "FEHLER"; exit;}

if(cms_angemeldet()) {
  $dbs = cms_verbinden("s");

  $sql = "UPDATE favoritseiten SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE person = ? AND id = ?;";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("sis", $bezeichnung, $CMS_BENUTZERID, $fid);
  $sql->execute();

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
