<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['sj'])) {$sj = $_POST['sj'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($sj,0)) {echo "FEHLER"; exit;}

$zugriff = false;
cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Personen den Kursen zuordnen'];


if (cms_angemeldet() && $zugriff) {

	// PROFILDATEN UPDATEN
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("DELETE FROM kursemitglieder WHERE gruppe IN (SELECT id FROM kurse WHERE schuljahr = ?)");
	$sql->bind_param("i", $sj);
	$sql->execute();
	$sql->close();
	$sql = $dbs->prepare("DELETE FROM kursevorsitz WHERE gruppe IN (SELECT id FROM kurse WHERE schuljahr = ?)");
	$sql->bind_param("i", $sj);
	$sql->execute();
	$sql->close();

	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
