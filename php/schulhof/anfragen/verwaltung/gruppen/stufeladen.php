<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($schuljahr,0)) && ($schuljahr != '-')) {echo "FEHLER"; exit;}
if (isset($_POST['gewaehlt'])) {$gewaehlt = $_POST['gewaehlt'];} else {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($gewaehlt,0)) && ($gewaehlt != '-')) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Gruppen']['Klassen anlegen'] || $CMS_RECHTE['Gruppen']['Kurse anlegen'];

if (cms_angemeldet() && $zugriff) {

	// Finde Anzahl an Gruppen
	$stufen = array();
	if ($schuljahr == '-') {$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr IS NULL ORDER BY reihenfolge";}
	else {$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr = $schuljahr ORDER BY reihenfolge";}
	if ($anfrage = $dbs->query($sql)) {	// Safe weil ID Check
		while ($daten = $anfrage->fetch_assoc()) {
			array_push($stufen, $daten);
		}
		$anfrage->free();
	}

	$code = "";
	foreach ($stufen AS $s) {
		if ($gewaehlt == $s['id']) {$wahl = " selected=\"selected\";";} else {$wahl = "";}
		$code .= "<option value=\"".$s['id']."\"$wahl>".$s['bez']."</option>";
	}
	if ($gewaehlt == '-') {$wahl = " selected=\"selected\";";} else {$wahl = "";}
	$code .= "<option value=\"-\"$wahl>stufenübergreifend</option>";
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
