<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['lehrerkuerzel'])) {$lehrerkuerzel = $_POST['lehrerkuerzel'];} else {echo "FEHLER";exit;}
if (isset($_POST['stundenplan'])) {$stundenplan = $_POST['stundenplan'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
if (($modus != '1') && ($modus != 0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if ($modus == "1") {
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
	if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
}

if (cms_angemeldet() && r("schulhof.verwaltung.lehrer.kürzel")) {
	$fehler = false;

	if (!$fehler) {
		// PROFILDATEN UPDATEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE lehrer SET kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stundenplan = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
		$sql->bind_param("ssi", $lehrerkuerzel, $stundenplan, $id);
		$sql->execute();
		$sql->close();
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
