<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['grund'])) 				{$grund = cms_texttrafo_e_db($_POST['grund']);} 		else {echo "FEHLER";exit;}
if (isset($_POST['datumT'])) 				{$datumT = $_POST['datumT'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['datumM'])) 				{$datumM = $_POST['datumM'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['datumJ'])) 				{$datumJ = $_POST['datumJ'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['beginnS'])) 			{$beginnS = $_POST['beginnS'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['beginnM'])) 			{$beginnM = $_POST['beginnM'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['endeS'])) 				{$endeS = $_POST['endeS'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['endeM'])) 				{$endeM = $_POST['endeM'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['art'])) 					{$art = $_POST['art'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['standort'])) 			{$standort = $_POST['standort'];} 						else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($standort,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($datumT,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($datumM,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($datumJ,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($beginnS,0,23)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($beginnM,0,59)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($endeS,0,23)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($endeM,0,59)) {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}

cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if($art === "r") {$rart = "räume";}
else if($art === "l") {$rart = "leihgeräte";}

if (cms_angemeldet() && cms_r("schulhof.organisation.buchungen.$rart.vornehmen")) {
	$fehler = false;

	$beginn = mktime($beginnS, $beginnM, 0, $datumM, $datumT, $datumJ);
	$ende = mktime($endeS, $endeM, 0, $datumM, $datumT, $datumJ)-1;
  if ($beginn-$ende >= 0) {$fehler = true;}
	if (strlen($grund) == 0) {$fehler = true;}

	if ($art == 'r') {
		$buchungstabelle = 'raeumebuchen';
		$blockierungstabelle = 'raeumeblockieren';
		$standorttabelle = 'raeume';
	}
	else if ($art == 'l') {
		$buchungstabelle = 'leihenbuchen';
		$blockierungstabelle = 'leihenblockieren';
		$standorttabelle = 'leihen';
	}

	$dbs = cms_verbinden('s');
	// Prüfen, ob der Standort existiert
	$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM $standorttabelle WHERE id = ?");
  $sql->bind_param("i", $standort);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {echo "STANDORT"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Prüfen, ob eine andere Buchung vorliegt
	$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM $buchungstabelle WHERE ((? BETWEEN beginn AND ende) OR (? BETWEEN beginn AND ende) OR (? < beginn AND ? > ende)) AND standort = ?");
  $sql->bind_param("iiiii", $beginn, $ende, $beginn, $ende, $standort);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {echo "ÜBER"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Ermitteln, ob der Tag ein Ferientag ist
	$ferientag = false;
	$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM ferien WHERE (? BETWEEN beginn AND ende)");
  $sql->bind_param("i", $beginn);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {$ferientag = true;}}
  }
  $sql->close();

	// Prüfen, ob eine Blockierung vorliegt
	// Wochentag laden
	$wochentag = date('N', $beginn);
	$beginnS = date('H', $beginn);
	$beginnM = date('i', $beginn);
	$endeS = date('H', $ende);
	$endeM = date('i', $ende);
	$beginntag = $beginnS*60+$beginnM;
	$endetag = $endeS*60+$endeM-1;
	$blockfehler = false;
	$sql = $dbs->prepare("SELECT AES_DECRYPT(beginns, '$CMS_SCHLUESSEL'), AES_DECRYPT(beginnm, '$CMS_SCHLUESSEL'), AES_DECRYPT(endes, '$CMS_SCHLUESSEL'), AES_DECRYPT(endem, '$CMS_SCHLUESSEL'), ferien FROM $blockierungstabelle WHERE standort = ? AND wochentag = ?");
  $sql->bind_param("ii", $standort, $wochentag);
  if ($sql->execute()) {
    $sql->bind_result($blockbs, $blockbm, $blockes, $blockem, $blockf);
    while($sql->fetch()) {
			if (!$ferientag || ($blockf == '1'))
			$blockbeginn = $blockbs*60+$blockbm;
			$blockende = $blockes*60+$blockem-1;
			if (($beginntag <= $blockbeginn) && ($endetag >= $blockbeginn)) {$blockfehler = true;}
			if (($endetag >= $blockende) && ($beginntag <= $blockende)) {$blockfehler = true;}
			if (($beginntag >= $blockbeginn) && ($endetag <= $blockende)) {$blockfehler = true;}
    }
  }
  $sql->close();

	if ($blockfehler) {
		echo "ÜBER";
		$fehler = true;
	}

	if (!$fehler) {
    $id = cms_generiere_kleinste_id($buchungstabelle);
		// FERIEN EINTRAGEN
		$sql = $dbs->prepare("UPDATE $buchungstabelle SET standort = ?, person = ?, grund = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ? WHERE id = ?");
		$sql->bind_param("iisiii", $standort, $CMS_BENUTZERID, $grund, $beginn, $ende, $id);
	  $sql->execute();
	  $sql->close();
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
