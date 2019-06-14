<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnT'])) 	{$beginnT = $_POST['beginnT'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnM'])) 	{$beginnM = $_POST['beginnM'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnJ'])) 	{$beginnJ = $_POST['beginnJ'];} else {echo "FEHLER";exit;}
if (isset($_POST['endeT'])) 		{$endeT = $_POST['endeT'];} else {echo "FEHLER";exit;}
if (isset($_POST['endeM'])) 		{$endeM = $_POST['endeM'];} else {echo "FEHLER";exit;}
if (isset($_POST['endeJ'])) 		{$endeJ = $_POST['endeJ'];} else {echo "FEHLER";exit;}
if (isset($_POST['aktiv'])) 		{$aktiv = $_POST['aktiv'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagmo'])) 		{$mo = $_POST['tagmo'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagdi'])) 		{$di = $_POST['tagdi'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagmi'])) 		{$mi = $_POST['tagmi'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagdo'])) 		{$do = $_POST['tagdo'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagfr'])) 		{$fr = $_POST['tagfr'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagsa'])) 		{$sa = $_POST['tagsa'];} else {echo "FEHLER";exit;}
if (isset($_POST['tagso'])) 		{$so = $_POST['tagso'];} else {echo "FEHLER";exit;}
if (isset($_POST['schulstundenanzahl'])) {$schulstundenanzahl = $_POST['schulstundenanzahl'];} else {echo "FEHLER";exit;}
if (isset($_POST['schulstundenids'])) {$schulstundenids = $_POST['schulstundenids'];} else {echo "FEHLER";exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$id = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (($aktiv != 0) && ($aktiv != 1) || ($mo != 0) && ($mo != 1) || ($di != 0) && ($di != 1) ||
	    ($mi != 0) && ($mi != 1) || ($do != 0) && ($do != 1) || ($fr != 0) && ($fr != 1) ||
			($sa != 0) && ($sa != 1) || ($so != 0) && ($so != 1)) {
		$fehler = true;
	}

	$beginn = mktime(0, 0, 0, $beginnM, $beginnT, $beginnJ);
	$ende = mktime(23, 59, 59, $endeM, $endeT, $endeJ);

	if ($beginn > $ende) {$fehler = true;}

	// Prüfen, ob der Zeitraum im gewählten Schuljahr liegt
	$dbs = cms_verbinden('s');
	$sql = "SELECT COUNT(*) AS anzahl FROM schuljahre WHERE beginn <= $beginn AND ende >= $beginn AND ende >= $ende AND beginn <= $ende AND id = $schuljahr";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($daten['anzahl'] != 1) {$fehler = true; echo "SCHULJAHR";}
		}
		$anfrage->free();
	}

	// Prüfen, ob im Schuljahr andere Zeiträume existieren, die dieses Schuljahr enthalten
	$sql = "SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE ((beginn <= $beginn AND ende >= $beginn) OR (beginn <= $ende AND ende >= $ende)) AND schuljahr = $schuljahr AND id != $id";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($daten['anzahl'] > 0) {$fehler = true; echo "DOPPELT";}
		}
		$anfrage->free();
	}

	// Zeitraum laden
	$sql = "SELECT id, schuljahr, beginn, ende, mo, di, mi, do, fr, sa, so FROM zeitraeume WHERE id = $id";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$zeitraum = $daten;
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}
	cms_trennen($dbs);

	// stunden überprüfen
	$schulstunden = array();
	if ($schulstundenanzahl > 0) {
		$sids = explode('|', $schulstundenids);
		$sqlwhere = substr(implode(' OR ', $sids), 4);
		for ($i=1; $i<count($sids); $i++) {
			$schulstunden[$i-1]['id'] = $sids[$i];
			if (isset($_POST["sbezeichnung_".$sids[$i]])) {$schulstunden[$i-1]['bezeichnung'] = cms_texttrafo_e_db($_POST["sbezeichnung_".$sids[$i]]);} else {echo "FEHLER"; exit;}
			if (strlen($schulstunden[$i-1]['bezeichnung']) == 0) {$fehler = true;}

			if (isset($_POST["sbeginnstd_".$sids[$i]])) {$schulstunden[$i-1]['beginnstd'] = $_POST["sbeginnstd_".$sids[$i]];} else {echo "FEHLER"; exit;}
			if (isset($_POST["sbeginnmin_".$sids[$i]])) {$schulstunden[$i-1]['beginnmin'] = $_POST["sbeginnmin_".$sids[$i]];} else {echo "FEHLER"; exit;}
			$schulstunden[$i-1]['beginn'] = mktime($schulstunden[$i-1]['beginnstd'], $schulstunden[$i-1]['beginnmin'], 0, 1, 1, 2000);
			if (isset($_POST["sendestd_".$sids[$i]])) {$schulstunden[$i-1]['endestd'] = $_POST["sendestd_".$sids[$i]];} else {echo "FEHLER"; exit;}
			if (isset($_POST["sendemin_".$sids[$i]])) {$schulstunden[$i-1]['endemin'] = $_POST["sendemin_".$sids[$i]];} else {echo "FEHLER"; exit;}
			$schulstunden[$i-1]['ende'] = mktime($schulstunden[$i-1]['endestd'], $schulstunden[$i-1]['endemin'], 0, 1, 1, 2000);
			if ($schulstunden[$i-1]['beginn'] > $schulstunden[$i-1]['ende']) {echo "FEHLER"; exit;}
		}
	}

	// prüfen, ob sich schulstunden überschneiden
	for ($i = 0; $i<count($schulstunden); $i++) {
		for ($j = $i+1; $j<count($schulstunden); $j++) {
			if ((($schulstunden[$j]['beginn'] < $schulstunden[$i]['beginn']) && ($schulstunden[$j]['ende'] > $schulstunden[$i]['beginn'])) ||
				  (($schulstunden[$j]['beginn'] < $schulstunden[$i]['ende']) && ($schulstunden[$j]['ende'] > $schulstunden[$i]['ende']))) {
				$fehler = true;
				echo "STUNDEN";
			}
		}
	}

	if (!$fehler) {

		$jetzt = time();

		$dbs = cms_verbinden('s');
		$sql = "UPDATE zeitraeume SET schuljahr = $schuljahr, aktiv = $aktiv, beginn = $beginn, ende = $ende, mo = $mo, di = $di, mi = $mi, do = $do, fr = $fr, sa = $sa, so = $so WHERE id = $id";
		$dbs->query($sql);

		// Alte Schulstunden dieses Zeitraums laden
		$sstds = array();
		$sql = "SELECT id, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE zeitraum = $id";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sstds[$daten['id']]['bs'] = $daten['bs'];
				$sstds[$daten['id']]['bm'] = $daten['bm'];
				$sstds[$daten['id']]['es'] = $daten['es'];
				$sstds[$daten['id']]['em'] = $daten['em'];
			}
			$anfrage->free();
		}

		// Schulstunden eintragen
		$geaendertsql = "";
		for ($i=0; $i<count($schulstunden); $i++) {
			if (substr($schulstunden[$i]['id'], 0, 4) != 'temp') {
				$geaendertsql .= " AND id != ".$schulstunden[$i]['id'];
				$sql = "UPDATE schulstunden SET zeitraum = $id, bezeichnung = AES_ENCRYPT('".$schulstunden[$i]['bezeichnung']."', '$CMS_SCHLUESSEL'), ";
				$sql .= "beginnstd = AES_ENCRYPT('".$schulstunden[$i]['beginnstd']."', '$CMS_SCHLUESSEL'), beginnmin = AES_ENCRYPT('".$schulstunden[$i]['beginnmin']."', '$CMS_SCHLUESSEL'), ";
				$sql .= "endestd = AES_ENCRYPT('".$schulstunden[$i]['endestd']."', '$CMS_SCHLUESSEL'), endemin = AES_ENCRYPT('".$schulstunden[$i]['endemin']."', '$CMS_SCHLUESSEL') ";
				$sql .= "WHERE id = ".$schulstunden[$i]['id'];
				$dbs->query($sql);

				// Update Zeiten bestehender Stunden
				$bdifferenzstd = $sstds[$schulstunden[$i]['id']]['bs'] - $schulstunden[$i]['beginnstd'];
				$bdifferenzmin = $sstds[$schulstunden[$i]['id']]['bm'] - $schulstunden[$i]['beginnmin'];
				$bdifferenz = $bdifferenzmin*60 + $bdifferenzstd*60*60;
				$edifferenzstd = $sstds[$schulstunden[$i]['id']]['es'] - $schulstunden[$i]['endestd'];
				$edifferenzmin = $sstds[$schulstunden[$i]['id']]['em'] - $schulstunden[$i]['endemin'];
				$edifferenz = $edifferenzmin*60 + $edifferenzstd*60*60;
				$sql = "UPDATE tagebuch_".$zeitraum['schuljahr']." SET beginn = beginn - $bdifferenz, tbeginn = tbeginn - $bdifferenz, ende = ende - $edifferenz, tende = tende - $edifferenz WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND stunde = ".$schulstunden[$i]['id'];
				$dbs->query($sql);
			}
			else {
				$sid = cms_generiere_kleinste_id('schulstunden');
				$geaendertsql .= " AND id != ".$sid;
				$sql = "UPDATE schulstunden SET zeitraum = $id, bezeichnung = AES_ENCRYPT('".$schulstunden[$i]['bezeichnung']."', '$CMS_SCHLUESSEL'), ";
				$sql .= "beginnstd = AES_ENCRYPT('".$schulstunden[$i]['beginnstd']."', '$CMS_SCHLUESSEL'), beginnmin = AES_ENCRYPT('".$schulstunden[$i]['beginnmin']."', '$CMS_SCHLUESSEL'), ";
				$sql .= "endestd = AES_ENCRYPT('".$schulstunden[$i]['endestd']."', '$CMS_SCHLUESSEL'), endemin = AES_ENCRYPT('".$schulstunden[$i]['endemin']."', '$CMS_SCHLUESSEL') ";
				$sql .= "WHERE id = $sid";
				$dbs->query($sql);
			}
		}

		// Löschen nicht mehr benötigter Schulstunden
		$sql = "DELETE FROM schulstunden WHERE zeitraum = $id".$geaendertsql;
		$dbs->query($sql);

		// Löschen aus Tagebüchern alle Stunden, die in Schulstunden stattfinden die nicht mehr existieren
		$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id".str_replace('id !=', 'stunde !=', $geaendertsql);
		$dbs->query($sql);

		// Müssen Stunden aufgrund der Zeitraumveränderung gelöscht werden?
		// Neuer Beginn ist später
		if ($zeitraum['beginn'] < $beginn) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND beginn < $beginn";
			$dbs->query($sql);
		}
		// Neues Ende ist früher
		if ($zeitraum['ende'] > $ende) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND ende > $ende";
			$dbs->query($sql);
		}

		// Müssen Stunden aufgrund von veränderten Schultagen gelöscht werden
		if ($zeitraum['mo'] > $mo) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 1";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 1";$dbs->query($sql);
		}
		if ($zeitraum['di'] > $di) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 2";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 2";$dbs->query($sql);
		}
		if ($zeitraum['mi'] > $mi) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 3";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 3";$dbs->query($sql);
		}
		if ($zeitraum['do'] > $do) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 4";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 4";$dbs->query($sql);
		}
		if ($zeitraum['fr'] > $fr) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 5";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 5";$dbs->query($sql);
		}
		if ($zeitraum['sa'] > $sa) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 6";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 6";$dbs->query($sql);
		}
		if ($zeitraum['so'] > $so) {
			$sql = "DELETE FROM tagebuch_".$zeitraum['schuljahr']." WHERE beginn > $jetzt AND vertretungsplan != 1 AND zeitraum = $id AND tag = 7";$dbs->query($sql);
			$sql = "DELETE FROM stunden WHERE zeitraum = $id AND tag = 7";$dbs->query($sql);
		}

		// Wochenabstand 60 sek = 1 min * 60 = 1 h * 24 = 1 d * 7 = 1 w
		$abstand = 60 * 60 * 24 * 7;
		$schulstunden = array();
		// Schulstunden dieses Zeitraums mit neuen Ids laden
		$sql = "SELECT id, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE zeitraum = $id";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schulstunden[$daten['id']]['bs'] = $daten['bs'];
				$schulstunden[$daten['id']]['bm'] = $daten['bm'];
				$schulstunden[$daten['id']]['es'] = $daten['es'];
				$schulstunden[$daten['id']]['em'] = $daten['em'];
			}
			$anfrage->free();
		}


		// Müssen Stunden aufgrund der Zeitraumveränderung hinzugefügt werden
		// Zeitraum beginnt früher als bisher und diese Zeit ist noch nicht vergangen
		$start = max($beginn, $jetzt);
		if ($start < $zeitraum['beginn']) {
			// Betroffene Stunden suchen
			$sql = "SELECT * FROM stunden WHERE zeitraum = $id";
			if ($anfrage = $dbs->query($sql)) {
				while ($daten = $anfrage->fetch_assoc()) {
					$tag = $daten['tag'];

					$startTnr = date('N', $start);
					$startT = date('j', $start);
					$startM = date('n', $start);
					$startJ = date('Y', $start);

					if ($startTnr < $tag) {$startT = $startT + ($tag-$startTnr);}
					else if ($tag < $startTnr) {$startT = $startT + 7 - ($startTnr-$tag);}

					$sbeginn = mktime($schulstunden[$daten['id']]['bs'], $schulstunden[$daten['id']]['bm'], 0, $startM, $startT, $startJ);
					$sende = mktime($schulstunden[$daten['id']]['es'], $schulstunden[$daten['id']]['em'], 0, $startM, $startT, $startJ);

					if ($sbeginn < $jetzt) {
						$sbeginn += $abstand;
						$sende += $abstand;
					}
					while ($sende < $zeitraum['beginn']) {
						$tid = cms_generiere_kleinste_id("tagebuch_".$zeitraum['schuljahr']);
						$sql = "UPDATE tagebuch_".$zeitraum['schuljahr']." SET lehrkraft = ".$daten['lehrkraft'].", raum = ".$daten['raum'].", kurs = ".$daten['kurs'].", tag = ".$daten['tag'].", stunde = ".$daten['stunde'].", beginn = $sbeginn, ende = $sende, tbeginn = $sbeginn, tende = $sende, zeitraum = ".$zeitraum['id']." WHERE id = $tid";
						$dbs->query($sql);

						$sbeginn += $abstand;
						$sende += $abstand;
					}
				}
				$anfrage->free();
			}
		}


		// Zeitraum endet später als bisher und diese Zeit ist noch nicht vergangen
		$start = max($zeitraum['ende'], $jetzt);
		if ($start < $ende) {
			// Betroffene Stunden suchen
			$sql = "SELECT * FROM stunden WHERE zeitraum = $id";
			if ($anfrage = $dbs->query($sql)) {
				while ($daten = $anfrage>fetch_assoc()) {
					$tag = $daten['tag'];

					$startTnr = date('N', $start);
					$startT = date('j', $start);
					$startM = date('n', $start);
					$startJ = date('Y', $start);

					if ($startTnr < $tag) {$startT = $startT + ($tag-$startTnr);}
					else if ($tag < $startTnr) {$startT = $startT + 7 - ($startTnr-$tag);}

					$sbeginn = mktime($schulstunden[$daten['id']]['bs'], $schulstunden[$daten['id']]['bm'], 0, $startM, $startT, $startJ);
					$sende = mktime($schulstunden[$daten['id']]['es'], $schulstunden[$daten['id']]['em'], 0, $startM, $startT, $startJ);

					if ($sbeginn < $jetzt) {
						$sbeginn += $abstand;
						$sende += $abstand;
					}
					while ($sende < $ende) {
						$tid = cms_generiere_kleinste_id("tagebuch_".$zeitraum['schuljahr']);
						$sql = "UPDATE tagebuch_".$zeitraum['schuljahr']." SET lehrkraft = ".$daten['lehrkraft'].", raum = ".$daten['raum'].", tlehrkraft = ".$daten['lehrkraft'].", traum = ".$daten['raum'].", kurs = ".$daten['kurs'].", tag = ".$daten['tag'].", stunde = ".$daten['stunde'].", tstunde = ".$daten['stunde'].", beginn = $sbeginn, ende = $sende, zeitraum = ".$zeitraum['id']." WHERE id = $tid";
						$dbs->query($sql);

						$sbeginn += $abstand;
						$sende += $abstand;
					}
				}
				$anfrage->free();
			}
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
?>
