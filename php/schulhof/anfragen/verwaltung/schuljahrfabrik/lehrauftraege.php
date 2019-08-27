<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/personen/personloeschenfkt.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['kurse'])) {$kurse = $_POST['kurse'];} else {echo "FEHLER";exit;}
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

$dbs = cms_verbinden('s');

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!cms_check_idfeld($kurse) || !cms_check_idfeld($klassen)) {$fehler = true;}

	$lehrerids = "";

	$KURSE = array();
	$personenfehler = false;
	if (strlen($kurse) > 0) {
		$kurseids = explode('|', substr($kurse,1));
		foreach($kurseids as $k) {
			if (!isset($_POST['kurs_lehrer_'.$k])) {$personenfehler = true;}
			else if (!cms_check_idfeld($_POST['kurs_lehrer_'.$k])) {$personenfehler = true;}
			else {
				$kurs = array();
				$kurs['id'] = $k;
				$kurs['lehrer'] = $_POST['kurs_lehrer_'.$k];
				$lehrerids .= $_POST['kurs_lehrer_'.$k];
				array_push($KURSE, $kurs);
			}
		}
	}
	if ($personenfehler) {$fehler = true; echo "PERSONEN";}

	$KLASSEN = array();
	$personenfehler = false;
	if (strlen($klassen) > 0) {
		$klassenids = explode('|', substr($klassen,1));
		foreach($klassenids as $k) {
			if (!isset($_POST['klasse_lehrer_'.$k])) {$personenfehler = true;}
			else if (!cms_check_idfeld($_POST['klasse_lehrer_'.$k])) {$personenfehler = true;}
			else {
				$klasse = array();
				$klasse['id'] = $k;
				$klasse['lehrer'] = $_POST['klasse_lehrer_'.$k];
				$lehrerids .= $_POST['klasse_lehrer_'.$k];
				array_push($KLASSEN, $klasse);
			}
		}
	}
	if ($personenfehler) {$fehler = true; echo "PERSONEN";}

	// Prüfen, ob die Kurse im richtigen Schuljahr liegen
	$kursfehler = false;
	$kids = cms_generiere_sqlidliste($kurse);
	if (strlen($kids) > 2) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM kurse WHERE id IN $kids AND schuljahr != ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {if ($anzahl > 0) {$kursfehler = true;}}
		} else {$kursfehler = true;}
		$sql->close();
	}
	else {$kursfehler = true;}
	if ($kursfehler) {$fehler = true; echo "KURSE";}


	// Prüfen, ob die Klassen im richtigen Schuljahr liegen
	$klassenfehler = false;
	$kids = cms_generiere_sqlidliste($klassen);
	if (strlen($kids) > 2) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM klassen WHERE id IN $kids AND schuljahr != ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl > 0) {$klassenfehler = true;}}
		} else {$klassenfehler = true;}
		$sql->close();
	}
	else {$klassenfehler = true;}
	if ($klassenfehler) {$fehler = true; echo "KLASSEN";}


	if (!$fehler) {
		$lids = cms_generiere_sqlidliste($lehrerids);

		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN $lids AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL')");
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
		} else {$personenfehler = true;}
		$sql->close();

		if ($personenfehler) {$fehler = true; echo "PERSONEN";}
	}

	if (!$fehler) {

		$GRUPPENMART[0] = 'mitglieder';
		$GRUPPENMART[1] = 'vorsitz';
		$GRUPPENMART[2] = 'aufsicht';

		foreach ($GRUPPENMART as $a) {
			$sql = $dbs->prepare("DELETE FROM kurse".$a." WHERE gruppe IN (SELECT id FROM kurse WHERE schuljahr = ? AND id IN (SELECT kurs FROM kurseklassen JOIN klassen ON klasse = klassen.id WHERE klassen.schuljahr = ?)) AND person IN (SELECT id FROM personen WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL'))");
			$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
			$sql->execute();
			$sql->close();
			$sql = $dbs->prepare("DELETE FROM klassen".$a." WHERE gruppe IN (SELECT id FROM klassen WHERE schuljahr = ?) AND person IN (SELECT id FROM personen WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL'))");
			$sql->bind_param("i", $neuschuljahr);
			$sql->execute();
			$sql->close();
		}

		$jetzt = time();
		$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, chattenab) VALUES (?, ?, 1, 1, 1, 1, 1, 1, 1, ?)");
		foreach ($KURSE as $k) {
			if (strlen($k['lehrer']) > 0) {
				$personen = explode("|", substr($k['lehrer'], 1));
				foreach ($personen as $p) {
					$sql->bind_param("iii", $k['id'], $p, $jetzt);
					$sql->execute();
				}
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO klassenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, chattenab) VALUES (?, ?, 1, 1, 1, 1, 1, 1, 1, ?)");
		foreach ($KLASSEN as $k) {
			if (strlen($k['lehrer']) > 0) {
				$personen = explode("|", substr($k['lehrer'], 1));
				foreach ($personen as $p) {
					$sql->bind_param("iii", $k['id'], $p, $jetzt);
					$sql->execute();
				}
			}
		}
		$sql->close();

		$sql = $dbs->prepare("INSERT INTO kursevorsitz (gruppe, person) VALUES (?, ?)");
		foreach ($KURSE as $k) {
			if (strlen($k['lehrer']) > 0) {
				$personen = explode("|", substr($k['lehrer'], 1));
				foreach ($personen as $p) {
					$sql->bind_param("ii", $k['id'], $p);
					$sql->execute();
				}
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO klassenvorsitz (gruppe, person) VALUES (?, ?)");
		foreach ($KLASSEN as $k) {
			if (strlen($k['lehrer']) > 0) {
				$personen = explode("|", substr($k['lehrer'], 1));
				foreach ($personen as $p) {
					$sql->bind_param("ii", $k['id'], $p);
					$sql->execute();
				}
			}
		}
		$sql->close();

		// Personen der Kurse in die jeweilige Stufen übernehmen
		$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, chattenab) SELECT DISTINCT stufe, person, 0, 1, 0, 0, 0, 0, 0, ? FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ? AND (stufe, person) NOT IN (SELECT stufe, person FROM stufenmitglieder JOIN stufen ON gruppe = stufen.id WHERE schuljahr = ?)");
		$sql->bind_param("iii", $jetzt, $neuschuljahr, $neuschuljahr);
		$sql->execute();
		$sql->close();
		// Personen der Klassen in die jeweilige Stufen übernehmen
		$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, chattenab) SELECT DISTINCT stufe, person, 0, 1, 0, 0, 0, 0, 0, ? FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE schuljahr = ? AND (stufe, person) NOT IN (SELECT stufe, person FROM stufenmitglieder JOIN stufen ON gruppe = stufen.id WHERE schuljahr = ?)");
		$sql->bind_param("iii", $jetzt, $neuschuljahr, $neuschuljahr);
		$sql->execute();
		$sql->close();

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
