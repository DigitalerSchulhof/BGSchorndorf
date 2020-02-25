<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER1"; exit;}
if (isset($_POST['beginnT'])) {$beginnT = $_POST['beginnT'];} else {echo "FEHLER2"; exit;}
if (isset($_POST['beginnM'])) {$beginnM = $_POST['beginnM'];} else {echo "FEHLER3"; exit;}
if (isset($_POST['beginnJ'])) {$beginnJ = $_POST['beginnJ'];} else {echo "FEHLER4"; exit;}
if (isset($_POST['endeT'])) {$endeT = $_POST['endeT'];} else {echo "FEHLER5"; exit;}
if (isset($_POST['endeM'])) {$endeM = $_POST['endeM'];} else {echo "FEHLER6"; exit;}
if (isset($_POST['endeJ'])) {$endeJ = $_POST['endeJ'];} else {echo "FEHLER7"; exit;}
if (isset($_SESSION['ZEITRAUMKLONEN'])) {$STAMMZEITRAUM = $_SESSION['ZEITRAUMKLONEN'];} else {echo "FEHLER8";exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.anlegen")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_titel($bezeichnung)) {echo "FEHLER9"; exit;}
	if (!cms_check_ganzzahl($beginnT,1,31)) {echo "FEHLER10"; exit;}
	if (!cms_check_ganzzahl($beginnM,1,12)) {echo "FEHLER11"; exit;}
	if (!cms_check_ganzzahl($beginnJ,0)) {echo "FEHLER12"; exit;}
	if (!cms_check_ganzzahl($endeT,1,31)) {echo "FEHLER13"; exit;}
	if (!cms_check_ganzzahl($endeM,1,12)) {echo "FEHLER14"; exit;}
	if (!cms_check_ganzzahl($endeJ,0)) {echo "FEHLER15"; exit;}

	$beginn = mktime(0,0,0,$beginnM,$beginnT,$beginnJ);
	$ende = mktime(23,59,59,$endeM,$endeT,$endeJ);

	if ($beginn >= $ende) {echo "FEHLER16"; exit;}

	$dbs = cms_verbinden('s');
	// Existiert der alte Zeitraum
  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, schuljahr, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE id = ?");
  $sql->bind_param('i', $STAMMZEITRAUM);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $SCHULJAHR, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen);
    if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

	if (!$fehler) {
		// Existiert das Schuljahr
	  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, beginn, ende FROM schuljahre WHERE id = ?");
	  $sql->bind_param('i', $SCHULJAHR);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl, $sjbeginn, $sjende);
	    if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
				if (($beginn < $sjbeginn) || ($ende > $sjende)) {echo "ZEIT"; $fehler = true;}
			} else {$fehler = true;}
	  } else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {
		// Prüfen, ob sich die Zeiträume überschneiden
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn < ? AND ende > ?)");
		$sql->bind_param("iiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende);
		if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {
				if ($anzahl != 0) {$fehler = true; echo "DOPPELT";}
			} else {$fehler = true;}
	  } else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {
		// ALTE SCHULSTUNDEN LADEN
		$stdaltid = array();
		$stdneuid = array();
		$SCHULSTUNDEN = array();
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ?");
		$sql->bind_param("i", $STAMMZEITRAUM);
		if ($sql->execute()) {
			$sql->bind_result($stdid, $stdbez, $stdbs, $stdbm, $stdes, $stdem);
			while ($sql->fetch()) {
				$std = array();
				$std['id'] = $stdid;
				$std['bez'] = $stdbez;
				$std['beginns'] = $stdbs;
				$std['beginnm'] = $stdbm;
				$std['endes'] = $stdes;
				$std['endem'] = $stdem;
				array_push($SCHULSTUNDEN, $std);
				array_push($stdaltid, $stdid);
			}
		}
		$sql->close();

		// NÄCHSTE FREIE ID SUCHEN
		$ZIELZEITRAUM = cms_generiere_kleinste_id('zeitraeume');
		// ZEITRAUM EINTRAGEN
		$sql = $dbs->prepare("UPDATE zeitraeume SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ?, mo = ?, di = ?, mi = ?, do = ?, fr = ?, sa = ?, so = ?, rythmen = ? WHERE id = ?");
	  $sql->bind_param("isiiiiiiiiiii", $SCHULJAHR, $bezeichnung, $beginn, $ende, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen, $ZIELZEITRAUM);
	  $sql->execute();
	  $sql->close();

		// SCHULSTUNDEN ÜBERTRAGEN
		for ($s=0; $s<count($SCHULSTUNDEN); $s++) {
			$SCHULSTUNDEN[$s]['neuid'] = cms_generiere_kleinste_id('schulstunden');
			$stdneuid[$SCHULSTUNDEN[$s]['id']] = $SCHULSTUNDEN[$s]['neuid'];
		}
		$sql = $dbs->prepare("UPDATE schulstunden SET zeitraum = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginns = ?, beginnm = ?, endes = ?, endem = ? WHERE id = ?");
		foreach ($SCHULSTUNDEN AS $s) {
			$sql->bind_param("isiiiii", $ZIELZEITRAUM, $s['bez'], $s['beginns'], $s['beginnm'], $s['endes'], $s['endem'], $s['neuid']);
		  $sql->execute();
		}
		$sql->close();

		// REGELUNTERRICHT LADEN
		$REGELUNTERRICHT = array();
		if (count($stdaltid) > 0) {
			$stdwhere = "(".implode(',', $stdaltid).")";
			$sql = $dbs->prepare("SELECT id, schulstunde, tag, rythmus, kurs, lehrer, raum FROM regelunterricht WHERE schulstunde IN $stdwhere");
			if ($sql->execute()) {
				$sql->bind_result($ruid, $rustd, $rutag, $rurythmus, $rukurs, $rulehrer, $ruraum);
				while ($sql->fetch()) {
					$ru = array();
					$ru['id'] = $ruid;
					$ru['std'] = $rustd;
					$ru['tag'] = $rutag;
					$ru['rythmus'] = $rurythmus;
					$ru['kurs'] = $rukurs;
					$ru['lehrer'] = $rulehrer;
					$ru['raum'] = $ruraum;
					array_push($REGELUNTERRICHT, $ru);
				}
			}
			$sql->close();
		}

		// REGELUNTERRICHT ÜBERTRAGEN
		foreach ($REGELUNTERRICHT AS $r) {
			$rneu = cms_generiere_kleinste_id('regelunterricht');
			$sql = $dbs->prepare("UPDATE regelunterricht SET schulstunde = ?, tag = ?, rythmus = ?, kurs = ?, lehrer = ?, raum = ? WHERE id = ?");
			$sql->bind_param("isiiiii", $stdneuid[$r['std']], $r['tag'], $r['rythmus'], $r['kurs'], $r['lehrer'], $r['raum'], $rneu);
			$sql->execute();
			$sql->close();
		}


		cms_trennen($dbs);
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
