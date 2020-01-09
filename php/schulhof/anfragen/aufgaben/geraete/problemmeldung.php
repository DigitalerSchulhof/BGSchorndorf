<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['geraeteids'])) {$geraeteids = $_POST['geraeteids'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['standort'])) {$standort = $_POST['standort'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($standort,0)) {echo "FEHLER"; exit;}
if (($art != 'r') && ($art != 'l')) {echo "FEHLER"; exit;}

$CMS_BENUTZERID = $_SESSION["BENUTZERID"];

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.technik.geräte.probleme")) {
	$fehler = false;

	$ids = explode('|', $geraeteids);
	$meldungen = array();
	$anzahl = 0;

	for ($i=1; $i<count($ids); $i++) {
		if ((isset($_POST['aktiv_'.$ids[$i]])) && (isset($_POST['meldung_'.$ids[$i]]))) {
			if (($_POST['aktiv_'.$ids[$i]] == 1) && (strlen($_POST['meldung_'.$ids[$i]]))) {
				$meldungen[$anzahl]['id'] = $ids[$i];
				$meldungen[$anzahl]['meldung'] = $_POST['meldung_'.$ids[$i]];
				$anzahl++;
			}
		}
		else {$fehler = true;}
	}

	if ($art == 'r') {$tabelle = 'raeumegeraete';}
	if ($art == 'l') {$tabelle = 'leihengeraete';}

	if (!$fehler) {
		// MELDUNG EINTRAGEN
		$dbs = cms_verbinden('s');
		$jetzt = time();
		foreach ($meldungen as $m) {
			$meldung = cms_texttrafo_e_db($m['meldung']);
			$sql = $dbs->prepare("UPDATE $tabelle SET statusnr = 1, meldung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), absender = ?, zeit = ? WHERE standort = ? AND id = ?");
			$sql->bind_param("siiii", $meldung, $CMS_BENUTZERID, $jetzt, $standort, $m['id']);
			$sql->execute();
			$sql->close();
		}
		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
