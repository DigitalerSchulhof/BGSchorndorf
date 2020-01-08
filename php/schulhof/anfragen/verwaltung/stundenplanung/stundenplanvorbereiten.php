<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art']))       {$art = $_POST['art'];} else {echo "FEHLER";exit;}
if (isset($_POST['id']))        {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['zeitraum']))  {$zeitraum = $_POST['zeitraum'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID']))  {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (($art != 'm') && ($art != 'l') && ($art != 'p') && ($art != 'r') && ($art != 'k') && ($art != 't')) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($zeitraum,0) && ($zeitraum !== '-')) {echo "FEHLER";exit;}

if ($art == 'm') {
	if (cms_angemeldet()) {
		$_SESSION['MEINSTUNDENPLANID'] = $CMS_BENUTZERID;
		$_SESSION['MEINSTUNDENPLANZEITRAUM'] = $zeitraum;
		echo "ERFOLG";
	}
	else {
		echo "BERECHTIGUNG";
	}
}
else {
	cms_rechte_laden();
	if ($art == 'p') {
		if (cms_angemeldet() && cms_r("schulhof.verwaltung.personen.daten"))) {
			$_SESSION['PERSONSTUNDENPLANID'] = $id;
			$_SESSION['PERSONSTUNDENPLANZEITRAUM'] = $zeitraum;
			echo "ERFOLG";
		}
		else {
			echo "BERECHTIGUNG";
		}
	}
	else if ($art == 'l') {
		if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.lehrer"))) {
			$_SESSION['LEHRERSTUNDENPLANID'] = $id;
			$_SESSION['LEHRERSTUNDENPLANZEITRAUM'] = $zeitraum;
			echo "ERFOLG";
		}
		else {
			echo "BERECHTIGUNG";
		}
	}
	else if ($art == 'r') {
		if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.räume"))) {
			$_SESSION['RAUMSTUNDENPLANID'] = $id;
			$_SESSION['RAUMSTUNDENPLANZEITRAUM'] = $zeitraum;
			echo "ERFOLG";
		}
		else {
			echo "BERECHTIGUNG";
		}
	}
	else if ($art == 'k') {
		if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.klassen"))) {
			$_SESSION['KLASSENSTUNDENPLANID'] = $id;
			$_SESSION['KLASSENSTUNDENPLANZEITRAUM'] = $zeitraum;
			echo "ERFOLG";
		}
		else {
			echo "BERECHTIGUNG";
		}
	}
	else if ($art == 't') {
		if (cms_angemeldet() && cms_r("schulhof.information.pläne.stundenpläne.stufen"))) {
			$_SESSION['STUFENSTUNDENPLANID'] = $id;
			$_SESSION['STUFENSTUNDENPLANZEITRAUM'] = $zeitraum;
			echo "ERFOLG";
		}
		else {
			echo "BERECHTIGUNG";
		}
	}
	else {
		echo "FEHLER";
	}
}
?>
