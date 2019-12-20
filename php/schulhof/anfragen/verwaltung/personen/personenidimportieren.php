<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['csv'])) {$csv = $_POST['csv'];} else {echo "FEHLER"; exit;}
if (isset($_POST['trennung'])) {$trennung = $_POST['trennung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['personenart'])) {$personenart = $_POST['personenart'];} else {echo "FEHLER"; exit;}
if (isset($_POST['idslot'])) {$idslot = $_POST['idslot'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vornach'])) {$vornach = $_POST['vornach'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachvor'])) {$nachvor = $_POST['nachvor'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vor'])) {$vor = $_POST['vor'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nach'])) {$nach = $_POST['nach'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Personenids importieren'];

if (cms_angemeldet() && $zugriff) {

	$fehler = false;

	if (strlen($csv) == 0) {$fehler = true;}
	if (strlen($trennung) == 0) {$fehler = true;}
	if (($personenart != 's') && ($personenart != 'l') && ($personenart != 'e') && ($personenart != 'x') && ($personenart != 'v')) {$fehler = true;}
	if (($idslot != 'zweitid') && ($idslot != 'drittid') && ($idslot != 'viertid')) {$fehler = true;}

	if (!$fehler) {
		$maxspalten = 0;
		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$aktspalten = count(explode($trennung, $csvteil));
			if ($aktspalten > $maxspalten) {$maxspalten = $aktspalten;}
		}

		if (!cms_check_ganzzahl($id, 1, $maxspalten)) {$fehler = true;}
		if (!cms_check_ganzzahl($vornach, 1, $maxspalten) && ($vornach != '-')) {$fehler = true;}
		if (!cms_check_ganzzahl($nachvor, 1, $maxspalten) && ($nachvor != '-')) {$fehler = true;}
		if (!cms_check_ganzzahl($nach, 1, $maxspalten) && ($nach != '-')) {$fehler = true;}
		if (!cms_check_ganzzahl($vor, 1, $maxspalten) && ($vor != '-')) {$fehler = true;}
		$kombinationsfehler = true;
		if ((($vornach != '-') && ($nachvor == '-') && ($vor == '-') && ($nach == '-')) ||
		    (($vornach == '-') && ($nachvor != '-') && ($vor == '-') && ($nach == '-')) ||
		 	  (($vornach == '-') && ($nachvor == '-') && ($vor != '-') && ($nach != '-')))
		{$kombinationsfehler = false;}
		if ($kombinationsfehler) {$fehler = true;}
	}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
		$PERSONEN = array();

		$csvanalyse = explode("\n", $csv);
		foreach ($csvanalyse as $csvteil) {
			$daten = explode($trennung, $csvteil);
			$P = array();
			$P['id'] = $daten[$id-1];
			if ($vornach != '-') {
				$name = explode(', ', $daten[$vornach-1]);
				$P['vor'] = $name[0];
				$P['nach'] = $name[1];
			}
			else if ($nachvor != '-') {
				$name = explode(', ', $daten[$nachvor-1]);
				$P['vor'] = $name[1];
				$P['nach'] = $name[0];
			}
			else {
				$P['vor'] = $daten[$vor-1];
				$P['nach'] = $daten[$nach-1];
			}
			if (!in_array($P, $PERSONEN)) {array_push($PERSONEN, $P);}
		}
	}

	if (!$fehler) {
		$PERSONENFEHLER = array();
		$PERSONENIDS = array();
		// Prüfen, ob all diese Entitäten existieren
		// Personen
		$sql = $dbs->prepare("SELECT id FROM personen WHERE vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		foreach ($PERSONEN as $P) {
			$sql->bind_param("sss", $P['vor'], $P['nach'], $personenart);
			if ($sql->execute()) {
				$sql->bind_result($checkid);
				while ($sql->fetch()) {
					if (!in_array($checkid, $PERSONENIDS)) {array_push($PERSONENIDS, $checkid);}
				}
			}
		}
		$sql->close();

		if (count($PERSONENIDS) > 0) {
			$idsuche = implode(',', $PERSONENIDS);
			$sql = $dbs->prepare("UPDATE personen SET $idslot = NULL WHERE id IN ($idsuche)");
			$sql->execute();
			$sql->close();
		}

		// IDs eintragen
		$sql = $dbs->prepare("UPDATE personen SET $idslot = ? WHERE id != 0 AND id IN (SELECT id FROM (SELECT id, COUNT(*) FROM personen WHERE vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND $idslot IS NULL) AS x)");
		foreach ($PERSONEN AS $P) {
			$sql->bind_param("isss", $P['id'], $P['vor'], $P['nach'], $personenart);
			$sql->execute();
			if ($sql->affected_rows == 0) {array_push($PERSONENFEHLER, $P);}
		}
		$sql->close();


		echo "ERFOLG\n\n\n";
		$FEHLERTEXT = "";
		foreach ($PERSONENFEHLER AS $P) {
			$FEHLERTEXT .= "<li>".$P['vor']." ".$P['nach']."</li>";
		}
		echo $FEHLERTEXT;
	}
	cms_trennen($dbs);
}
else {
	echo "FEHLER";
}
?>
