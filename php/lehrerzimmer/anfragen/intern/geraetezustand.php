<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/meldungen.php");
session_start();
$fehler = false;
$angemeldet = false;

// Variablen einlesen, falls übergeben
if (isset($_POST['kennung'])) {$url = $_POST['kennung'];} else {$fehler = true;}

$dbs = cms_verbinden('s');

$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('Gerätekennung', '$CMS_SCHLUESSEL')");
if ($sql->execute()) {
  $sql->bind_result($kennung);
  $sql->fetch();
}
$sql->close();

if ($url == $kennung) {$angemeldet = true;}

if ($fehler) {echo cms_meldung_bastler();}
if (!$angemeldet) {echo cms_meldung_berechtigung();}

if ($angemeldet && !$fehler) {
  include_once("../../lehrerzimmer/seiten/intern/geraetezustandladen.php");
  echo cms_geraetezustand_laden($dbs);
}

cms_trennen($dbs);
?>
