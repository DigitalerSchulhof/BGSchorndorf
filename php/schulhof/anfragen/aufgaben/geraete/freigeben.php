<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETESTANDORT"])) {$standort = $_SESSION["GERAETESTANDORT"];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["GERAETEART"])) {$art = $_SESSION["GERAETEART"];} else {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($standort, 0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Technik']['Geräte verwalten'];

if (cms_angemeldet() && $zugriff) {
	if ($art == 'l') {$tabelle = 'leihengeraete';}
	else if ($art == 'r') {$tabelle = 'raeumegeraete';}

	// GERÄTE ZURÜCKSETZEN
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE $tabelle SET statusnr = 0, meldung = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), kommentar = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), absender = NULL, zeit = NULL, ticket = '' WHERE standort = ? AND id = ?");
  $sql->bind_param("ii", $standort, $id);
  $sql->execute();
  $sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
