<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['name'])) 			{$name = $_POST['name'];} 				else {echo "FEHLER"; exit;}
if (isset($_POST['namegenitiv'])) 	{$namegenitiv = $_POST['namegenitiv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ort'])) 			{$ort = $_POST['ort'];} 				else {echo "FEHLER"; exit;}
if (isset($_POST['strasse'])) 		{$strasse = $_POST['strasse'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['plzort'])) 		{$plzort = $_POST['plzort'];} 			else {echo "FEHLER"; exit;}
if (isset($_POST['webmaster'])) 	{$webmaster = $_POST['webmaster'];} 	else {echo "FEHLER"; exit;}
if (isset($_POST['domain'])) 	{$domain = $_POST['domain'];} 	else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Administration']['Adressen des Schulhofs verwalten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!cms_check_mail($webmaster)) {
		$fehler = true;
	}

	if (!$fehler) {
		// CONFIG-Datei neu schreieben
		$pfad = '../../schulhof/funktionen/';
		chmod($pfad, 0777);
		$datei = fopen('../../schulhof/funktionen/config.php', 'w');
		if ($datei) {
			include_once("../../schulhof/anfragen/verwaltung/configaendern.php");
			$CMS_SCHULE  				= $name;
			$CMS_SCHULE_GENITIV = $namegenitiv;
			$CMS_ORT  		 			= $ort;
			$CMS_STRASSE    		= $strasse;
			$CMS_PLZORT  	 			= $plzort;
			$CMS_WEBMASTER  		= $webmaster;
			$CMS_DOMAIN  				= $domain;
			$text 							= cms_configaendern();
			fwrite($datei, $text);
			fclose($datei);
			echo "ERFOLG";
		}
		else {
			echo "FEHLER";
		}
		chmod($pfad, 0755);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
