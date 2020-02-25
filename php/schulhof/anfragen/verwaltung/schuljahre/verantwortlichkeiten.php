<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['klasseninfo'])) {$klasseninfo = $_POST['klasseninfo'];} else {echo "FEHLER";exit;}
if (isset($_POST['stufeninfo'])) {$stufeninfo = $_POST['stufeninfo'];} else {echo "FEHLER";exit;}
if (isset($_SESSION["VERANTWORTLICHKEITENSCHULJAHR"])) {$SCHULJAHR = $_SESSION["VERANTWORTLICHKEITENSCHULJAHR"];} else {echo "FEHLER";exit;}

cms_rechte_laden();

$dbs = cms_verbinden('s');

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.verantwortlichkeiten")) {
	$fehler = false;

	$KLASSENIDS = array();
	$STUFENIDS = array();
	$KLASSENV = array();
	$STUFENV = array();
	$RAUMIDS = array();
	$LEHRERIDS = array();

	// Klassen einlesen
	if (strlen($klasseninfo) > 0) {
		$klassen = explode("|", $klasseninfo);
		foreach ($klassen as $k) {
			$k = explode(";", $k);
			if (count($k) == 4) {
				// Klassenid hinzufügen
				if (cms_check_ganzzahl($k[0],0) &&
				   (cms_check_ganzzahl($k[1],0) || ($k[1] == '-')) &&
					 (cms_check_ganzzahl($k[2],0) || ($k[2] == '-')) &&
					 (cms_check_ganzzahl($k[3],0) || ($k[3] == '-'))) {
					$V = array();
					$V['klasse'] = $k[0];
					$V['lehrer'] = $k[1];
					$V['stellv'] = $k[2];
					$V['raum'] = $k[3];
					if (!in_array($k[0], $KLASSENIDS)) {
						array_push($KLASSENV, $V);
						array_push($KLASSENIDS, $k[0]);
					}
					if (($k[1] != '-') && !in_array($k[1], $LEHRERIDS)) {array_push($LEHRERIDS, $k[1]);}
					if (($k[2] != '-') && !in_array($k[2], $LEHRERIDS)) {array_push($LEHRERIDS, $k[2]);}
					if (($k[3] != '-') && !in_array($k[3], $RAUMIDS)) {array_push($RAUMIDS, $k[3]);}
				} else {$fehler = true;}
			}
			else {$fehler = true;}
		}
	}

	// Stufen einlesen
	if (strlen($stufeninfo) > 0) {
		$stufen = explode("|", $stufeninfo);
		foreach ($stufen as $s) {
			$s = explode(";", $s);
			if (count($s) == 4) {
				// Klassenid hinzufügen
				if (cms_check_ganzzahl($s[0],0) &&
				   (cms_check_ganzzahl($s[1],0) || ($s[1] == '-')) &&
					 (cms_check_ganzzahl($s[2],0) || ($s[2] == '-')) &&
					 (cms_check_ganzzahl($s[3],0) || ($s[3] == '-'))) {
					$V = array();
					$V['stufe'] = $s[0];
					$V['lehrer'] = $s[1];
					$V['stellv'] = $s[2];
					$V['raum'] = $s[3];
					if (!in_array($s[0], $STUFENIDS)) {
						array_push($STUFENV, $V);
						array_push($STUFENIDS, $s[0]);
					}
					if (($s[1] != '-') && !in_array($s[1], $LEHRERIDS)) {array_push($LEHRERIDS, $s[1]);}
					if (($s[2] != '-') && !in_array($s[2], $LEHRERIDS)) {array_push($LEHRERIDS, $s[2]);}
					if (($s[3] != '-') && !in_array($s[3], $RAUMIDS)) {array_push($RAUMIDS, $s[3]);}
				} else {$fehler = true;}
			}
			else {$fehler = true;}
		}
	}

	$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE id = ?");
	$sql->bind_param("i", $SCHULJAHR);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

	// Existenz der einzelnen Objekte prüfen
	if (!$fehler) {
		if (count($KLASSENIDS) > 0) {
			$sql = $dbs->prepare("SELECT COUNT(*) FROM klassen WHERE id IN (".implode(",", $KLASSENIDS).") AND schuljahr = ?");
			$sql->bind_param("i", $SCHULJAHR);
			if ($sql->execute()) {
				$sql->bind_result($anzahl);
				if ($sql->fetch()) {if ($anzahl != count($KLASSENIDS)) {$fehler = true;}}
				else {$fehler = true;}
			}
			$sql->close();
		}

		if (count($STUFENIDS) > 0) {
			$sql = $dbs->prepare("SELECT COUNT(*) FROM stufen WHERE id IN (".implode(",", $STUFENIDS).") AND schuljahr = ?");
			$sql->bind_param("i", $SCHULJAHR);
			if ($sql->execute()) {
				$sql->bind_result($anzahl);
				if ($sql->fetch()) {if ($anzahl != count($STUFENIDS)) {$fehler = true;}}
				else {$fehler = true;}
			}
			$sql->close();
		}

		if (count($LEHRERIDS) > 0) {
			$sql = $dbs->prepare("SELECT COUNT(*) FROM lehrer WHERE id IN (".implode(",", $LEHRERIDS).")");
			if ($sql->execute()) {
				$sql->bind_result($anzahl);
				if ($sql->fetch()) {if ($anzahl != count($LEHRERIDS)) {$fehler = true;}}
				else {$fehler = true;}
			}
			$sql->close();
		}

		if (count($RAUMIDS) > 0) {
			$sql = $dbs->prepare("SELECT COUNT(*) FROM raeume WHERE id IN (".implode(",", $RAUMIDS).")");
			if ($sql->execute()) {
				$sql->bind_result($anzahl);
				if ($sql->fetch()) {if ($anzahl != count($RAUMIDS)) {$fehler = true;}}
				else {$fehler = true;}
			}
			$sql->close();
		}
	}

	if (!$fehler) {
		// Alte Veranwortlichkeiten löschen
		$sql = $dbs->prepare("DELETE FROM raeumeklassen WHERE klasse IN (".implode(",", $KLASSENIDS).")");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM raeumestufen WHERE stufe IN (".implode(",", $STUFENIDS).")");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM klassenlehrer WHERE klasse IN (".implode(",", $KLASSENIDS).")");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM stufenlehrer WHERE stufe IN (".implode(",", $STUFENIDS).")");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM klassenlehrerstellvertreter WHERE klasse IN (".implode(",", $KLASSENIDS).")");
		$sql->execute();
		$sql->close();
		$sql = $dbs->prepare("DELETE FROM stufenlehrerstellvertreter WHERE stufe IN (".implode(",", $STUFENIDS).")");
		$sql->execute();
		$sql->close();

		// NEUE VERANTWORTLICHKEITEN SPEICHERN
		$sql = $dbs->prepare("INSERT INTO klassenlehrer (klasse, lehrer) VALUES (?,?)");
		foreach ($KLASSENV as $k) {
			if ($k['lehrer'] != '-') {
				$sql->bind_param("ii", $k['klasse'], $k['lehrer']);
				$sql->execute();
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO klassenlehrerstellvertreter (klasse, lehrer) VALUES (?,?)");
		foreach ($KLASSENV as $k) {
			if ($k['stellv'] != '-') {
				$sql->bind_param("ii", $k['klasse'], $k['stellv']);
				$sql->execute();
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO raeumeklassen (raum, klasse) VALUES (?,?)");
		foreach ($KLASSENV as $k) {
			if ($k['raum'] != '-') {
				$sql->bind_param("ii", $k['raum'], $k['klasse']);
				$sql->execute();
			}
		}
		$sql->close();


		$sql = $dbs->prepare("INSERT INTO stufenlehrer (stufe, lehrer) VALUES (?,?)");
		foreach ($STUFENV as $k) {
			if ($k['lehrer'] != '-') {
				$sql->bind_param("ii", $k['stufe'], $k['lehrer']);
				$sql->execute();
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO stufenlehrerstellvertreter (stufe, lehrer) VALUES (?,?)");
		foreach ($STUFENV as $k) {
			if ($k['stellv'] != '-') {
				$sql->bind_param("ii", $k['stufe'], $k['stellv']);
				$sql->execute();
			}
		}
		$sql->close();
		$sql = $dbs->prepare("INSERT INTO raeumestufen (raum, stufe) VALUES (?,?)");
		foreach ($STUFENV as $k) {
			if ($k['raum'] != '-') {
				$sql->bind_param("ii", $k['raum'], $k['stufe']);
				$sql->execute();
			}
		}
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
