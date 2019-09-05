<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['benutzername'])) {$benutzername = $_POST['benutzername'];} else {echo "FEHLER";exit;}
if (isset($_POST['mail'])) {$mail = $_POST['mail'];} else {echo "FEHLER";exit;}
$fehler = false;

// Pflichteingaben prüfen
if (strlen($benutzername) < 6) {$fehler = true;}
if (!cms_check_mail($mail)) {$fehler = true;}

$dbs = cms_verbinden('s');
// Prüfen, ob Benutzername und eMail zusammen passen
$jetzt = time();
$benutzername_db = cms_texttrafo_e_db($benutzername);


$sql = $dbs->prepare("SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(salt, '$CMS_SCHLUESSEL') AS salt FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');");
$sql->bind_param("ss", $benutzername_db, $mail);
if ($sql->execute()) {
	$sql->bind_result($id, $art, $titel, $vorname, $nachname, $geschlecht, $salt);
	if (!$sql->fetch()) {$fehler = true;}
}
else {$fehler = true;}
$sql->close();

if (!$fehler) {
	// NEUES PASSWORT GENERIEREN
	$passwort = cms_generiere_passwort();
	// 1 Stunde in Sekunden später läuft das Passwort ab
	$passworttimeout = time() + 60*60;

	// SALT holen
	$passwortsalted = $passwort.$salt;
	$passwortsalted = cms_texttrafo_e_db($passwortsalted);

	// PERSON UPDATEN - SESSIONID eintragen
  $sql = $dbs->prepare("UPDATE nutzerkonten SET passwort = SHA1(?), passworttimeout = ? WHERE id = ?");
  $sql->bind_param("sii", $passwortsalted, $passworttimeout, $id);
  $sql->execute();
  $sql->close();

	// PASSWORT VERSCHICKEN
	$empfaenger = $mail;
	$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Passwort vergessen';

	$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);
	$text;
	for ($i=0; $i<2; $i++) {
		$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
		$text[$i] = $text[$i].'Es wurde ein neues Passwort generiert. Hier sind die Zugangsdaten:'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
		$text[$i] = $text[$i].'Benutzername: '.$benutzername.$CMS_MAILZ[$i];
		$text[$i] = $text[$i].'Passwort: '.$passwort.$CMS_MAILZ[$i];
		$text[$i] = $text[$i].'E-Mail-Adresse: '.$mail.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
		$text[$i] = $text[$i].$CMS_MAILWV[$i].'Achtung!'.$CMS_MAILWH[$i].' Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur '.$CMS_MAILWV[$i].'eine Stunde'.$CMS_MAILWH[$i].' gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über '.$CMS_MAILHV[$i].'Passwort vergessen?'.$CMS_MAILHH[$i].' ein neues Passwort angefordert werden. Dazu werden die Angaben '.$CMS_MAILHV[$i].'Benutzername'.$CMS_MAILHH[$i].' und '.$CMS_MAILHV[$i].'E-Mail-Adresse'.$CMS_MAILHH[$i].' benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
		$text[$i] = $text[$i].$CMS_MAILWV[$i].'Kurz:'.$CMS_MAILWH[$i].' Das Passwort sollte sobald wie möglich geändert werden!!'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
		$text[$i] = $text[$i].'Viel Spaß mit dem neuen Zugang!'.$CMS_MAILZ[$i];
		$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
	}

	require_once '../../phpmailer/PHPMailerAutoload.php';

	// Mail verschicken:
	$empfaenger = cms_generiere_anzeigename($vorname, $nachname, $titel);
	$mailerfolg = cms_mailsenden($anrede, $mail, $betreff, $text[1], $text[0]);

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
