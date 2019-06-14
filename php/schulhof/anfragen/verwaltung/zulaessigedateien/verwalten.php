<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['groesse'])) 	{$groesse = $_POST['groesse'];} 	else {$groesse = '';}
if (isset($_POST['einheit'])) 	{$einheit = $_POST['einheit'];} 	else {$einheit = '';}
if (isset($_POST['max'])) 		{$max = $_POST['max'];} 			else {$max = '';}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Administration']['Zulässige Dateien verwalten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if ((!cms_check_ganzzahl($groesse,0)) || ($groesse < 1)) {$fehler = true;}
	if (($einheit != "B") && ($einheit != "KB") && ($einheit != "MB") && ($einheit != "GB") && ($einheit != "TB") && ($einheit != "PB") && ($einheit != "EB")) {$fehler = true;}

	if (!$fehler) {
		if ($einheit == "KB") {$groesse = $groesse * 1024;}
		else if ($einheit == "MB") {$groesse = $groesse * 1024 * 1024;}
		else if ($einheit == "GB") {$groesse = $groesse * 1024 * 1024 * 1024;}
		else if ($einheit == "TB") {$groesse = $groesse * 1024 * 1024 * 1024 * 1024;}
		else if ($einheit == "PB") {$groesse = $groesse * 1024 * 1024 * 1024 * 1024 * 1024;}
		else if ($einheit == "EB") {$groesse = $groesse * 1024 * 1024 * 1024 * 1024 * 1024 * 1024;}

		// CONFIG-Datei neu schreieben
		$pfad = '../../schulhof/funktionen/';
		chmod($pfad, 0777);
		$datei = fopen('../../schulhof/funktionen/config.php', 'w');
		if ($datei) {
			include_once("../../schulhof/anfragen/verwaltung/configaendern.php");
			$CMS_MAX_DATEI = $groesse;
			$text = cms_configaendern();
			fwrite($datei, $text);
			fclose($datei);

			// Datenbank mit Datentypen updaten
			$dbs = cms_verbinden('s');
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
		chmod($pfad, 0755);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
