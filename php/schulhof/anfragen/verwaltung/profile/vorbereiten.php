<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Profile bearbeiten'] || $CMS_RECHTE['Planung']['Profile anlegen'] || $CMS_RECHTE['Planung']['Profile löschen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["PROFILSCHULJAHR"] = $id;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
