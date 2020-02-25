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


$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if (cms_angemeldet()) {
	$fehler = false;

	if ($art == 'r') {$buchungstabelle = 'raeumebuchen'; $rart = "räume";}
	else if ($art == 'l') {$buchungstabelle = 'leihenbuchen'; $rart = "leihgeräte"}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
		if (cms_r("schulhof.organisation.buchungen.$rart.löschen")) {
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
