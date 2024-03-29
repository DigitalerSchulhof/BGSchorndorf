<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/seiten/verwaltung/gruppen/auflisten.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['name'])) {$art = $_POST['name'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($art)) {echo "FEHLER"; exit;}



$loeschen 	= cms_r("schulhof.gruppen.$art.löschen");
$bearbeiten = cms_r("schulhof.gruppen.$art.bearbeiten");
$zugriff = $bearbeiten || $loeschen;

$CMS_IMLN = false;
if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];}

$artk = cms_textzudb($art);
$artg = cms_vornegross($art);

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	echo cms_gruppen_verwaltung_listeausgeben_schuljahr($dbs, $art, $bearbeiten, $loeschen, $schuljahr);
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
