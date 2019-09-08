<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER";exit;}
if (isset($_POST['zeitraum'])) {$zeitraum = $_POST['zeitraum'];} else {echo "FEHLER";exit;}
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($schuljahr, 0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($zeitraum, 0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($stufe, 0)) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden und Tagebücher erzeugen'];

if (cms_angemeldet() && $zugriff) {
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

	$jetzt = time();

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

	// Prüfen, ob die Stufe zum Schuljahr passt
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

	// FERIEN in diesem Zeitraum laden
	if (!$fehler) {
		$FERIEN = array();
		$sql = "SELECT beginn, ende FROM ferien WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn < ? AND ende > ?)";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("iiiiii", $zbeginn, $zende, $zbeginn, $zende, $zbeginn, $zende);
		if ($sql->execute()) {
			$sql->bind_result($fbeginn, $fende);
			while ($sql->fetch()) {
				$f = array();
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
		$SCHULSTUNDENIDS = array();
		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraeume = ? ORDER BY beginns, beginnm";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $zeitraum);
		if ($sql->execute()) {
			$sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
			while ($sql->fetch()) {
				array_push($SCHULSTUNDENIDS, $stdid)
				$SCHULSTUNDEN[$stdid]['bez'] = $stdbez;
				$SCHULSTUNDEN[$stdid]['beginns'] = $stdbeginns;
				$SCHULSTUNDEN[$stdid]['beginnm'] = $stdbeginnm;
				$SCHULSTUNDEN[$stdid]['endes'] = $stdendes;
				$SCHULSTUNDEN[$stdid]['endem'] = $stdendem;
			}
		} else {$fehler = true;}
		$sql->close();
	}

	// REGELUNTERRICHT LADEN

	if (!$fehler) {
		$RYTHMEN = array();
		if ($zrythmen > 1) {
			// Rythemn in diesem Zeitraum laden
			$sql = "SELECT beginn, kw, rythmus FROM rythmisierung WHERE zeitraum = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $zeitraum);
			if ($sql->execute()) {
				$sql->bind_result($rbeginn, $rkw, $rrythmus);
				while ($sql->fetch()) {
					$r = array();
					$r['beginn'] = $rbeginn;
					$r['kw'] = $rkw;
					$r['rathmus'] = $rrythmus;
					array_push($RYTHMEN, $r);
				}
			} else {$fehler = true;}
			$sql->close();
		}
	}




	if (!$fehler) {
		// Alten Unterricht löschen
		$sql = "DELETE FROM unterricht WHERE tbeginn > $jetzt";
		$sql = $dbs->prepare($sql);
		$sql->execute();
		$sql->close();

		echo "<textarea rows=\"50\" cols=\"500\">";
		print_r($FERIEN);
		print_r($RYTHMEN);
		echo "</textarea>";


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
