<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER";exit;}

cms_rechte_laden();
$fehler = false;

$sql = $dbs->prepare("SELECT beginn, oeffentlichkeit, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM termine WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($BEGINN, $bezeichnung, $oeffentlichkeit);
	if (!$sql->fetch()) {$fehler = true;}
}
else {$fehler = true;}
$sql->close();

if(!cms_check_ganzzahl($oeffentlichkeit, 0, 4)) {
  die("FEHLER");
}

if (cms_r("artikel.$oeffentlichkeit.termine.löschen")) {
	$zugriff = true;
}

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	if (!$fehler) {
		$monatsname = cms_monatsnamekomplett(date('m', $BEGINN));
		$jahr = date('Y', $BEGINN);
		$tag = date('d', $BEGINN);
		$eintrag['gruppe']    = "Termine";
		$eintrag['gruppenid'] = $oeffentlichkeit;
		$eintrag['zielid']    = $id;
		$eintrag['status']    = "l";
		$eintrag['art']       = "t";
		$eintrag['titel']     = $bezeichnung;
		$eintrag['vorschau']  = cms_tagname(date('w', $BEGINN))." $tag. ".$monatsname." $jahr";
		$eintrag['link']      = "";
		cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);

		$sql = $dbs->prepare("DELETE FROM termine WHERE id = ?");
	  $sql->bind_param("i", $id);
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
