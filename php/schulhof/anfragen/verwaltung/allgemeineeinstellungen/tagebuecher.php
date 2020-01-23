<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['abwesenheit'])) {$abwesenheit = $_POST['abwesenheit'];} else {echo "FEHLER";exit;}
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER";exit;}
if (isset($_POST['lobtadel'])) {$lobtadel = $_POST['lobtadel'];} else {echo "FEHLER";exit;}
if (isset($_POST['hausaufgaben'])) {$hausaufgaben = $_POST['hausaufgaben'];} else {echo "FEHLER";exit;}
if (isset($_POST['entschuldigungen'])) {$entschuldigungen = $_POST['entschuldigungen'];} else {echo "FEHLER";exit;}
if (isset($_POST['mindestabwesenheit'])) {$mindestabwesenheit = $_POST['mindestabwesenheit'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Administration']['Allgemeine Einstellungen vornehmen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!preg_match('/^([-st1234567]|14)$/', $abwesenheit)) {$fehler = true;}
	if (!preg_match('/^([-st1234567]|14)$/', $inhalt)) {$fehler = true;}
	if (!preg_match('/^([-st1234567]|14)$/', $lobtadel)) {$fehler = true;}
	if (!preg_match('/^([-st1234567]|14)$/', $hausaufgaben)) {$fehler = true;}
	if (!preg_match('/^([-st1234567]|14)$/', $entschuldigungen)) {$fehler = true;}

	if (!cms_check_ganzzahl($mindestabwesenheit,0)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$einstellungsname = "Tagebuch Frist Abewsenheit";
		$sql->bind_param("ss", $abwesenheit, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Tagebuch Frist Inhalt";
		$sql->bind_param("ss", $inhalt, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Tagebuch Frist Lob und Tadel";
		$sql->bind_param("ss", $lobtadel, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Tagebuch Frist Hausaufgaben";
		$sql->bind_param("ss", $hausaufgaben, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Tagebuch Frist Entschuldigungen";
		$sql->bind_param("ss", $entschuldigungen, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Tagebuch Mindestabwesenheit";
		$sql->bind_param("ss", $mindestabwesenheit, $einstellungsname);
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
