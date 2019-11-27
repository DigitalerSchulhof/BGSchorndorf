<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen(array("seite", "status"));
$CMS_BENUTZERID = $_SESSION['BENUTZERID'] ?? "tomate";
if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {die("FEHLER");}

$status = $status === "true";

if(!cms_angemeldet())
  die("FEHLER");

$dbs = cms_verbinden("s");
$seite = cms_texttrafo_e_db($seite);
$sql = "";
if($status) {
  $sql = "INSERT INTO favoritseiten (person, url, bezeichnung) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'));";
  $sql = $dbs->prepare($sql);
  $bez = explode("/", $seite)[count(explode("/", $seite)) - 1];
  $sql->bind_param("iss", $CMS_BENUTZERID, $seite, $bez);
} else {
  $sql = "DELETE FROM favoritseiten WHERE person = ? AND url = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("is", $CMS_BENUTZERID, $seite);
}
$sql->execute();

echo "ERFOLG";
?>
