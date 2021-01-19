<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['groesse'])) 	{$groesse = $_POST['groesse'];} 	else {echo "FEHLER"; exit;}
if (isset($_POST['einheit'])) 	{$einheit = $_POST['einheit'];} 	else {echo "FEHLER"; exit;}
if (isset($_POST['gesamt'])) 	{$gesamt = $_POST['gesamt'];} 	else {echo "FEHLER"; exit;}
if (isset($_POST['geinheit'])) 	{$geinheit = $_POST['geinheit'];} 	else {echo "FEHLER"; exit;}
if (isset($_POST['max'])) 		{$max = $_POST['max'];} 			else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($groesse,1)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($gesamt,1)) {echo "FEHLER"; exit;}
if (($einheit != "B") && ($einheit != "KB") && ($einheit != "MB") && ($einheit != "GB") && ($einheit != "TB") && ($einheit != "PB") && ($einheit != "EB")) {echo "FEHLER"; exit;}
if (($geinheit != "B") && ($geinheit != "KB") && ($geinheit != "MB") && ($geinheit != "GB") && ($geinheit != "TB") && ($geinheit != "PB") && ($geinheit != "EB")) {echo "FEHLER"; exit;}


if (cms_angemeldet() && cms_r("technik.server.dateienerlaubnis")) {
	$fehler = false;

	if (!$fehler) {
		if ($einheit == "KB") {$groesse = $groesse * 1000;}
		else if ($einheit == "MB") {$groesse = $groesse * 1000 * 1000;}
		else if ($einheit == "GB") {$groesse = $groesse * 1000 * 1000 * 1000;}
		else if ($einheit == "TB") {$groesse = $groesse * 1000 * 1000 * 1000 * 1000;}
		else if ($einheit == "PB") {$groesse = $groesse * 1000 * 1000 * 1000 * 1000 * 1000;}
		else if ($einheit == "EB") {$groesse = $groesse * 1000 * 1000 * 1000 * 1000 * 1000 * 1000;}

		if ($geinheit == "KB") {$gesamt = $gesamt * 1000;}
		else if ($geinheit == "MB") {$gesamt = $gesamt * 1000 * 1000;}
		else if ($geinheit == "GB") {$gesamt = $gesamt * 1000 * 1000 * 1000;}
		else if ($geinheit == "TB") {$gesamt = $gesamt * 1000 * 1000 * 1000 * 1000;}
		else if ($geinheit == "PB") {$gesamt = $gesamt * 1000 * 1000 * 1000 * 1000 * 1000;}
		else if ($geinheit == "EB") {$gesamt = $gesamt * 1000 * 1000 * 1000 * 1000 * 1000 * 1000;}

		// Datenbank mit Datentypen updaten
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$inhalt = 'Maximale Dateigröße';
		$sql->bind_param("ss", $groesse, $inhalt);
		$sql->execute();
		$inhalt = 'Gesamtspeicherplatz';
		$sql->bind_param("ss", $gesamt, $inhalt);
		$sql->execute();
		$sql->close();

		$sql = $dbs->prepare("UPDATE zulaessigedateien SET zulaessig = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		for ($i=0; $i<=$max; $i++) {
			if (isset($_POST['endung'.$i])) {
				if (!cms_check_toggle($_POST['endung'.$i])) {
					$sql->bind_param("si", $_POST['endung'.$i], $i);
				  $sql->execute();
				}
			}
		}
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
