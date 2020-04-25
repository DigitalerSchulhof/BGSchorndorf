<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnj'])) {$beginnj = $_POST['beginnj'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnm'])) {$beginnm = $_POST['beginnm'];} else {echo "FEHLER";exit;}
if (isset($_POST['beginnt'])) {$beginnt = $_POST['beginnt'];} else {echo "FEHLER";exit;}
if (isset($_POST['endej'])) {$endej = $_POST['endej'];} else {echo "FEHLER";exit;}
if (isset($_POST['endem'])) {$endem = $_POST['endem'];} else {echo "FEHLER";exit;}
if (isset($_POST['endet'])) {$endet = $_POST['endet'];} else {echo "FEHLER";exit;}
if (isset($_POST['schulleitung'])) {$schulleitung = $_POST['schulleitung'];} else {echo "FEHLER";exit;}
if (isset($_POST['stellschulleitung'])) {$stellschulleitung = $_POST['stellschulleitung'];} else {echo "FEHLER";exit;}
if (isset($_POST['abteilungsleitung'])) {$abteilungsleitung = $_POST['abteilungsleitung'];} else {echo "FEHLER";exit;}
if (isset($_POST['sekretariat'])) {$sekretariat = $_POST['sekretariat'];} else {echo "FEHLER";exit;}
if (isset($_POST['sozialarbeit'])) {$sozialarbeit = $_POST['sozialarbeit'];} else {echo "FEHLER";exit;}
if (isset($_POST['oberstufenberatung'])) {$oberstufenberatung = $_POST['oberstufenberatung'];} else {echo "FEHLER";exit;}
if (isset($_POST['beratungslehrer'])) {$beratungslehrer = $_POST['beratungslehrer'];} else {echo "FEHLER";exit;}
if (isset($_POST['verbindungslehrer'])) {$verbindungslehrer = $_POST['verbindungslehrer'];} else {echo "FEHLER";exit;}
if (isset($_POST['schuelersprecher'])) {$schuelersprecher = $_POST['schuelersprecher'];} else {echo "FEHLER";exit;}
if (isset($_POST['elternbeirat'])) {$elternbeirat = $_POST['elternbeirat'];} else {echo "FEHLER";exit;}
if (isset($_POST['vertretungsplanung'])) {$vertretungsplanung = $_POST['vertretungsplanung'];} else {echo "FEHLER";exit;}
if (isset($_POST['datenschutz'])) {$datenschutz = $_POST['datenschutz'];} else {echo "FEHLER";exit;}
if (isset($_POST['hausmeister'])) {$hausmeister = $_POST['hausmeister'];} else {echo "FEHLER";exit;}
if (isset($_SESSION["SCHULJAHREBEARBEITEN"])) {$id = $_SESSION["SCHULJAHREBEARBEITEN"];} else {echo "FEHLER";exit;}



$dbs = cms_verbinden('s');

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.bearbeiten")) {
	$fehler = false;

	// Pflichteingaben prüfen
	if ((!cms_check_titel($bezeichnung)) || (strtolower($bezeichnung) == 'schuljahrübergreifend')) {
		$fehler = true;
	}

	// Beginn und Ende in Zahlen umwandeln
	$beginnd = mktime(0, 0, 0, $beginnm, $beginnt, $beginnj);
	$ended = mktime(23, 59, 59, $endem, $endet, $endej);

	if ($beginnd-$ended >= 0) {
		$fehler = true;
	}

	if (!$fehler) {
		$bezeichnung = cms_texttrafo_e_db($bezeichnung);
		// Prüfen, ob bereits ein Schuljahr in diesem Zeitraum existiert - beginn liegt in anderem - ende liegt in anderem - neues umfasst andere
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE ((beginn <= ? AND ende >= ?) OR (beginn <= ? AND ende >= ?) OR (beginn >= ? AND ende <= ?)) AND id != ?");
	  $sql->bind_param("iiiiiii", $beginnd, $beginnd, $ended, $ended, $beginnd, $ended, $id);
		if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
		$sql->bind_param("si", $bezeichnung, $id);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		// Prüfen, ob die Personen in den Schlüsselpositionen der richtigen Personengruppe angehören
		$personenfehler = false;
		$perslehrer = $schulleitung.$stellschulleitung.$abteilungsleitung.$oberstufenberatung.$beratungslehrer.$verbindungslehrer.$vertretungsplanung;
		$persmischmasch = $sekretariat.$sozialarbeit.$hausmeister;
		$persschueler = $schuelersprecher;
		$perseltern = $elternbeirat;

		$personen[0]['id'] = str_replace("|", ",", $perslehrer);
		$personen[0]['art'] = 'l';
		$personen[1]['id'] = str_replace("|", ",", $persschueler);
		$personen[1]['art'] = 's';
		$personen[2]['id'] = str_replace("|", ",", $perseltern);
		$personen[2]['art'] = 'e';

		for ($i=0; $i<count($personen); $i++) {
			$ids = $personen[$i]['id'];
			$art = $personen[$i]['art'];
			if ($ids > 2) {
				$ids = "(".substr($ids, 1).")";
				if (cms_check_idliste($ids)) {
					$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$ids." AND art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL');");
				  $sql->bind_param("s", $art);
				  if ($sql->execute()) {
				    $sql->bind_result($anzahl);
				    if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
						else {$fehler = true;}
				  }
				  else {$fehler = true;}
				  $sql->close();
				}
				else {$fehler = true;}
			}
		}

		$ids = str_replace("|", ",", $persmischmasch);
		$art1 = "v";
		$art2 = "x";
		if ($ids > 2) {
			$ids = "(".substr($ids, 1).")";
			if (cms_check_idliste($ids)) {
				$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$ids." AND (art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') OR art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'));");
				$sql->bind_param("ss", $art1, $art2);
				if ($sql->execute()) {
					$sql->bind_result($anzahl);
					if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
					else {$fehler = true;}
				}
				else {$fehler = true;}
				$sql->close();
			}
			else {$fehler = true;}
		}

		if ($personenfehler) {
			$fehler = true;
			echo "PERSONEN";
		}
	}

	if (!$fehler) {
		// SUCHE ZEITÄUME, die nicht im neuen SCHULJAHRZEITRAUM liegen
		$sql = "SELECT MIN(beginn) AS erster, MAX(ende) AS letzter FROM zeitraeume WHERE schuljahr = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($erster, $letzter);
			if ($sql->fetch()) {
				$zeitraumfehler = false;
				if (!is_null($erster)) {
					if ($erster < $beginnd) {$zeitraumfehler = true;}
					if ($letzter > $ended) {$zeitraumfehler = true;}
				}
				if ($zeitraumfehler) {$fehler = true; echo "ZEITRAUM";}
			}
		}
		else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		// SCHULJAHR ÄNDERN EINTRAGEN
		$sql = $dbs->prepare("UPDATE schuljahre SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ? WHERE id = ?");
	  $sql->bind_param("siii", $bezeichnung, $beginnd, $ended, $id);
	  $sql->execute();
	  $sql->close();

		// SCHLÜSSELPOSITION LÖSCHEN
		$sql = $dbs->prepare("DELETE FROM schluesselposition WHERE schuljahr = ?");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		// Schlüsselpositionen hinzufügen
		$personen[0]['id'] = explode("|", $schulleitung);
		$personen[0]['bez'] = 'Schulleitung';
		$personen[1]['id'] = explode("|", $stellschulleitung);
		$personen[1]['bez'] = 'Stellvertretende Schulleitung';
		$personen[2]['id'] = explode("|", $abteilungsleitung);
		$personen[2]['bez'] = 'Abteilungsleitung';
		$personen[3]['id'] = explode("|", $vertretungsplanung);
		$personen[3]['bez'] = 'Vertretungsplanung';
		$personen[4]['id'] = explode("|", $sekretariat);
		$personen[4]['bez'] = 'Sekretariat';
		$personen[5]['id'] = explode("|", $sozialarbeit);
		$personen[5]['bez'] = 'Sozialarbeit';
		$personen[6]['id'] = explode("|", $oberstufenberatung);
		$personen[6]['bez'] = 'Oberstufenberatung';
		$personen[7]['id'] = explode("|", $beratungslehrer);
		$personen[7]['bez'] = 'Beratungslehrkräfte';
		$personen[8]['id'] = explode("|", $verbindungslehrer);
		$personen[8]['bez'] = 'Verbindungslehrkräfte';
		$personen[9]['id'] = explode("|", $schuelersprecher);
		$personen[9]['bez'] = 'Schülersprecher';
		$personen[10]['id'] = explode("|", $elternbeirat);
		$personen[10]['bez'] = 'Elternbeiratsvorsitzende';
		$personen[11]['id'] = explode("|", $datenschutz);
		$personen[11]['bez'] = 'Datenschutzbeauftragter';
		$personen[12]['id'] = explode("|", $hausmeister);
		$personen[12]['bez'] = 'Hausmeister';

		// j läuft über die einzelnen schlüsselpositionen
		$sql = $dbs->prepare("INSERT INTO schluesselposition (person, position, schuljahr) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?);");
		for ($j=0; $j<count($personen); $j++) {
			// i läuft über die einzelnen personen
			for ($i = 1; $i <count($personen[$j]['id']); $i++) {
				// EINSTELLUNGEN DER PERSON EINTRAGEN
				$sql->bind_param("isi", $personen[$j]['id'][$i], $personen[$j]['bez'], $id);
			  $sql->execute();
			}
		}
	  $sql->close();
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
