<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['passwort'])) {$passwort = $_POST['passwort'];} else {echo "FEHLER"; exit;}
if (isset($_POST['passwort2'])) {$passwort2 = $_POST['passwort2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail'])) {$mail = $_POST['mail'];} else {echo "FEHLER"; exit;}
if (isset($_POST['datenschutz'])) {$datenschutz = $_POST['datenschutz'];} else {echo "FEHLER"; exit;}
if (isset($_POST['volljaehrig'])) {$volljaehrig = $_POST['volljaehrig'];} else {echo "FEHLER"; exit;}
if (isset($_POST['korrekt'])) {$korrekt = $_POST['korrekt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['spam'])) {$spam = $_POST['spam'];} else {echo "FEHLER"; exit;}
if (isset($_POST['uid'])) {$uid = $_POST['uid'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['SPAMSCHUTZ_'.$uid])) {$captcha = $_SESSION['SPAMSCHUTZ_'.$uid];} else {echo "FEHLER"; exit;}
unset($_SESSION['SPAMSCHUTZ_'.$uid]);
if ($spam != $captcha) {echo "FEHLER"; exit;}
if (!cms_check_nametitel($titel)) {echo "FEHLER"; exit;}
if (!cms_check_name($vorname)) {echo "FEHLER"; exit;}
if (!cms_check_name($nachname)) {echo "FEHLER"; exit;}
if (!cms_check_mail($mail)) {echo "FEHLER"; exit;}
if (strlen($passwort) == 0) {echo "FEHLER"; exit;}
if ($passwort != $passwort2) {echo "FEHLER"; exit;}
if ($datenschutz != 1) {echo "FEHLER"; exit;}
if ($volljaehrig != 1) {echo "FEHLER"; exit;}
if ($korrekt != 1) {echo "FEHLER"; exit;}


$dbs = cms_verbinden('s');
$mail = cms_texttrafo_e_db($mail);
$vorname = cms_texttrafo_e_db($vorname);
$nachname = cms_texttrafo_e_db($nachname);
$titel = cms_texttrafo_e_db($titel);
$klasse = cms_texttrafo_e_db($klasse);

$salt = cms_generiere_passwort().cms_generiere_passwort();
$salt = cms_texttrafo_e_db($salt);
$passwortsalted = $passwort.$salt;
$passwortsalted = cms_texttrafo_e_db($passwortsalted);

$benutzer = rand(0,1000000);

$id = cms_generiere_kleinste_id('nutzerregistrierung', "s", $benutzer);

$sql = $dbs->prepare("UPDATE nutzerregistrierung SET titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), klasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), salt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), passwort = SHA1(?) WHERE id = ?");

$sql->bind_param("sssssssi", $titel, $vorname, $nachname, $klasse, $mail, $salt, $passwortsalted, $id);
$sql->execute();
$sql->close();

echo "ERFOLG";
?>
