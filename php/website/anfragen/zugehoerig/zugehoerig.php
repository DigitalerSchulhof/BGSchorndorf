<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../website/seiten/zugehoerig/zugehoerig.php");

session_start();

$fehler = false;
// Variablen einlesen, falls übergeben
if (isset($_POST['url'])) {$url = $_POST['url'];} else {$fehler = true;}
if (isset($_POST['feldid'])) {$feldid = $_POST['feldid'];} else {$fehler = true;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {$fehler = true;}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {$fehler = true;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {$fehler = true;}

if (!$fehler) {
	$dbs = cms_verbinden('s');
  $ausgabe = cms_zugehoerig_jahr_ausgeben ($dbs, $feldid, $gruppe, $gruppenid, $jahr, $url);
	cms_trennen($dbs);
	echo $ausgabe;
}
?>
