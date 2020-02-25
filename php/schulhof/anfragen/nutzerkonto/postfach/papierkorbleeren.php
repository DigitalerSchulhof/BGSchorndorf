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
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {

	$dbp = cms_verbinden('p');
	$tabelle = "post$modus"."_".$CMS_BENUTZERID;

	// Nachricht löschen inklusive Anhang
	$sql = $dbp->prepare("SELECT id FROM $tabelle WHERE papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');");
	if ($sql->execute()) {
		$sql->bind_result($nid);
		while ($sql->fetch()) {
			if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/$nid")) {
				cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/$nid");
			}
		}
	}
	$sql->close();

	$sql = $dbp->prepare("DELETE FROM $tabelle WHERE papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');");
	$sql->execute();

	cms_trennen($dbp);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
