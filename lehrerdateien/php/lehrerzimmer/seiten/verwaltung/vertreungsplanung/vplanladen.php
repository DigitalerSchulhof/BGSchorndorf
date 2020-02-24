<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
// Variablen einlesen, falls übergeben
$fehler = false;
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kennung'])) {$kennung = $_POST['kennung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zeit'])) {$zeit = $_POST['zeit'];} else {echo "FEHLER"; exit;}
if (($art != 'l') && ($art != 's')) {echo "FEHLER"; exit;}
if (($zeit != 'h') && ($zeit != 'm')) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$dbl = cms_verbinden('l');
if (!$fehler) {
  $inhalt = "VPlan".strtoupper($art);
  $gefunden = false;
  $sql = $dbs->prepare("SELECT COUNT(*) FROM internedienste WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
  $sql->bind_param("ss", $inhalt, $kennung);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
      if ($anzahl > 0) {$gefunden = true;}
    }
  }
  $sql->close();

  $code = "";
  if ($gefunden)  {
    include_once("../../lehrerzimmer/seiten/verwaltung/vertreungsplanung/vplaninternausgeben.php");
    if ($zeit == 'h') {
      $code = cms_vertretungsplan_komplettansicht_heute($dbs, $dbl, $art);
    }
    else if ($zeit == 'm') {
      $code = cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, $dbl, $art);
    }
    cms_lehrerdb_header(true);
    echo $code;
  }
  else {
    cms_lehrerdb_header(false);
    echo "BERECHTIGUNG";
  }
}
else {
  cms_lehrerdb_header(true);
  echo "FEHLER";
}
cms_trennen($dbl);
cms_trennen($dbs);
?>
