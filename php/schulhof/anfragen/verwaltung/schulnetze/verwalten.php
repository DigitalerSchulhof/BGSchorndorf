<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['shost'])) 	{$shost = $_POST['shost'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['sbenutzer'])) {$sbenutzer = $_POST['sbenutzer'];} 	else {echo "FEHLER";exit;}
if (isset($_POST['spass'])) 	{$spass = $_POST['spass'];} 						else {echo "FEHLER";exit;}
if (isset($_POST['sdb'])) 		{$sdb = $_POST['sdb'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['base'])) 		{$base = $_POST['base'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['lnzbvpn'])) 	{$lnzbvpn = $_POST['lnzbvpn'];} 			else {echo "FEHLER";exit;}
if (isset($_POST['lnda'])) 		{$lnda = $_POST['lnda'];} 							else {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("technik.server.netze")) {
	$fehler = false;

	if (strlen($shost) == 0) {$fehler = true;}
	if (strlen($sbenutzer) == 0) {$fehler = true;}
	if (strlen($sdb) == 0) {$fehler = true;}

	if (!$fehler) {
		// CONFIG-Datei neu schreieben
		$pfad = '../../schulhof/funktionen/';
		chmod($pfad, 0777);
		$datei = fopen($pfad.'config.php', 'w');
		if ($datei) {
			include_once("../../schulhof/anfragen/verwaltung/configaendern.php");
			$CMS_DBS_HOST  = $shost;
			$CMS_DBS_USER  = $sbenutzer;
			$CMS_DBS_PASS  = $spass;
			$CMS_DBS_DB    = $sdb;
			$CMS_BASE      = $base;
			$CMS_LN_ZB_VPN = $lnzbvpn;
			$CMS_LN_DA 	   = $lnda;
			$text = cms_configaendern();
			fwrite($datei, $text);
			fclose($datei);
			echo "ERFOLG";
		}
		else {
			echo "DATEI kann nicht geöffnet werden - S | ";
			echo "FEHLER";
		}
		chmod($pfad, 0775);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
