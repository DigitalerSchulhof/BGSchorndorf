<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['trennung'])) {$trennung = $_POST['trennung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurse'])) {$kurse = $_POST['kurse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {$SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (strlen($trennung) == 0) {$fehler = true;}
	if (strlen($kurse) == 0) {$fehler = true;}
	if (!cms_check_ganzzahl($SCHULJAHR, 0)) {$fehler = true;}
	if (!cms_check_ganzzahl($ZEITRAUM, 0)) {$fehler = true;}

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
		$sql = $dbs->prepare("SELECT COUNT(*) FROM zeitraeume WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $ZEITRAUM, $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($zanzahl);
			if ($sql->fetch()) {
				if ($zanzahl != '1') {$fehler = true;}
			} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	$K = array();
	$kurseeinzeln = explode("\n", $kurse);
	foreach ($kurseeinzeln AS $einkurs) {
		$einkurs = explode($trennung, $einkurs);
		$ek = array();
		$ek['bez'] = $einkurs[0];
		$ek['kbez'] = $einkurs[1];
		$ek['stufe'] = $einkurs[2];
		$ek['fach'] = $einkurs[3];
		$ek['icon'] = $einkurs[4];
		$ek['klassen'] = $einkurs[5];
		$ek['schienen'] = $einkurs[6];
		array_push($K, $ek);
	}

	// Prüfen welche Kurse existieren und neue kurse speichern
	$NK = array();
	$KURSEINSCHIENEN = array();
	$sql = $dbs->prepare("SELECT id, COUNT(*) FROM kurse WHERE schuljahr = ? AND bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	foreach ($K as $ek) {
		$sql->bind_param("is", $SCHULJAHR, $ek['bez']);
		if ($sql->execute()) {
			$sql->bind_result($idcheck, $check);
			if ($sql->fetch()) {
				if ($check == 0) {
					array_push($NK, $ek);
				}
				else {
					if (strlen($ek['schienen']) > 0) {
						$eschienen = explode("|", substr($ek['schienen'], 1));
						foreach ($eschienen AS $es) {
							$schienenzuordnung = array();
							$schienenzuordnung['schiene'] = $es;
							$schienenzuordnung['kurs'] = $idcheck;
							if (!in_array($schienenzuordnung, $KURSEINSCHIENEN)) {
								array_push($KURSEINSCHIENEN, $schienenzuordnung);
							}
						}
					}
				}
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
	}
	$sql->close();

	if ($fehler) {
		echo "FEHLER\n\n\n";
	}
	else {
		// Kurse in Schienen einsortieren
		$sql = $dbs->prepare("INSERT INTO schienenkurse (schiene, kurs) VALUES (?,?)");
		foreach ($KURSEINSCHIENEN AS $k) {
			$sql->bind_param("ii", $k['schiene'], $k['kurs']);
			$sql->execute();
		}
		$sql->close();

		echo "ERFOLG\n\n";
		foreach ($NK as $ek) {
			echo "\n".$ek['bez'].$trennung.$ek['kbez'].$trennung.$ek['stufe'].$trennung.$ek['fach'].$trennung.$ek['icon'].$trennung.$ek['klassen'];
			echo $trennung.$ek['schiene'].$trennung;
		}
		if (count($NK) == 0) {echo "\n";}
	}
	cms_trennen($dbs);
}
else {
	echo "FEHLER";
}
?>
