<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		    {$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid']))     	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zeitraum'])) {$zeitraum = $_POST['zeitraum'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['erster'])) {$erster = $_POST['erster'];} else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($schuljahr, 0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($zeitraum, 0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kurs, 0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($tag, 1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat, 1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr, 0)) {cms_anfrage_beenden(); exit;}
if (($erster != 'j') && ($erster != 'n')) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.stundentagebücher.erzeugen")) {
	$dbs = cms_verbinden('s');
	$fehler = false;

	// Pürfen, ob das Schuljahr existiert
	$sql = "SELECT COUNT(*) FROM schuljahre WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $schuljahr);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	$gerade = time();
	$jetzt = mktime(0,0,0,$monat,$tag, $jahr);
	if ($jetzt <= $gerade) {$fehler = true;}

	// Prüfen, ob der Zeitraum zum Schuljahr passt und mindestens teilweise in der Zukunft liegt
	$sql = "SELECT COUNT(*), beginn, ende, rythmen FROM zeitraeume WHERE id = ? AND schuljahr = ? AND ende >= ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("iii", $zeitraum, $schuljahr, $jetzt);
	if ($sql->execute()) {
		$sql->bind_result($anzahl, $zbeginn, $zende, $zrythmen);
		if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	// Prüfen, ob der Kurs zum Schuljahr passt
	$sql = "SELECT COUNT(*), stufe FROM kurse WHERE id = ? AND schuljahr = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $kurs, $schuljahr);
	if ($sql->execute()) {
		$sql->bind_result($anzahl, $stufe);
		if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	if (!$fehler) {
		// Prüfen, ob die Stufe ein Tagebuch verwenden muss
		$sql = "SELECT COUNT(*), tagebuch FROM stufen WHERE id = ? AND schuljahr = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ii", $stufe, $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($anzahl, $tagebuch);
			if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	// FERIEN in diesem Zeitraum laden
	if (!$fehler) {
		$FERIEN = array();
		$sql = "SELECT bezeichnung, beginn, ende FROM ferien WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn <= ? AND ende >= ?) ORDER BY beginn ASC, ende DESC";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("iiiiii", $jetzt, $zende, $jetzt, $zende, $jetzt, $zende);
		if ($sql->execute()) {
			$sql->bind_result($fbez, $fbeginn, $fende);
			while ($sql->fetch()) {
				$f = array();
				$f['bez'] = $fbez;
				$f['beginn'] = $fbeginn;
				$f['ende'] = $fende;
				array_push($FERIEN, $f);
			}
		} else {$fehler = true;}
		$sql->close();
	}

	// SCHULSTUNDEN in diesem Zeitraum laden
	if (!$fehler) {
		$SCHULSTUNDEN = array();
		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $zeitraum);
		if ($sql->execute()) {
			$sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
			while ($sql->fetch()) {
				$SCHULSTUNDEN[$stdid]['bez'] = $stdbez;
				$SCHULSTUNDEN[$stdid]['beginns'] = $stdbeginns;
				$SCHULSTUNDEN[$stdid]['beginnm'] = $stdbeginnm;
				$SCHULSTUNDEN[$stdid]['endes'] = $stdendes;
				$SCHULSTUNDEN[$stdid]['endem'] = $stdendem;
			}
		} else {$fehler = true;}
		$sql->close();
	}

	// REGELUNTERRICHT[RYTHMEN][WOCHENTAG][...] LADEN
	if (!$fehler) {
		// Rythmen im Regelunterricht vorbereiten
		$REGELUNTERRICHT[0] = array();
		if ($zrythmen != 1) {
			for ($r=1; $r<=$zrythmen; $r++) {
				$REGELUNTERRICHT[$r] = array();
			}
		}
		// Tage im Regelunterricht vorbereiten
		for ($r=0; $r<COUNT($REGELUNTERRICHT); $r++) {
			for ($t = 1; $t<= 7; $t++) {
				$REGELUNTERRICHT[$r][$t] = array();
			}
		}
		$sql = "SELECT schulstunde, tag, rythmus, kurs, lehrer, raum FROM regelunterricht WHERE kurs = ? AND schulstunde IN (SELECT id FROM schulstunden WHERE zeitraum = ?)";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ii", $kurs, $zeitraum);
		if ($sql->execute()) {
			$sql->bind_result($rustd, $rutag, $rury, $ruku, $rule, $rura);
			while ($sql->fetch()) {
				$ru = array();
				$ru['schulstunde'] = $rustd;
				$ru['kurs'] = $ruku;
				$ru['lehrer'] = $rule;
				$ru['raum'] = $rura;
				array_push($REGELUNTERRICHT[$rury][$rutag], $ru);
			}
		} else {$fehler = true;}
		$sql->close();
	}

	// RYTHMEN DES ZEITRAUMS LADEN
	if (!$fehler) {
		$RYTHMEN = array();
		if ($zrythmen > 1) {
			// Rythemn in diesem Zeitraum laden
			$sql = "SELECT beginn, rythmus FROM rythmisierung WHERE zeitraum = ? ORDER BY beginn";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $zeitraum);
			if ($sql->execute()) {
				$sql->bind_result($rbeginn, $rrythmus);
				while ($sql->fetch()) {
					$r = array();
					$r['beginn'] = $rbeginn;
					$r['ende'] = mktime(23,59,59,date('m', $rbeginn), date('d', $rbeginn)+(7-date('N', $rbeginn)), date('Y', $rbeginn));
					$r['rythmus'] = $rrythmus;
					array_push($RYTHMEN, $r);
				}
			} else {$fehler = true;}
			$sql->close();
		}
	}

	if (!$fehler) {
		$dbl = cms_verbinden('l');
		// Alten Unterricht löschen
		if ($erster == 'j') {
			$LOESCHEN = array();
			$sql = $dbs->prepare("SELECT id FROM unterricht WHERE tbeginn > ?");
			$sql->bind_param("i", $jetzt);
			if ($sql->execute()) {
				$sql->bind_result($loeschid);
				while ($sql->fetch()) {
					array_push($LOESCHEN, $loeschid);
				}
			}
			$sql->close();

			if (count($LOESCHEN) > 0) {
				$loeschenids = implode(",", $LOESCHEN);
				$sql = $dbs->prepare("DELETE FROM unterricht WHERE id IN ($loeschenids)");
				$sql->execute();
				$sql->close();

				$sql = $dbs->prepare("DELETE FROM tagebuch WHERE id IN ($loeschenids)");
				$sql->execute();
				$sql->close();

				$sql = $dbl->prepare("DELETE FROM tagebuch WHERE id IN ($loeschenids)");
				$sql->execute();
				$sql->close();
			}
		}

		// Anfang der Stundenerzeugung festlegen
		if ($zbeginn > $jetzt) {$jetzt = $zbeginn;}
		$ferienzeiger = 0;
		$ryzeiger = 0;
		$aktry = 0;
		// Falls in diesem Zeitraum Ferien existieren
		if ($ferienzeiger < count($FERIEN)) {
			// Falls gerade Ferien sind, auf Zeit nach Ferien einstellen
			while (($ferienzeiger < count($FERIEN)) && ($jetzt >= $FERIEN[$ferienzeiger]['beginn']) && ($jetzt <= $FERIEN[$ferienzeiger]['ende'])) {
				$jetzt = mktime(0,0,0,date('m', $FERIEN[$ferienzeiger]['ende']),date('d', $FERIEN[$ferienzeiger]['ende'])+1,date('Y', $FERIEN[$ferienzeiger]['ende']));
				$ferienzeiger++;
			}
		}

		// Solange Stunden erzeugen, bis der Zeitraum überschritten ist
		$sql = $dbs->prepare("UPDATE unterricht SET pkurs = ?, pbeginn = ?, pende = ?, plehrer = ?, praum = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = 0, vplanart = '-', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?");
		$sqll = $dbl->prepare("INSERT INTO tagebuch (id) VALUES (?)");
		$sqls = $dbs->prepare("INSERT INTO tagebuch (id) VALUES (?)");
		while ($jetzt < $zende) {
			// Aktuellen Rythmus finden
			// Wenn das aktuelle Datum nach dem Ende des Rythmus liegt
			// Suche im nächsten weiter, falls er existiert
			while (($ryzeiger < count($RYTHMEN)) && ($jetzt > $RYTHMEN[$ryzeiger]['ende'])) {
				$ryzeiger++;
				// Falls noch ein Rythmus kommt, nehmen, falls nicht, kein Rythmus
				if ($ryzeiger < count($RYTHMEN)) {
					// Falls der nächsten Rythmus den jetzigen Zeitpunkt enthält
					// Übernehme den nächsten Rythmus, andernfalls kein Rythmus
					if (($jetzt >= $RYTHMEN[$ryzeiger]['beginn']) && ($jetzt <= $RYTHMEN[$ryzeiger]['ende'])) {
						$aktry = $RYTHMEN[$ryzeiger]['rythmus'];
					}
					else {$aktry = 0;}
				}
				else {$aktry = 0;}
			}

			// Aktuellen Wochentag bestimmen
			$wochentag = date('N', $jetzt);

			// Alle Stunden dieses Ryhtmus an diesem Wochentag anlegen
			if ($aktry != 0) {
				//print_r($SCHULSTUNDEN);echo "<br><br>";
				foreach ($REGELUNTERRICHT[0][$wochentag] AS $ru) {
					//print_r($ru); echo "<br><br>";
					$uid = cms_generiere_kleinste_id('unterricht', 's');
					$ubeginn = mktime($SCHULSTUNDEN[$ru['schulstunde']]['beginns'], $SCHULSTUNDEN[$ru['schulstunde']]['beginnm'], 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
					$uende = mktime($SCHULSTUNDEN[$ru['schulstunde']]['endes'], $SCHULSTUNDEN[$ru['schulstunde']]['endem'], 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt))-1;
					$sql->bind_param("iiiiiiiiiii", $ru['kurs'], $ubeginn, $uende, $ru['lehrer'], $ru['raum'], $ru['kurs'], $ubeginn, $uende, $ru['lehrer'], $ru['raum'], $uid);
					$sql->execute();
					$sqll->bind_param("i", $uid);
					$sqll->execute();
					$sqls->bind_param("i", $uid);
					$sqls->execute();
				}
			}
			foreach ($REGELUNTERRICHT[$aktry][$wochentag] AS $ru) {
				$uid = cms_generiere_kleinste_id('unterricht', 's');
				$ubeginn = mktime($SCHULSTUNDEN[$ru['schulstunde']]['beginns'], $SCHULSTUNDEN[$ru['schulstunde']]['beginnm'], 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
				$uende = mktime($SCHULSTUNDEN[$ru['schulstunde']]['endes'], $SCHULSTUNDEN[$ru['schulstunde']]['endem'], 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt))-1;
				$sql->bind_param("iiiiiiiiiii", $ru['kurs'], $ubeginn, $uende, $ru['lehrer'], $ru['raum'], $ru['kurs'], $ubeginn, $uende, $ru['lehrer'], $ru['raum'], $uid);
				$sql->execute();
				$sqll->bind_param("i", $uid);
				$sqll->execute();
				$sqls->bind_param("i", $uid);
				$sqls->execute();
			}

			// Nächsten Tag bestimmen
			$jetzt = mktime(0,0,0,date('m', $jetzt),date('d', $jetzt)+1,date('Y', $jetzt));

			// Falls in diesem Zeitraum Ferien existieren
			if ($ferienzeiger < count($FERIEN)) {
				//echo $jetzt." - ".$FERIEN[$ferienzeiger]['beginn']." - ".$FERIEN[$ferienzeiger]['bez']."<br>";
				// Falls gerade Ferien sind, auf Zeit nach Ferien einstellen
				if (($ferienzeiger < count($FERIEN)) && ($jetzt >= $FERIEN[$ferienzeiger]['beginn']) && ($jetzt <= $FERIEN[$ferienzeiger]['ende'])) {
					//echo $jetzt." - ".$FERIEN[$ferienzeiger]['beginn']." - ".$FERIEN[$ferienzeiger]['bez']."<br>";
					$jetzt = mktime(0,0,0,date('m', $FERIEN[$ferienzeiger]['ende']),date('d', $FERIEN[$ferienzeiger]['ende'])+1,date('Y', $FERIEN[$ferienzeiger]['ende']));
					$ferienzeiger++;
				}
			}
		}
		$sql->close();

		cms_lehrerdb_header(false);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
