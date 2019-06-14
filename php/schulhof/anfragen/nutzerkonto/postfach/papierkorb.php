<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {

	$fehler = false;
	$dbp = cms_verbinden('p');
	$jetzt = time();
	$tabelle = "post$modus"."_".$CMS_BENUTZERID;
	// Nachricht in den Papierkorb verschieben
	$sql = $dbp->prepare("UPDATE $tabelle SET papierkorb = AES_ENCRYPT('1', '$CMS_SCHLUESSEL'), papierkorbseit = ? WHERE id = ?;");
	$sql->bind_param("ii", $jetzt, $id);
	$sql->execute();
	$sql->close();

	cms_trennen($dbp);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
