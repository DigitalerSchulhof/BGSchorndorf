<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER0";exit;}
if (isset($_POST['hausaufgaben'])) {$hausaufgabe = $_POST['hausaufgaben'];} else {echo "FEHLER1";exit;}
if (isset($_POST['leistungsmessung'])) {$leistungsmessung = $_POST['leistungsmessung'];} else {echo "FEHLER2";exit;}
if (isset($_POST['freigabe'])) {$freigabe = $_POST['freigabe'];} else {echo "FEHLER3";exit;}
if (isset($_POST['fzids'])) {$fzids = $_POST['fzids'];} else {echo "FEHLER4";exit;}
if (isset($_POST['ltids'])) {$ltids = $_POST['ltids'];} else {echo "FEHLER5";exit;}
if (isset($_SESSION['TAGEBUCHEINTRAG'])) {$eintrag = $_SESSION['TAGEBUCHEINTRAG'];} else {echo "FEHLER6";exit;}
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_BENUTZERID = $_SESSION['BENUTZERID'];

if (!cms_check_ganzzahl($eintrag, 0)) {echo "FEHLER7";exit;}
if (!cms_check_toggle($leistungsmessung)) {echo "FEHLER8";exit;}
if (!cms_check_toggle($freigabe)) {echo "FEHLER9";exit;}
if (strlen($inhalt) == 0) {echo "FEHLER10";exit;}
$fids = explode("|", $fzids);
$lids = explode("|", $ltids);
$fehlzeiten = [];
$lobtadel = [];
$personen = [];

$dbs = cms_verbinden("s");
$tlehrer = null;
// Stundeninformationen laden
$sql = $dbs->prepare("SELECT tagebuch.id, tbeginn, tende, tkurs, tlehrer FROM tagebuch JOIN unterricht ON tagebuch.id = unterricht.id WHERE tagebuch.id = ? AND freigabe != 1");
$sql->bind_param("i", $eintrag);
if ($sql->execute()) {
	$sql->bind_result($uid, $tbeginn, $tende, $tkurs, $tlehrer);
	$sql->fetch();
}
$sql->close();

$tag = date("d", $tbeginn);
$monat = date("m", $tbeginn);
$jahr = date("Y", $tbeginn);
for ($i=1; $i<count($fids); $i++) {
	$fzid = $fids[$i];
	if (isset($_POST["fzperson_".$fzid])) {$fehlzeiten[$fzid]['person'] = $_POST["fzperson_".$fzid];} else {echo "FEHLER13"; exit;}
	if (isset($_POST["fzzeitbh_".$fzid])) {$fehlzeiten[$fzid]['bh'] = $_POST["fzzeitbh_".$fzid];} else {echo "FEHLER14"; exit;}
	if (isset($_POST["fzzeitbm_".$fzid])) {$fehlzeiten[$fzid]['bm'] = $_POST["fzzeitbm_".$fzid];} else {echo "FEHLER15"; exit;}
	if (isset($_POST["fzzeiteh_".$fzid])) {$fehlzeiten[$fzid]['eh'] = $_POST["fzzeiteh_".$fzid];} else {echo "FEHLER16"; exit;}
	if (isset($_POST["fzzeitem_".$fzid])) {$fehlzeiten[$fzid]['em'] = $_POST["fzzeitem_".$fzid];} else {echo "FEHLER17"; exit;}
	if (isset($_POST["fzbemerkung_".$fzid])) {$fehlzeiten[$fzid]['bem'] = $_POST["fzbemerkung_".$fzid];} else {echo "FEHLER18"; exit;}

	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['person'],0)) {echo "FEHLER19"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bh'],0,23)) {echo "FEHLER20"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bm'],0,59)) {echo "FEHLER21"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['eh'],0,23)) {echo "FEHLER22"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['em'],0,59)) {echo "FEHLER23"; exit;}
	if ($fehlzeiten[$fzid]['bh']*60+$fehlzeiten[$fzid]['bm'] >= $fehlzeiten[$fzid]['eh']*60+$fehlzeiten[$fzid]['em']) {echo "FEHLER24"; exit;}
	array_push($personen, $fehlzeiten[$fzid]['person']);
	$fehlzeiten[$fzid]['von'] = mktime($fehlzeiten[$fzid]['bh'], $fehlzeiten[$fzid]['bm'], 0, $monat, $tag, $jahr);
	$fehlzeiten[$fzid]['bis'] = mktime($fehlzeiten[$fzid]['eh'], $fehlzeiten[$fzid]['em'], 0, $monat, $tag, $jahr);;
}

for ($i=1; $i<count($lids); $i++) {
	$ltid = $lids[$i];
	if (isset($_POST["ltperson_".$ltid])) {$lobtadel[$ltid]['person'] = $_POST["ltperson_".$ltid];} else {echo "FEHLER25"; exit;}
	if (isset($_POST["ltart_".$ltid])) {$lobtadel[$ltid]['art'] = $_POST["ltart_".$ltid];} else {echo "FEHLER26"; exit;}
	if (isset($_POST["ltbemerkung_".$ltid])) {$lobtadel[$ltid]['bem'] = $_POST["ltbemerkung_".$ltid];} else {echo "FEHLER27"; exit;}
}
foreach ($lobtadel as $lt) {
	if (!cms_check_ganzzahl($lt['person'],0) && $lt['person'] != "-") {echo "FEHLER28"; exit;}
	if ($lt['art'] != 'v' && $lt['art'] != 'm' && $lt['art'] != 'l') {echo "FEHLER29"; exit;}
	if ($lt['person'] != '-') {
		array_push($personen, $lt['person']);
	}
}
$pers = "";
if (count($personen) > 0) {
	$pers = "(".implode(",", $personen).")";
	if (!cms_check_idliste($pers)) {echo "FEHLER30"; exit;}
}

if (cms_angemeldet() && ($CMS_BENUTZERART == 'l') && $tlehrer == $CMS_BENUTZERID) {
	$fehler = false;
	// Personenzuordnung prüfen
	if (count($personen) > 0) {
		$anzahl = 0;
		$sql = $dbs->prepare("SELECT count(id) FROM personen WHERE id NOT IN (SELECT person FROM kursemitglieder WHERE gruppe = ?) AND id IN $pers");
		$sql->bind_param("i", $tkurs);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($anzahl > 0) {
				$fehler = true;
			}
		}
		$sql->close();
	}

	if (!$fehler) {
		// Änderung am Tagebucheintrag vornehmen
		$sql = $dbs->prepare("UPDATE tagebuch SET inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausaufgabe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), freigabe = ?, leistungsmessung = ?, urheber = ? WHERE id = ?");
		$sql->bind_param("ssiiii", $inhalt, $hausaufgabe, $freigabe, $leistungsmessung, $CMS_BENUTZERID, $eintrag);
		$sql->execute();
		$sql->close();

		// Lob und Tadel bearbeiten
		$sql = $dbs->prepare("DELETE FROM lobtadel WHERE eintrag = ?");
		$sql->bind_param("i", $eintrag);
		$sql->execute();
		$sql->close();
		foreach ($lobtadel AS $lt) {
			$id = cms_generiere_kleinste_id('lobtadel', 's');
			if ($lt['person'] == '-') {
	    	$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = NULL, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	    	$sql->bind_param("issi", $eintrag, $lt['art'], $lt['bem'], $id);
			} else {
				$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = ?, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	    	$sql->bind_param("iissi", $eintrag, $lt['person'], $lt['art'], $lt['bem'], $id);
			}
	    $sql->execute();
	    $sql->close();
		}

		// Fehlzeiten bearbeiten
		$sql = $dbs->prepare("DELETE FROM fehlzeiten WHERE (von < ? AND bis > ?) OR (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) AND person IN (SELECT person FROM kursemitglieder WHERE id = ?)");
		$sql->bind_param("iiiiiii", $tbeginn, $tende, $tbeginn, $tende, $tbeginn, $tende, $tkurs);
		$sql->execute();
		$sql->close();
		foreach ($fehlzeiten AS $fz) {
			$id = cms_generiere_kleinste_id('fehlzeiten', 's');
	    $sql = $dbs->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	    $sql->bind_param("iiisi", $fz['person'], $fz['von'], $fz['bis'], $fz['bem'], $id);
	    $sql->execute();
	    $sql->close();
		}

		cms_trennen($dbs);
		echo "ERFOLG";
	} else {
		cms_trennen($dbs);
		echo "ZUORDNUNG";
	}
}
else {
	cms_trennen($dbs);
	echo "BERECHTIGUNG";
}
?>
