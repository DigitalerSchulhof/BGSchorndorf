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
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($gruppe) && ($gruppe != 'Termine')) {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER";exit;}
$gk = cms_textzudb($gruppe);

$CMS_RECHTE = cms_rechte_laden();
$zugriff = false;
if ($gruppe == 'Termine') {$zugriff = $CMS_RECHTE['Organisation']['Termine genehmigen'];}
else if (in_array($gruppe, $CMS_GRUPPEN)) {$zugriff = $CMS_RECHTE['Organisation']['Gruppentermine genehmigen'];}

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	$fehler = false;
	if ($gruppe == 'Termine') {
		$oeffentlichkeit = "oeffentlichkeit";
		$gruppenid = "'' AS gruppe";
		$tabelle = "termine";
	}
	else {
		$tabelle = cms_textzudb($gruppe)."termineintern";
		$oeffentlichkeit = "'' AS oeffentlichkeit";
		$gruppenid = "gruppe";

		// Gruppe laden
		$sql = $dbs->prepare("SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') as grbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = ?");
		$sql->bind_param("i", $gruppenid);
		$gruppensj = "Schuljahrübergreifend";
		$gruppenbez = "";
		if ($sql->execute()) {
			$sql->bind_result($sjbez, $grbez);
			if ($sql->fetch()) {
				if (!is_null($sjbez)) {$gruppensj = cms_textzulink($sjbez);}
				$gruppenbez = cms_textzulink($grbez);
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
	}

	$sql = $dbs->prepare("SELECT beginn, $gruppenid, $oeffentlichkeit, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM $tabelle WHERE id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($BEGINN, $gruppenid, $oeffentlichkeit, $bezeichnung);
    if (!$sql->fetch()) {$fehler = true; echo 1;}
  }
  else {$fehler = true; echo 2;}
  $sql->close();

	if (!$fehler) {
		$sql = $dbs->prepare("UPDATE $tabelle SET genehmigt = 1 WHERE id = ?");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		$monatsname = cms_monatsnamekomplett(date('m', $BEGINN));
		$jahr = date('Y', $BEGINN);
		$tag = date('d', $BEGINN);

		if ($gruppe == 'Termine') {
			$gruppenid = $oeffentlichkeit;
			$link = "Schulhof/Termine/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);
		}
		else {
			$link = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($gruppe)."/$gruppenbez/Termine/$jahr/$monatsname/$tag/".cms_textzulink($bezeichnung);
		}

		$eintrag['gruppe']    = $gruppe;
		$eintrag['gruppenid'] = $gruppenid;
		$eintrag['zielid']    = $id;
		$eintrag['status']    = "g";
		$eintrag['art']       = "t";
		$eintrag['titel']     = $bezeichnung;
		$eintrag['vorschau']  = cms_tagname(date('w', $BEGINN))." $tag. ".$monatsname." $jahr";
		$eintrag['link']      = $link;
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
