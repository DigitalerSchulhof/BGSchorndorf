<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			    else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		    else {cms_anfrage_beenden(); exit;}
if (isset($_POST['shost'])) 				{$shost = $_POST['shost'];} 							else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sbenutzer'])) 		{$sbenutzer = $_POST['sbenutzer'];} 			else {cms_anfrage_beenden(); exit;}
if (isset($_POST['spass'])) 				{$spass = $_POST['spass'];} 							else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sdb'])) 					{$sdb = $_POST['sdb'];} 									else {cms_anfrage_beenden(); exit;}
if (isset($_POST['lhost'])) 				{$lhost = $_POST['lhost'];} 							else {cms_anfrage_beenden(); exit;}
if (isset($_POST['lbenutzer'])) 		{$lbenutzer = $_POST['lbenutzer'];}	 			else {cms_anfrage_beenden(); exit;}
if (isset($_POST['lpass'])) 				{$lpass = $_POST['lpass'];} 							else {cms_anfrage_beenden(); exit;}
if (isset($_POST['ldb'])) 					{$ldb = $_POST['ldb'];} 									else {cms_anfrage_beenden(); exit;}
if (isset($_POST['schueler'])) 			{$schueler = $_POST['schueler'];} 				else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

if (strlen($shost) == 0) 				{cms_anfrage_beenden(); exit;}
if (strlen($sbenutzer) == 0) 		{cms_anfrage_beenden(); exit;}
if (strlen($sdb) == 0) 					{cms_anfrage_beenden(); exit;}
if (strlen($lhost) == 0)				{cms_anfrage_beenden(); exit;}
if (strlen($lbenutzer) == 0) 		{cms_anfrage_beenden(); exit;}
if (strlen($ldb) == 0) 					{cms_anfrage_beenden(); exit;}
if (strlen($schueler) == 0) 		{cms_anfrage_beenden(); exit;}

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG
$zugriff = cms_r("technik.server.netze");

if ($angemeldet && $zugriff) {
  // CONFIG-Datei neu schreieben
	$pfad = '../../lehrerzimmer/funktionen/config.php';
	$datei = fopen($pfad, 'w');
	if ($datei) {
		$text  = "<?php\n";
		$text .= "$"."CMS_SCHLUESSEL = \"$CMS_SCHLUESSEL\";\n\n";
		$text .= "$"."CMS_SCHLUESSELL = \"$CMS_SCHLUESSELL\";\n";
		$text .= "$"."CMS_DBS_HOST = \"$shost\";\n";
		$text .= "$"."CMS_DBS_USER = \"$sbenutzer\";\n";
		$text .= "$"."CMS_DBS_PASS = \"$spass\";\n";
		$text .= "$"."CMS_DBS_DB = \"$sdb\";\n\n";
		$text .= "$"."CMS_DBL_HOST = \"$lhost\";\n";
		$text .= "$"."CMS_DBL_USER = \"$lbenutzer\";\n";
		$text .= "$"."CMS_DBL_PASS = \"$lpass\";\n";
		$text .= "$"."CMS_DBL_DB = \"$ldb\";\n\n";
		$text .= "$"."CMS_SH_SERVER = \"$schueler\";\n";
		$text .= "?>";
		fwrite($datei, $text);
		fclose($datei);
    cms_lehrerdb_header(true);
		echo "ERFOLG";
	}
	else {
    cms_lehrerdb_header(true);
		echo "DATEI kann nicht geöffnet werden - S | ";
		echo "FEHLER";
	}
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
