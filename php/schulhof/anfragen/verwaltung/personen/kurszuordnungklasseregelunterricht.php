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

$zugriff = cms_r("schulhof.verwaltung.personen.zuordnen.kurse");


if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');

	// Kurse laden
	$KURSE = array();
	$sql = $dbs->prepare("SELECT DISTINCT id FROM kurse WHERE schuljahr = ? AND id IN (SELECT kurs FROM kurseklassen)");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($kid);
		while ($sql->fetch()) {
			array_push($KURSE, $kid);
		}
	}
	$sql->close();

	// Kurse leeren
	if (count($KURSE) > 0) {
		$kursesql = "(".implode(",", $KURSE).")";
		$sql = $dbs->prepare("DELETE FROM kursemitglieder WHERE gruppe IN $kursesql");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM kursevorsitz WHERE gruppe IN $kursesql");
		$sql->execute();
		$sql->close();
	}

	// Schüler aus Klassen eintragen
	$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT ? AS gruppe, person, 0 AS dateiupload, 1 AS dateidownload, 0 AS dateiloeschen, 0 AS dateiumbenennen, 0 AS termine, 0 AS blogeintraege, 1 AS chatten, 0 AS nachrichtloeschen, 0 AS nutzerstummschalten, null AS chatbannbis, null AS chatbannvon FROM klassenmitglieder JOIN personen ON person = personen.id WHERE gruppe IN (SELECT klasse FROM kurseklassen WHERE kurs = ?) AND art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
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


	// Stufen neu zuordnen
	$STUFEN = array();
	$sql = $dbs->prepare("SELECT DISTINCT id FROM stufen WHERE schuljahr = ?");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($sid);
		while ($sql->fetch()) {
			array_push($STUFEN, $sid);
		}
	}
	$sql->close();

	// Stufen leeren
	if (count($STUFEN) > 0) {
		$suchmuster = "(".implode(",", $STUFEN).")";
		$sql = $dbs->prepare("DELETE FROM stufenmitglieder WHERE gruppe IN $suchmuster");
		$sql->execute();
		$sql->close();
	}

	// Stufen neu erstellen
	$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten) SELECT DISTINCT ? AS gruppe, person, '0' AS dateiupload, '1' AS dateidownload, '0' AS dateiloeschen, '0' AS dateiumbenennen, '0' AS termine, '0' AS blogeintraege, '1' AS chatten, '0' AS nachrichtloeschen, '0' AS nutzerstummschalten FROM kursemitglieder WHERE gruppe IN (SELECT id FROM kurse WHERE stufe = ?)");
	foreach ($STUFEN AS $S) {
		$sql->bind_param("ii", $S, $S);
		$sql->execute();
	}
	$sql->close();


	// Klassen neu zuordnen
	$KLASSEN = array();
	$sql = $dbs->prepare("SELECT DISTINCT id FROM klassen WHERE schuljahr = ?");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($sid);
		while ($sql->fetch()) {
			array_push($KLASSEN, $sid);
		}
	}
	$sql->close();

	// Klassen leeren
	if (count($KLASSEN) > 0) {
		$suchmuster = "(".implode(",", $KLASSEN).")";
		$sql = $dbs->prepare("DELETE FROM klassenmitglieder WHERE gruppe IN $suchmuster");
		$sql->execute();
		$sql->close();
	}

	// Klassen neu erstellen
	$sql = $dbs->prepare("INSERT INTO klassenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten) SELECT DISTINCT ? AS gruppe, person, '0' AS dateiupload, '1' AS dateidownload, '0' AS dateiloeschen, '0' AS dateiumbenennen, '0' AS termine, '0' AS blogeintraege, '1' AS chatten, '0' AS nachrichtloeschen, '0' AS nutzerstummschalten FROM kursemitglieder WHERE gruppe IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND gruppe NOT IN (SELECT kurs FROM kurseklassen WHERE klasse != ?)");
	foreach ($KLASSEN AS $K) {
		$sql->bind_param("iii", $K, $K, $K);
		$sql->execute();
	}
	$sql->close();



  // Lehrer in Stufenkursen eintragen
  // Kurse laden
	$KURSE = array();
	$sql = $dbs->prepare("SELECT DISTINCT id FROM kurse WHERE schuljahr = ? AND id NOT IN (SELECT kurs FROM kurseklassen)");
	$sql->bind_param("i", $sj);
	if ($sql->execute()) {
		$sql->bind_result($kid);
		while ($sql->fetch()) {
			array_push($KURSE, $kid);
		}
	}
	$sql->close();

	// Kurse leeren
	if (count($KURSE) > 0) {
		$kursesql = "(".implode(",", $KURSE).")";
		$sql = $dbs->prepare("DELETE FROM kursemitglieder WHERE gruppe IN $kursesql AND person IN (SELECT id FROM lehrer)");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM kursevorsitz WHERE gruppe IN $kursesql AND person IN (SELECT id FROM lehrer)");
		$sql->execute();
		$sql->close();
	}

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
