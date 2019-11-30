<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['csv'])) {$csv = $_POST['csv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['trennung'])) {$trennung = $_POST['trennung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sj'])) {$sj = $_POST['sj'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tutor'])) {$tutor = $_POST['tutor'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schueler'])) {$schueler = $_POST['schueler'];} else {echo "FEHLER"; exit;}
if (isset($_POST['idart'])) {$idart = $_POST['idart'];} else {echo "FEHLER"; exit;}
if (strlen($csv) == 0) {echo "FEHLER"; exit;}
if (strlen($trennung) == 0) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($stufe, 0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($sj, 0)) {echo "FEHLER"; exit;}
if (($idart != 'zweit') && ($idart != 'dritt') && ($idart != 'viert') && ($idart != 'sh')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Personen den Kursen zuordnen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$STUFEBEZ = "";

	if (!$fehler) {
		$maxspalten = 0;
		$csvanalyse = str_replace("\r", "", $csv);
		$csvanalyse = str_replace("\t", "", $csv);
		$csvanalyse = str_replace("\R", "", $csv);
		$csvanalyse = str_replace("\N", "", $csv);
		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$aktspalten = count(explode($trennung, $csvteil));
			if ($aktspalten > $maxspalten) {$maxspalten = $aktspalten;}
		}

		if (!cms_check_ganzzahl($kurs, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($tutor, 1, $maxspalten) && ($tutor != '-')) {$fehler = true;}
		if (!cms_check_ganzzahl($schueler, 1, $maxspalten)) {$fehler = true;}
	}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
		// Existenz des Schuljahres prüfen
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schuljahre WHERE id = ?");
		$sql->bind_param("i", $sj);
		if ($sql->execute()) {
			$sql->bind_result($checkanz);
			if ($sql->fetch()) {
				if ($checkanz != 1) {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		// Existenz des Schuljahres prüfen
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), COUNT(*) FROM stufen WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $stufe, $sj);
		if ($sql->execute()) {
			$sql->bind_result($STUFEBEZ, $checkanz);
			if ($sql->fetch()) {
				if ($checkanz != 1) {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	function cms_bgfach($text) {
		$text = strtoupper($text);
		$text = preg_replace("/^GK$/", "GMK", $text);
		$text = preg_replace("/^BIO$/", "B", $text);
		$text = preg_replace("/^S$/", "SP", $text);
		$text = preg_replace("/^BLL$/", "SEM", $text);
		$text = preg_replace("/^GEO$/", "EK", $text);
		$text = preg_replace("/^REL$/", "REV", $text);
		$text = preg_replace("/^WI$/", "W", $text);
		$text = preg_replace("/^VMA$/", "VM", $text);
		return $text;
	}

	function cms_bglehrer($text) {
		$text = strtoupper($text);
		$text = str_replace(" ", "", $text);
		$text = preg_replace("/^J1-KO.*$/", "MPG ", $text);
		$text = preg_replace("/^J2-KO.*$/", "MPG ", $text);
		$text = substr($text,0,-1);
		return $text;
	}

	if (!$fehler) {
		$TUTOREN = array();
		$KURSE = array();

		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$daten = explode($trennung, $csvteil);
			if ($tutor != '-') {
				$T = array();
				$T['pers'] = $daten[$schueler-1];
				$T['tut'] = cms_bglehrer($daten[$tutor-1]);
				if (!in_array($T, $TUTOREN)) {array_push($TUTOREN, $T);}
			}
			$K = array();
			$K['pers'] = $daten[$schueler-1];
			$kurstemp = str_replace(" ", "", $daten[$kurs-1]);
			preg_match('/([0-9]*)([a-zA-Z]+)([0-9]*)/', $kurstemp, $ergebnis);
			$kursnr = '1';
			$kursbez = null;
			$kursart = null;
			if (isset($ergebnis[1])) {
				if ($ergebnis[1] == '4') {$kursart = 'LK';}
				else if ($ergebnis[1] == '2') {$kursart = 'GK';}
			}
			if (isset($ergebnis[2])) {
				$kursbez = $ergebnis[2];
			}
			if (isset($ergebnis[3])) {
				if (strlen($ergebnis[3]) > 0) {$kursnr = $ergebnis[3];}
			}
			if ($kursart === null) {
				$kursart = "GK";
				if (ctype_lower($kursbez)) {$kursart = "GK";}
				else if (ctype_upper($kursbez)) {$kursart = "LK";}
			}
			$kursbez = cms_bgfach($kursbez);
			if ($kursbez == "SEM") {$kursart = "SK";}
			$K['kurs'] = $STUFEBEZ." ".$kursart." ".$kursbez.$kursnr;
			if (!in_array($K,$KURSE)) {array_push($KURSE, $K);}
		}

		// TUTOREN laden, falls nötig
		if ($tutor != '-') {
			$LEHRER = array();
			$sql = $dbs->prepare("SELECT id, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM lehrer");
			if ($sql->execute()) {
				$sql->bind_result($lid, $kurz);
				while ($sql->fetch()) {
					$LEHRER[$kurz] = $lid;
				}
			}
			$sql->close();
		}

		// KURSE LADEN
		$KURS = array();
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') FROM kurse WHERE stufe = ? AND schuljahr = ?");
		$sql->bind_param("ii", $stufe, $sj);
		if ($sql->execute()) {
			$sql->bind_result($kid, $kurz);
			while ($sql->fetch()) {
				$KURS[$kurz] = $kid;
			}
		}
		$sql->close();

		// SCHUELER LADEN
		if ($idart != 'sh') {
			$SCHUELER = array();
			if ($idart == 'zweit') {$sql = $dbs->prepare("SELECT id, zweitid FROM personen WHERE zweitid IS NOT NULL");}
			else if ($idart == 'dritt') {$sql = $dbs->prepare("SELECT id, drittid FROM personen WHERE drittid IS NOT NULL");}
			else if ($idart == 'viert') {$sql = $dbs->prepare("SELECT id, viertid FROM personen WHERE viertid IS NOT NULL");}
			if ($sql->execute()) {
				$sql->bind_result($sid, $rid);
				while ($sql->fetch()) {
					$SCHUELER[$rid] = $sid;
				}
			}
			$sql->close();
		}

		// Tutoren neu zuordnen
		$TUTORENEINTRAGEN = array();
		foreach ($TUTOREN AS $T) {
			if ($idart != 'sh') {
				if (isset($LEHRER[$T['tut']]) && isset($SCHUELER[intval($T['pers'])])) {
					$NEU = array();
					$NEU['tut'] = $LEHRER[$T['tut']];
					$NEU['sch'] = $SCHUELER[intval($T['pers'])];
					if (!in_array($NEU, $TUTORENEINTRAGEN)) {array_push($TUTORENEINTRAGEN, $NEU);}
				}
			}
			else {
				if (isset($LEHRER[$T['tut']])) {
					$NEU = array();
					$NEU['tut'] = $LEHRER[$T['tut']];
					$NEU['sch'] = $T['pers'];
					if (!in_array($NEU, $TUTORENEINTRAGEN)) {array_push($TUTORENEINTRAGEN, $NEU);}
				}
			}
		}

		$KURSEEINTRAGEN = array();
		foreach ($KURSE AS $K) {
			if ($idart != 'sh') {
				if (isset($KURS[$K['kurs']]) && isset($SCHUELER[intval($K['pers'])])) {
					$NEU = array();
					$NEU['kur'] = $KURS[$K['kurs']];
					$NEU['sch'] = $SCHUELER[intval($K['pers'])];
					if (!in_array($NEU, $KURSEEINTRAGEN)) {array_push($KURSEEINTRAGEN, $NEU);}
				}
			}
			else {
				if (isset($KURS[$K['kurs']])) {
					$NEU = array();
					$NEU['kur'] = $KURS[$K['kurs']];
					$NEU['sch'] = $K['pers'];
					if (!in_array($NEU, $KURSEEINTRAGEN)) {array_push($KURSEEINTRAGEN, $NEU);}
				}
			}
		}

		$sql = $dbs->prepare("INSERT INTO tutorenwesen (lehrer, schueler, schuljahr) VALUES (?, ?, ?)");
		foreach ($TUTORENEINTRAGEN AS $T) {
			$sql->bind_param("iii", $T['tut'], $T['sch'], $sj);
			$sql->execute();
		}
		$sql->close();

		$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) VALUES (?, ?, 0, 1, 0, 0, 0, 0, 0, 0, 0, null, null)");
		foreach ($KURSEEINTRAGEN AS $K) {
			$sql->bind_param("ii", $K['kur'], $K['sch']);
			$sql->execute();
		}
		$sql->close();

		echo "ERFOLG";
	}
	cms_trennen($dbs);
}
else {
	echo "FEHLER";
}
?>
