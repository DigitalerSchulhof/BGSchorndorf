<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) 						{$id = $_POST['id'];} 		else {echo "FEHLER";exit;}
if (isset($_POST['art'])) 					{$art = $_POST['art'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['standort'])) 			{$standort = $_POST['standort'];} 						else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($standort,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$zugriff = $CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if ($art == 'r') {$buchungstabelle = 'raeumebuchen';}
	else if ($art == 'l') {$buchungstabelle = 'leihenbuchen';}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
		if ($CMS_RECHTE['Organisation']['Buchungen löschen']) {
			$sql = $dbs->prepare("DELETE FROM $buchungstabelle WHERE id = ? AND standort = ?");
			$sql->bind_param("ii", $id, $standort);
		  $sql->execute();
		  $sql->close();
		}
		else {
			$sql = $dbs->prepare("DELETE FROM $buchungstabelle WHERE id = ? AND person = ? AND standort = ?");
			$sql->bind_param("iii", $id, $CMS_BENUTZERID, $standort);
		  $sql->execute();
		  $sql->close();
		}
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
