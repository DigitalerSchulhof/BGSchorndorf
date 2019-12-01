<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {$titel = '';}
if (isset($_POST['farbe'])) {$farbe = $_POST['farbe'];} else {$farbe = '';}
if (isset($_SESSION['TAGBEARBEITEN'])) {$tagid = $_SESSION['TAGBEARBEITEN'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($farbe,0,63)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {
	$fehler = false;
	$person = $CMS_BENUTZERID;

	if (strlen($titel) < 1) {
		$fehler = true;
	}

	if (!$fehler) {
		$dbp = cms_verbinden('p');
		$titel = cms_texttrafo_e_db($titel);
		$sql = $dbp->prepare("UPDATE posttags_$person SET farbe = ?, titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
		$sql->bind_param("isi", $farbe, $titel, $tagid);
		$sql->execute();
		$sql->close();
		cms_trennen($dbp);

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
