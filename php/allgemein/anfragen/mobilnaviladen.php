<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/seiten/navigationen/navigationen.php");
include_once("../../website/seiten/seitenauswertung.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}

$fehler = false;
if (!cms_check_ganzzahl($id)) {$fehler = true;}

$dbs = cms_verbinden();

$sql = "SELECT * FROM seiten WHERE id = '{$id}'";
if ($anfrage = $dbs->query($sql)) {	// Safe weil Ganzzahl check
	if ($daten = $anfrage->fetch_assoc()) {
		$seite = $daten;
	}
	else {$fehler = true;}
	$anfrage->free();
}
else {$fehler = true;}

if (!$fehler) {
	if (($seite['art'] == 's') || ($seite['art'] == 'm')) {
		echo cms_mobilnavigation_oberseite($dbs, $seite['id']);
	}
	else if (($seite['art'] == 't') || ($seite['art'] == 'g') || ($seite['art'] == 'b')) {
		$jetzt = time();
		$jahrbeginn = date('Y', $jetzt);
		$jahrende = $jahrbeginn;
		$art = '';
		if ($seite['art'] == 't') {
			$sql = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";
			$art = 'Termine';
		}
		if ($seite['art'] == 'b') {
			$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM blogeintraege WHERE aktiv = '1'";
			$art = 'Blog';
		}
		if ($seite['art'] == 'g') {
			$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM galerien WHERE aktiv = '1'";
			$art = 'Galerien';
		}
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			if ($daten = $anfrage->fetch_assoc()) {
				if (!is_null($daten['beginn'])) {
					$jahrbeginn = min($jahrbeginn, date('Y', $daten['beginn']));
					$jahrende = max($jahrende, date('Y', $daten['ende']));
				}
			}
			$anfrage->free();
		}
		$monat = cms_monatsnamekomplett(date('n', $jetzt));
		$code = "";
		for ($i = $jahrende; $i>=$jahrbeginn; $i--) {
			$code .= "<li><a href=\"Website/".$art."/$i/$monat\">".$i."</a></li>";
		}
		if (strlen($code) > 0) {$code = "<ul>".$code."</ul>";}
		echo $code;
	}
	else {echo "FEHLER";}
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
