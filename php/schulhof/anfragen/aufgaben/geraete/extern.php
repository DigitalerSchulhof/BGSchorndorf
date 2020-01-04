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

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.technik.geräte.verwalten")) {

	$dbs = cms_verbinden('s');
	if ($art == 'l') {$geraetetabelle = 'leihengeraete';}
	else if ($art == 'r') {$geraetetabelle = 'raeumegeraete';}
	$ticket = cms_generiere_passwort().$id.cms_generiere_passwort().(time()).cms_generiere_passwort().'Geräteverwaltung'.cms_generiere_passwort();

	// GERÄTE ZURÜCKSETZEN
	$sql = $dbs->prepare("UPDATE $geraetetabelle SET statusnr = 4, kommentar = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ticket = SHA1(?) WHERE standort = ? AND id = ?");
  $sql->bind_param("ssii", $kommentar, $ticket, $standort, $id);
  $sql->execute();
  $sql->close();

	$EINSTELLUNGEN = cms_einstellungen_laden();
	if ($EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' existiert'] != 1) {
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


		$mail = $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Mail'];
		$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Problembericht zu einem Gerät';

		$anrede = cms_mail_anrede($EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Titel'], $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Vorname'], $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Nachname'], 'v', $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Geschlecht']);
		$text;
		for ($i=0; $i<2; $i++) {
			$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Es liegt eine neue Problemmeldung bezüglich eines Geräts am '.$CMS_SCHULE.' '.$CMS_ORT.' vor.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Standort: '.$standort.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Gerät: '.$geraetebezeichnung.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Fehlerbeschreibung: '.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$meldung.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Kommentar der Geräteverwaltung vor Ort: '.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$kommentar.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Problem behoben? Dann bitte hier klicken: '.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_DOMAIN.'/Problembehebung/Ticket_'.$ticket.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Bitte kümmern Sie sich zeitnah um das Problem. Vielen Dank.'.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
		}

		require_once '../../phpmailer/PHPMailerAutoload.php';

		// Mail verschicken:
		$empfaenger = cms_generiere_anzeigename($EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Vorname'], $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Nachname'], $EINSTELLUNGEN['Externe Geräteverwaltung'.$ansprechpartner.' Titel']);

		$mailerfolg = cms_mailsenden($anrede, $mail, $betreff, $text[1], $text[0]);

		echo "ERFOLG";
	}

	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
