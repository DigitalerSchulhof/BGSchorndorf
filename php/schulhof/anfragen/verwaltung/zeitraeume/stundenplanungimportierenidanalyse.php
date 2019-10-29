<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['csv'])) {$csv = $_POST['csv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['trennung'])) {$trennung = $_POST['trennung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_POST['fach'])) {$fach = $_POST['fach'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schienen'])) {$schienen = $_POST['schienen'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

if (cms_angemeldet() && $zugriff) {

	$fehler = false;

	if (strlen($csv) == 0) {$fehler = true;}
	if (strlen($trennung) == 0) {$fehler = true;}
	if (!cms_check_ganzzahl($SCHULJAHR, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($ZEITRAUM, 0)) {$fehler = true;}

	if (!$fehler) {
		$maxspalten = 0;
		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$aktspalten = count(explode($trennung, $csvteil));
			if ($aktspalten > $maxspalten) {$maxspalten = $aktspalten;}
		}

		if (!cms_check_ganzzahl($lehrer, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($tag, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($stunde, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($fach, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($raum, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($schienen, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($klasse, 1, $maxspalten)) {$fehler = true;}
	}

	$dbs = cms_verbinden('s');
	if (!$fehler) {
		// Schuljahr und Zeitraum checken
		$sql = $dbs->prepare("SELECT COUNT(*) FROM schuljahre WHERE id = ?");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($sjanzahl);
			if ($sql->fetch()) {
				if ($sjanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
		$sql = $dbs->prepare("SELECT COUNT(*) FROM zeitraeume WHERE id = ?");
		$sql->bind_param("i", $ZEITRAUM);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		$LEHRER = array();
		$RAEUME = array();
		$SCHULSTUNDEN = array();
		$TAGE = array();
		$FAECHER = array();
		$KLASSEN = array();
		$STUFEN = array();

		function cms_klasseninfo($klasse) {
			$rueckgabe['klasse'] = "-";
			$rueckgabe['stufe'] = "-";
			if (preg_match("/^[0-9]{1,2}[A-Z]$/", $klasse)) {
				$rueckgabe['klasse'] = $klasse;
				$rueckgabe['stufe'] = substr($klasse,0,-1);
			}
			else if (($klasse == 'J1') || ($klasse == 'J2')) {
				$rueckgabe['stufe'] = $klasse;
			}
			else {
				$rueckgabe['stufe'] = "///BLOCKIERUNG///";
			}
			return $rueckgabe;
		}

		function cms_bg_lehrer($text) {
			$text = str_replace('MPGL2', 'MPG', $text);
			$text = str_replace('MPGL3', 'MPG', $text);
			return $text;
		}

		function cms_bg_raum($text) {
			$text = str_replace('MPGR1', 'MPG', $text);
			$text = str_replace('MPGR2', 'MPG', $text);
			$text = str_replace('MPGR3', 'MPG', $text);
			$text = str_replace('WAHA', 'KarlWahl', $text);
			$text = str_replace('HALL', 'OskarFrech', $text);
			$text = str_replace('INFO', 'INF - 126', $text);
			$text = str_replace('MU1', 'MU1 - 1.01', $text);
			$text = str_replace('MU2', 'MU2 - 1.05', $text);
			$text = str_replace('BIO1', 'BIO1 - 131', $text);
			$text = str_replace('BIO2', 'BIO2 - 129', $text);
			$text = str_replace('BIO3', 'BIO3 - 125', $text);
			$text = str_replace('PH1', 'PH1 - 021', $text);
			$text = str_replace('PH2', 'PH2 - 024', $text);
			$text = str_replace('ZE1', 'ZE1 - 027', $text);
			$text = str_replace('ZE2', 'ZE2 - 031', $text);
			$text = str_replace('EK', 'EK - 127', $text);
			$text = str_replace('CH1', 'CH1 - 122', $text);
			$text = str_replace('CH2', 'CH2 - 124', $text);
			$text = str_replace('025', 'NWT - 025', $text);
			$text = str_replace('1.02', 'TH - 1.02', $text);
			return $text;
		}

		function cms_bg_fach($text) {
			$text = preg_replace("/^EM$/", 'SEM', $text);
			$text = preg_replace("/^ET$/", 'ETH', $text);
			$text = preg_replace("/^RE$/", 'REV', $text);
			$text = preg_replace("/^RK$/", 'RRK', $text);
			$text = preg_replace("/^GM$/", 'GMK', $text);
			$text = preg_replace("/^GM B$/", 'GMK B', $text);
			$text = preg_replace("/^SPORTMÄ$/", 'SPM', $text);
			$text = preg_replace("/^SPJU/", 'SPJ', $text);
			$text = preg_replace("/^INFO 5$/", 'INF', $text);
			$text = preg_replace("/^INFO 7$/", 'INF', $text);
			$text = preg_replace("/^IN$/", 'INF', $text);
			$text = preg_replace("/^INFO-AG$/", 'INF', $text);
			$text = preg_replace("/^IMP P$/", 'IMP', $text);
			$text = preg_replace("/^IMP I$/", 'IMP', $text);
			$text = preg_replace("/^IMP M$/", 'IMP', $text);
			$text = preg_replace("/^VKB Vorbereitungskurs Bili$/", 'VKB', $text);
			return $text;
		}

		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$daten = explode($trennung, $csvteil);
			$dlehrer = $daten[$lehrer-1];
			$draum = $daten[$raum-1];
			$dstunde = $daten[$stunde-1];
			$dtag = $daten[$tag-1];
			$dfach = $daten[$fach-1];
			$klasseninfo = cms_klasseninfo($daten[$klasse-1]);
			$dklasse = $klasseninfo['klasse'];
			$dstufe = $klasseninfo['stufe'];

			if ($CMS_SCHULE == "Burg-Gymnasium") {
				$dlehrer = cms_bg_lehrer($dlehrer);
				$draum = cms_bg_raum($draum);
			}
			if (!in_array($dlehrer, $LEHRER)) {array_push($LEHRER, $dlehrer);}
			if (strlen($draum) > 0) {if (!in_array($draum, $RAEUME)) {array_push($RAEUME, $draum);}}
			if (!in_array($dstunde, $SCHULSTUNDEN)) {array_push($SCHULSTUNDEN, $dstunde);}
			if (!in_array($dtag, $TAGE)) {
				array_push($TAGE, $dtag);
				if (!cms_check_ganzzahl($dtag,1,7)) {$fehler = true;}
			}

			if ($dklasse != '-') {if (!in_array($dklasse, $KLASSEN)) {array_push($KLASSEN, $dklasse);}}
			if (($dstufe != '-') && ($dstufe != '///BLOCKIERUNG///')) {
				if (!in_array($dstufe, $STUFEN)) {array_push($STUFEN, $dstufe);}
			}

			if (($dstufe != 'J1') && ($dstufe != 'J2') && ($dstufe != '///BLOCKIERUNG///')) {
				if ($CMS_SCHULE == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!in_array($dfach, $FAECHER)) {array_push($FAECHER, $dfach);}
			}
			else if (($dstufe == 'J1') || ($dstufe == 'J2')) {
				$dfach = substr($dfach,1,-1);
				if ($CMS_SCHULE == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!in_array($dfach, $FAECHER)) {array_push($FAECHER, $dfach);}
			}
		}

		// echo "LEHRER:<br>";
		// print_r($LEHRER); echo "<br><br>RÄUME:<br>";
		// print_r($RAEUME); echo "<br><br>SCHULSTUNDEN:<br>";
		// print_r($SCHULSTUNDEN); echo "<br><br>TAGE:<br>";
		// print_r($TAGE); echo "<br><br>KLASSEN:<br>";
		// print_r($KLASSEN); echo "<br><br>STUFEN:<br>";
		// print_r($STUFEN); echo "<br><br>FÄCHER:<br>";
		// print_r($FAECHER); echo "<br><br>";
	}

	if (!$fehler) {
		$LEHRERFEHLER = array();
		$RAEUMEFEHLER = array();
		$SCHULSTUNDENFEHLER = array();
		$KLASSENFEHLER = array();
		$STUFENFEHLER = array();
		$FACHFEHLER = array();
		$LEHRERIDS = array();
		$RAEUMEIDS = array();
		$SCHULSTUNDENIDS = array();
		$KLASSENIDS = array();
		$STUFENIDS = array();
		$FACHIDS = array();
		// Prüfen, ob all diese Entitäten existieren
		// LEHRER
		$sql = $dbs->prepare("SELECT id, COUNT(*) FROM lehrer WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($LEHRER as $E) {
			$sql->bind_param("s", $E);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($LEHRERFEHLER, $E);}
					else {$LEHRERIDS[$E] = $checkid;}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();

		// RÄUME
		$sql = $dbs->prepare("SELECT id, COUNT(*) FROM raeume WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($RAEUME as $E) {
			$sql->bind_param("s", $E);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($RAEUMEFEHLER, $E);}
					else {$RAEUMEIDS[$E] = $checkid;}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();

		// SCHULSTUNDEN
		$sql = $dbs->prepare("SELECT id, COUNT(*) FROM schulstunden WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND zeitraum = ?");
		foreach ($SCHULSTUNDEN as $E) {
			$sql->bind_param("si", $E, $ZEITRAUM);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($SCHULSTUNDENFEHLER, $E);}
					else {$SCHULSTUNDENIDS[$E] = $checkid;}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();

		// KLASSEN
		$sql = $dbs->prepare("SELECT id, COUNT(*) FROM klassen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
		foreach ($KLASSEN as $E) {
			$sql->bind_param("si", $E, $SCHULJAHR);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($KLASSENFEHLER, $E);}
					else {$KLASSENIDS[$E] = $checkid;}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();

		// STUFEN
		$sql = $dbs->prepare("SELECT id, COUNT(*) FROM stufen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
		foreach ($STUFEN as $E) {
			$sql->bind_param("si", $E, $SCHULJAHR);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($STUFENFEHLER, $E);}
					else {$STUFENIDS[$E] = $checkid;}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();

		// FÄCHER
		$sql = $dbs->prepare("SELECT id, COUNT(*), AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(icon, '$CMS_SCHLUESSEL') FROM faecher WHERE kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
		foreach ($FAECHER as $E) {
			$sql->bind_param("si", $E, $SCHULJAHR);
			if ($sql->execute()) {
				$sql->bind_result($checkid, $checkanzahl, $checkbez, $checkicon);
				if ($sql->fetch()) {
					if ($checkanzahl != '1') {$fehler = true; array_push($FACHFEHLER, $E);}
					else {
						$FACHIDS[$E]['id'] = $checkid;
						$FACHIDS[$E]['bez'] = $checkbez;
						$FACHIDS[$E]['ico'] = $checkicon;
					}
				} else {$fehler = true;}
			} else {$fehler = true;}
		}
		$sql->close();
	}

	if (!$fehler) {
		// KURSE ZUSAMMENBAUEN
		$csvanalyse = explode("\n", $csv);
		$aktlehrer = "";
		$KURSE = array();
		$KURSEBEZ = array();
		$UNTERRICHT = array();
		$STATTFINDEN = "";
		foreach ($csvanalyse as $csvteil) {
			// LEHRER abschließen
			$daten = explode($trennung, $csvteil);
			$dlehrer = $daten[$lehrer-1];
			$draum = $daten[$raum-1];
			$dstunde = $daten[$stunde-1];
			$dtag = $daten[$tag-1];
			$dfach = $daten[$fach-1];
			$klasseninfo = cms_klasseninfo($daten[$klasse-1]);
			$dklasse = $klasseninfo['klasse'];
			$dstufe = $klasseninfo['stufe'];
			if ($CMS_SCHULE == "Burg-Gymnasium") {
				$dlehrer = cms_bg_lehrer($dlehrer);
				$draum = cms_bg_raum($draum);
			}
			// Kurse dieses Lehrers abschließen
			if (($aktlehrer != "-") && ($dlehrer != $aktlehrer)) {
				$aktlehrer = $dlehrer;
				foreach ($UNTERRICHT as $t) {
					foreach ($t as $s) {
						foreach ($s as $u) {
							asort($u['stufen']);
							asort($u['klassen']);
							if (count($u['stufen']) > 1) {$ustufe = '-';}
							else {$ustufe = $u['stufen'][0];}
							$uklassen = "";
							$uklassenids = "";
							foreach ($u['klassen'] as $k) {
								$uklassen .= substr($k, -1);
								$uklassenids .= "|".$KLASSENIDS[$k];
							}
							$ufach = $u['fach'];

							$bez = $ustufe.$uklassen." ".$FACHIDS[$ufach]['bez'];
							$kurzbez = $ustufe.$uklassen." ".$ufach;
							if (!in_array($bez, $KURSEBEZ)) {
								$K = array();
								$K['bezeichnung'] = $bez;
								$K['icon'] = $FACHIDS[$ufach]['ico'];
								$K['stufe'] = $STUFENIDS[$ustufe];
								$K['kurzbez'] = $kurzbez;
								$K['fach'] = $FACHIDS[$ufach]['id'];
								$K['klassen'] = substr($uklassenids, 1);
								array_push($KURSE, $K);
								array_push($KURSEBEZ, $bez);
							}
							$STATTFINDEN .= $bez.$trennung.$u['tag'].$trennung.$SCHULSTUNDENIDS[$u['stunde']].$trennung;
							$STATTFINDEN .= $LEHRERIDS[$aktlehrer].$trennung.$RAEUMEIDS[$u['raum']].$trennung."\n";
						}
					}
				}
				$UNTERRICHT = array();
			}

			// Kurse einlesen
			if (($dstufe != 'J1') && ($dstufe != 'J2') && ($dstufe != '///BLOCKIERUNG///')) {
				if ($CMS_SCHULE == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!isset($UNTERRICHT[$dtag][$dstunde][$draum])) {
					$UNTERRICHT[$dtag][$dstunde][$draum]['klassen'] = array();
					$UNTERRICHT[$dtag][$dstunde][$draum]['stufen'] = array();
					$UNTERRICHT[$dtag][$dstunde][$draum]['fach'] = $dfach;
					$UNTERRICHT[$dtag][$dstunde][$draum]['tag'] = $dtag;
					$UNTERRICHT[$dtag][$dstunde][$draum]['stunde'] = $dstunde;
					$UNTERRICHT[$dtag][$dstunde][$draum]['raum'] = $draum;
				}
				if (!in_array($dklasse, $UNTERRICHT[$dtag][$dstunde][$draum]['klassen'])) {
					array_push($UNTERRICHT[$dtag][$dstunde][$draum]['klassen'], $dklasse);
				}
				if (!in_array($dstufe, $UNTERRICHT[$dtag][$dstunde][$draum]['stufen'])) {
					array_push($UNTERRICHT[$dtag][$dstunde][$draum]['stufen'], $dstufe);
				}
			}
			else if (($dstufe == 'J1') || ($dstufe == 'J2')) {
				$kursart = substr($dfach,0,1);
				$kursnr = substr($dfach,-1);
				$dfach = substr($dfach,1,-1);
				if ($CMS_SCHULE == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				$bez = $dstufe." ".$kursart."K ".$FACHIDS[$dfach]['bez']." ".$kursnr;
				$kurzbez = $dstufe." ".$kursart."K ".$dfach.$kursnr;
				if (!in_array($bez, $KURSEBEZ)) {
					$K = array();
					$K['bezeichnung'] = $bez;
					$K['icon'] = $FACHIDS[$dfach]['ico'];
					$K['stufe'] = $STUFENIDS[$dstufe];
					$K['kurzbez'] = $kurzbez;
					$K['fach'] = $FACHIDS[$dfach]['id'];
					$K['klassen'] = "";
					array_push($KURSE, $K);
					array_push($KURSEBEZ, $bez);
				}
				$STATTFINDEN .= $bez.$trennung.$dtag.$trennung;
				$STATTFINDEN .= $SCHULSTUNDENIDS[$dstunde].$trennung;
				$STATTFINDEN .= $LEHRERIDS[$dlehrer].$trennung;
				$STATTFINDEN .= $RAEUMEIDS[$draum].$trennung."\n";
			}
		}
		$aktlehrer = $dlehrer;
		foreach ($UNTERRICHT as $t) {
			foreach ($t as $s) {
				foreach ($s as $u) {
					asort($u['stufen']);
					asort($u['klassen']);
					if (count($u['stufen']) > 1) {$ustufe = '-';}
					else {$ustufe = $u['stufen'][0];}
					$uklassen = "";
					$uklassenids = "";
					foreach ($u['klassen'] as $k) {
						$uklassen .= substr($k, -1);
						$uklassenids .= "|".$KLASSENIDS[$k];
					}
					$ufach = $u['fach'];

					$bez = $ustufe.$uklassen." ".$FACHIDS[$ufach]['bez'];
					$kurzbez = $ustufe.$uklassen." ".$ufach;
					if (!in_array($bez, $KURSEBEZ)) {
						$K = array();
						$K['bezeichnung'] = $bez;
						$K['icon'] = $FACHIDS[$ufach]['ico'];
						$K['stufe'] = $STUFENIDS[$ustufe];
						$K['kurzbez'] = $kurzbez;
						$K['fach'] = $FACHIDS[$ufach]['id'];
						$K['klassen'] = substr($uklassenids, 1);
						array_push($KURSE, $K);
						array_push($KURSEBEZ, $bez);
					}
					$STATTFINDEN .= $bez.$trennung.$u['tag'].$trennung.$SCHULSTUNDENIDS[$u['stunde']].$trennung;
					$STATTFINDEN .= $LEHRERIDS[$aktlehrer].$trennung.$RAEUMEIDS[$u['raum']].$trennung."\n";
				}
			}
		}
	}



	// echo "LEHRER:<br>";
	// print_r($LEHRERIDS); echo "<br><br>RÄUME:<br>";
	// print_r($RAEUMEIDS); echo "<br><br>SCHULSTUNDEN:<br>";
	// print_r($SCHULSTUNDENIDS); echo "<br><br>KLASSEN:<br>";
	// print_r($KLASSENIDS); echo "<br><br>STUFEN:<br>";
	// print_r($STUFENIDS); echo "<br><br>FÄCHER:<br>";
	// print_r($FACHIDS); echo "<br><br>";
	$KURSTEXT = "";
	foreach ($KURSE AS $K) {
		$KURSTEXT .= $K['bezeichnung'].$trennung;
		$KURSTEXT .= $K['kurzbez'].$trennung;
		$KURSTEXT .= $K['stufe'].$trennung;
		$KURSTEXT .= $K['fach'].$trennung;
		$KURSTEXT .= $K['klassen'].$trennung."\n\n\n";
	}

	echo $KURSTEXT.$STATTFINDEN;




	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "FEHLER";
}
?>
