<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['beginnT'])) 				{$beginnT = $_POST['beginnT'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnM'])) 				{$beginnM = $_POST['beginnM'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnJ'])) 				{$beginnJ = $_POST['beginnJ'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['endeT'])) 					{$endeT = $_POST['endeT'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endeM'])) 					{$endeM = $_POST['endeM'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['endeJ'])) 					{$endeJ = $_POST['endeJ'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['bezeichnung'])) 		{$bezeichnung = $_POST['bezeichnung'];} 				else {echo "FEHLER";exit;}
if (isset($_POST['art'])) 						{$art = $_POST['art'];} 												else {echo "FEHLER";exit;}

cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if (cms_angemeldet() && cms_r("schulhof.organisation.ferien.anlegen"))) {
	$fehler = false;

	$mehrtaegigt = 0;
	if (($beginnM != $endeM) || ($beginnT != $endeT) || ($beginnJ != $endeJ)) {$mehrtaegigt = 1;}

	$beginn = mktime(0, 0, 0, $beginnM, $beginnT, $beginnJ);
	$ende = mktime(23, 59, 59, $endeM, $endeT, $endeJ);
  if ($beginn-$ende >= 0) {$fehler = true;}
	if (($art != 'f') && ($art != 'b') && ($art != 't') && ($art != 's')) {$fehler = true;}
	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	$bezeichnung = cms_texttrafo_e_db($bezeichnung);

	if (!$fehler) {
    $id = cms_generiere_kleinste_id('ferien');
		// FERIEN EINTRAGEN
    $dbs = cms_verbinden('s');

		$sql = $dbs->prepare("UPDATE ferien SET bezeichnung = ?, art = ?, beginn = ?, ende = ?, mehrtaegigt = ? WHERE id = ?");
	  $sql->bind_param("ssiiii", $bezeichnung, $art, $beginn, $ende, $mehrtaegigt, $id);
	  $sql->execute();
	  $sql->close();
  	cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
