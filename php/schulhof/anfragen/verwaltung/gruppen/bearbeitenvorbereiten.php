<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['name'])) {$art = $_POST['name'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
cms_rechte_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $art, $id, $CMS_BENUTZERID);

if (isset($CMS_RECHTE['Gruppen'][$art.' bearbeiten'])) {$zugriff = $CMS_RECHTE['Gruppen'][$art.' bearbeiten'] || $CMS_GRUPPENRECHTE['bearbeiten'];}
else {$zugriff = false;}

$artk = cms_textzudb($art);
$artg = cms_vornegross($art);

if (cms_angemeldet() && $zugriff) {
	$_SESSION['Gruppen']['bearbeiten']['id'] = $id;
  $_SESSION['Gruppen']['bearbeiten']['art'] = $art;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
