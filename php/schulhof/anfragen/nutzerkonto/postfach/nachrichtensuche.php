<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/seiten/nutzerkonto/postfach/postfilter.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_POST['papierkorb'])) {$papierkorb = $_POST['papierkorb'];} else {echo "FEHLER";exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER";exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER";exit;}
if (isset($_POST['betreff'])) {$betreff = $_POST['betreff'];} else {echo "FEHLER";exit;}
if (isset($_POST['vonT'])) {$vonT = $_POST['vonT'];} else {echo "FEHLER";exit;}
if (isset($_POST['vonM'])) {$vonM = $_POST['vonM'];} else {echo "FEHLER";exit;}
if (isset($_POST['vonJ'])) {$vonJ = $_POST['vonJ'];} else {echo "FEHLER";exit;}
if (isset($_POST['bisT'])) {$bisT = $_POST['bisT'];} else {echo "FEHLER";exit;}
if (isset($_POST['bisM'])) {$bisM = $_POST['bisM'];} else {echo "FEHLER";exit;}
if (isset($_POST['bisJ'])) {$bisJ = $_POST['bisJ'];} else {echo "FEHLER";exit;}
if (isset($_POST['tags'])) {$tags = $_POST['tags'];} else {echo "FEHLER";exit;}
if (isset($_POST['nummer'])) {$nummer = $_POST['nummer'];} else {echo "FEHLER";exit;}
if (isset($_POST['app'])) {$app = $_POST['app'];} else {echo "FEHLER";exit;}
if (isset($_POST['limit'])) {$limit = $_POST['limit'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = true;

if (cms_angemeldet()) {
	$fehler = false;
	if (!cms_check_ganzzahl($CMS_BENUTZERID)) {$fehler = true;}
	if (!cms_check_name($vorname) && (strlen($vorname) != 0)) {$fehler = true;}
	if (!cms_check_name($nachname) && (strlen($nachname) != 0)) {$fehler = true;}
	if (!cms_check_nametitel($betreff) && (strlen($betreff) != 0)) {$fehler = true;}
	if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$start = mktime(0, 0, 0, $vonM, $vonT, $vonJ);
		$ende = mktime(23, 59, 59, $bisM, $bisT, $bisJ);
		echo cms_postfach_nachrichten_listen ($modus, $papierkorb, $start, $ende, $nachname, $vorname, $betreff, $tags, $nummer, $limit, true, $app);
		cms_trennen($dbs);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
