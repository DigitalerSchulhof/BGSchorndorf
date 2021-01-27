<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['inhalt'])) 						{$inhalt = $_POST['inhalt'];} 										else {cms_anfrage_beenden(); exit;}
if (isset($_POST['hausaufgaben'])) 			{$hausaufgabe = $_POST['hausaufgaben'];} 					else {cms_anfrage_beenden(); exit;}
if (isset($_POST['leistungsmessung'])) 	{$leistungsmessung = $_POST['leistungsmessung'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['freigabe'])) 					{$freigabe = $_POST['freigabe'];}									else {cms_anfrage_beenden(); exit;}
if (isset($_POST['fzids'])) 						{$fzids = $_POST['fzids'];} 											else {cms_anfrage_beenden(); exit;}
if (isset($_POST['ltids'])) 						{$ltids = $_POST['ltids'];} 											else {cms_anfrage_beenden(); exit;}
if (isset($_POST['eintrag'])) 					{$eintrag = $_POST['eintrag'];} 									else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

if (!cms_check_ganzzahl($eintrag, 0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_toggle($leistungsmessung)) {cms_anfrage_beenden(); exit;}
if (!cms_check_toggle($freigabe)) {cms_anfrage_beenden(); exit;}
if (strlen($inhalt) == 0) {cms_anfrage_beenden(); exit;}

$dbs = cms_verbinden("s");
$dbl = cms_verbinden("l");

include_once("../../lehrerzimmer/anfragen/tagebuch/uebertragen.php");

$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
$sql->bind_param("i", $CMS_BENTUZERID);
if ($sql->execute()) {
	$sql->bind_result($CMS_BENUTZERART);
	$sql->fetch();
}
$sql->close();

$fids = explode("|", $fzids);
$lids = explode("|", $ltids);
$fehlzeiten = [];
$lobtadel = [];
$personen = [];


$tlehrer = null;
// Stundeninformationen laden
$sql = $dbs->prepare("SELECT id, tbeginn, tende, tkurs, tlehrer FROM unterricht WHERE id = ?");
$sql->bind_param("i", $eintrag);
if ($sql->execute()) {
	$sql->bind_result($uid, $tbeginn, $tende, $tkurs, $tlehrer);
	$sql->fetch();
}
$sql->close();

if ($tlehrer === null) {cms_anfrage_beenden(); exit;}

$t = date("d", $tbeginn);
$m = date("m", $tbeginn);
$j = date("Y", $tbeginn);
$a = mktime(0,0,0,$m,$t,$j);
$x = mktime(0,0,0,$m,$t+1,$j)-1;
for ($i=1; $i<count($fids); $i++) {
	$fzid = $fids[$i];
	$fehlzeiten[$fzid]['id'] = $fids[$i];
	if (isset($_POST["fzperson_".$fzid])) {$fehlzeiten[$fzid]['person'] = $_POST["fzperson_".$fzid];} else {cms_anfrage_beenden(); exit;}
	if (isset($_POST["fzzeitbh_".$fzid])) {$fehlzeiten[$fzid]['bh'] = $_POST["fzzeitbh_".$fzid];} else {cms_anfrage_beenden(); exit;}
	if (isset($_POST["fzzeitbm_".$fzid])) {$fehlzeiten[$fzid]['bm'] = $_POST["fzzeitbm_".$fzid];} else {cms_anfrage_beenden(); exit;}
	if (isset($_POST["fzzeiteh_".$fzid])) {$fehlzeiten[$fzid]['eh'] = $_POST["fzzeiteh_".$fzid];} else {cms_anfrage_beenden(); exit;}
	if (isset($_POST["fzzeitem_".$fzid])) {$fehlzeiten[$fzid]['em'] = $_POST["fzzeitem_".$fzid];} else {cms_anfrage_beenden(); exit;}
	if (isset($_POST["fzbemerkung_".$fzid])) {$fehlzeiten[$fzid]['bem'] = $_POST["fzbemerkung_".$fzid];} else {cms_anfrage_beenden(); exit;}

	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['person'],0)) {cms_anfrage_beenden(); exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bh'],0,23)) {cms_anfrage_beenden(); exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['bm'],0,59)) {cms_anfrage_beenden(); exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['eh'],0,23)) {cms_anfrage_beenden(); exit;}
	if (!cms_check_ganzzahl($fehlzeiten[$fzid]['em'],0,59)) {cms_anfrage_beenden(); exit;}
	if ($fehlzeiten[$fzid]['bh']*60+$fehlzeiten[$fzid]['bm'] >= $fehlzeiten[$fzid]['eh']*60+$fehlzeiten[$fzid]['em']) {cms_anfrage_beenden(); exit;}
	array_push($personen, $fehlzeiten[$fzid]['person']);
	$fehlzeiten[$fzid]['von'] = mktime($fehlzeiten[$fzid]['bh'], $fehlzeiten[$fzid]['bm'], 0, $m, $t, $j);
	$fehlzeiten[$fzid]['bis'] = mktime($fehlzeiten[$fzid]['eh'], $fehlzeiten[$fzid]['em'], 0, $m, $t, $j)-1;

	// Püfe, ob sich diese Fehlzeit mit den bisherigen überschneidet
	foreach ($fehlzeiten as $f) {
		if ($f['person'] == $fehlzeiten[$fzid]['person'] && $f['id'] != $fehlzeiten[$fzid]['id']) {
			if (($f['von'] <= $fehlzeiten[$fzid]['von'] && $f['bis'] >= $fehlzeiten[$fzid]['von']) ||
		      ($f['von'] <= $fehlzeiten[$fzid]['bis'] && $f['bis'] >= $fehlzeiten[$fzid]['bis'])) {
				cms_anfrage_beenden("FEHLZEIT"); exit;
			}
		}
	}
}

for ($i=1; $i<count($lids); $i++) {
	$ltid = $lids[$i];
	if (isset($_POST["ltperson_".$ltid])) {$lobtadel[$ltid]['person'] = $_POST["ltperson_".$ltid];} else {cms_anfrage_beenden();exit;}
	if (isset($_POST["ltart_".$ltid])) {$lobtadel[$ltid]['art'] = $_POST["ltart_".$ltid];} else {cms_anfrage_beenden();exit;}
	if (isset($_POST["ltbemerkung_".$ltid])) {$lobtadel[$ltid]['bem'] = $_POST["ltbemerkung_".$ltid];} else {cms_anfrage_beenden();exit;}
}
foreach ($lobtadel as $lt) {
	if (!cms_check_ganzzahl($lt['person'],0) && $lt['person'] != "a") {cms_anfrage_beenden();exit;}
	if ($lt['art'] != 'v' && $lt['art'] != 'm' && $lt['art'] != 'l') {cms_anfrage_beenden();exit;}
	if ($lt['person'] != 'a') {
		array_push($personen, $lt['person']);
	}
}
$pers = "";
if (count($personen) > 0) {
	$pers = "(".implode(",", $personen).")";
	if (!cms_check_idliste($pers)) {cms_anfrage_beenden();exit;}
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
		$update = false;
		$sql = $dbl->prepare("SELECT COUNT(*) FROM tagebuch WHERE id = ?");
		$sql->bind_param("i", $eintrag);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			$sql->fetch();
			if ($anzahl > 0) {$update = true;}
		}
		$sql->close();
		if ($update) {
			$sql = $dbl->prepare("UPDATE tagebuch SET inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), hausaufgabe = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), freigabe = ?, leistungsmessung = ?, urheber = ?, eintragsdatum = ? WHERE id = ?");
			$sql->bind_param("ssiiiii", $inhalt, $hausaufgabe, $freigabe, $leistungsmessung, $CMS_BENUTZERID, $jetzt, $eintrag);
		} else {
			$sql = $dbl->prepare("INSERT INTO tagebuch (id, inhalt, hausaufgabe, freigabe, leistungsmessung, urheber, eintragsdatum) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), ?, ?, ?, ?)");
			$sql->bind_param("issiiii", $eintrag, $inhalt, $hausaufgabe, $freigabe, $leistungsmessung, $CMS_BENUTZERID, $jetzt);
		}
		$sql->execute();
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM tagebuch WHERE id = ?");
		$sql->bind_param("i", $eintrag);
		$sql->execute();
		$sql->close();

		// Lob und Tadel bearbeiten
		// Lob und Tadel dieser Stunde laden
		$ltbestand = [];
		$ltbestandsids = [];
		$sql = $dbl->prepare("SELECT id, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM lobtadel WHERE eintrag = ?");
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
				$id = cms_generiere_kleinste_id('lobtadel', 'l');
				array_push($ltbearbeiten, $id);
				if ($lt['person'] == 'a') {
		    	$sql = $dbl->prepare("UPDATE lobtadel SET eintrag = ?, person = NULL, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
		    	$sql->bind_param("issiii", $eintrag, $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $id);
				} else {
					$sql = $dbl->prepare("UPDATE lobtadel SET eintrag = ?, person = ?, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
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
						$sql = $dbl->prepare("UPDATE lobtadel SET eintrag = ?, person = NULL, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
						$sql->bind_param("issiii", $eintrag, $lobtadel[$ltid]['art'], $lobtadel[$ltid]['bem'], $CMS_BENUTZERID, $jetzt, $ltid);
					} else {
						$sql = $dbl->prepare("UPDATE lobtadel SET eintrag = ?, person = ?, art = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
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
			$sql = $dbl->prepare("DELETE FROM lobtadel WHERE eintrag = ? AND id NOT IN $ltb");
			$sql->bind_param("i", $eintrag);
			$sql->execute();
			$sql->close();
		} else {
			$sql = $dbl->prepare("DELETE FROM lobtadel WHERE eintrag = ?");
			$sql->bind_param("i", $eintrag);
			$sql->execute();
			$sql->close();
		}


		// FEHLZEITEN
		// Personen des Kurses laden
		$pimkurs = [];
		$sql = $dbs->prepare("SELECT person FROM kursemitglieder WHERE gruppe = ?");
		$sql->bind_param("i", $tkurs);
		if ($sql->execute()) {
			$sql->bind_result($pik);
			while ($sql->fetch()) {
				array_push($pimkurs, $pik);
			}
		}
		$sql->close();
		// Fehlzeiten dieses Tages laden
		$fzbestand = [];
		$fzbestandsids = [];
		if (count($pimkurs) > 0) {
			$pimkurs = "(".implode(",", $pimkurs).")";
			$sql = $dbl->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN $pimkurs");
			$sql->bind_param("iiii", $a, $x, $a, $x);
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
					$id = cms_generiere_kleinste_id('fehlzeiten', 'l');
					array_push($fzbearbeiten, $id);
			    $sql = $dbl->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
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
						$sql = $dbl->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), urheber = ?, eintragszeit = ? WHERE id = ?");
						$sql->bind_param("iiisiii", $fehlzeiten[$fzid]['person'], $fehlzeiten[$fzid]['von'], $fehlzeiten[$fzid]['bis'], $fehlzeiten[$fzid]['bem'], $CMS_BENUTZERID, $jetzt, $fzid);
				    $sql->execute();
				    $sql->close();
					}
				}
			}

			// Fehlzeiten löschen
			if (count($fzbearbeiten) > 0) {
				$fzb = "(".implode(",", $fzbearbeiten).")";
				$sql = $dbl->prepare("DELETE FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN $pimkurs AND id NOT IN $fzb");
				$sql->bind_param("iiii", $a, $x, $a, $x);
				$sql->execute();
				$sql->close();
			} else {
				$sql = $dbl->prepare("DELETE FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?)) AND person IN $pimkurs");
				$sql->bind_param("iiii", $a, $x, $a, $x);
				$sql->execute();
				$sql->close();
			}
		}

		cms_lehrerdb_header(true);
		echo "ERFOLG";
	} else {
		cms_lehrerdb_header(false);
		echo "FEHLERZUORDNUNG";
	}
}
else {
	cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbl);
cms_trennen($dbs);
?>
