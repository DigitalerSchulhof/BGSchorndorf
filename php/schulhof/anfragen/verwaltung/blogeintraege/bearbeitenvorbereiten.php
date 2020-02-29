<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}
if (isset($_POST['ziel'])) {$ziel = $_POST['ziel'];} else {echo "FEHLER";exit;}


// Zugriffssteuerung je nach Gruppe
$zugriff = false;
$fehler = false;

$dbs = cms_verbinden('s');
$sql = $dbs->prepare("SELECT datum, oeffentlichkeit, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM blogeintraege WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($datum, $oeffentlichkeit, $bezeichnung);
	if (!$sql->fetch()) {$fehler = true;}
}
else {$fehler = true;}
$sql->close();
cms_trennen($dbs);

if(!cms_check_ganzzahl($oeffentlichkeit, 0, 4)) {
  die("FEHLER");
}

if (cms_r("artikel.$oeffentlichkeit.blogeinträge.bearbeiten")) {
	$zugriff = true;
}

if($fehler) {
	die("FEHLER");
}

if (cms_angemeldet() && $zugriff) {
	$_SESSION["BLOGEINTRAGID"] = $id;
  $_SESSION["BLOGEINTRAGZIEL"] = $ziel;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
