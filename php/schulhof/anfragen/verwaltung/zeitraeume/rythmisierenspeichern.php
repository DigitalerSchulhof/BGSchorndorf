<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['ZEITRAUMRYTHMISIEREN'])) {$zeitraum = $_SESSION['ZEITRAUMRYTHMISIEREN'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($zeitraum,0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume rythmisieren'];

$dbs = cms_verbinden('s');
if (cms_angemeldet() && $zugriff) {
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
		$bjahr = date('Y', $zbeginn);
		$bkw = date('W', $zbeginn);
		$ejahr = date('Y', $zende);
		$ekw = date('W', $zende);

		$jahr = $bjahr;
    $kw = $bkw;
    $ende = false;
		$RYTHMEN = array();
    while (!$ende) {
      if (($kw == $ekw) && ($jahr == $ejahr)) {$ende = true;}
      if (isset($_POST[$jahr."_".$kw])) {
        if (cms_check_ganzzahl($_POST[$jahr."_".$kw],1,$rythmen)) {
					$r = array();
					$r['jahr'] = $jahr;
					$r['kw'] = $kw;
					$r['rythmus'] = $_POST[$jahr."_".$kw];
					array_push($RYTHMEN, $r);
				}
      } else {$fehler = true;}
      $kw++;
      if ($kw > 52) {$kw = 1; $jahr++;}
    }
	}

	if (!$fehler) {
		// Alte Rythmen löschen
		$sql = $dbs->prepare("DELETE FROM rythmisierung WHERE zeitraum = ?");
		$sql->bind_param("i", $zeitraum);
		$sql->execute();
		$sql->close();

		// Neue Rythmen eintragen
		$sql = $dbs->prepare("INSERT INTO rythmisierung (zeitraum, jahr, kw, rythmus) VALUES (?, ?, ?, ?)");
		foreach($RYTHMEN AS $r) {
			$sql->bind_param("iiii", $zeitraum, $r['jahr'], $r['kw'], $r['rythmus']);
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
