<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($gruppe)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');

$zugriff = ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v');

if (cms_angemeldet() && $zugriff) {
	$_SESSION['Coronatest']['gruppenid'] = $id;
  $_SESSION['Coronatest']['gruppenart'] = $gruppe;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
