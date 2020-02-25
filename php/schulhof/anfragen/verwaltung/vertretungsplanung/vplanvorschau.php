<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.planung.vertretungsplan.vertretungsplanung")) {
  $dbs = cms_verbinden('s');
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $he = mktime(0,0,0,$monat, $tag+1, $jahr)-1;
  $code = "";

  include_once('../../schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');

  $code .= "<h4>Lehrervertretungsplan</h4>";
  $code .= cms_vertretungsplan_komplettansicht($dbs, 'lv', $hb, $he, '1', '-');
  $code .= "<h4>Schülervertretungsplan</h4>";
  $code .= cms_vertretungsplan_komplettansicht($dbs, 'sv', $hb, $he, '1', '-');




  echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
cms_trennen($dbs);
?>
