<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['inhalt'])) {$inhalt = $_POST['inhalt'];} else {echo "FEHLER";exit;}
if (isset($_POST['hausaufgaben'])) {$hausaufgabe = $_POST['hausaufgaben'];} else {echo "FEHLER";exit;}
if (isset($_POST['leistungsmessung'])) {$leistungsmessung = $_POST['leistungsmessung'];} else {echo "FEHLER";exit;}
if (isset($_POST['freigabe'])) {$freigabe = $_POST['freigabe'];} else {echo "FEHLER";exit;}
if (isset($_POST['fzids'])) {$fzids = $_POST['fzids'];} else {echo "FEHLER";exit;}
if (isset($_POST['ltids'])) {$ltids = $_POST['ltids'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['TAGEBUCHEINTRAG'])) {$eintrag = $_SESSION['TAGEBUCHEINTRAG'];} else {echo "FEHLER";exit;}
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_BENUTZERID = $_SESSION['BENUTZERID'];

if (!cms_check_ganzzahl($eintrag, 0)) {echo "FEHLER";exit;}
if (!cms_check_toggle($leistungsmessung)) {echo "FEHLER";exit;}
if (!cms_check_toggle($freigabe)) {echo "FEHLER";exit;}
if (strlen($inhalt) == 0) {echo "FEHLER";exit;}
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

if ($tlehrer === null) {echo "FEHLER";exit;}

$t = date("d", $tbeginn);
$m = date("m", $tbeginn);
$j = date("Y", $tbeginn);
$a = mktime(0,0,0,$m,$t,$j);
$x = mktime(0,0,0,$m,$t+1,$j)-1;
for ($i=1; $i<count($fids); $i++) {
	$fzid = $fids[$i];
	$fehlzeiten[$fzid]['id'] = $fids[$i];
	if (isset($_POST["fzperson_".$fzid])) {$fehlzeiten[$fzid]['person'] = $_POST["fzperson_".$fzid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["fzzeitbh_".$fzid])) {$fehlzeiten[$fzid]['bh'] = $_POST["fzzeitbh_".$fzid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["fzzeitbm_".$fzid])) {$fehlzeiten[$fzid]['bm'] = $_POST["fzzeitbm_".$fzid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["fzzeiteh_".$fzid])) {$fehlzeiten[$fzid]['eh'] = $_POST["fzzeiteh_".$fzid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["fzzeitem_".$fzid])) {$fehlzeiten[$fzid]['em'] = $_POST["fzzeitem_".$fzid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["fzbemerkung_".$fzid])) {$fehlzeiten[$fzid]['bem'] = $_POST["fzbemerkung_".$fzid];} else {echo "FEHLER"; exit;}

	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['person'],0)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bh'],0,23)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bm'],0,59)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['eh'],0,23)) {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['em'],0,59)) {echo "FEHLER"; exit;}
	if ($fehlzeiten[$fzid]['bh']*60+$fehlzeiten[$fzid]['bm'] >= $fehlzeiten[$fzid]['eh']*60+$fehlzeiten[$fzid]['em']) {echo "FEHLER"; exit;}
	array_push($personen, $fehlzeiten[$fzid]['person']);
	$fehlzeiten[$fzid]['von'] = mktime($fehlzeiten[$fzid]['bh'], $fehlzeiten[$fzid]['bm'], 0, $m, $t, $j);
	$fehlzeiten[$fzid]['bis'] = mktime($fehlzeiten[$fzid]['eh'], $fehlzeiten[$fzid]['em'], 0, $m, $t, $j)-1;

	if ($fehlzeiten[$fzid]['von'] < $tbeginn || $fehlzeiten[$fzid]['bis'] > $tende) {
		echo "FEHLZEIT"; exit;
	}

	// Püfe, ob sich diese Fehlzeit mit den bisherigen überschneidet
	foreach ($fehlzeiten as $f) {
		if ($f['person'] == $fehlzeiten[$fzid]['person'] && $f['id'] != $fehlzeiten[$fzid]['id']) {
			if (($f['von'] <= $fehlzeiten[$fzid]['von'] && $f['bis'] >= $fehlzeiten[$fzid]['von']) ||
		      ($f['von'] <= $fehlzeiten[$fzid]['bis'] && $f['bis'] >= $fehlzeiten[$fzid]['bis'])) {
				echo "FEHLZEIT"; exit;
			}
		}
	}
}

for ($i=1; $i<count($lids); $i++) {
	$ltid = $lids[$i];
	if (isset($_POST["ltperson_".$ltid])) {$lobtadel[$ltid]['person'] = $_POST["ltperson_".$ltid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["ltart_".$ltid])) {$lobtadel[$ltid]['art'] = $_POST["ltart_".$ltid];} else {echo "FEHLER"; exit;}
	if (isset($_POST["ltbemerkung_".$ltid])) {$lobtadel[$ltid]['bem'] = $_POST["ltbemerkung_".$ltid];} else {echo "FEHLER"; exit;}
}
foreach ($lobtadel as $lt) {
	if (!cms_check_ganzzahl($lt['person'],0) && $lt['person'] != "a") {echo "FEHLER"; exit;}
	if ($lt['art'] != 'v' && $lt['art'] != 'm' && $lt['art'] != 'l') {echo "FEHLER"; exit;}
	if ($lt['person'] != 'a') {
		array_push($personen, $lt['person']);
	}
}
$pers = "";
if (count($personen) > 0) {
	$pers = "(".implode(",", $personen).")";
	if (!cms_check_idliste($pers)) {echo "FEHLER"; exit;}
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
		$jetzt = time();
		// Änderung am Tagebucheintrag vornehmen
		$sql = $dbs->prepare("UPDATE tagebuch SET inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), hausaufgabe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), freigabe = ?, leistungsmessung = ?, urheber = ?, eintragsdatum = ? WHERE id = ?");
		$sql->bind_param("ssiiiii", $inhalt, $hausaufgabe, $freigabe, $leistungsmessung, $CMS_BENUTZERID, $jetzt, $eintrag);
		$sql->execute();
		$sql->close();

		// Lob und Tadel bearbeiten
		// Lob und Tadel dieser Stunde laden
		$ltbestand = [];
		$ltbestandsids = [];
		$sql = $dbs->prepare("SELECT id, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM lobtadel WHERE eintrag = ?");
		$sql->bind_param("i", $eintrag);
		if ($sql->execute()) {
			$sql->bind_result($ltid, $ltperson, $ltart, $ltbem);
			while ($sql->fetch()) {
				if ($ltperson === null) {$ltperson = 'a';}
				$ltbestand[$ltid]['person'] = $ltperson;
				$ltbestand[$ltid]['art'] = $ltart;
				$ltbestand[$ltid]['bem'] = $ltbem;
				array_push($ltbestandsids, $ltid);
			}
		}
		$sql->close();

		// Lob und Tadel bearbeiten
		$ltbearbeiten = [];
		for ($i=1; $i<count($lids); $i++) {
			$ltid = $lids[$i];
			// Neuen Lob-Tadel-Eintrag anlegen
			if (substr($ltid,0,4) == 'temp') {
				$id = cms_generiere_kleinste_id('lobtadel', 's');
				array_push($ltbearbeiten, $id);
				if ($lt['person'] == 'a') {
		    	$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = NULL, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
		    	$sql->bind_param("issiii", $eintrag, $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $id);
				} else {
					$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = ?, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
		    	$sql->bind_param("iissiii", $eintrag, $lobtadel[$ltid]['person'], $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $id);
				}
		    $sql->execute();
		    $sql->close();
			} else if (cms_check_ganzzahl($ltid,0) && in_array($ltid, $ltbestandsids)) {
				array_push($ltbearbeiten, $ltid);
				// Prüfen, ob Änderung erfolgte
				if ($ltbestand[$ltid]['person'] != $lobtadel[$ltid]['person'] ||
			      $ltbestand[$ltid]['art'] != $lobtadel[$ltid]['art'] ||
					  $ltbestand[$ltid]['bem'] != $lobtadel[$ltid]['bem']) {
					if ($lobtadel[$ltid]['person'] == 'a') {
						$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = NULL, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
						$sql->bind_param("issiii", $eintrag, $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $ltid);
					} else {
						$sql = $dbs->prepare("UPDATE lobtadel SET eintrag = ?, person = ?, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
						$sql->bind_param("iissiii", $eintrag, $lobtadel[$ltid]['person'], $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $ltid);
					}
			    $sql->execute();
			    $sql->close();
				}
			}
		}

		// Lob und Tadel löschen
		if (count($ltbearbeiten) > 0) {
			$ltb = "(".implode(",", $ltbearbeiten).")";
			$sql = $dbs->prepare("DELETE FROM lobtadel WHERE eintrag = ? AND id NOT IN $ltb");
			$sql->bind_param("i", $eintrag);
			$sql->execute();
			$sql->close();
		} else {
			$sql = $dbs->prepare("DELETE FROM lobtadel WHERE eintrag = ?");
			$sql->bind_param("i", $eintrag);
			$sql->execute();
			$sql->close();
		}


		// FEHLZEITEN
		// Fehlzeiten dieses Tages laden
		$fzbestand = [];
		$fzbestandsids = [];
		$sql = $dbs->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL') FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN (SELECT person FROM kursemitglieder WHERE gruppe = ?)");
		$sql->bind_param("iiiii", $a, $x, $a, $x, $tkurs);
		if ($sql->execute()) {
			$sql->bind_result($fzid, $fzperson, $fzvon, $fzbis, $fzbem);
			while ($sql->fetch()) {
				$fzbestand[$fzid]['person'] = $fzperson;
  			$fzbestand[$fzid]['von'] = $fzvon;
  			$fzbestand[$fzid]['bis'] = $fzbis;
  			$fzbestand[$fzid]['bem'] = $fzbem;
  			array_push($fzbestandsids, $fzid);
			}
		}
		$sql->close();


		// Fehlzeiten bearbeiten
		$fzbearbeiten = [];
		for ($i=1; $i<count($fids); $i++) {
			$fzid = $fids[$i];
			// Neuen Fehlzeiten-Eintrag anlegen
			if (substr($fzid,0,4) == 'temp') {
				$id = cms_generiere_kleinste_id('fehlzeiten', 's');
				array_push($fzbearbeiten, $id);
		    $sql = $dbs->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
		    $sql->bind_param("iiisiii", $fehlzeiten[$fzid]['person'], $fehlzeiten[$fzid]['von'], $fehlzeiten[$fzid]['bis'], $fehlzeiten[$fzid]['bem'], $CMS_BENUTZERID, $jetzt, $id);
		    $sql->execute();
		    $sql->close();
			} else if (cms_check_ganzzahl($fzid,0) && in_array($fzid, $fzbestandsids)) {
				array_push($fzbearbeiten, $fzid);
				// Prüfen, ob Änderung erfolgte
				if ($fzbestand[$fzid]['person'] != $fehlzeiten[$fzid]['person'] ||
			      $fzbestand[$fzid]['von'] != $fehlzeiten[$fzid]['von'] ||
					  $fzbestand[$fzid]['bis'] != $fehlzeiten[$fzid]['bis'] ||
					  $fzbestand[$fzid]['bem'] != $fehlzeiten[$fzid]['bem']) {
					$sql = $dbs->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), urheber = ?, eintragszeit = ? WHERE id = ?");
					$sql->bind_param("iiisiii", $fehlzeiten[$fzid]['person'], $fehlzeiten[$fzid]['von'], $fehlzeiten[$fzid]['bis'], $fehlzeiten[$fzid]['bem'], $CMS_BENUTZERID, $jetzt, $fzid);
			    $sql->execute();
			    $sql->close();
				}
			}
		}

		// Fehlzeiten löschen
		if (count($fzbearbeiten) > 0) {
			$fzb = "(".implode(",", $fzbearbeiten).")";
			$sql = $dbs->prepare("DELETE FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN (SELECT person FROM kursemitglieder WHERE gruppe = ?) AND id NOT IN $fzb");
			$sql->bind_param("iiiii", $a, $x, $a, $x, $tkurs);
			$sql->execute();
			$sql->close();
		} else {
			$sql = $dbs->prepare("DELETE FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN (SELECT person FROM kursemitglieder WHERE gruppe = ?)");
			$sql->bind_param("iiiii", $a, $x, $a, $x, $tkurs);
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
