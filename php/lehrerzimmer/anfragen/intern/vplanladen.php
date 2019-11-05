<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../allgemein/funktionen/sql.php");
// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER5";exit;}
if (isset($_POST['kennung'])) {$kennung = $_POST['kennung'];} else {echo "FEHLER4";exit;}
if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];} else {echo "FEHLER3";exit;}
if (($art != 'l') && ($art != 's')) {echo "FEHLER2";exit;}
if ($CMS_IMLN != 1) {echo "FEHLER1"; exit;}

session_start();

$inhalt = "VPlan".strtoupper($art);
$gefunden = false;
$dbs = cms_verbinden('s');
$sql = $dbs->prepare("SELECT COUNT(*) FROM internedienste WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
$sql->bind_param("ss", $inhalt, $kennung);
if ($sql->execute()) {
  $sql->bind_result($anzahl);
  if ($sql->fetch()) {
    if ($anzahl > 0) {$gefunden = true;}
  }
}
$sql->close();

if ($gefunden) {
  include_once('../../schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
  include_once('../../lehrerzimmer/seiten/intern/vplanladen.php');
  echo cms_vplan_laden($dbs, 's');
}
else {echo "BERECHTIGUNG";}

cms_trennen($dbs);
?>
