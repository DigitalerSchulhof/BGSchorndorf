<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['person'])) {$person = $_POST['person'];} else {echo "FEHLER"; exit;}
if (isset($_POST['test'])) {$test = $_POST['test'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($person,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($test,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}

$gefunden = false;
$dbs = cms_verbinden('s');
$sql = $dbs->prepare("SELECT COUNT(*) FROM coronatest WHERE id = ? AND tester = ?");
$sql->bind_param("ii", $test, $CMS_BENUTZERID);
$sql->execute();
$sql->bind_result($anzahl);
if ($sql->fetch()) {
	if ($anzahl == 1) {$gefunden = true;}
}
$sql->close();
// Test laden
if (!$gefunden) {
	echo "FEHLER"; exit;
}

if (cms_angemeldet() && ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {
	// Testung löschen
	$sql = $dbs->prepare("DELETE FROM coronagetestet WHERE person = ? AND test = ?");
	$sql->bind_param("ii", $person, $test);
	$sql->execute();
	$sql->close();
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}

cms_trennen($dbs);
?>
