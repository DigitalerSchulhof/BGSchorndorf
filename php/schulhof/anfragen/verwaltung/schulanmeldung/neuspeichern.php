<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();
if (isset($_POST['akzeptiert'])) {$akzeptiert = cms_texttrafo_e_db($_POST['akzeptiert']);} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname'])) {$nachname = cms_texttrafo_e_db($_POST['nachname']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = cms_texttrafo_e_db($_POST['vorname']);} else {echo "FEHLER"; exit;}
if (isset($_POST['rufname'])) {$rufname = cms_texttrafo_e_db($_POST['rufname']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumT'])) {$geburtsdatumT = cms_texttrafo_e_db($_POST['geburtsdatumT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumM'])) {$geburtsdatumM = cms_texttrafo_e_db($_POST['geburtsdatumM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsdatumJ'])) {$geburtsdatumJ = cms_texttrafo_e_db($_POST['geburtsdatumJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsort'])) {$geburtsort = cms_texttrafo_e_db($_POST['geburtsort']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geburtsland'])) {$geburtsland = cms_texttrafo_e_db($_POST['geburtsland']);} else {echo "FEHLER"; exit;}
if (isset($_POST['muttersprache'])) {$muttersprache = cms_texttrafo_e_db($_POST['muttersprache']);} else {echo "FEHLER"; exit;}
if (isset($_POST['verkehrssprache'])) {$verkehrssprache = cms_texttrafo_e_db($_POST['verkehrssprache']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht'])) {$geschlecht = cms_texttrafo_e_db($_POST['geschlecht']);} else {echo "FEHLER"; exit;}
if (isset($_POST['religion'])) {$religion = cms_texttrafo_e_db($_POST['religion']);} else {echo "FEHLER"; exit;}
if (isset($_POST['religionsunterricht'])) {$religionsunterricht = cms_texttrafo_e_db($_POST['religionsunterricht']);} else {echo "FEHLER"; exit;}
if (isset($_POST['land1'])) {$land1 = cms_texttrafo_e_db($_POST['land1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['land2'])) {$land2 = cms_texttrafo_e_db($_POST['land2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse'])) {$strasse = cms_texttrafo_e_db($_POST['strasse']);} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer'])) {$hausnummer = cms_texttrafo_e_db($_POST['hausnummer']);} else {echo "FEHLER"; exit;}
if (isset($_POST['plz'])) {$plz = cms_texttrafo_e_db($_POST['plz']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ort'])) {$ort = cms_texttrafo_e_db($_POST['ort']);} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort'])) {$teilort = cms_texttrafo_e_db($_POST['teilort']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon1'])) {$telefon1 = cms_texttrafo_e_db($_POST['telefon1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon2'])) {$telefon2 = cms_texttrafo_e_db($_POST['telefon2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['handy1'])) {$handy1 = cms_texttrafo_e_db($_POST['handy1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['handy2'])) {$handy2 = cms_texttrafo_e_db($_POST['handy2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['mail'])) {$mail = cms_texttrafo_e_db($_POST['mail']);} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungT'])) {$einschulungT = cms_texttrafo_e_db($_POST['einschulungT']);} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungM'])) {$einschulungM = cms_texttrafo_e_db($_POST['einschulungM']);} else {echo "FEHLER"; exit;}
if (isset($_POST['einschulungJ'])) {$einschulungJ = cms_texttrafo_e_db($_POST['einschulungJ']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorigeschule'])) {$vorigeschule = cms_texttrafo_e_db($_POST['vorigeschule']);} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = cms_texttrafo_e_db($_POST['klasse']);} else {echo "FEHLER"; exit;}
if (isset($_POST['profil'])) {$profil = cms_texttrafo_e_db($_POST['profil']);} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname1'])) {$nachname1 = cms_texttrafo_e_db($_POST['nachname1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname1'])) {$vorname1 = cms_texttrafo_e_db($_POST['vorname1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht1'])) {$geschlecht1 = cms_texttrafo_e_db($_POST['geschlecht1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['sorgerecht1'])) {$sorgerecht1 = cms_texttrafo_e_db($_POST['sorgerecht1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['briefe1'])) {$briefe1 = cms_texttrafo_e_db($_POST['briefe1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse1'])) {$strasse1 = cms_texttrafo_e_db($_POST['strasse1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer1'])) {$hausnummer1 = cms_texttrafo_e_db($_POST['hausnummer1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['plz1'])) {$plz1 = cms_texttrafo_e_db($_POST['plz1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ort1'])) {$ort1 = cms_texttrafo_e_db($_POST['ort1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort1'])) {$teilort1 = cms_texttrafo_e_db($_POST['teilort1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon11'])) {$telefon11 = cms_texttrafo_e_db($_POST['telefon11']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon21'])) {$telefon21 = cms_texttrafo_e_db($_POST['telefon21']);} else {echo "FEHLER"; exit;}
if (isset($_POST['handy11'])) {$handy11 = cms_texttrafo_e_db($_POST['handy11']);} else {echo "FEHLER"; exit;}
if (isset($_POST['mail1'])) {$mail1 = cms_texttrafo_e_db($_POST['mail1']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ansprechpartner2'])) {$ansprechpartner2 = cms_texttrafo_e_db($_POST['ansprechpartner2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname2'])) {$nachname2 = cms_texttrafo_e_db($_POST['nachname2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname2'])) {$vorname2 = cms_texttrafo_e_db($_POST['vorname2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht2'])) {$geschlecht2 = cms_texttrafo_e_db($_POST['geschlecht2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['sorgerecht2'])) {$sorgerecht2 = cms_texttrafo_e_db($_POST['sorgerecht2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['briefe2'])) {$briefe2 = cms_texttrafo_e_db($_POST['briefe2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['strasse2'])) {$strasse2 = cms_texttrafo_e_db($_POST['strasse2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['hausnummer2'])) {$hausnummer2 = cms_texttrafo_e_db($_POST['hausnummer2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['plz2'])) {$plz2 = cms_texttrafo_e_db($_POST['plz2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['ort2'])) {$ort2 = cms_texttrafo_e_db($_POST['ort2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['teilort2'])) {$teilort2 = cms_texttrafo_e_db($_POST['teilort2']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon12'])) {$telefon12 = cms_texttrafo_e_db($_POST['telefon12']);} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon22'])) {$telefon22 = cms_texttrafo_e_db($_POST['telefon22']);} else {echo "FEHLER"; exit;}
if (isset($_POST['handy12'])) {$handy12 = cms_texttrafo_e_db($_POST['handy12']);} else {echo "FEHLER"; exit;}
if (isset($_POST['mail2'])) {$mail2 = cms_texttrafo_e_db($_POST['mail2']);} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.organisation.schulanmeldung.vorbereiten")) {
	$fehler = false;
	$jetzt = time();

	if (!cms_check_toggle($akzeptiert)) {$fehler = true;}
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
	if (strlen($strasse) <= 0) {$fehler = true;}
	if (strlen($hausnummer) <= 0) {$fehler = true;}
	if (strlen($plz) <= 0) {$fehler = true;}
	if (strlen($ort) <= 0) {$fehler = true;}
	if ((strlen($telefon1) <= 0) && (strlen($telefon2) <= 0) && (strlen($handy1) <= 0) && (strlen($handy2) <= 0)) {$fehler = true;}
	if (strlen($mail)) {if (!cms_check_mail($mail)) {$fehler = true;}}
	if ($einschulung >= $jetzt) {$fehler = true;}
	if (strlen($vorigeschule) <= 0) {$fehler = true;}
	if (strlen($klasse) <= 0) {$fehler = true;}
	if (strlen($profil) <= 0) {$fehler = true;}
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

	if ($akzeptiert == '1') {$akzeptiert = 'ja';} else {$akzeptiert = 'nein';}

	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$schuelerid = cms_generiere_kleinste_id('voranmeldung_schueler');
		$sql = $dbs->prepare("UPDATE voranmeldung_schueler SET vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), rufname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsdatum = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geburtsland = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), muttersprache = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), verkehrssprache = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), religion = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), religionsunterricht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), staatsangehoerigkeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zstaatsangehoerigkeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), strasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausnummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), plz = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), teilort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), mail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), einschulung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorigeschule = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorigeklasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kuenftigesprofil = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), akzeptiert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), eingegangen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	  $sql->bind_param("sssssssssssssssssssssssssssssi", $vorname, $rufname, $nachname, $geburtsdatum, $geburtsort, $geburtsland, $muttersprache, $verkehrssprache, $geschlecht, $religion, $religionsunterricht, $land1, $land2, $strasse, $hausnummer, $plz, $ort, $teilort, $telefon1, $telefon2, $handy1, $handy2, $mail, $einschulung, $vorigeschule, $klasse, $profil, $akzeptiert, $jetzt, $schuelerid);
	  $sql->execute();
	  $sql->close();



		// Ansprechpartner 1 eintragen
		$id = cms_generiere_kleinste_id('voranmeldung_eltern');
		$sql = $dbs->prepare("UPDATE voranmeldung_eltern SET schueler = ?, nummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sorgerecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), briefe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), strasse = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausnummer = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), plz = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), teilort = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon1 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), telefon2 = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), handy = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), mail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		$nummer = 'eins';
	  $sql->bind_param("isssssssssssssssi", $schuelerid, $nummer, $vorname1, $nachname1, $geschlecht1, $sorgerecht1, $briefe1, $strasse1, $hausnummer1, $plz1, $ort1, $teilort1, $telefon11, $telefon21, $handy11, $mail1, $id);
	  $sql->execute();

		// Ansprechpartner 2 eintragen
		if ($ansprechpartner2 == '1') {
			$id = cms_generiere_kleinste_id('voranmeldung_eltern');
			$nummer = 'zwei';
		  $sql->bind_param("isssssssssssssssi", $schuelerid, $nummer, $vorname2, $nachname2, $geschlecht2, $sorgerecht2, $briefe2, $strasse2, $hausnummer2, $plz2, $ort2, $teilort2, $telefon12, $telefon22, $handy12, $mail2, $id);
		  $sql->execute();
		}
		$sql->close();

		cms_trennen($dbs);
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
