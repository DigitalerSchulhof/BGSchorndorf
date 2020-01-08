<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['absender'])) 		{$absender = $_POST['absender'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['host'])) 				{$host = $_POST['host'];} 						else {echo "FEHLER"; exit;}
if (isset($_POST['benutzer'])) 		{$benutzer = $_POST['benutzer'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['passwort'])) 		{$passwort = $_POST['passwort'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['smtpauth'])) 		{$smtpauth = $_POST['smtpauth'];} 		else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.schule.mail"))) {
	$fehler = false;

	if (!cms_check_mail($absender)) {
		$fehler = true;
	}

	if (($smtpauth != 1) && ($smtpauth != 0)) {
		$fehler = true;
	}

	if (!$fehler) {
		if ($smtpauth == 1) {$smtpauth = true;}
		else {$smtpauth = false;}

		// CONFIG-Datei neu schreieben
		$pfad = '../../schulhof/funktionen/';
		chmod($pfad, 0777);
		$datei = fopen('../../schulhof/funktionen/config.php', 'w');
		if ($datei) {
			include_once("../../schulhof/anfragen/verwaltung/configaendern.php");
			$CMS_MAILABSENDER  	= $absender;
			$CMS_MAILHOST 			= $host;
			$CMS_MAILSMTPAUTH  	= $smtpauth;
			$CMS_MAILUSERNAME   = $benutzer;
			$CMS_MAILPASSWORT  	= $passwort;
			$text = cms_configaendern();
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
