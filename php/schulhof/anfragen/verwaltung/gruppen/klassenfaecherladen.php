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

$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Gruppen']['Klassen anlegen'];

if (cms_angemeldet() && $zugriff) {

	// Finde Anzahl an Gruppen
	$faecher = array();
	if ($schuljahr == '-') {$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM faecher WHERE schuljahr IS NULL) AS x ORDER BY bez";}
	else {$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM faecher WHERE schuljahr = $schuljahr) AS x ORDER BY bez";}
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			array_push($faecher, $daten);
		}
		$anfrage->free();
	}

	$code = "";
	$allefaecherids = "";
	foreach ($faecher as $f) {
		$code .= cms_togglebutton_generieren("cms_gruppe_faecher_".$f['id'], $f['bez'], 0, 'cms_gruppe_faecheraktualisieren()')." ";
		$allefaecherids .= "|".$f['id'];
	}
	if (count($faecher) == 0) {$code .= "<span class=\"cms_notiz\">Keine Fächer für dieses Schuljahr angelegt</span>";}
	$code .= "<input type=\"hidden\" name=\"cms_gruppe_faecher_alle\" id=\"cms_gruppe_faecher_alle\" value=\"$allefaecherids\">";
	echo $code;
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
