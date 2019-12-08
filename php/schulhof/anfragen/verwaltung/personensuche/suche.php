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
if (isset($_POST['extern'])) {$extern = $_POST['extern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['verwaltung'])) {$verwaltung = $_POST['verwaltung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gewaehlt'])) {$gewaehlt = $_POST['gewaehlt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['feld'])) {$feld = $_POST['feld'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gruppe'])) {$gruppe= $_POST['gruppe'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$zugriff = true;

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	// Zusammenbauen der Bedingung
	$id = $_SESSION['BENUTZERID'];
	$sqlwhere = '';

	// Prüfen, welche Benutzerarten erlaubt sind
	$artg = cms_vornegross($art);
	$eeltern = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Eltern'];
  $eschueler = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Schüler'];
  $elehrer = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Lehrer'];
  $everwaltung = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Verwaltungsangestellte'];
  $eextern = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Externe'];

	// Personenarten in die Bedingung einschließen
	$sqlpersonenart = "";
	if (($schueler == 1) && $eschueler) {$sqlpersonenart .= " OR art = 's'";}
	if (($lehrer == 1) && $elehrer) {$sqlpersonenart .= " OR art = 'l'";}
	if (($eltern == 1) && $eeltern) {$sqlpersonenart .= " OR art = 'e'";}
	if (($verwaltung == 1) && $everwaltung) {$sqlpersonenart .= " OR art = 'v'";}
	if (($extern == 1) && $eextern) {$sqlpersonenart .= " OR art = 'x'";}

	if (strlen($sqlpersonenart) > 0) {
		$sqlwhere = "(".substr($sqlpersonenart, 4).")";
	}
	else {
		if ($eschueler) {$sqlpersonenart .= " OR art = 's'";}
		if ($elehrer) {$sqlpersonenart .= " OR art = 'l'";}
		if ($eeltern) {$sqlpersonenart .= " OR art = 'e'";}
		if ($everwaltung) {$sqlpersonenart .= " OR art = 'v'";}
		if ($eextern) {$sqlpersonenart .= " OR art = 'x'";}
		$sqlwhere = "(".substr($sqlpersonenart, 4).")";
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

 	// Nutzerkonto vorausgesetzt beim Postfach
	if ($art == 'postfach') {
		$sqlwhere .= " AND nutzerkonto IS NOT NULL ";
 }

	// IDs ausschließen
	if ($art == 'postfach') {
		$gewaehlt = $gewaehlt."|".$id;
	}
	if (strlen($gewaehlt) > 0) {
		$ausschluss = "(".str_replace('|', ',', substr($gewaehlt, 1)).")";
		if (cms_check_idliste($ausschluss)) {
			$sqlwhere .= "AND id NOT IN $ausschluss";
		}
	}

	// Zugängliche Benutzergruppen festlegen
	// Kommt noch

	// AUSGABE
	$ausgabe = "ERFOLG;";

	if (strlen($sqlwhere) > 0) {
		$sqlwhere = "WHERE ".$sqlwhere;
	}

	$sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname FROM personen) AS personen $sqlwhere ORDER BY nachname ASC, vorname ASC");

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
