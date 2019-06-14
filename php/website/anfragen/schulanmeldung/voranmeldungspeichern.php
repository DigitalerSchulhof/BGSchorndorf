<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT'])) {$verbindlichkeit = $_SESSION['VORANMELDUNG_VERBINDLICHKEIT'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'])) {$gleichbehandlung = $_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_DATENSCHUTZ'])) {$datenschutz = $_SESSION['VORANMELDUNG_DATENSCHUTZ'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_COOKIES'])) {$cookies = $_SESSION['VORANMELDUNG_COOKIES'];} else {echo "FEHLER"; exit;}

if (isset($_POST['korrekt'])) {$korrekt = $_POST['korrekt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['code'])) {$code = $_POST['code'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['SPAMSCHUTZ'])) {$codevergleich = $_SESSION['SPAMSCHUTZ'];} else {echo "FEHLER"; exit;}

if (isset($_SESSION['VORANMELDUNG_S_NACHNAME'])) {$snachname = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_NACHNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_VORNAME'])) {$svorname = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_VORNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_RUFNAME'])) {$srufname = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_RUFNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSDATUM'])) {$sgeburtsdatum = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_GEBURTSDATUM']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSORT'])) {$sgeburtsort = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_GEBURTSORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_GEBURTSLAND'])) {$sgeburtsland = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_GEBURTSLAND']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE'])) {$smuttersprache = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE'])) {$sverkehrssprache = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_GESCHLECHT'])) {$sgeschlecht = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_GESCHLECHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_RELIGION'])) {$sreligion = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_RELIGION']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT'])) {$sreligionsunterricht = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_LAND1'])) {$sland1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_LAND1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_LAND2'])) {$sland2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_LAND2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_STRASSE'])) {$sstrasse = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_STRASSE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_HAUSNUMMER'])) {$shausnummer = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_HAUSNUMMER']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_PLZ'])) {$splz = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_PLZ']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$sort = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_ORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_TEILORT'])) {$steilort = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_TEILORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_TELEFON1'])) {$stelefon1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_TELEFON1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_TELEFON2'])) {$stelefon2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_TELEFON2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_HANDY1'])) {$shandy1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_HANDY1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_HANDY2'])) {$shandy2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_HANDY2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_MAIL'])) {$smail = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_MAIL']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_ORT'])) {$sort = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_ORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_EINSCHULUNG'])) {$seinschulung = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_EINSCHULUNG']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_VORIGESCHULE'])) {$svorigeschule = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_VORIGESCHULE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_KLASSE'])) {$sklasse = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_KLASSE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_S_PROFIL'])) {$sprofil = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_S_PROFIL']);} else {echo "FEHLER"; exit;}

if (isset($_SESSION['VORANMELDUNG_A1_NACHNAME'])) {$nachname1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_NACHNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_VORNAME'])) {$vorname1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_VORNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_GESCHLECHT'])) {$geschlecht1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_GESCHLECHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_SORGERECHT'])) {$sorgerecht1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_SORGERECHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_BRIEFE'])) {$briefe1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_BRIEFE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_STRASSE'])) {$strasse1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_STRASSE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_HAUSNUMMER'])) {$hausnummer1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_HAUSNUMMER']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_PLZ'])) {$plz1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_PLZ']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_ORT'])) {$ort1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_ORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_TEILORT'])) {$teilort1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_TEILORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON1'])) {$telefon11 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_TELEFON1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_TELEFON2'])) {$telefon21 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_TELEFON2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_HANDY1'])) {$handy11 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_HANDY1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A1_MAIL'])) {$mail1 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A1_MAIL']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2'])) {$ansprechpartner2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_NACHNAME'])) {$nachname2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_NACHNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_VORNAME'])) {$vorname2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_VORNAME']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_GESCHLECHT'])) {$geschlecht2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_GESCHLECHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_SORGERECHT'])) {$sorgerecht2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_SORGERECHT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_BRIEFE'])) {$briefe2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_BRIEFE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_STRASSE'])) {$strasse2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_STRASSE']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_HAUSNUMMER'])) {$hausnummer2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_HAUSNUMMER']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_PLZ'])) {$plz2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_PLZ']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_ORT'])) {$ort2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_ORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_TEILORT'])) {$teilort2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_TEILORT']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON1'])) {$telefon12 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_TELEFON1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_TELEFON2'])) {$telefon22 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_TELEFON2']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_HANDY1'])) {$handy12 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_HANDY1']);} else {echo "FEHLER"; exit;}
if (isset($_SESSION['VORANMELDUNG_A2_MAIL'])) {$mail2 = cms_texttrafo_e_db($_SESSION['VORANMELDUNG_A2_MAIL']);} else {echo "FEHLER"; exit;}

$jetzt = time();

$fehler = false;
// Pflichteingaben prüfen
if (($verbindlichkeit != 1) || ($gleichbehandlung != 1) || ($datenschutz != 1) || ($cookies != 1)) {
	$fehler = true;
}

if ($korrekt != '1') {echo "FEHLER"; exit;}

if ($code != $codevergleich) {echo "CODE"; exit;}

$jetzt = time();
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();

if (($jetzt < $CMS_VORANMELDUNG['Anmeldung von']) || ($jetzt > $CMS_VORANMELDUNG['Anmeldung bis'])) {
	echo "ZEITRAUM"; exit;
}

if (!$fehler) {
	$dbs = cms_verbinden('s');
	// Schüler eintragen
	$schuelerid = cms_generiere_kleinste_id('voranmeldung_schueler', 's', '0');
	$sql = $dbs->prepare("UPDATE voranmeldung_schueler SET vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), rufname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsdatum = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsland = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), muttersprache = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), verkehrssprache = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), religion = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), religionsunterricht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), staatsangehoerigkeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zstaatsangehoerigkeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), strasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausnummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), plz = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), teilort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), mail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), einschulung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorigeschule = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorigeklasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kuenftigesprofil = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), akzeptiert = AES_ENCRYPT('nein', '$CMS_SCHLUESSEL'), eingegangen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	$sql->bind_param("sssssssssssssssssssssssssssss", $svorname, $srufname, $snachname, $sgeburtsdatum, $sgeburtsort, $sgeburtsland, $smuttersprache, $sverkehrssprache, $sgeschlecht, $sreligion, $sreligionsunterricht, $sland1, $sland2, $sstrasse, $shausnummer, $splz, $sort, $steilort, $stelefon1, $stelefon2, $shandy1, $shandy2, $smail, $seinschulung, $svorigeschule, $sklasse, $sprofil, $jetzt, $schuelerid);
	$sql->execute();
	$sql->close();


	// Ansprechpartner 1 eintragen
	$id = cms_generiere_kleinste_id('voranmeldung_eltern', 's', '0');

	$sql = $dbs->prepare("UPDATE voranmeldung_eltern SET schueler = ?, nummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sorgerecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), briefe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), strasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausnummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), plz = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), teilort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), mail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	$nummer = 'eins';
	$sql->bind_param("isssssssssssssssi", $schuelerid, $nummer, $vorname1, $nachname1, $geschlecht1, $sorgerecht1, $briefe1, $strasse1, $hausnummer1, $plz1, $ort1, $teilort1, $telefon11, $telefon21, $handy11, $mail1, $id);
	$sql->execute();

	// Ansprechpartner 2 eintragen
	if ($ansprechpartner2 == '1') {
		$id = cms_generiere_kleinste_id('voranmeldung_eltern', 's', '0');
		$nummer = 'zwei';
		$sql->bind_param("isssssssssssssssi", $schuelerid, $nummer, $vorname2, $nachname2, $geschlecht2, $sorgerecht2, $briefe2, $strasse2, $hausnummer2, $plz2, $ort2, $teilort2, $telefon12, $telefon22, $handy12, $mail2, $id);
		$sql->execute();
	}
	$sql->close();
	cms_trennen($dbs);

	// Information
	unset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT']);
	unset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG']);
	unset($_SESSION['VORANMELDUNG_DATENSCHUTZ']);
	unset($_SESSION['VORANMELDUNG_COOKIES']);
	unset($_SESSION['VORANMELDUNG_FORTSCHITT']);
	unset($_SESSION['SPAMSCHUTZ']);

	// Schüler
	unset($_SESSION['VORANMELDUNG_S_NACHNAME']);
	unset($_SESSION['VORANMELDUNG_S_VORNAME']);
	unset($_SESSION['VORANMELDUNG_S_RUFNAME']);
	unset($_SESSION['VORANMELDUNG_S_GEBURTSDATUM']);
	unset($_SESSION['VORANMELDUNG_S_GEBURTSORT']);
	unset($_SESSION['VORANMELDUNG_S_GEBURTSLAND']);
	unset($_SESSION['VORANMELDUNG_S_MUTTERSPRACHE']);
	unset($_SESSION['VORANMELDUNG_S_VERKEHRSSPRACHE']);
	unset($_SESSION['VORANMELDUNG_S_GESCHLECHT']);
	unset($_SESSION['VORANMELDUNG_S_RELIGION']);
	unset($_SESSION['VORANMELDUNG_S_RELIGIONSUNTERRICHT']);
	unset($_SESSION['VORANMELDUNG_S_LAND1']);
	unset($_SESSION['VORANMELDUNG_S_LAND2']);
	unset($_SESSION['VORANMELDUNG_S_STRASSE']);
	unset($_SESSION['VORANMELDUNG_S_HAUSNUMMER']);
	unset($_SESSION['VORANMELDUNG_S_PLZ']);
	unset($_SESSION['VORANMELDUNG_S_ORT']);
	unset($_SESSION['VORANMELDUNG_S_TEILORT']);
	unset($_SESSION['VORANMELDUNG_S_TELEFON1']);
	unset($_SESSION['VORANMELDUNG_S_TELEFON2']);
	unset($_SESSION['VORANMELDUNG_S_HANDY1']);
	unset($_SESSION['VORANMELDUNG_S_HANDY2']);
	unset($_SESSION['VORANMELDUNG_S_MAIL']);
	unset($_SESSION['VORANMELDUNG_S_EINSCHULUNG']);
	unset($_SESSION['VORANMELDUNG_S_VORIGESCHULE']);
	unset($_SESSION['VORANMELDUNG_S_KLASSE']);
	unset($_SESSION['VORANMELDUNG_S_PROFIL']);

	// Ansprechpartner
	unset($_SESSION['VORANMELDUNG_A1_NACHNAME']);
	unset($_SESSION['VORANMELDUNG_A1_VORNAME']);
	unset($_SESSION['VORANMELDUNG_A1_GESCHLECHT']);
	unset($_SESSION['VORANMELDUNG_A1_SORGERECHT']);
	unset($_SESSION['VORANMELDUNG_A1_BRIEFE']);
	unset($_SESSION['VORANMELDUNG_A1_STRASSE']);
	unset($_SESSION['VORANMELDUNG_A1_HAUSNUMMER']);
	unset($_SESSION['VORANMELDUNG_A1_PLZ']);
	unset($_SESSION['VORANMELDUNG_A1_ORT']);
	unset($_SESSION['VORANMELDUNG_A1_TEILORT']);
	unset($_SESSION['VORANMELDUNG_A1_TELEFON1']);
	unset($_SESSION['VORANMELDUNG_A1_TELEFON2']);
	unset($_SESSION['VORANMELDUNG_A1_HANDY1']);
	unset($_SESSION['VORANMELDUNG_A1_MAIL']);
	unset($_SESSION['VORANMELDUNG_A2']);
	unset($_SESSION['VORANMELDUNG_A2_NACHNAME']);
	unset($_SESSION['VORANMELDUNG_A2_VORNAME']);
	unset($_SESSION['VORANMELDUNG_A2_GESCHLECHT']);
	unset($_SESSION['VORANMELDUNG_A2_SORGERECHT']);
	unset($_SESSION['VORANMELDUNG_A2_BRIEFE']);
	unset($_SESSION['VORANMELDUNG_A2_STRASSE']);
	unset($_SESSION['VORANMELDUNG_A2_HAUSNUMMER']);
	unset($_SESSION['VORANMELDUNG_A2_PLZ']);
	unset($_SESSION['VORANMELDUNG_A2_ORT']);
	unset($_SESSION['VORANMELDUNG_A2_TEILORT']);
	unset($_SESSION['VORANMELDUNG_A2_TELEFON1']);
	unset($_SESSION['VORANMELDUNG_A2_TELEFON2']);
	unset($_SESSION['VORANMELDUNG_A2_HANDY1']);
	unset($_SESSION['VORANMELDUNG_A2_MAIL']);
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
