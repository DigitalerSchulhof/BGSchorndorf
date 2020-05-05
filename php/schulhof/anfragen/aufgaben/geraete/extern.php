<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kommentar'])) {$kommentar = cms_texttrafo_e_db($_POST['kommentar']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ansprechpartner'])) {$ansprechpartner = $_POST['ansprechpartner'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETESTANDORT"])) {$standort = $_SESSION["GERAETESTANDORT"];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETEART"])) {$art = $_SESSION["GERAETEART"];} else {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($standort, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($ansprechpartner, 1,2)) {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.technik.geräte.verwalten")) {

	$dbs = cms_verbinden('s');
	if ($art == 'l') {$geraetetabelle = 'leihengeraete';}
	else if ($art == 'r') {$geraetetabelle = 'raeumegeraete';}
	$ticket = cms_generiere_passwort().$id.cms_generiere_passwort().(time()).cms_generiere_passwort().'Geräteverwaltung'.cms_generiere_passwort();

	// GERÄTE ZURÜCKSETZEN
	$sql = $dbs->prepare("UPDATE $geraetetabelle SET statusnr = 4, kommentar = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ticket = SHA1(?) WHERE standort = ? AND id = ?");
  $sql->bind_param("ssii", $kommentar, $ticket, $standort, $id);
  $sql->execute();
  $sql->close();

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
	if ($CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' existiert'] != 1) {
		echo "MAIL";
	}
	else {
		$standort = "";
		$tabelle = "";
		$meldung = "";
		if ($art == 'r') {$standort = 'Raum » '; $tabelle = 'raeume';}
		else if ($art == 'l') {$standort = 'Leihgeräte » '; $tabelle = 'leihen';}
		$geraetebezeichnung = "";

		$sql = $dbs->prepare("SELECT AES_DECRYPT($geraetetabelle.bezeichnung, '$CMS_SCHLUESSEL') AS geraetebezeichnung, AES_DECRYPT($tabelle.bezeichnung, '$CMS_SCHLUESSEL') AS standortbezeichnung, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS meldung, ticket FROM $geraetetabelle JOIN $tabelle ON $tabelle.id = $geraetetabelle.standort WHERE $geraetetabelle.id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$geraetebezeichnung = "";
			$ort = "";
			$meldung = "";
			$ticket = "";
		  $sql->bind_result($geraetebezeichnung, $ort, $meldung, $ticket);
		  $sql->fetch();
			$standort .= $ort;
		}
		else {$fehler = true;}
		$sql->close();


		$CMS_MAIL = cms_einstellungen_laden('maileinstellungen');
		$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
		$mail = $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Mail'];
		$betreff = 'Problembericht zu einem Gerät';

		$anrede = cms_mail_anrede($CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Titel'], $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Vorname'], $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Nachname'], 'v', $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Geschlecht']);

		$text = "<p>".$anrede."</p>";
		$text .= "<p>Es liegt eine neue Problemmeldung bezüglich eines Geräts an der Schule <b>".$CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']."</b> vor.</p>";
		$text .= "<p>Standort: ".$standort."<br>";
		$text .= "Gerät: ".$geraetebezeichnung."<br><br>";
		$text .= "Fehlerbeschreibung: <br>";
		$text .= $meldung."<br><br>";
		$text .= "Kommentar der Geräteverwaltung vor Ort:<br>".$kommentar."</p>";
		$text .= "<p>Problem behoben? Dann bitte hier klicken: <br>";
		$text .= $CMS_WICHTIG['Schule Domain']."/Problembehebung/Ticket_".$ticket."</p>";
		$text .= "<p>Bitte kümmern Sie sich zeitnah um das Problem. Vielen Dank</p>";

		require_once '../../phpmailer/PHPMailerAutoload.php';

		// Mail verschicken:
		$empfaenger = cms_generiere_anzeigename($CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Vorname'], $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Nachname'], $CMS_EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Titel']);
		$mailerfolg = cms_mailsenden($empfaenger, $mail, $betreff, $text);

		echo "ERFOLG";
	}

	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
