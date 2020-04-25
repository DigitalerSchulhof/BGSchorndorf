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
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {echo "FEHLER";exit;}

if (cms_angemeldet()) {

	$fehler = false;

	$dbp = cms_verbinden('p');
	$tabelle = "post$modus"."_".$CMS_BENUTZERID;

	$sql = $dbp->prepare("UPDATE $tabelle SET papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL'), papierkorbseit = NULL WHERE id = ?;");
	$sql->bind_param("i", $id);
	$sql->execute();
	$sql->close();

	cms_trennen($dbp);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
