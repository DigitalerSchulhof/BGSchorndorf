<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("seite", "bezeichnung"));
$CMS_BENUTZERID = $_SESSION['BENUTZERID'] ?? "lasagne";
if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {die("FEHLER");}

if(!cms_angemeldet())
  die("FEHLER");

$dbs = cms_verbinden("s");
$seite = cms_texttrafo_e_db($seite);
$bezeichnung = cms_texttrafo_e_db($bezeichnung);

$sql = "";
$sql = "UPDATE favoritseiten SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE person = ? AND url = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');";
$sql = $dbs->prepare($sql);
$sql->bind_param("sis", $bezeichnung, $CMS_BENUTZERID, $seite);
$sql->execute();

echo "ERFOLG";
?>
