<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bereich'])) 	{$bereich = $_POST['bereich'];} 	else {echo "FEHLER"; exit;}

if (($bereich != 'system') && ($bereich != 'website') && ($bereich != 'schulhof') && ($bereich != 'gruppen') && ($bereich != 'personen')) {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("statistik.speicherplatz")) {
	// Temporäre Dateien löschen
	$pfad = "../../../dateien/download";
	chmod($pfad, 0777);
	$dateien = scandir($pfad);

	foreach ($dateien as $d) {
		if (($d != ".") && ($d != "..") && ($d != ".htaccess")) {
			// echo "Lösche: ".$pfad."/".$d."<br>";
			unlink($pfad."/".$d);
		}
	}
	chmod($pfad, 0775);

	// Speicherplatz bereichnen
	$fehler = false;
	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
	$speicherplatz = $CMS_EINSTELLUNGEN['Gesamtspeicherplatz'];
	$datgroesse = 0;
	$dbgroesse = 0;
	$tabellensystem = array("allgemeineeinstellungen", "zulaessigedateien");
	$tabellenwebsite = array("auszeichnungen", "blogeintraege", "blogeintragdownloads", "boxen", "boxenaussen", "diashowbilder", "diashows", "downloads", "editoren", "eventuebersichten", "galerien", "galerienbilder", "newsletterempfaenger", "newslettertypen", "seiten", "spalten", "termine", "terminedownloads", "weiterleiten");
	$tabellengruppen = array();

	foreach ($CMS_GRUPPEN AS $g) {
		$gk = cms_textzudb($g);
		array_push($tabellengruppen, $gk);
		array_push($tabellengruppen, $gk."aufsicht");
		array_push($tabellengruppen, $gk."blogeintraege");
		array_push($tabellengruppen, $gk."blogeintraegeintern");
		array_push($tabellengruppen, $gk."blogeintragbeschluesse");
		array_push($tabellengruppen, $gk."blogeintragdownloads");
		array_push($tabellengruppen, $gk."chat");
		array_push($tabellengruppen, $gk."chatmeldungen");
		array_push($tabellengruppen, $gk."galerien");
		array_push($tabellengruppen, $gk."mitglieder");
		array_push($tabellengruppen, $gk."newsletter");
		array_push($tabellengruppen, $gk."notifikationsabo");
		array_push($tabellengruppen, $gk."termine");
		array_push($tabellengruppen, $gk."termineintern");
		array_push($tabellengruppen, $gk."termineinterndownloads");
		array_push($tabellengruppen, $gk."vorsitz");
	}

	if ($bereich == "system") {
		$datgroesse += cms_dateisystem_ordner_info("../../../css")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../js")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../res")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../php")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../.htaccess")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../index.php")['groesse'];
		$dbgroesse += cms_db_tabellengroesse($CMS_DBS_DB, $tabellensystem);
	}
	else if ($bereich == "website") {
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/titelbilder")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/website")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../drucken.php")['groesse'];
		$dbgroesse += cms_db_tabellengroesse($CMS_DBS_DB, $tabellenwebsite);
	}
	else if ($bereich == "schulhof") {
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/schulhof/stundenplaene")['groesse'];
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/schulhof/vpn")['groesse'];
		$dbgroesse += cms_db_groesse($CMS_DBS_DB);
		$dbgroesse -= cms_db_tabellengroesse($CMS_DBS_DB, $tabellensystem);
		$dbgroesse -= cms_db_tabellengroesse($CMS_DBS_DB, $tabellenwebsite);
		$dbgroesse -= cms_db_tabellengroesse($CMS_DBS_DB, $tabellengruppen);
	}
	else if ($bereich == "gruppen") {
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/schulhof/gruppen")['groesse'];
		$dbgroesse += cms_db_tabellengroesse($CMS_DBS_DB, $tabellengruppen);
	}
	else if ($bereich == "personen") {
		$datgroesse += cms_dateisystem_ordner_info("../../../dateien/schulhof/personen")['groesse'];
		$dbgroesse += cms_db_groesse($CMS_DBP_DB);
	}


	$db_absolut_rein = $dbgroesse;
	$db_prozentual_rein = $dbgroesse / $speicherplatz * 100;
	if (preg_match("/[Ee]/", $db_prozentual_rein)) {$db_prozentual_rein = 0;}
	$db_absolut_einheit = cms_groesse_umrechnen($dbgroesse);
	$db_prozentual_einheit = str_replace(".", ",", round($db_prozentual_rein, 2))." %";

	$dat_absolut_rein = $datgroesse;
	$dat_prozentual_rein = $datgroesse / $speicherplatz * 100;
	if (preg_match("/[Ee]/", $dat_prozentual_rein)) {$dat_prozentual_rein = 0;}
	$dat_absolut_einheit = cms_groesse_umrechnen($datgroesse);
	$dat_prozentual_einheit = str_replace(".", ",", round($dat_prozentual_rein, 2))." %";

	$ges_absolut_rein = $datgroesse + $dbgroesse;
	$ges_prozentual_rein = $ges_absolut_rein / $speicherplatz * 100;
	if (preg_match("/[Ee]/", $ges_prozentual_rein)) {$ges_prozentual_rein = 0;}
	$ges_absolut_einheit = cms_groesse_umrechnen($ges_absolut_rein);
	$ges_prozentual_einheit = str_replace(".", ",", round($ges_prozentual_rein, 2))." %";

	echo "ERFOLG|$db_absolut_rein|$db_prozentual_rein|$db_absolut_einheit|$db_prozentual_einheit";
	echo "|$dat_absolut_rein|$dat_prozentual_rein|$dat_absolut_einheit|$dat_prozentual_einheit";
	echo "|$ges_absolut_rein|$ges_prozentual_rein|$ges_absolut_einheit|$ges_prozentual_einheit";
}
else {
	echo "BERECHTIGUNG";
}
?>
