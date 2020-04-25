<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['notizen'])) {$notizen = $_POST['notizen'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.nutzerkonto.notizen")) {
	// PROFILDATEN UPDATEN
	$dbs = cms_verbinden('s');
	$notizen = cms_texttrafo_e_db($notizen);
	$sql = $dbs->prepare("UPDATE nutzerkonten SET notizen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
	$sql->bind_param("si", $notizen, $id);
	$sql->execute();
	$sql->close();
	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
