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


if (cms_angemeldet() && cms_r("schulhof.gruppen.kurse.anlegen")) {

	// Finde Anzahl an Gruppen
	$klassen = array();
	if (($schuljahr == '-') && ($stufe == '-')) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr IS NULL) AS x ORDER BY reihenfolge ASC, bez ASC");
	}
	else if (($schuljahr != '-') && ($stufe == '-')) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge ASC, bez ASC");
		$sql->bind_param("i", $schuljahr);
	}
	else if (($schuljahr == '-') && ($stufe != '-')) {
		$sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr IS NULL AND stufe = ?) AS x ORDER BY reihenfolge ASC, bez ASC");
		$sql->bind_param("i", $stufe);
	}
	else {
		$sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ? AND stufe = ?) AS x ORDER BY reihenfolge ASC, bez ASC");
		$sql->bind_param("ii", $schuljahr, $stufe);
	}
	if ($sql->execute()) {
		$sql->bind_result($kid, $kbez, $kreihe);
		while ($sql->fetch()) {
			$k = array();
			$k['id'] = $kid;
			$k['bez'] = $kbez;
			$k['reihenfolge'] = $kreihe;
			array_push($klassen, $k);
		}
	}
	$sql->close();

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
