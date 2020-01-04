<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();

if (isset($_POST['id'])) 			  		 {$terminid = $_POST['id'];} 						      	else {echo "FEHLER1";exit;}
if (isset($_POST['gruppe'])) 				 {$gruppe = $_POST['gruppe'];} 									else {echo "FEHLER2";exit;}
if (isset($_POST['gruppenid'])) 		 {$gruppenid = $_POST['gruppenid'];} 						else {echo "FEHLER3";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER4";exit;}
if (isset($_SESSION['BENUTZERID']))  {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	else {echo "FEHLER5";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER6";exit;}
if (!cms_check_ganzzahl($terminid,0)) 			{echo "FEHLER7";exit;}
if (!cms_valide_gruppe($gruppe)) 						{echo "FEHLER8";exit;}
if (!cms_check_ganzzahl($gruppenid,0)) 			{echo "FEHLER9";exit;}


$gk = cms_textzudb($gruppe);

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$dbs = cms_verbinden('s');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);

$zugriff = $CMS_GRUPPENRECHTE['termine'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	$sql = $dbs->prepare("SELECT beginn, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $gk"."termineintern WHERE id = ?");
  $sql->bind_param("i", $terminid);
  if ($sql->execute()) {
    $sql->bind_result($datum, $bezeichnung);
    if (!$sql->fetch()) {$fehler = true; echo 1;}
  }
  else {$fehler = true; echo 2;}
  $sql->close();

	if (!$fehler) {
		$monatsname = cms_monatsnamekomplett(date('m', $datum));
		$jahr = date('Y', $datum);
		$tag = date('d', $datum);
		$eintrag['gruppe']    = "$gruppe";
		$eintrag['gruppenid'] = $gruppenid;
		$eintrag['zielid']    = $terminid;
		$eintrag['status']    = "l";
		$eintrag['art']       = "t";
		$eintrag['titel']     = $bezeichnung;
		$eintrag['vorschau']  = cms_tagname(date('w', $datum))." $tag. ".$monatsname." $jahr";
		$eintrag['link']      = "";
		cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);

		$sql = $dbs->prepare("DELETE FROM $gk"."termineintern WHERE id = ?");
	  $sql->bind_param("i", $terminid);
	  $sql->execute();
	  $sql->close();

		echo "ERFOLG";
	}
	else {
		echo "3FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
