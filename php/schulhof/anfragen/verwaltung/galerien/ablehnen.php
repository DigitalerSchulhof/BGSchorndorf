<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();
$zugriff = false;

if (cms_angemeldet() && cms_r("artikel.genehmigen.galerien"))) {
	$dbs = cms_verbinden('s');
	$fehler = false;

	$sql = $dbs->prepare("SELECT datum, oeffentlichkeit, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM galerien WHERE id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($datum, $oeffentlichkeit, $bezeichnung);
    if (!$sql->fetch()) {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	if (!$fehler) {
		$sql = $dbs->prepare("DELETE FROM galerien WHERE id = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		$monatsname = cms_monatsnamekomplett(date('m', $datum));
		$jahr = date('Y', $datum);
		$tag = date('d', $datum);

		$eintrag['gruppe'] 		= "Galerien";
		$eintrag['gruppenid'] = $oeffentlichkeit;
		$eintrag['zielid']    = null;
		$eintrag['status']    = "a";
		$eintrag['art']       = "g";
		$eintrag['titel']     = $bezeichnung;
		$eintrag['vorschau']  = cms_tagname(date('w', $datum))." $tag. ".$monatsname." $jahr";
		$eintrag['link']      = "";
		cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
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
