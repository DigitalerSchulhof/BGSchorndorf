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
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

$dbs = cms_verbinden('s');

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.fabrik")) {
	$fehler = false;

	if (!cms_check_idfeld($kurse)) {$fehler = true;}

	$schuelerids = "";
	$lehrerids = "";
	$KURSE = array();
	$personenfehler = false;
	if (strlen($kurse) > 0) {
		$kurseids = explode('|', substr($kurse,1));
		foreach($kurseids as $k) {
			if (!isset($_POST['schueler_'.$k]) || !isset($_POST['lehrer_'.$k])) {$personenfehler = true;}
			else if (!cms_check_idfeld($_POST['schueler_'.$k]) || !cms_check_idfeld($_POST['lehrer_'.$k])) {$personenfehler = true;}
			else {
				$kurs = array();
				$kurs['id'] = $k;
				$kurs['schueler'] = $_POST['schueler_'.$k];
				$kurs['lehrer'] = $_POST['lehrer_'.$k];
				$schuelerids .= $_POST['schueler_'.$k];
				$lehrerids .= $_POST['lehrer_'.$k];
				array_push($KURSE, $kurs);
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

	if (!$fehler) {
		$sids = cms_generiere_sqlidliste($schuelerids);
		$lids = cms_generiere_sqlidliste($lehrerids);

		if (strlen($sids) > 2) {
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN $sids AND art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
			if ($sql->execute()) {
			  $sql->bind_result($anzahl);
			  if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
			} else {$personenfehler = true;}
			$sql->close();
		}

		if (strlen($lids) > 2) {
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN $lids AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL')");
			if ($sql->execute()) {
			  $sql->bind_result($anzahl);
			  if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
			} else {$personenfehler = true;}
			$sql->close();
		}

		if ($personenfehler) {$fehler = true; echo "PERSONEN";}
	}

	//echo "<textarea cols=\"500\" rows=\"50\">";print_r($KURSE);echo "</textarea>";

	if (!$fehler) {

		$GRUPPENMART[0] = 'mitglieder';
		$GRUPPENMART[1] = 'vorsitz';
		$GRUPPENMART[2] = 'aufsicht';

		foreach ($GRUPPENMART as $a) {
			$sql = $dbs->prepare("DELETE FROM kurse".$a." WHERE gruppe IN (SELECT id FROM kurse WHERE schuljahr = ? AND id NOT IN (SELECT kurs FROM kurseklassen JOIN klassen ON klasse = klassen.id WHERE klassen.schuljahr = ?))");
			$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
			$sql->execute();
			$sql->close();
		}

		$jetzt = time();
		$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) VALUES (?, ?, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
		foreach ($KURSE as $k) {
			if (strlen($k['schueler']) > 0) {
				$personen = explode("|", substr($k['schueler'], 1));
				foreach ($personen as $p) {
					$sql->bind_param("ii", $k['id'], $p);
					$sql->execute();
				}
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) VALUES (?, ?, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0)");
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

		// Personen der Kurse in die jeweilige Stufen übernehmen
		$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT DISTINCT stufe, person, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ? AND (stufe, person) NOT IN (SELECT stufe, person FROM stufenmitglieder JOIN stufen ON gruppe = stufen.id WHERE schuljahr = ?)");
		$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
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
