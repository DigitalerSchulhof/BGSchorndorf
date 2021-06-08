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
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_POST['fach'])) {$fach = $_POST['fach'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rythmen'])) {$rythmen = $_POST['rythmen'];} else {echo "FEHLER"; exit;}
if (isset($_POST['rythmenreihenfolge'])) {$rythmenreihenfolge = $_POST['rythmenreihenfolge'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schienen'])) {$schienen = $_POST['schienen'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;

	$CMS_WICHTIG = cms_einstellungen_laden("wichtigeeinstellungen");

	if (strlen($csv) == 0) {$fehler = true;}
	if (strlen($trennung) == 0) {$fehler = true;}
	if (!cms_check_ganzzahl($SCHULJAHR, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($ZEITRAUM, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($rythmenreihenfolge, 0)) {$fehler = true;}

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
		if (!cms_check_ganzzahl($rythmen, 1, $maxspalten) && ($rythmen != '-')) {$fehler = true;}
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
		$sql = $dbs->prepare("SELECT COUNT(*), rythmen FROM zeitraeume WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $ZEITRAUM, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl, $RYTHMUS);
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
		$SCHIENEN = array();

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
			if (strlen($text) == 0) {return 'MPG';}
			$text = str_replace('MPGR1', 'MPG', $text);
			$text = str_replace('MPGR2', 'MPG', $text);
			$text = str_replace('MPGR3', 'MPG', $text);
			$text = str_replace('JS1.2', 'MPG', $text);
			$text = str_replace('JS1.3', 'MPG', $text);
			$text = str_replace('WAHA', 'KarlWahl', $text);
			$text = str_replace('WAHA1', 'KarlWahl', $text);
			$text = str_replace('WAHA2', 'KarlWahl', $text);
			$text = str_replace('WAHA3', 'KarlWahl', $text);
			$text = str_replace('WAHA4', 'KarlWahl', $text);
			$text = str_replace('HALL', 'OskarFrech', $text);
			$text = str_replace('HALL1', 'OskarFrech', $text);
			$text = str_replace('HALL2', 'OskarFrech', $text);
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
			$text = str_replace('THEA', 'TH - 1.02', $text);
			return $text;
		}

		function cms_bg_fach($text) {
      $text = strtoupper($text);
			$text = preg_replace("/^EM$/", 'SEM', $text);
			$text = preg_replace("/^ET$/", 'ETH', $text);
			$text = preg_replace("/^RE$/", 'REV', $text);
			$text = preg_replace("/^RK$/", 'RRK', $text);
			$text = preg_replace("/^GM$/", 'GMK', $text);
			$text = preg_replace("/^GK$/", 'GMK', $text);
			$text = preg_replace("/^GM B$/", 'GMK B', $text);
			$text = preg_replace("/^SPORTMÄ$/", 'SPM', $text);
			$text = preg_replace("/^SPJU/", 'SPJ', $text);
			$text = preg_replace("/^INFO 5$/", 'INF', $text);
			$text = preg_replace("/^INFO 7$/", 'INF', $text);
			$text = preg_replace("/^IN$/", 'INF', $text);
			$text = preg_replace("/^IF$/", 'INF', $text);
			$text = preg_replace("/^INFO-AG$/", 'INF', $text);
			$text = preg_replace("/^IMP P$/", 'IMP', $text);
			$text = preg_replace("/^IMP I$/", 'IMP', $text);
			$text = preg_replace("/^IMP M$/", 'IMP', $text);
			$text = preg_replace("/^VKB VORBEREITUNGSKURS BILI$/", 'VKB', $text);
			$text = preg_replace("/^EB$/", 'VKB', $text);
			return $text;
		}

		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$daten = explode($trennung, $csvteil);
			$dlehrer = $daten[$lehrer-1];
			$draum = $daten[$raum-1];
			$dstunde = $daten[$stunde-1];
			$dschiene = $daten[$schienen-1];
			$dtag = $daten[$tag-1];
			$dfach = $daten[$fach-1];
			$klasseninfo = cms_klasseninfo($daten[$klasse-1]);
			$dklasse = $klasseninfo['klasse'];
			$dstufe = $klasseninfo['stufe'];

			if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
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
			if (!in_array($dschiene, $SCHIENEN)) {array_push($SCHIENEN, $dschiene);}

			if ($dklasse != '-') {if (!in_array($dklasse, $KLASSEN)) {array_push($KLASSEN, $dklasse);}}
			if (($dstufe != '-') && ($dstufe != '///BLOCKIERUNG///')) {
				if (!in_array($dstufe, $STUFEN)) {array_push($STUFEN, $dstufe);}
			}

			if (($dstufe != 'J1') && ($dstufe != 'J2') && ($dstufe != '///BLOCKIERUNG///')) {
				if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!in_array($dfach, $FAECHER)) {array_push($FAECHER, $dfach);}
			}
			else if (($dstufe == 'J1') || ($dstufe == 'J2')) {
				$dfach = substr($dfach,0,-1);
				if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!in_array($dfach, $FAECHER)) {array_push($FAECHER, $dfach);}
			}
		}
	}

	$LEHRERFEHLER = array();
	$RAEUMEFEHLER = array();
	$SCHULSTUNDENFEHLER = array();
	$KLASSENFEHLER = array();
	$STUFENFEHLER = array();
	$FACHFEHLER = array();

	if (!$fehler) {
		$LEHRERIDS = array();
		$RAEUMEIDS = array();
		$SCHULSTUNDENIDS = array();
		$KLASSENIDS = array();
		$STUFENIDS = array();
		$FACHIDS = array();
		$SCHIENENIDS = array();
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

		// SCHIENEN erstellen
		// Alte Schienen in diesem Zeitraum löschen
		$sql = $dbs->prepare("DELETE FROM schienen WHERE zeitraum = ?");
		$sql->bind_param("i", $ZEITRAUM);
		$sql->execute();
		$sql->close();
		// Neue Schienen anlegen
		foreach ($SCHIENEN as $E) {
			$E = cms_texttrafo_e_db($E);
			$sid = cms_generiere_kleinste_id('schienen');
			$sql = $dbs->prepare("UPDATE schienen SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zeitraum = ? WHERE id = ?");
			$sql->bind_param("sii", $E, $ZEITRAUM, $sid);
			$sql->execute();
			$sql->close();
			$SCHIENENIDS[$E] = $sid;
		}
	}

	$RREIHENFOLGE = array();
	if ($rythmenreihenfolge != 0) {
		$rwoche = $rythmenreihenfolge;
		// r gibt die Position der 1 an
		for ($r=1; $r <= $RYTHMUS; $r++) {
			// Rythmusstring erzeugen:
			$rs = "";
			// Nullen vorher
			for ($n=1; $n<$r; $n++) {$rs .= "0";}
			$rs .= 1;
			// Nullen nachher
			for ($n=$r+1; $n<=$RYTHMUS; $n++) {$rs .= "0";}
			$RREIHENFOLGE[$rs] = $rwoche;
			$rwoche ++;
			if ($rwoche > $RYTHMUS) {$rwoche = 1;}
		}
	}

	//print_r($RREIHENFOLGE);

	function cms_untis_rythmen($daten, $reihenfolge) {
		$daten = str_replace("-", "", $daten);
		if (isset($reihenfolge[$daten])) {
			return $reihenfolge[$daten];
		}
		else {
			return '0';
		}
	}

	if (!$fehler) {
		// KURSE ZUSAMMENBAUEN
		$csvanalyse = explode("\n", $csv);
		$aktlehrer = "";
		$KURSE = array();
		$KURSEBEZ = array();
		$UNTERRICHT = array();
		$STATTFINDEN = "";
		$kindex = 0;
		$KURSINDEX = array();
		foreach ($csvanalyse as $csvteil) {
			$daten = explode($trennung, $csvteil);
			$dlehrer = $daten[$lehrer-1];
			$draum = $daten[$raum-1];
			$dstunde = $daten[$stunde-1];
			$dtag = $daten[$tag-1];
			$dfach = $daten[$fach-1];
			$dschiene = $daten[$schienen-1];
			$drythmen = '0';
			if (($rythmenreihenfolge != '0') && ($rythmen != '-')) {
				$drythmen = cms_untis_rythmen($daten[$rythmen-1], $RREIHENFOLGE);
			}
			$klasseninfo = cms_klasseninfo($daten[$klasse-1]);
			$dklasse = $klasseninfo['klasse'];
			$dstufe = $klasseninfo['stufe'];
			if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
				$dlehrer = cms_bg_lehrer($dlehrer);
				$draum = cms_bg_raum($draum);
			}
			// Kurse dieses Lehrers abschließen
			if (($aktlehrer != "-") && ($dlehrer != $aktlehrer)) {
				foreach ($UNTERRICHT as $t) {
					foreach ($t as $r) {
						foreach ($r as $s) {
							foreach ($s as $u) {
								asort($u['stufen']);
								asort($u['klassen']);
								if (count($u['stufen']) > 1) {$ustufe = '-';}
								else {$ustufe = $u['stufen'][0];}
								$uklassen = "";
								$uklassenids = "";
								$uschienenids = "";
								foreach ($u['klassen'] as $k) {
									$uklassen .= substr($k, -1);
									$uklassenids .= "|".$KLASSENIDS[$k];
								}
								foreach ($u['schienen'] as $s) {
									$uschienenids .= "|".$SCHIENENIDS[$s];
								}
								$ufach = $u['fach'];

								$bez = $ustufe.$uklassen." ".$FACHIDS[$ufach]['bez'];
								$kurzbez = $ustufe.$uklassen." ".$ufach;
								if (!cms_check_titel($bez)) {$fehler = true;}
								if (!cms_check_titel($kurzbez)) {$fehler = true;}
								if (!in_array($bez, $KURSEBEZ)) {
									$K = array();
									$K['bezeichnung'] = $bez;
									$K['icon'] = $FACHIDS[$ufach]['ico'];
									$K['stufe'] = $STUFENIDS[$ustufe];
									$K['kurzbez'] = $kurzbez;
									$K['fach'] = $FACHIDS[$ufach]['id'];
									$K['klassen'] = $uklassenids;
									$K['schienen'] = $uschienenids;
									array_push($KURSE, $K);
									array_push($KURSEBEZ, $bez);
									$KURSINDEX[$bez] = $kindex;
									$kindex++;
								}
								else {
									$bestehende = $KURSE[$KURSINDEX[$bez]]['schienen']."|";
									foreach ($u['schienen'] as $s) {
										if (!preg_match("/".$SCHIENENIDS[$s]."/", $bestehende)) {$KURSE[$KURSINDEX[$bez]]['schienen'] .= "|".$SCHIENENIDS[$s];}
									}
								}
								$STATTFINDEN .= $bez.$trennung.$u['tag'].$trennung.$u['rythmus'].$trennung.$SCHULSTUNDENIDS[$u['stunde']].$trennung;
								$STATTFINDEN .= $LEHRERIDS[$aktlehrer].$trennung.$RAEUMEIDS[$u['raum']].$trennung."\n";
							}
						}
					}
				}
				$aktlehrer = $dlehrer;
				$UNTERRICHT = array();
			}

			// Kurse einlesen
			if (($dstufe != 'J1') && ($dstufe != 'J2') && ($dstufe != '///BLOCKIERUNG///')) {
				if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
				if (!isset($UNTERRICHT[$dtag][$drythmen][$dstunde][$draum])) {
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['klassen'] = array();
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['stufen'] = array();
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['fach'] = $dfach;
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['tag'] = $dtag;
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['stunde'] = $dstunde;
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['raum'] = $draum;
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['rythmus'] = $drythmen;
					$UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['schienen'] = array();
				}
				if (!in_array($dklasse, $UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['klassen'])) {
					array_push($UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['klassen'], $dklasse);
				}
				if (!in_array($dstufe, $UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['stufen'])) {
					array_push($UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['stufen'], $dstufe);
				}
				if (!in_array($dschiene, $UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['schienen'])) {
					array_push($UNTERRICHT[$dtag][$drythmen][$dstunde][$draum]['schienen'], $dschiene);
				}
			}
			else if (($dstufe == 'J1') || ($dstufe == 'J2')) {
				$kursart = substr($dfach,0,1);
				$kursnr = substr($dfach,-1);
				$dfach = substr($dfach,0,-1);
				if ($CMS_WICHTIG['Schulname'] == "Burg-Gymnasium") {
					$dfach = cms_bg_fach($dfach);
				}
        if (ctype_lower($kursart)) {
          $kursart = "B";
        } else {
          $kursart = "L";
        }
				$bez = $dstufe." ".$kursart."F ".$FACHIDS[$dfach]['bez']." ".$kursnr;
				$kurzbez = $dstufe." ".$kursart."F ".$dfach.$kursnr;
				if (!cms_check_titel($bez)) {$fehler = true;}
				if (!cms_check_titel($kurzbez)) {$fehler = true;}
				if (!in_array($bez, $KURSEBEZ)) {
					$K = array();
					$K['bezeichnung'] = $bez;
					$K['icon'] = $FACHIDS[$dfach]['ico'];
					$K['stufe'] = $STUFENIDS[$dstufe];
					$K['kurzbez'] = $kurzbez;
					$K['fach'] = $FACHIDS[$dfach]['id'];
					$K['klassen'] = "";
					$K['schienen'] = "|".$SCHIENENIDS[$dschiene];
					array_push($KURSE, $K);
					array_push($KURSEBEZ, $bez);
					$KURSINDEX[$bez] = $kindex;
					$kindex++;
				}
				else {
					$bestehende = $KURSE[$KURSINDEX[$bez]]['schienen']."|";
					if (!preg_match("/".$SCHIENENIDS[$dschiene]."/", $bestehende)) {$KURSE[$KURSINDEX[$bez]]['schienen'] .= "|".$SCHIENENIDS[$dschiene];}
				}
				$STATTFINDEN .= $bez.$trennung.$dtag.$trennung.$drythmen.$trennung;
				$STATTFINDEN .= $SCHULSTUNDENIDS[$dstunde].$trennung;
				$STATTFINDEN .= $LEHRERIDS[$dlehrer].$trennung;
				$STATTFINDEN .= $RAEUMEIDS[$draum].$trennung."\n";
			}
		}
		foreach ($UNTERRICHT as $t) {
			foreach ($t as $r) {
				foreach ($r AS $s) {
					foreach ($s as $u) {
						asort($u['stufen']);
						asort($u['klassen']);
						if (count($u['stufen']) > 1) {$ustufe = '-';}
						else {$ustufe = $u['stufen'][0];}
						$uklassen = "";
						$uklassenids = "";
						$uschienenids = "";
						foreach ($u['klassen'] as $k) {
							$uklassen .= substr($k, -1);
							$uklassenids .= "|".$KLASSENIDS[$k];
						}
						foreach ($u['schienen'] as $s) {
							$uschienenids .= "|".$SCHIENENIDS[$s];
						}
						$ufach = $u['fach'];

						$bez = $ustufe.$uklassen." ".$FACHIDS[$ufach]['bez'];
						$kurzbez = $ustufe.$uklassen." ".$ufach;
						if (!cms_check_titel($bez)) {$fehler = true;}
						if (!cms_check_titel($kurzbez)) {$fehler = true;}
						if (!in_array($bez, $KURSEBEZ)) {
							$K = array();
							$K['bezeichnung'] = $bez;
							$K['icon'] = $FACHIDS[$ufach]['ico'];
							$K['stufe'] = $STUFENIDS[$ustufe];
							$K['kurzbez'] = $kurzbez;
							$K['fach'] = $FACHIDS[$ufach]['id'];
							$K['klassen'] = $uklassenids;
							$K['schienen'] = $uschienenids;
							array_push($KURSE, $K);
							array_push($KURSEBEZ, $bez);
							$KURSINDEX[$bez] = $kindex;
							$kindex++;
						}
						else {
							$bestehende = $KURSE[$KURSINDEX[$bez]]['schienen']."|";
							foreach ($u['schienen'] as $s) {
								if (!preg_match("/".$SCHIENENIDS[$s]."/", $bestehende)) {$KURSE[$KURSINDEX[$bez]]['schienen'] .= "|".$SCHIENENIDS[$s];}
							}
						}
						$STATTFINDEN .= $bez.$trennung.$u['tag'].$trennung.$u['rythmus'].$trennung.$SCHULSTUNDENIDS[$u['stunde']].$trennung;
						$STATTFINDEN .= $LEHRERIDS[$aktlehrer].$trennung.$RAEUMEIDS[$u['raum']].$trennung."\n";
					}
				}
			}
		}
	}

	if ($fehler) {
		echo "FEHLER\n\n\n";
		echo implode($trennung, $LEHRERFEHLER)."\n\n\n";
		echo implode($trennung, $RAEUMEFEHLER)."\n\n\n";
		echo implode($trennung, $SCHULSTUNDENFEHLER)."\n\n\n";
		echo implode($trennung, $KLASSENFEHLER)."\n\n\n";
		echo implode($trennung, $STUFENFEHLER)."\n\n\n";
		echo implode($trennung, $FACHFEHLER);
	}
	else {
		echo "ERFOLG\n\n\n";
		$KURSTEXT = "";
		foreach ($KURSE AS $K) {
			$KURSTEXT .= $K['bezeichnung'].$trennung;
			$KURSTEXT .= $K['kurzbez'].$trennung;
			$KURSTEXT .= $K['stufe'].$trennung;
			$KURSTEXT .= $K['fach'].$trennung;
			$KURSTEXT .= $K['icon'].$trennung;
			$KURSTEXT .= $K['klassen'].$trennung;
			$KURSTEXT .= $K['schienen'].$trennung."\n";
		}
		echo $KURSTEXT."\n\n".$STATTFINDEN."\n\n";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
