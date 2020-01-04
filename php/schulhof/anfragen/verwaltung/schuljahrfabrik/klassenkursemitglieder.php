<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['uebertragungsid'])) {$uebertragungsid = $_POST['uebertragungsid'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKUEBERTRAGUNGSID'])) {$SCHULJAHRFABRIKUEBERTRAGUNGSID = $_SESSION['SCHULJAHRFABRIKUEBERTRAGUNGSID'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if ($uebertragungsid != $SCHULJAHRFABRIKUEBERTRAGUNGSID) {echo "FEHLER";exit;}

$dbs = cms_verbinden('s');
if (cms_angemeldet() && r("schulhof.planung.schuljahre.fabrik")) {
	// Mitglieder hinzufügen
	$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT kurs, person, dateiupload, dateidownload, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon FROM klassenmitglieder JOIN kurseklassen ON klassenmitglieder.gruppe = kurseklassen.klasse JOIN klassen ON kurseklassen.klasse = klassen.id JOIN personen ON personen.id = person WHERE schuljahr = ? AND art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
	$sql->bind_param("i", $neuschuljahr);
	$sql->execute();
	$sql->close();

	// Personen der Kurse in die jeweilige Stufen übernehmen
	$jetzt = time();
	$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT DISTINCT stufe, person, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ? AND (stufe, person) NOT IN (SELECT stufe, person FROM stufenmitglieder JOIN stufen ON gruppe = stufen.id WHERE schuljahr = ?)");
	$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
	$sql->execute();
	$sql->close();

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
