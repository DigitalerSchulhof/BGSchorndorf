<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['ZEITRAUMRYTHMISIEREN'])) {$zeitraum = $_SESSION['ZEITRAUMRYTHMISIEREN'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($zeitraum,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

$dbs = cms_verbinden('s');
if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.rythmisieren")) {
	$fehler = false;
	// Prüfen, wie viele Rythmen der Zeitraum zulässt
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, beginn, ende, rythmen FROM zeitraeume WHERE id = ?");
	$sql->bind_param("i", $zeitraum);
	if ($sql->execute()) {
		$sql->bind_result($anzahl, $zbeginn, $zende, $rythmen);
		if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();

	// Rythmen laden
	if (($rythmen > 1) && (!$fehler)) {
		// Anzahl an Wochen ermitteln
		$jetzt = $zbeginn;
		// Dauer eines Tages
		$tagdauer = 86400;
		$wochen = 0;
		$bwtag = date('N', $zbeginn);
		$RYTHMEN = array();
		if ($bwtag != 1) {
			$wochen ++;
			$RYTHMEN[$wochen]['beginn'] = $jetzt;
			$RYTHMEN[$wochen]['kw'] = date('W', $jetzt);
			$RYTHMEN[$wochen]['rythmus'] = null;
			$jetzt = mktime(0,0,0,date('m', $jetzt), date('d', $jetzt)+(7-$bwtag+1), date('Y', $jetzt));
		}
		while ($jetzt <= $zende) {
			$wochen++;
			$RYTHMEN[$wochen]['beginn'] = $jetzt;
			$RYTHMEN[$wochen]['kw'] = date('W', $jetzt);
			$RYTHMEN[$wochen]['rythmus'] = null;
			$jetzt = mktime(0,0,0,date('m', $jetzt), date('d', $jetzt)+7, date('Y', $jetzt));
		}

		// Wocheneingaben prüfen
    for ($w = 1; $w <= $wochen; $w++) {
			if (isset($_POST["woche_".$w])) {
        if (cms_check_ganzzahl($_POST["woche_".$w],1,$rythmen)) {
					$RYTHMEN[$w]['rythmus'] = $_POST["woche_".$w];
				} else {$fehler = true;}
      } else {$fehler = true;}
    }
	}

	if (!$fehler) {
		// Alte Rythmen löschen
		$sql = $dbs->prepare("DELETE FROM rythmisierung WHERE zeitraum = ?");
		$sql->bind_param("i", $zeitraum);
		$sql->execute();
		$sql->close();

		// Neue Rythmen eintragen
		$sql = $dbs->prepare("INSERT INTO rythmisierung (zeitraum, beginn, kw, rythmus) VALUES (?, ?, ?, ?)");
		foreach($RYTHMEN AS $r) {
			$sql->bind_param("iiii", $zeitraum, $r['beginn'], $r['kw'], $r['rythmus']);
			$sql->execute();
		}
		$sql->close();
		echo "ERFOLG";
	}
	else {echo "FEHLER";}
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
