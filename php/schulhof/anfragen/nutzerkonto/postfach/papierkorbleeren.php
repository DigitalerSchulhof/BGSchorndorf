<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {

	$dbp = cms_verbinden('p');
	$tabelle = "post$modus"."_".$CMS_BENUTZERID;

	// Nachricht löschen inklusive Anhang
	$sql = "SELECT id FROM $tabelle WHERE papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');";
	if ($anfrage = $dbp->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$daten['id'])) {
				cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$daten['id']);
			}
		}
		$anfrage->free();
	}

	$sql = "DELETE FROM $tabelle WHERE papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');";
	$anfrage = $dbp->query($sql);

	cms_trennen($dbp);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
