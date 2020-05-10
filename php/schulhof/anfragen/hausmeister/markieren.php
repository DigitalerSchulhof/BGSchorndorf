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
if (isset($_POST['id'])) 	{$id = $_POST['id'];} 		else {echo "FEHLER";exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} 	else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	                    else {echo "FEHLER";exit;}


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if (($art != 'n') && ($art != 'e')) {echo "FEHLER";exit;}

if (cms_angemeldet() && cms_r("schulhof.technik.hausmeisteraufträge.markieren")) {
	$fehler = false;

	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), raumgeraet, leihgeraet AS titel FROM hausmeisterauftraege WHERE id = ?");
	$sql->bind_param("i", $id);
	if ($sql->execute()) {
	  $sql->bind_result($anzahl, $titel, $raumgeraet, $leihgeraet);
	  if ($sql->fetch()) {
			if ($anzahl != 1) {
				$fehler = true;
			}
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	if (!$fehler) {
    // AUFTRAG ÄNDERN
		$jetzt = time();
		$sql = $dbs->prepare("UPDATE hausmeisterauftraege SET status = ?, erledigt = ?, erledigtvon = ? WHERE id = ?");
		$sql->bind_param("siii", $art, $jetzt, $CMS_BENUTZERID, $id);
	  $sql->execute();
	  $sql->close();

		// Möglichen Gerätestatus ändern
		if (($raumgeraet !== null) || ($leihgeraet !== null)) {
			if ($raumgeraet !== null) {
				$gid = $raumgeraet;
				$sql = $dbs->prepare("UPDATE raeumegeraete SET statusnr = ? WHERE id = ?");
			}
			if ($leihgeraet !== null) {
				$gid = $leihgeraet;
				$sql = $dbs->prepare("UPDATE leihengeraete SET statusnr = ? WHERE id = ?");
			}
			if ($art == "e") {$statusnr = 5;}
			else {$statusnr = 2;}
			$sql->bind_param("ii", $statusnr, $gid);
			$sql->execute();
			$sql->close();
		}

		// Notifikation verschicken
		if ($art == "e") {$status = "e";} else {$status = "w";}
		$eintrag['gruppe']    = "Hausmeister";
		$eintrag['gruppenid'] = 0;
		$eintrag['zielid']    = $id;
		$eintrag['status']    = $status;
		$eintrag['art']       = "a";
		$eintrag['titel']     = $titel;
		$eintrag['vorschau']  = "";
		$eintrag['link']      = "";

		$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
		$CMS_MAIL = cms_einstellungen_laden('maileinstellungen');
		
		cms_notifikation_senden($dbs, $eintrag, "");
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
