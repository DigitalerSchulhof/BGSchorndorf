<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER"; exit;}
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vonT'])) {$vonT = $_POST['vonT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vonM'])) {$vonM = $_POST['vonM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vonJ'])) {$vonJ = $_POST['vonJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bisT'])) {$bisT = $_POST['bisT'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bisM'])) {$bisM = $_POST['bisM'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bisJ'])) {$bisJ = $_POST['bisJ'];} else {echo "FEHLER"; exit;}
if (isset($_POST['pinnwand'])) {$pinnwand = $_POST['pinnwand'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ANSCHLAGBEARBEITEN'])) {$id = $_SESSION['ANSCHLAGBEARBEITEN'];} else {echo "FEHLER"; exit;}
$titel = cms_texttrafo_e_db($titel);
$inhalt = cms_texttrafo_e_db($inhalt);

if (!cms_check_name($titel))              {echo "FEHLER"; exit;}
if (!cms_check_titel($pinnwand))          {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($vonT,1,31))      {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($vonM,1,12))      {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($vonJ,0))         {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($bisT,1,31))      {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($bisM,1,12))      {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($bisJ,0))         {echo "FEHLER"; exit;}

$beginn = mktime(0, 0, 0, $vonM, $vonT, $vonJ);
$ende = mktime(0, 0, 0, $bisM, $bisT, $bisJ);
if ($beginn-$ende >= 0) {echo "FEHLER"; exit;}

// Pinnwand laden
$dbs = cms_verbinden('s');
cms_rechte_laden();

// Prüfen, ob der Anschlag zur ausführenden Person gehört
$zugehoerig = false;
if (cms_r("schulhof.information.pinnwände.anschläge.bearbeiten"))) {$zugehoerig = true;}
else {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM pinnwandanschlag JOIN pinnwaende ON pinnwandanschlag.pinnwand = pinnwaende.id WHERE pinnwandanschlag.id = ? AND pinnwaende.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND pinnwandanschlag.idvon = ?");
	$sql->bind_param("isi", $id, $pinnwand, $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl == 1) {$zugehoerig = true;}}
	}
	$sql->close();
}

$zugriff = cms_r("schulhof.information.pinnwände.anschläge.bearbeiten")) || $zugehoerig;

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$jetzt = time();
		$sql = $dbs->prepare("UPDATE pinnwandanschlag SET titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ?, idvon = ?, idzeit = ? WHERE id = ?");
	  $sql->bind_param("ssiiiii", $titel, $inhalt, $beginn, $ende, $CMS_BENUTZERID, $jetzt, $id);
	  $sql->execute();
	  $sql->close();

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
