<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
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
if (isset($_POST['faechergewaehlt'])) {$faechergewaehlt = $_POST['faechergewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['gremiengewaehlt'])) {$gremiengewaehlt = $_POST['gremiengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['fachschaftengewaehlt'])) {$fachschaftengewaehlt = $_POST['fachschaftengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['stufengewaehlt'])) {$stufengewaehlt = $_POST['stufengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['klassengewaehlt'])) {$klassengewaehlt = $_POST['klassengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['arbeitsgemeinschaftengewaehlt'])) {$arbeitsgemeinschaftengewaehlt = $_POST['arbeitsgemeinschaftengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['arbeitskreisegewaehlt'])) {$arbeitskreisegewaehlt = $_POST['arbeitskreisegewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['fahrtengewaehlt'])) {$fahrtengewaehlt = $_POST['fahrtengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['wettbewerbegewaehlt'])) {$wettbewerbegewaehlt = $_POST['wettbewerbegewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['ereignissegewaehlt'])) {$ereignissegewaehlt = $_POST['ereignissegewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_POST['sonstigegruppengewaehlt'])) {$sonstigegruppengewaehlt = $_POST['sonstigegruppengewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

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


	// Prüfen, ob die zugeordneten Gruppen und Fächer existieren
	$NEUEFAECHER = array();
	$sqlids = cms_generiere_sqlidliste($faechergewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, farbe, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM faecher WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($fbez, $fkur, $ffar, $fico);
		  while ($sql->fetch()) {
				$NEUESFACH = array();
				$NEUESFACH['bezeichnung'] = $fbez;
				$NEUESFACH['kuerzel'] = $fkur;
				$NEUESFACH['farbe'] = $ffar;
				$NEUESFACH['icon'] = $fico;
				array_push($NEUEFAECHER, $NEUESFACH);
			}
		}
		$sql->close();
	}

	$NEUEGREMIEN = array();
	$sqlids = cms_generiere_sqlidliste($gremiengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM gremien WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEGREMIEN, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEFACHSCHAFTEN = array();
	$sqlids = cms_generiere_sqlidliste($fachschaftengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM fachschaften WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEFACHSCHAFTEN, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUESTUFEN = array();
	$STUFENIDS = array();
	$sqlids = cms_generiere_sqlidliste($stufengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, reihenfolge FROM stufen WHERE id IN $sqlids AND schuljahr = $altschuljahr ORDER BY reihenfolge");
		$r = 0;
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha, $grei);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				$NEUEGRUPPE['reihenfolge'] = $r;
				$r++;
				array_push($NEUESTUFEN, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEKLASSEN = array();
	$sqlids = cms_generiere_sqlidliste($klassengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, stufe FROM klassen WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha, $gstu);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				$NEUEGRUPPE['stufe'] = $gstu;
				$NEUEGRUPPE['stufeneu'] = false;
				array_push($NEUEKLASSEN, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEAGS = array();
	$sqlids = cms_generiere_sqlidliste($arbeitsgemeinschaftengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM arbeitsgemeinschaften WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEAGS, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEAKS = array();
	$sqlids = cms_generiere_sqlidliste($arbeitskreisegewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM arbeitskreise WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEAKS, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEFAHRTEN = array();
	$sqlids = cms_generiere_sqlidliste($fahrtengewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM fahrten WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEFAHRTEN, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEWETTBEWERBE = array();
	$sqlids = cms_generiere_sqlidliste($wettbewerbegewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM wettbewerbe WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEWETTBEWERBE, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUEEREIGNISSE = array();
	$sqlids = cms_generiere_sqlidliste($ereignissegewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM ereignisse WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUEEREIGNISSE, $NEUEGRUPPE);
			}
		}
		$sql->close();
	}

	$NEUESONSTIGE = array();
	$sqlids = cms_generiere_sqlidliste($ereignissegewaehlt);
	if (cms_check_idliste($sqlids)) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv FROM sonstigegruppen WHERE id IN $sqlids AND schuljahr = $altschuljahr");
		if ($sql->execute()) {
		  $sql->bind_result($gaid, $gbez, $gicon, $gsic, $gcha);
		  while ($sql->fetch()) {
				$NEUEGRUPPE = array();
				$NEUEGRUPPE['alteid'] = $gaid;
				$NEUEGRUPPE['bezeichnung'] = $gbez;
				$NEUEGRUPPE['icon'] = $gicon;
				$NEUEGRUPPE['sichtbar'] = $gsic;
				$NEUEGRUPPE['chataktiv'] = $gcha;
				array_push($NEUESONSTIGE, $NEUEGRUPPE);
			}
		}
		$sql->close();
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
		/*$sql = "CREATE TABLE tagebuch_$id (
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
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD PRIMARY KEY (id)";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."lehrer FOREIGN KEY (lehrkraft) REFERENCES personen(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."raeume FOREIGN KEY (raum) REFERENCES raeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."kurse FOREIGN KEY (kurs) REFERENCES kurse(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."zeitraeume FOREIGN KEY (zeitraum) REFERENCES zeitraeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."tlehrer FOREIGN KEY (tlehrkraft) REFERENCES personen(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);	// Kommentar
		$sql = "ALTER TABLE tagebuch_$id ADD CONSTRAINT tagebuch".$id."traeume FOREIGN KEY (traum) REFERENCES raeume(id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$anfrage = $dbs->query($sql);*/	// Kommentar

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

		// Fächer übertragen
		$sql = $dbs->prepare("UPDATE faecher SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), farbe = ?, icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		foreach ($NEUEFAECHER AS $e) {
			$eid = cms_generiere_kleinste_id('faecher');
			$sql->bind_param("issisi", $id, $e['bezeichnung'], $e['kuerzel'], $e['farbe'], $e['icon'], $eid);
			$sql->execute();
		}
		$sql->close();

		// Gremien übertragen
		$sql = $dbs->prepare("UPDATE gremien SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEGREMIEN AS $e) {
			$eid = cms_generiere_kleinste_id('gremien');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/gremien/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// Fachschaften übertragen
		$sql = $dbs->prepare("UPDATE fachschaften SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEFACHSCHAFTEN AS $e) {
			$eid = cms_generiere_kleinste_id('fachschaften');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/fachschaften/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// Stufen übertragen
		$sql = $dbs->prepare("UPDATE stufen SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ?, reihenfolge = ? WHERE id = ?");
		foreach ($NEUESTUFEN AS $e) {
			$eid = cms_generiere_kleinste_id('stufen');
			$sql->bind_param("issiiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $e['reihenfolge'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/stufen/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
			for ($i = 0; $i<count($NEUEKLASSEN); $i++) {
				if ($NEUEKLASSEN[$i]['stufe'] == $e['alteid']) {
					$NEUEKLASSEN[$i]['stufe'] = $eid;
					$NEUEKLASSEN[$i]['stufeneu'] = true;
				}
			}
		}
		$sql->close();

		// Klassen übertragen
		$sql = $dbs->prepare("UPDATE klassen SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ?, stufe = ? WHERE id = ?");
		foreach ($NEUEKLASSEN AS $e) {
			if ($e['stufeneu']) {
				$eid = cms_generiere_kleinste_id('klassen');
				$sql->bind_param("issiiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $e['stufe'], $eid);
				$sql->execute();

				// Dateisystem erzeugen
				$pfad = '../../../dateien/schulhof/gruppen/klassen/'.$eid;
				if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
				mkdir($pfad);
				chmod($pfad, 0775);
			}
		}
		$sql->close();

		// AGs übertragen
		$sql = $dbs->prepare("UPDATE arbeitsgemeinschaften SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEAGS AS $e) {
			$eid = cms_generiere_kleinste_id('arbeitsgemeinschaften');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/arbeitsgemeinschaften/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// AKs übertragen
		$sql = $dbs->prepare("UPDATE arbeitskreise SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEAKS AS $e) {
			$eid = cms_generiere_kleinste_id('arbeitskreise');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/arbeitskreise/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// Fahrten übertragen
		$sql = $dbs->prepare("UPDATE fahrten SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEFAHRTEN AS $e) {
			$eid = cms_generiere_kleinste_id('fahrten');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/fahrten/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// Wettbewerbe übertragen
		$sql = $dbs->prepare("UPDATE wettbewerbe SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEWETTBEWERBE AS $e) {
			$eid = cms_generiere_kleinste_id('wettbewerbe');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/wettbewerbe/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// Ereignisse übertragen
		$sql = $dbs->prepare("UPDATE ereignisse SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUEEREIGNISSE AS $e) {
			$eid = cms_generiere_kleinste_id('ereignisse');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/ereignisse/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		// SonstigeGruppen übertragen
		$sql = $dbs->prepare("UPDATE sonstigegruppen SET schuljahr = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
		foreach ($NEUESONSTIGE AS $e) {
			$eid = cms_generiere_kleinste_id('sonstigegruppen');
			$sql->bind_param("issiii", $id, $e['bezeichnung'], $e['icon'], $e['sichtbar'], $e['chataktiv'], $eid);
			$sql->execute();

			// Dateisystem erzeugen
			$pfad = '../../../dateien/schulhof/gruppen/sonstigegruppen/'.$eid;
			if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
			mkdir($pfad);
			chmod($pfad, 0775);
		}
		$sql->close();

		$_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'] = $id;

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
