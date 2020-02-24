<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['lhost'])) 		{$lhost = $_POST['lhost'];} 				else {$lhost = '';}
if (isset($_POST['lbenutzer'])) {$lbenutzer = $_POST['lbenutzer'];} else {$lbenutzer = '';}
if (isset($_POST['lpass'])) 		{$lpass = $_POST['lpass'];} 				else {$lpass = '';}
if (isset($_POST['ldb'])) 			{$ldb = $_POST['ldb'];} 						else {$ldb = '';}
if (isset($_POST['shserver'])) 	{$shserver = $_POST['shserver'];} 	else {$shserver = '';}
if (isset($_POST['nutzerid'])) 	{$nutzerid = $_POST['nutzerid'];} 	else {$nutzerid = '';}
if (isset($_POST['sessionid'])) {$sessionid = $_POST['sessionid'];} else {$sessionid = '';}
if (isset($_POST['dbshost'])) 	{$dbshost = $_POST['dbshost'];} 		else {$dbshost = '';}
if (isset($_POST['dbsuser'])) 	{$dbsuser = $_POST['dbsuser'];} 		else {$dbsuser = '';}
if (isset($_POST['dbspass'])) 	{$dbspass = $_POST['dbspass'];} 		else {$dbspass = '';}
if (isset($_POST['dbsdb'])) 		{$dbsdb = $_POST['dbsdb'];} 				else {$dbsdb = '';}
if (isset($_POST['dbsschluessel'])) {$dbsschluessel = $_POST['dbsschluessel'];} else {$dbsschluessel = '';}

$CMS_IV = substr($sessionid, 0, 16);
$CMS_BENUTZERID 	 = openssl_decrypt ($nutzerid, 'aes128', $CMS_IV, 0, $CMS_IV);
$CMS_SCHLUESSEL  = openssl_decrypt ($dbsschluessel, 'aes128', $CMS_IV, 0, $CMS_IV);
$CMS_SESSIONID  = $sessionid;
$CMS_DBS_HOST	= $dbshost;
$CMS_DBS_USER	= $dbsuser;
$CMS_DBS_PASS	= $dbspass;
$CMS_DBS_DB		= $dbsdb;

include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/check.php");

$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Administration']['Schulnetze verwalten'];

if ($angemeldet && $zugriff) {
	$fehler = false;

	if (strlen($lhost) == 0) {$fehler = true;}
	if (strlen($lbenutzer) == 0) {$fehler = true;}
	if (strlen($ldb) == 0) {$fehler = true;}

	if (!$fehler) {
		// CONFIG-Datei neu schreieben
		$pfad = '../../lehrerzimmer/funktionen/';
		chmod($pfad, 0777);
		$datei = fopen($pfad.'config.php', 'w');
		if ($datei) {
			$CMS_DBL_HOST = $lhost;
			$CMS_DBL_USER = $lbenutzer;
			$CMS_DBL_PASS = $lpass;
			$CMS_DBL_DB   = $ldb;
			$CMS_SH_SERVER = $shserver;
			$text  = '<?php'."\r\n";
			$text .= '$CMS_SCHLUESSELL = "'.cms_dateischreiben_vorbereiten($CMS_SCHLUESSELL).'";'."\r\n";
			$text .= "\r\n";
			$text .= '$CMS_DBL_HOST = "'.cms_dateischreiben_vorbereiten($CMS_DBL_HOST).'";'."\r\n";
			$text .= '$CMS_DBL_USER = "'.cms_dateischreiben_vorbereiten($CMS_DBL_USER).'";'."\r\n";
			$text .= '$CMS_DBL_PASS = "'.cms_dateischreiben_vorbereiten($CMS_DBL_PASS).'";'."\r\n";
			$text .= '$CMS_DBL_DB = "'.cms_dateischreiben_vorbereiten($CMS_DBL_DB).'";'."\r\n";
			$text .= "\r\n";
			$text .= '$CMS_SH_SERVER = "'.cms_dateischreiben_vorbereiten($CMS_SH_SERVER).'";'."\r\n";
			$text .= '?>';
			fwrite($datei, $text);
			fclose($datei);
			echo "ERFOLG";
			cms_lehrerdb_header(false);
		}
		else {
			echo "FEHLER";
			cms_lehrerdb_header(true);
		}
		chmod($pfad, 0775);
	}
	else {
		echo "FEHLER";
		cms_lehrerdb_header(true);
	}
}
else {
	echo "BERECHTIGUNG";
	cms_lehrerdb_header(true);
}
?>
