<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER";exit;}
if (isset($_POST['farbe'])) {$farbe = $_POST['farbe'];} else {echo "FEHLER";exit;}
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
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('posttags_'.$person, 'p');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		$dbp = cms_verbinden('p');
		// Neuen Tag eintragen
		$titel = cms_texttrafo_e_db($titel);
		$sql = $dbp->prepare("UPDATE posttags_$person SET person = ?, titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), farbe = ? WHERE id = ?;");
		$sql->bind_param("issi", $person, $titel, $farbe, $id);
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
