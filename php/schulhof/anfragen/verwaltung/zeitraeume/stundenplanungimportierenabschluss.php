<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

cms_rechte_laden();
if (isset($_POST['zuordnen'])) {$zuordnen = $_POST['zuordnen'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {$ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];} else {echo "FEHLER"; exit;}
if (!cms_check_toggle($zuordnen)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;
	$dbs = cms_verbinden("s");
	// Doppelte Schienen bereinigen
	// Entferne alle Schienen mit nur einem Fach
	$sql = $dbs->prepare("DELETE FROM schienen WHERE id IN (SELECT id FROM (SELECT schienen.id AS id, COUNT(kurs) AS anzahl FROM schienen LEFT JOIN schienenkurse ON schienen.id = schienenkurse.schiene WHERE schienen.zeitraum = ? GROUP BY schienen.id HAVING anzahl <= 1) AS x)");
	$sql->bind_param('i', $ZEITRAUM);
	$sql->execute();
	$sql->close();

	// Alle Schienen laden
	$SCHIENEN = array();
	$SCHIENENIDS = array();
	$sql = $dbs->prepare("SELECT DISTINCT id, kurs FROM schienen JOIN schienenkurse ON schienen.id = schienenkurse.schiene WHERE zeitraum = ?");
	$sql->bind_param('i', $ZEITRAUM);
	if ($sql->execute()) {
		$sql->bind_result($sid, $kid);
		while ($sql->fetch()) {
			if (!in_array($sid, $SCHIENENIDS)) {
				$SCHIENEN[$sid]['id'] = $sid;
				$SCHIENEN[$sid]['kurse'] = array();
				array_push($SCHIENEN[$sid]['kurse'], $kid);
				array_push($SCHIENENIDS, $sid);
			}
			else {
				array_push($SCHIENEN[$sid]['kurse'], $kid);
			}
		}
	}
	$sql->close();

	// Suche für jede Schiene Dopplungen und lösche sie
	$SCHIENENLOESCHEN = array();
	foreach ($SCHIENEN AS $s) {
		if (!in_array($s['id'], $SCHIENENLOESCHEN)) {
			foreach ($SCHIENEN AS $v) {
				// Prüfe alle nicht zum Löschen Vorgemerkten Schinen, außer der Schiene selbst auf Übereinstimmung
				if (!in_array($v['id'], $SCHIENENLOESCHEN) && ($v['id'] != $s['id'])) {
					$uebereinstimmung = true;
					foreach ($v['kurse'] AS $vk) {
						if (!in_array($vk, $s['kurse'])) {$uebereinstimmung = false;}
					}
					foreach ($s['kurse'] AS $sk) {
						if (!in_array($sk, $v['kurse'])) {$uebereinstimmung = false;}
					}
					if ($uebereinstimmung) {array_push($SCHIENENLOESCHEN, $v['id']);}
				}
			}
		}
	}

	if (count($SCHIENENLOESCHEN) > 0) {
		$schienenmuster = "(".implode(",", $SCHIENENLOESCHEN).")";
		$sql = $dbs->prepare("DELETE FROM schienen WHERE zeitraum = ? AND id IN $schienenmuster");
		$sql->bind_param("i", $ZEITRAUM);
		$sql->execute();
		$sql->close();
	}


	// Schüler und Lehrer aufgrund der Klassenzusammensetzung und des Regelstundenplans zuordnen
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
