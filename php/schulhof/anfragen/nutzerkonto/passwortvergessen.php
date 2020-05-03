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
	$CMS_WICHTIG = cms_einstellungen_laden("wichtigeeinstellungen");
	$CMS_MAIL = cms_einstellungen_laden("maileinstellungen");
	$betreff = "Passwort vergessen";

	$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);
	$text = "<p>$anrede</p>";
	$text .= "<p>Es wurde ein neues Passwort generiert. Hier sind die Zugangsdaten:<br>";
	$text .= "Benutzername: ".$benutzername."<br>";
	$text .= "Passwort: ".$passwort."<br>";
	$text .= "eMailadresse: ".$mail."</p>";
	$text .= "<p><b>Achtung!</b> Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur <b>eine Stunde</b> gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über <i>Passwort vergessen?</i> ein neues Passwort angefordert werden. Dazu werden die Angaben <i>Benutzername</i> und <i>eMailadresse</i> benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.</p>";
	$text .= "<p><b>Kurz:</b> Das Passwort sollte sobald wie möglich geändert werden!!</p>";
	$text .= "<p>Viel Spaß mit dem neuen Zugang!";

	require_once '../../phpmailer/PHPMailerAutoload.php';

	// Mail verschicken:
	$empfaenger = cms_generiere_anzeigename($vorname, $nachname, $titel);
	$mailerfolg = cms_mailsenden($empfaenger, $mail, $betreff, $text);

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
