<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER";exit;}
if (isset($_POST['postfach'])) {$postfach = $_POST['postfach'];} else {echo "FEHLER";exit;}
if (isset($_POST['leer'])) {$leer = $_POST['leer'];} else {echo "FEHLER";exit;}
if (isset($_POST['eltern'])) {$eltern = $_POST['eltern'];} else {echo "FEHLER";exit;}
if (isset($_POST['kinder'])) {$kinder = $_POST['kinder'];} else {echo "FEHLER";exit;}
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER";exit;}
if (isset($_POST['reli'])) {$reli = $_POST['reli'];} else {echo "FEHLER";exit;}
if (isset($_POST['adresse'])) {$adresse = $_POST['adresse'];} else {echo "FEHLER";exit;}
if (isset($_POST['kontaktdaten'])) {$kontaktdaten = $_POST['kontaktdaten'];} else {echo "FEHLER";exit;}
if (isset($_POST['geburtsdatum'])) {$geburtsdatum = $_POST['geburtsdatum'];} else {echo "FEHLER";exit;}
if (isset($_POST['konfession'])) {$konfession = $_POST['konfession'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();

$zugriff = false;

if (!cms_check_toggle($postfach)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($leer)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($eltern)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($kinder)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($klassen)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($reli)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($adresse)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($kontaktdaten)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($geburtsdatum)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($konfession)) {echo "FEHLER"; exit;}

if (!cms_valide_gruppe($gruppe)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($gruppenid, 0)) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$zugriff = $CMS_RECHTE['Gruppen'][$gruppe." Listen sehen"];

$gk = cms_textzudb($gruppe);

// Prüfen, ob Mitglied in dieser Gruppe
if (!$zugriff) {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk JOIN $gk"."mitglieder ON $gk.id = $gk"."mitglieder.gruppe WHERE $gk"."mitglieder.person = $CMS_BENUTZERID AND $gk.id = ?");
	$sql->bind_param("i", $gruppenid);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl == 1) {$zugriff = true;}}
	}
	$sql->close();
}

// Gruppeninformation abholen
$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk WHERE $gk.id = ?");
$sql->bind_param("i", $gruppenid);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
	if ($sql->fetch()) {if ($anzahl != 1) {$zugriff = false;}}
}
$sql->close();


if (cms_angemeldet() && $zugriff) {

	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

	include_once('../../schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
  $schreibenpool = cms_postfach_empfaengerpool_generieren($dbs);

  include_once('../../schulhof/seiten/listen/listenausgeben.php');
  $rueckgabe = cms_listen_gruppenliste_ausgeben($dbs, $gk, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli,
	                                       $adresse, $kontaktdaten, $geburtsdatum, $konfession);
	echo $rueckgabe['tabelle'];
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
