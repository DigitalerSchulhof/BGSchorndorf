<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$tagid = $_POST['id'];} else {$tagid = '';}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$person = $CMS_BENUTZERID;
	$fehler = false;

	$dbp = cms_verbinden('p');

	if (!$fehler) {
		// Tag löschen
		$sql = $dbp->prepare("DELETE FROM posttags_$person WHERE id = ?;");
		$sql->bind_param("i", $tagid);
		$sql->execute();
		$sql->close();

		echo "ERFOLG";
	}
	else {
		echo "BERECHTIGUNG";
	}
	cms_trennen($dbp);
}
else {
	echo "BERECHTIGUNG";
}
?>
