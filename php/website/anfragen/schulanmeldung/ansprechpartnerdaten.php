<?php
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT'])) {$verbindlichkeit = $_SESSION['VORANMELDUNG_VERBINDLICHKEIT'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'])) {$gleichbehandlung = $_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_DATENSCHUTZ'])) {$datenschutz = $_SESSION['VORANMELDUNG_DATENSCHUTZ'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_COOKIES'])) {$cookies = $_SESSION['VORANMELDUNG_COOKIES'];} else {echo "FEHLER"; exit;}

if (isset($_POST['nachname1'])) {$nachname1 = $_POST['nachname1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname1'])) {$vorname1 = $_POST['vorname1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht1'])) {$geschlecht1 = $_POST['geschlecht1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sorgerecht1'])) {$sorgerecht1 = $_POST['sorgerecht1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['briefe1'])) {$briefe1 = $_POST['briefe1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse1'])) {$strasse1 = $_POST['strasse1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer1'])) {$hausnummer1 = $_POST['hausnummer1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['plz1'])) {$plz1 = $_POST['plz1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ort1'])) {$ort1 = $_POST['ort1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort1'])) {$teilort1 = $_POST['teilort1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon11'])) {$telefon11 = $_POST['telefon11'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon21'])) {$telefon21 = $_POST['telefon21'];} else {echo "FEHLER"; exit;}
if (isset($_POST['handy11'])) {$handy11 = $_POST['handy11'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail1'])) {$mail1 = $_POST['mail1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ansprechpartner2'])) {$ansprechpartner2 = $_POST['ansprechpartner2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname2'])) {$nachname2 = $_POST['nachname2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname2'])) {$vorname2 = $_POST['vorname2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht2'])) {$geschlecht2 = $_POST['geschlecht2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sorgerecht2'])) {$sorgerecht2 = $_POST['sorgerecht2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['briefe2'])) {$briefe2 = $_POST['briefe2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse2'])) {$strasse2 = $_POST['strasse2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer2'])) {$hausnummer2 = $_POST['hausnummer2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['plz2'])) {$plz2 = $_POST['plz2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ort2'])) {$ort2 = $_POST['ort2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort2'])) {$teilort2 = $_POST['teilort2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon12'])) {$telefon12 = $_POST['telefon12'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon22'])) {$telefon22 = $_POST['telefon22'];} else {echo "FEHLER"; exit;}
if (isset($_POST['handy12'])) {$handy12 = $_POST['handy12'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail2'])) {$mail2 = $_POST['mail2'];} else {echo "FEHLER"; exit;}

$fehler = false;
// Pflichteingaben prüfen
if (($verbindlichkeit != 1) || ($gleichbehandlung != 1) || ($datenschutz != 1) || ($cookies != 1)) {
	$fehler = true;
}

if (!cms_check_name($vorname1)) {$fehler = true;}
if (!cms_check_name($nachname1)) {$fehler = true;}
if (($geschlecht1 != 'm') && ($geschlecht1 != 'w') && ($geschlecht1 != 'd')) {$fehler = true;}
if (!cms_check_toggle($sorgerecht1)) {$fehler = true;}
if (!cms_check_toggle($briefe1)) {$fehler = true;}
if (strlen($strasse1) <= 0) {$fehler = true;}
if (strlen($hausnummer1) <= 0) {$fehler = true;}
if (strlen($plz1) <= 0) {$fehler = true;}
if (strlen($ort1) <= 0) {$fehler = true;}
if ((strlen($telefon11) <= 0) && (strlen($telefon21) <= 0) && (strlen($handy11) <= 0)) {$fehler = true;}
if (strlen($mail1)) {if (!cms_check_mail($mail1)) {$fehler = true;}}
if (!cms_check_toggle($ansprechpartner2)) {$fehler = true;}
if ($ansprechpartner2 == '1') {
	if (!cms_check_name($vorname2)) {$fehler = true;}
	if (!cms_check_name($nachname2)) {$fehler = true;}
	if (($geschlecht2 != 'm') && ($geschlecht2 != 'w') && ($geschlecht2 != 'd')) {$fehler = true;}
	if (!cms_check_toggle($sorgerecht2)) {$fehler = true;}
	if (!cms_check_toggle($briefe2)) {$fehler = true;}
	if (strlen($strasse2) <= 0) {$fehler = true;}
	if (strlen($hausnummer2) <= 0) {$fehler = true;}
	if (strlen($plz2) <= 0) {$fehler = true;}
	if (strlen($ort2) <= 0) {$fehler = true;}
	if ((strlen($telefon12) <= 0) && (strlen($telefon22) <= 0) && (strlen($handy12) <= 0)) {$fehler = true;}
	if (strlen($mail2)) {if (!cms_check_mail($mail2)) {$fehler = true;}}
}

if (!$fehler) {

	$_SESSION['VORANMELDUNG_A1_NACHNAME'] = $nachname1;
	$_SESSION['VORANMELDUNG_A1_VORNAME'] = $vorname1;
	$_SESSION['VORANMELDUNG_A1_GESCHLECHT'] = $geschlecht1;
	$_SESSION['VORANMELDUNG_A1_SORGERECHT'] = $sorgerecht1;
	$_SESSION['VORANMELDUNG_A1_BRIEFE'] = $briefe1;
	$_SESSION['VORANMELDUNG_A1_STRASSE'] = $strasse1;
	$_SESSION['VORANMELDUNG_A1_HAUSNUMMER'] = $hausnummer1;
	$_SESSION['VORANMELDUNG_A1_PLZ'] = $plz1;
	$_SESSION['VORANMELDUNG_A1_ORT'] = $ort1;
	$_SESSION['VORANMELDUNG_A1_TEILORT'] = $teilort1;
	$_SESSION['VORANMELDUNG_A1_TELEFON1'] = $telefon11;
	$_SESSION['VORANMELDUNG_A1_TELEFON2'] = $telefon21;
	$_SESSION['VORANMELDUNG_A1_HANDY1'] = $handy11;
	$_SESSION['VORANMELDUNG_A1_MAIL'] = $mail1;
	$_SESSION['VORANMELDUNG_A2'] = $ansprechpartner2;
	$_SESSION['VORANMELDUNG_A2_NACHNAME'] = $nachname2;
	$_SESSION['VORANMELDUNG_A2_VORNAME'] = $vorname2;
	$_SESSION['VORANMELDUNG_A2_GESCHLECHT'] = $geschlecht2;
	$_SESSION['VORANMELDUNG_A2_SORGERECHT'] = $sorgerecht2;
	$_SESSION['VORANMELDUNG_A2_BRIEFE'] = $briefe2;
	$_SESSION['VORANMELDUNG_A2_STRASSE'] = $strasse2;
	$_SESSION['VORANMELDUNG_A2_HAUSNUMMER'] = $hausnummer2;
	$_SESSION['VORANMELDUNG_A2_PLZ'] = $plz2;
	$_SESSION['VORANMELDUNG_A2_ORT'] = $ort2;
	$_SESSION['VORANMELDUNG_A2_TEILORT'] = $teilort2;
	$_SESSION['VORANMELDUNG_A2_TELEFON1'] = $telefon12;
	$_SESSION['VORANMELDUNG_A2_TELEFON2'] = $telefon22;
	$_SESSION['VORANMELDUNG_A2_HANDY1'] = $handy12;
	$_SESSION['VORANMELDUNG_A2_MAIL'] = $mail2;

  if (isset($_SESSION["VORANMELDUNG_FORTSCHITT"])) {
    if ($_SESSION["VORANMELDUNG_FORTSCHITT"] < 2) {$_SESSION["VORANMELDUNG_FORTSCHITT"] = 2;}
  }
  else {
    $_SESSION["VORANMELDUNG_FORTSCHITT"] = 2;
  }
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
