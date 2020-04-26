<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();
// Variablen einlesen, falls übergeben
if (isset($_POST['schueler'])) {$schueler = $_POST['schueler'];} else {echo "FEHLER"; exit;}
if (isset($_POST['eltern'])) {$eltern = $_POST['eltern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['verwaltung'])) {$verwaltung = $_POST['verwaltung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['extern'])) {$extern = $_POST['extern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gewaehlt'])) {$gewaehlt = $_POST['gewaehlt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['feld'])) {$feld = $_POST['feld'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['POSTEMPFAENGERPOOL'])) {$empfaengerpool = $_SESSION['POSTEMPFAENGERPOOL'];} else {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$dbs = cms_verbinden('s');

	// Zusammenbauen der Bedingung
	$id = $_SESSION['BENUTZERID'];
	$sqlwhere = 'id IN ('.implode(',', $empfaengerpool).') ';
	if (strlen($gewaehlt) > 0) {
		$ausschluss = "(".str_replace('|', ',', substr($gewaehlt, 1)).")";
		if (cms_check_idliste($ausschluss)) {
			$sqlwhere .= "AND id NOT IN $ausschluss";
		}
	}
	// Arten einbauen
	if (($schueler != 0) || ($eltern != 0) || ($lehrer != 0) || ($verwaltung != 0) || ($extern != 0)) {
		$artsql = "";
		if ($schueler == 1) {$artsql .= "OR art = 's' ";}
		if ($eltern == 1) {$artsql .= "OR art = 'e' ";}
		if ($lehrer == 1) {$artsql .= "OR art = 'l' ";}
		if ($verwaltung == 1) {$artsql .= "OR art = 'v' ";}
		if ($extern == 1) {$artsql .= "OR art = 'x' ";}
		if (strlen($artsql) > 0) {$sqlwhere .= "AND (".substr($artsql, 3).")";}
	}

	// Personennamen untersuchen
	if (cms_check_suchtext($nachname)) {
		$nachname = cms_texttrafo_e_db($nachname);
		$sqlwhere .= " AND UPPER(CONVERT(nachname USING utf8)) LIKE UPPER('$nachname%')";
	}
	if (cms_check_suchtext($vorname)) {
		$vorname = cms_texttrafo_e_db($vorname);
		$sqlwhere .= " AND UPPER(CONVERT(vorname USING utf8)) LIKE UPPER('$vorname%')";
		$aenderung = true;
	}

	// AUSGABE
	$ausgabe = "ERFOLG;";

	if (strlen($sqlwhere) > 0) {
		$sqlwhere = "WHERE ".$sqlwhere;
	}

	$sql = $dbs->prepare("SELECT * FROM (SELECT id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname FROM personen) AS personen $sqlwhere ORDER BY nachname ASC, vorname ASC");

	if ($sql->execute()) {
		$sql->bind_result($pid, $part, $ptitel, $pnachname, $pvorname);
		while ($sql->fetch()) {
			$ausgabe .= "$pid,$part,".cms_generiere_anzeigename($pvorname, $pnachname, $ptitel).";";
		}
	}
	$sql->close();
	cms_trennen($dbs);

	echo $ausgabe;
}
else {
	echo "BERECHTIGUNG";
}
?>
