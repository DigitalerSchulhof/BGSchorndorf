<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {$bereich = '';}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {$pfad = '';}
if (isset($_POST['datei'])) {$datei = $_POST['datei'];} else {$datei = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$CMS_BENUTZERID = $_SESSION['BENUTZERID'];

$dbs = cms_verbinden('s');

$notifikation = false;
$zugriff = false;
$fehler = false;
$existiert = false;

$pfadteile = explode('/', $pfad);
if ($bereich == "website") {
	$gruppenrechte = cms_websitedateirechte_laden();
	if ($pfad[0] == "website") {$fehler = true;}
}
else if ($bereich == "titelbilder") {
	$gruppenrechte = cms_titelbilderdateirechte_laden();
	if ($pfad[0] == "titelbilder") {$fehler = true;}
}
else if ($bereich == "schulhof") {
	// Gruppe ermitteln
	if (count($pfadteile) < 4) {$fehler = true;}
	else {
		$gruppe = strtoupper(substr($pfadteile[2],0,1)).substr($pfadteile[2],1);
		if ($gruppe == "Sonstigegruppen") {$gruppe = "Sonstige Gruppen";}
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		if ($pfadteile[3] != $id) {$fehler = true;}
		$notifikation = true;
	}
}
else {$fehler = true;}

$zugriff = $gruppenrechte['dateiloeschen'];


if (cms_angemeldet() && $zugriff) {

	if (strlen($datei) < 1) {
		$fehler = true;
	}


	if (!$fehler) {
		$pfad = "../../../dateien/".$pfad;
		if (is_dir($pfad)) {
			if (is_file($pfad."/".$datei)) {
				$fehler = !unlink($pfad."/".$datei);
			}
			else {
				$fehler = true;
			}
		}
		else {
			$fehler = true;
		}
	}

	if (!$fehler) {
		if($notifikation) {
			$g = $gruppe;
			$gid = $id;
			$gk = cms_textzudb($g);
			$sql = $dbs->prepare("SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') as grbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = ?");
			$sql->bind_param("i", $gid);
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

			$anzeigepfad = $pfad;
			if(substr_count($anzeigepfad, "/") < 8) {
				$anzeigepfad = "";
			} else {
				$anzeigepfad = substr($pfad, strposX($anzeigepfad, "/", 8));
			}

			$eintrag = array();
			$eintrag['gruppe']    = $g;
			$eintrag['gruppenid'] = $gid;
			$eintrag['zielid']    = null;
			$eintrag['status']    = "l";
			$eintrag['art']       = "d";
			$eintrag['titel']     = $datei;
			$eintrag['vorschau']  = "$anzeigepfad/$datei";
			$eintrag['link']      = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($g)."/$gruppenbez";

			cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
		}

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
