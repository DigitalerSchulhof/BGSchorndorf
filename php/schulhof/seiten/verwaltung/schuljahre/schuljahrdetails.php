<?php
function cms_schuljahr_ausgeben ($schuljahr) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";
	$altekategorie = "";

	$bezeichnung = "";
	$schulleiter = ""; $schulleiterhidden = "";
	$stellschulleiter = ""; $stellschulleiterhidden = "";
	$abteilungsleiter = ""; $abteilungsleiterhidden = "";
	$sekretariat = ""; $sekretariathidden = "";
	$sozialarbeit = ""; $sozialarbeithidden = "";
	$oberstufenberater = ""; $oberstufenberaterhidden = "";
	$beratungslehrer = ""; $beratungslehrerhidden = "";
	$schuelersprecher = ""; $schuelersprecherhidden = "";
	$verbindungslehrer = ""; $verbindungslehrerhidden = "";
	$elternbeirat = ""; $elternbeirathidden = "";
	$vertretungsplanung = ""; $vertretungsplanunghidden = "";
	$datenschutz = ""; $datenschutzhidden = "";
	$jetzt = time();
	$beginnj = date('Y', $jetzt);
	$beginnm = '09';
	$beginnt = '01';
	$endej = date('Y', $jetzt)+1;
	$endem = '08';
	$endet = '31';

	if ($schuljahr != "") {
		$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre WHERE id = $schuljahr";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$bezeichnung = $daten['bezeichnung'];
				$beginnj = date('Y', $daten['beginn']);
				$beginnm = date('m', $daten['beginn']);
				$beginnt = date('d', $daten['beginn']);
				$endej = date('Y', $daten['ende']);
				$endem = date('m', $daten['ende']);
				$endet = date('d', $daten['ende']);
			}
			$anfrage->free();
		}

		// Zugeordnete Personen laden
		// Schulleiter
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Schulleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schulleiter .= cms_personensuche_personerzeugen ('schulhof_schuljahr_schulleitung', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$schulleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Stellvertrender Schulleiter
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Stellvertretende Schulleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$stellschulleiter .= cms_personensuche_personerzeugen ('schulhof_schuljahr_stellschulleitung', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$stellschulleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Abteilungsleiter
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Abteilungsleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$abteilungsleiter .= cms_personensuche_personerzeugen ('schulhof_schuljahr_abteilungsleitung', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$abteilungsleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Sekretariat
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Sekretariat', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sekretariat .= cms_personensuche_personerzeugen ('schulhof_schuljahr_sekretariat', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$sekretariathidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Vertretungsplanung
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Vertretungsplanung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$vertretungsplanung .= cms_personensuche_personerzeugen ('schulhof_schuljahr_vertretungsplanung', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$vertretungsplanunghidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Schulsozialarbeit
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Sozialarbeit', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sozialarbeit .= cms_personensuche_personerzeugen ('schulhof_schuljahr_sozialarbeit', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$sozialarbeithidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Oberstufenberatung
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Oberstufenberatung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$oberstufenberater .= cms_personensuche_personerzeugen ('schulhof_schuljahr_oberstufenberatung', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$oberstufenberaterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Beratungslehrer
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Beratungslehrkräfte', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$beratungslehrer .= cms_personensuche_personerzeugen ('schulhof_schuljahr_beratungslehrer', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$beratungslehrerhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Verbindungslehrer
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Verbindungslehrkräfte', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$verbindungslehrer .= cms_personensuche_personerzeugen ('schulhof_schuljahr_verbindungslehrer', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$verbindungslehrerhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Schülersprecher
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Schülersprecher', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schuelersprecher .= cms_personensuche_personerzeugen ('schulhof_schuljahr_schuelersprecher', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$schuelersprecherhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Elternbeirat
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Elternbeiratsvorsitzende', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$elternbeirat .= cms_personensuche_personerzeugen ('schulhof_schuljahr_elternbeirat', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$elternbeirathidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Datenschutz
		$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Datenschutzbeauftragter', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$datenschutz .= cms_personensuche_personerzeugen ('schulhof_schuljahr_datenschutz', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$datenschutzhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}


	}


	$anfang = "<h3>Schuljahrdetails</h3>";
	$anfang .= "<table class=\"cms_formular\">";
		$anfang .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_schulhof_schuljahr_bezeichnung\" id=\"cms_schulhof_schuljahr_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";

		$anfang .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_schulhof_schuljahr_beginn', $beginnt, $beginnm, $beginnj)."</td></tr>";
		$anfang .= "<tr><th>Ende:</th><td>".cms_datum_eingabe('cms_schulhof_schuljahr_ende', $endet, $endem, $endej)."</td></tr>";
	$anfang .= "</table>";

	$anfang .= "<h3>Schlüsselpositionen</h3>";
	$anfang .= "<table class=\"cms_liste\">";
		$anfang .= "<tr><th>Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schulleitungFo\"><span id=\"cms_schulhof_schuljahr_schulleitungF\">".$schulleiter."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_schulleitung', 'SchulleiterIn hinzufügen', $schulleiterhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Stellvertretende Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_stellschulleitungFo\"><span id=\"cms_schulhof_schuljahr_stellschulleitungF\">".$stellschulleiter."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_stellschulleitung', 'Stellvertretende(n) SchulleiterIn hinzufügen', $stellschulleiterhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Abteilungsleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_abteilungsleitungFo\"><span id=\"cms_schulhof_schuljahr_abteilungsleitungF\">".$abteilungsleiter."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_abteilungsleitung', 'AbteilungsleiterIn hinzufügen', $abteilungsleiterhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Vertretungsplanung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_vertretungsplanungFo\"><span id=\"cms_schulhof_schuljahr_vertretungsplanungF\">".$vertretungsplanung."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_vertretungsplanung', 'VertretungsplanerIn hinzufügen', $vertretungsplanunghidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Datenschutz:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_datenschutzFo\"><span id=\"cms_schulhof_schuljahr_datenschutzF\">".$datenschutz."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_datenschutz', 'DatenschutzbeauftragteN hinzufügen', $datenschutzhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sekretariat:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sekretariatFo\"><span id=\"cms_schulhof_schuljahr_sekretariatF\">".$sekretariat."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_sekretariat', 'SkretärIn hinzufügen', $sekretariathidden, false, false, false, true, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sozialarbeit:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sozialarbeitFo\"><span id=\"cms_schulhof_schuljahr_sozialarbeitF\">".$sozialarbeit."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_sozialarbeit', 'SozialarbeiterIn hinzufügen', $sozialarbeithidden, false, false, false, true, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Oberstufenberatung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_oberstufenberatungFo\"><span id=\"cms_schulhof_schuljahr_oberstufenberatungF\">".$oberstufenberater."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_oberstufenberatung', 'OberstufenberaterIn hinzufügen', $oberstufenberaterhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Beratungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_beratungslehrerFo\"><span id=\"cms_schulhof_schuljahr_beratungslehrerF\">".$beratungslehrer."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_beratungslehrer', 'BeratungslehrerIn hinzufügen', $beratungslehrerhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Verbindungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_verbindungslehrerFo\"><span id=\"cms_schulhof_schuljahr_verbindungslehrerF\">".$verbindungslehrer."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_verbindungslehrer', 'VerbindungslehrerIn hinzufügen', $verbindungslehrerhidden, false, true, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Schülersprecher:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schuelersprecherFo\"><span id=\"cms_schulhof_schuljahr_schuelersprecherF\">".$schuelersprecher."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_schuelersprecher', 'SchülersprecherIn hinzufügen', $schuelersprecherhidden, true, false, false, false, false, 1);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Elternbeiratsvorsitzende:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_elternbeiratFo\"><span id=\"cms_schulhof_schuljahr_elternbeiratF\">".$elternbeirat."</span>";
				$anfang .= cms_personensuche('schulhof_schuljahr_elternbeirat', 'ElternbeiratsvorsitzendeR hinzufügen', $elternbeirathidden, false, false, true, false, false, 1);
		$anfang.= "</td></tr>";
	$anfang .= "</table>";
	cms_trennen($dbs);


	return $anfang.(substr($code, 4))."</p>";
}
?>
