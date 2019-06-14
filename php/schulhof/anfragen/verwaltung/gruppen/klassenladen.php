<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($schuljahr,0) && ($schuljahr != '-')) {echo "FEHLER"; exit;}
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($stufe,0) && ($stufe != '-')) {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Gruppen']['Kurse anlegen'];

if (cms_angemeldet() && $zugriff) {

	// Finde Anzahl an Gruppen
	$klassen = array();
	if ($schuljahr == '-') {$schuljahrtest = "schuljahr IS NULL";} else {$schuljahrtest = "schuljahr = $schuljahr";}
	if ($stufe != '-') {$stufetest = " AND stufe = $stufe";} else {$stufetest = "";}
	$sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.$schuljahrtest"."$stufetest) AS x ORDER BY reihenfolge ASC, bez ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			array_push($klassen, $daten);
		}
		$anfrage->free();
	}

	$code = "";
	$alleklassenids = "";
	foreach ($klassen AS $k) {
		$code .= cms_togglebutton_generieren("cms_gruppe_klassen_".$k['id'], $k['bez'], 0, 'cms_gruppe_klassenaktualisieren()')." ";
		$alleklassenids .= '|'.$k['id'];
	}
	$code .= "<input type=\"hidden\" name=\"cms_gruppe_klassen_alle\" id=\"cms_gruppe_klassen_alle\" value=\"$alleklassenids\">";
	$code .= "<input type=\"hidden\" name=\"cms_gruppe_klassen\" id=\"cms_gruppe_klassen\" value=\"\">";
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
