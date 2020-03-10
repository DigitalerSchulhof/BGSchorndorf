<?php
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT'])) {$verbindlichkeit = $_SESSION['VORANMELDUNG_VERBINDLICHKEIT'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'])) {$gleichbehandlung = $_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_DATENSCHUTZ'])) {$datenschutz = $_SESSION['VORANMELDUNG_DATENSCHUTZ'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_COOKIES'])) {$cookies = $_SESSION['VORANMELDUNG_COOKIES'];} else {echo "FEHLER"; exit;}


if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rufname'])) {$rufname = $_POST['rufname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumT'])) {$geburtsdatumT = $_POST['geburtsdatumT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumM'])) {$geburtsdatumM = $_POST['geburtsdatumM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumJ'])) {$geburtsdatumJ = $_POST['geburtsdatumJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsort'])) {$geburtsort = $_POST['geburtsort'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsland'])) {$geburtsland = $_POST['geburtsland'];} else {echo "FEHLER"; exit;}
if (isset($_POST['muttersprache'])) {$muttersprache = $_POST['muttersprache'];} else {echo "FEHLER"; exit;}
if (isset($_POST['verkehrssprache'])) {$verkehrssprache = $_POST['verkehrssprache'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht'])) {$geschlecht = $_POST['geschlecht'];} else {echo "FEHLER"; exit;}
if (isset($_POST['religion'])) {$religion = $_POST['religion'];} else {echo "FEHLER"; exit;}
if (isset($_POST['religionsunterricht'])) {$religionsunterricht = $_POST['religionsunterricht'];} else {echo "FEHLER"; exit;}
if (isset($_POST['land1'])) {$land1 = $_POST['land1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['land2'])) {$land2 = $_POST['land2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['impfung'])) {$impfung = $_POST['impfung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse'])) {$strasse = $_POST['strasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer'])) {$hausnummer = $_POST['hausnummer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['plz'])) {$plz = $_POST['plz'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ort'])) {$ort = $_POST['ort'];} else {echo "FEHLER"; exit;}
if (isset($_POST['staat'])) {$staat = $_POST['staat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort'])) {$teilort = $_POST['teilort'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon1'])) {$telefon1 = $_POST['telefon1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon2'])) {$telefon2 = $_POST['telefon2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['handy1'])) {$handy1 = $_POST['handy1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['handy2'])) {$handy2 = $_POST['handy2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail'])) {$mail = $_POST['mail'];} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungT'])) {$einschulungT = $_POST['einschulungT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungM'])) {$einschulungM = $_POST['einschulungM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungJ'])) {$einschulungJ = $_POST['einschulungJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorigeschule'])) {$vorigeschule = $_POST['vorigeschule'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['profil'])) {$profil = $_POST['profil'];} else {echo "FEHLER"; exit;}
$jetzt = time();

$fehler = false;
// Pflichteingaben prüfen
if (($verbindlichkeit != 1) || ($gleichbehandlung != 1) || ($datenschutz != 1) || ($cookies != 1)) {
	$fehler = true;
}

$geburtsdatum = mktime(0,0,0,$geburtsdatumM, $geburtsdatumT, $geburtsdatumJ);
$einschulung = mktime(0,0,0,$einschulungM, $einschulungT, $einschulungJ);
if (!cms_check_name($vorname)) {$fehler = true;}
if (!cms_check_name($nachname)) {$fehler = true;}
if (!cms_check_name($rufname)) {$fehler = true;}
if ($geburtsdatum >= $jetzt) {$fehler = true;}
if (strlen($geburtsort) <= 0) {$fehler = true;}
if (strlen($geburtsland) <= 0) {$fehler = true;}
if (strlen($muttersprache) <= 0) {$fehler = true;}
if (strlen($verkehrssprache) <= 0) {$fehler = true;}
if (($geschlecht != 'm') && ($geschlecht != 'w') && ($geschlecht != 'd')) {$fehler = true;}
if (strlen($religion) <= 0) {$fehler = true;}
if (strlen($religionsunterricht) <= 0) {$fehler = true;}
if (strlen($land1) <= 0) {$fehler = true;}
if (!cms_check_toggle($impfung)) {$fehler = true;}
if (strlen($strasse) <= 0) {$fehler = true;}
if (strlen($hausnummer) <= 0) {$fehler = true;}
if (strlen($plz) <= 0) {$fehler = true;}
if (strlen($ort) <= 0) {$fehler = true;}
if (strlen($staat) <= 0) {$fehler = true;}
if ((strlen($telefon1) <= 0) && (strlen($telefon2) <= 0) && (strlen($handy1) <= 0) && (strlen($handy2) <= 0)) {$fehler = true;}
if (strlen($mail)) {if (!cms_check_mail($mail)) {$fehler = true;}}
if ($einschulung >= $jetzt) {$fehler = true;}
if (strlen($vorigeschule) <= 0) {$fehler = true;}
if (strlen($klasse) <= 0) {$fehler = true;}
if (strlen($profil) <= 0) {$fehler = true;}

if (!$fehler) {

	$_SESSION['VORANMELDUNG_S_NACHNAME'] = $nachname;
	$_SESSION['VORANMELDUNG_S_VORNAME'] = $vorname;
	$_SESSION['VORANMELDUNG_S_RUFNAME'] = $rufname;
	$_SESSION['VORANMELDUNG_S_GEBURTSDATUM'] = $geburtsdatum;
	$_SESSION['VORANMELDUNG_S_GEBURTSORT'] = $geburtsort;
	$_SESSION['VORANMELDUNG_S_GEBURTSLAND'] = $geburtsland;
	$_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'] = $muttersprache;
	$_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'] = $verkehrssprache;
	$_SESSION['VORANMELDUNG_S_GESCHLECHT'] = $geschlecht;
	$_SESSION['VORANMELDUNG_S_RELIGION'] = $religion;
	$_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'] = $religionsunterricht;
	$_SESSION['VORANMELDUNG_S_LAND1'] = $land1;
	$_SESSION['VORANMELDUNG_S_LAND2'] = $land2;
	$_SESSION['VORANMELDUNG_S_IMPFUNG'] = $impfung;
	$_SESSION['VORANMELDUNG_S_STRASSE'] = $strasse;
	$_SESSION['VORANMELDUNG_S_HAUSNUMMER'] = $hausnummer;
	$_SESSION['VORANMELDUNG_S_PLZ'] = $plz;
	$_SESSION['VORANMELDUNG_S_ORT'] = $ort;
	$_SESSION['VORANMELDUNG_S_STAAT'] = $staat;
	$_SESSION['VORANMELDUNG_S_TEILORT'] = $teilort;
	$_SESSION['VORANMELDUNG_S_TELEFON1'] = $telefon1;
	$_SESSION['VORANMELDUNG_S_TELEFON2'] = $telefon2;
	$_SESSION['VORANMELDUNG_S_HANDY1'] = $handy1;
	$_SESSION['VORANMELDUNG_S_HANDY2'] = $handy2;
	$_SESSION['VORANMELDUNG_S_MAIL'] = $mail;
	$_SESSION['VORANMELDUNG_S_EINSCHULUNG'] = $einschulung;
	$_SESSION['VORANMELDUNG_S_VORIGESCHULE'] = $vorigeschule;
	$_SESSION['VORANMELDUNG_S_KLASSE'] = $klasse;
	$_SESSION['VORANMELDUNG_S_PROFIL'] = $profil;

  if (isset($_SESSION["VORANMELDUNG_FORTSCHITT"])) {
    if ($_SESSION["VORANMELDUNG_FORTSCHITT"] < 1) {$_SESSION["VORANMELDUNG_FORTSCHITT"] = 1;}
  }
  else {
    $_SESSION["VORANMELDUNG_FORTSCHITT"] = 1;
  }
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
