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
if (!cms_check_mail($absender)) {echo "FEHLER"; exit;}
if (strlen($host) < 3) {echo "FEHLER"; exit;}
if (!cms_check_toggle($smtpauth)) {echo "FEHLER"; exit;}


if (cms_angemeldet() && cms_r("schulhof.verwaltung.schule.mail")) {
	$fehler = false;

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
		$CMS_MAIL = cms_einstellungen_laden('maileinstellungen');
		$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');

		$empfaengername = cms_generiere_anzeigename($vorname, $nachname, $titel);
		$betreff = 'Test des neuen Mailzugangs';

		// Vorübergehend Maildaten überschreiben
		$CMS_MAIL['Absender'] = $absender;
		$CMS_MAIL['SMTP-Host'] = $host;
		$CMS_MAIL['SMTP-Authentifizierung'] = $smtpauth;
		$CMS_MAIL['Benutzername'] = $benutzer;
		$CMS_MAIL['Passwort'] = $passwort;

		$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);

		$text = "<p>".$anrede."</p>";
		$text .= "<p>Wenn diese Mail ankommt, funktionieren die neuen Zugangsdaten zur Schulmailadresse!</p>";
		$text .= "<p>Herzlichen Glückwunsch!</p>";

		require_once '../../phpmailer/PHPMailerAutoload.php';

		// Mail verschicken:
		$mailerfolg = cms_mailsenden($empfaengername, $mail, $betreff, $text);

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
