<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.planung.vertretungsplan.ausplanungen")) {
  $dbs = cms_verbinden('s');
  $jetzt = mktime(0,0,0,$monat, $tag, $jahr);
  $KLASSEN = "";
  $sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr IN (SELECT id FROM schuljahre WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY reihenfolge, bezeichnung ASC";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $jetzt, $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($id, $bez, $r);
    while ($sql->fetch()) {
      $KLASSEN .= "<option value=\"$id\">$bez</option>";
    }
  }
  $sql->close();
  cms_trennen($dbs);
  echo $KLASSEN;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
