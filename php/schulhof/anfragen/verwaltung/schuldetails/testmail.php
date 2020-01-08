<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['absender'])) 		{$absender = $_POST['absender'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['host'])) 				{$host = $_POST['host'];} 						else {echo "FEHLER"; exit;}
if (isset($_POST['benutzer'])) 		{$benutzer = $_POST['benutzer'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['passwort'])) 		{$passwort = $_POST['passwort'];} 		else {echo "FEHLER"; exit;}
if (isset($_POST['smtpauth'])) 		{$smtpauth = $_POST['smtpauth'];} 		else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.schule.mail"))) {
	$fehler = false;

	if (!cms_check_mail($absender)) {$fehler = true;}

	if (($smtpauth != 1) && ($smtpauth != 0)) {$fehler = true;}

	// Zielmailadresse laden
	$id = $_SESSION['BENUTZERID'];
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("SELECT AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($mail, $vorname, $nachname, $titel, $art, $geschlecht);
    if (!$sql->fetch()) {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	cms_trennen($dbs);

	if (!$fehler) {
		if ($smtpauth == 1) {$smtpauth = true;}
		else {$smtpauth = false;}


		$empfaenger = cms_generiere_anzeigename($vorname, $nachname, $titel);
		$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Test des neuen Mailzugangs';

		// Vorübergehend Maildaten überschreiben
		$CMS_MAILABSENDER = $absender;
		$CMS_MAILHOST = $host;
		$CMS_MAILSMTPAUTH = $smtpauth;
		$CMS_MAILUSERNAME = $benutzer;
		$CMS_MAILPASSWORT = $passwort;

		$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);

		$text;
		for ($i=0; $i<2; $i++) {
			$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Wenn diese Mail ankommt, funktionieren die neuen Zugangsdaten zur Schulmailadresse!'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Herzlichen Glückwunsch!'.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
		}

		require_once '../../phpmailer/PHPMailerAutoload.php';

		// Mail verschicken:
		$mailerfolg = cms_mailsenden($empfaenger, $mail, $betreff, $text[1], $text[0]);

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
