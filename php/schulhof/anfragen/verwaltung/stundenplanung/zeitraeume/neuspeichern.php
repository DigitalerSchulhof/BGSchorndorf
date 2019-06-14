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


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen'];

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
	$sql = "SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE ((beginn <= $beginn AND ende >= $beginn) OR (beginn <= $ende AND ende >= $ende)) AND schuljahr = $schuljahr";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($daten['anzahl'] > 0) {$fehler = true; echo "DOPPELT";}
		}
		$anfrage->free();
	}
	cms_trennen($dbs);

	// stunden überprüfen
	$schulstunden = array();
	if ($schulstundenanzahl > 0) {
		$sids = explode('|', $schulstundenids);
		$sqlwhere = substr(implode(' OR ', $sids), 4);
		for ($i=1; $i<count($sids); $i++) {
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
		$id = cms_generiere_kleinste_id('zeitraeume');
		// Blogeintrag in die Datenbank schreiben
		$dbs = cms_verbinden('s');
		$sql = "UPDATE zeitraeume SET schuljahr = $schuljahr, aktiv = $aktiv, beginn = $beginn, ende = $ende, mo = $mo, di = $di, mi = $mi, do = $do, fr = $fr, sa = $sa, so = $so WHERE id = $id";
		$dbs->query($sql);

		// Schulstunden eintragen
		for ($i=0; $i<count($schulstunden); $i++) {
			$sid = cms_generiere_kleinste_id('schulstunden');
			$sql = "UPDATE schulstunden SET zeitraum = $id, bezeichnung = AES_ENCRYPT('".$schulstunden[$i]['bezeichnung']."', '$CMS_SCHLUESSEL'), ";
			$sql .= "beginnstd = AES_ENCRYPT('".$schulstunden[$i]['beginnstd']."', '$CMS_SCHLUESSEL'), beginnmin = AES_ENCRYPT('".$schulstunden[$i]['beginnmin']."', '$CMS_SCHLUESSEL'), ";
			$sql .= "endestd = AES_ENCRYPT('".$schulstunden[$i]['endestd']."', '$CMS_SCHLUESSEL'), endemin = AES_ENCRYPT('".$schulstunden[$i]['endemin']."', '$CMS_SCHLUESSEL') ";
			$sql .= "WHERE id = $sid";
			$dbs->query($sql);
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
