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
	$dbs = cms_verbinden('s');
	// Kurse laden
	$KURSE = array();
	$sql = $dbs->prepare("SELECT DISTINCT id FROM kurse WHERE schuljahr = ?");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($kid);
		while ($sql->fetch()) {
			array_push($KURSE, $kid);
		}
	}
	$sql->close();

	// Schüler aus Klassen eintragen
	$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT ? AS gruppe, person, 0 AS dateiupload, 1 AS dateidownload, 0 AS dateiloeschen, 0 AS dateiumbenennen, 0 AS termine, 0 AS blogeintraege, 0 AS chatten, 0 AS nachrichtloeschen, 0 AS nutzerstummschalten, null AS chatbannbis, null AS chatbannvon FROM klassenmitglieder WHERE gruppe IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)");
	foreach ($KURSE AS $K) {
		$sql->bind_param("ii", $K, $K);
		$sql->execute();
	}
	$sql->close();

	// Lehrer in Kurse eintragen
	$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT DISTINCT ? AS gruppe, lehrer, 1 AS dateiupload, 1 AS dateidownload, 1 AS dateiloeschen, 1 AS dateiumbenennen, 1 AS termine, 1 AS blogeintraege, 1 AS chatten, 1 AS nachrichtloeschen, 1 AS nutzerstummschalten, null AS chatbannbis, null AS chatbannvon FROM regelunterricht WHERE kurs = ?");
	foreach ($KURSE AS $K) {
		$sql->bind_param("ii", $K, $K);
		$sql->execute();
	}
	$sql->close();

	// Lehrer als Kursvorsitzende eintragen
	$sql = $dbs->prepare("INSERT INTO kursevorsitz (gruppe, person) SELECT DISTINCT ? AS gruppe, lehrer FROM regelunterricht WHERE kurs = ?");
	foreach ($KURSE AS $K) {
		$sql->bind_param("ii", $K, $K);
		$sql->execute();
	}
	$sql->close();

	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
