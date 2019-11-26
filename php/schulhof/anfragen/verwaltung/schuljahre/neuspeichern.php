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

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Schuljahre anlegen'];

$dbs = cms_verbinden('s');

if (cms_angemeldet() && $zugriff) {
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
		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE ((beginn <= ? AND ende >= ?) OR (beginn <= ? AND ende >= ?) OR (beginn >= ? AND ende <= ?))");
		$sql->bind_param("iiiiii", $beginnd, $beginnd, $ended, $ended, $beginnd, $ended);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$sql->bind_param("s", $bezeichnung);
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
		$perslehrer = $schulleitung.$stellschulleitung.$abteilungsleitung.$oberstufenberatung.$beratungslehrer.$verbindungslehrer.$vertretungsplanung.$datenschutz;
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
			if (strlen($ids) > 2) {
				$ids = "(".substr($ids, 1).")";
				if (cms_check_idliste($ids)) {
					$sql = "SELECT COUNT(*) AS anzahl FROM personen WHERE id IN ".$ids." AND art != AES_ENCRYPT('".$art."', '$CMS_SCHLUESSEL');";
					$anfrage = $dbs->query($sql);	// Safe weil ID Check
					if ($anfrage) {
						if ($daten = $anfrage->fetch_assoc()) {
							if ($daten['anzahl'] != 0) {
								$personenfehler = true;
							}
						}
						else {$fehler = true;}
						$anfrage->free();
					}
					else {$fehler = true;}
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
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id('schuljahre');
		if ($id == '-') {$fehler = true;}
	}

	if (!$fehler) {
		// SCHULJAHR EINTRAGEN
		$sql = $dbs->prepare("UPDATE schuljahre SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beginn = ?, ende = ? WHERE id = ?");
	  $sql->bind_param("siii", $bezeichnung, $beginnd, $ended, $id);
	  $sql->execute();
	  $sql->close();

		// Tagebuch für dieses Schuljahr generieren
		$sql = "CREATE TABLE tagebuch_$id (
			id bigint(255) UNSIGNED NOT NULL,
			lehrkraft bigint(255) UNSIGNED NULL,
			raum bigint(255) UNSIGNED NULL,
			kurs bigint(255) UNSIGNED NULL,
			zeitraum bigint(255) UNSIGNED NULL,
			tag int(5) UNSIGNED NULL,
			stunde bigint(255) UNSIGNED NULL,
			lehrkraftarchiv varbinary(3000) NOT NULL,
			raumarchiv varbinary(3000) NOT NULL,
			kursarchiv varbinary(3000) NOT NULL,
			beginn bigint(255) UNSIGNED NULL,
			ende bigint(255) UNSIGNED NULL,
			tbeginn bigint(255) UNSIGNED NULL,
			tende bigint(255) UNSIGNED NULL,
			tlehrkraft bigint(255) UNSIGNED DEFAULT NULL,
			traum bigint(255) UNSIGNED DEFAULT NULL,
			tstunde bigint(255) UNSIGNED DEFAULT NULL,
			entfall int(1) UNSIGNED NOT NULL DEFAULT '0',
			zusatzstunde int(1) UNSIGNED NOT NULL DEFAULT '0',
			vertretungsplan int(1) UNSIGNED NOT NULL DEFAULT '0',
			vertretungstext varbinary(3000) NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD PRIMARY KEY (id)";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."lehrer FOREIGN KEY (lehrkraft) REFERENCES personen(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."raeume FOREIGN KEY (raum) REFERENCES raeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."kurse FOREIGN KEY (kurs) REFERENCES kurse(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."zeitraeume FOREIGN KEY (zeitraum) REFERENCES zeitraeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."tlehrer FOREIGN KEY (tlehrkraft) REFERENCES personen(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."traeume FOREIGN KEY (traum) REFERENCES raeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe

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
