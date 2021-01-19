<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['shost'])) 				{$shost = $_POST['shost'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['sbenutzer'])) 		{$sbenutzer = $_POST['sbenutzer'];} 			else {echo "FEHLER";exit;}
if (isset($_POST['spass'])) 				{$spass = $_POST['spass'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['sdb'])) 					{$sdb = $_POST['sdb'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['phost'])) 				{$phost = $_POST['phost'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['pbenutzer'])) 		{$pbenutzer = $_POST['pbenutzer'];}	 			else {echo "FEHLER";exit;}
if (isset($_POST['ppass'])) 				{$ppass = $_POST['ppass'];} 							else {echo "FEHLER";exit;}
if (isset($_POST['pdb'])) 					{$pdb = $_POST['pdb'];} 									else {echo "FEHLER";exit;}

if (strlen($shost) == 0) 				{echo "FEHLER";exit;}
if (strlen($sbenutzer) == 0) 		{echo "FEHLER";exit;}
if (strlen($sdb) == 0) 					{echo "FEHLER";exit;}
if (strlen($phost) == 0)				{echo "FEHLER";exit;}
if (strlen($pbenutzer) == 0) 		{echo "FEHLER";exit;}
if (strlen($pdb) == 0) 					{echo "FEHLER";exit;}

if (cms_angemeldet() && cms_r("technik.server.netze")) {
	// CONFIG-Datei neu schreieben
	$pfad = '../../schulhof/funktionen/config.php';
	$datei = fopen($pfad, 'w');
	if ($datei) {
		$text  = "<?php\n";
		$text .= "$"."CMS_SCHLUESSEL = \"$CMS_SCHLUESSEL\";\n\n";
		$text .= "$"."CMS_DBS_HOST = \"$shost\";\n";
		$text .= "$"."CMS_DBS_USER = \"$sbenutzer\";\n";
		$text .= "$"."CMS_DBS_PASS = \"$spass\";\n";
		$text .= "$"."CMS_DBS_DB = \"$sdb\";\n\n";
		$text .= "$"."CMS_DBP_HOST = \"$phost\";\n";
		$text .= "$"."CMS_DBP_USER = \"$pbenutzer\";\n";
		$text .= "$"."CMS_DBP_PASS = \"$ppass\";\n";
		$text .= "$"."CMS_DBP_DB = \"$pdb\";\n";
		$text .= "?>";
		fwrite($datei, $text);
		fclose($datei);
		echo "ERFOLG";
	}
	else {
		echo "DATEI kann nicht geöffnet werden - S | ";
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
