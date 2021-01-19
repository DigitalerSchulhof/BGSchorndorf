<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();
if (isset($_POST['datumT'])) 				{$datumT = $_POST['datumT'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['datumM'])) 				{$datumM = $_POST['datumM'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['datumJ'])) 				{$datumJ = $_POST['datumJ'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['anliegen'])) 			{$anliegen = cms_texttrafo_e_db($_POST['anliegen']);} 	else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];}  else {echo "FEHLER";exit;}

$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if ((!cms_check_ganzzahl($datumT, 1, 31)) || (!cms_check_ganzzahl($datumM, 1, 12)) || (!cms_check_ganzzahl($datumJ, 0))) {
  echo "FEHLER";exit;
}
if (strlen($anliegen) == 0) {echo "FEHLER";exit;}

$zugriff = cms_r("lehrerzimmer.vertretungsplan.wünsche");

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');

  $datum = mktime(0,0,0,$datumM, $datumT, $datumJ);
  $wunschid = cms_generiere_kleinste_id('vplanwuensche');
	// WUNSCH EINTRAGEN
  $jetzt = time();
  $sql = $dbs->prepare("UPDATE vplanwuensche SET datum = ?, wunsch = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), status = 0, ersteller = ?, erstellzeit = ? WHERE id = ?");
  $sql->bind_param("isiii", $datum, $anliegen, $CMS_BENUTZERID, $jetzt, $wunschid);
  $sql->execute();
  $sql->close();

	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
