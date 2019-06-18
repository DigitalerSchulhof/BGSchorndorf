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
	$hausmeister = ""; $hausmeisterhidden = "";
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
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Schulleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schulleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Stellvertrender Schulleiter
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Stellvertretende Schulleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$stellschulleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Abteilungsleiter
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Abteilungsleitung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$abteilungsleiterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Sekretariat
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Sekretariat', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sekretariathidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Vertretungsplanung
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Vertretungsplanung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$vertretungsplanunghidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Schulsozialarbeit
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Sozialarbeit', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sozialarbeithidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Oberstufenberatung
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Oberstufenberatung', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$oberstufenberaterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Beratungslehrer
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Beratungslehrkräfte', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$beratungslehrerhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Verbindungslehrer
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Verbindungslehrkräfte', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$verbindungslehrerhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Schülersprecher
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Schülersprecher', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schuelersprecherhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Elternbeirat
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Elternbeiratsvorsitzende', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$elternbeirathidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Datenschutz
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Datenschutzbeauftragter', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$datenschutzhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		// Hausmeister
		$sql = "SELECT * FROM (SELECT DISTINCT id FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('Hausmeister', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$hausmeisterhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}


	}

	include_once('php/schulhof/seiten/personensuche/personensuche.php');


	$anfang = "<h3>Schuljahrdetails</h3>";
	$anfang .= "<table class=\"cms_formular\">";
		$anfang .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_schulhof_schuljahr_bezeichnung\" id=\"cms_schulhof_schuljahr_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";

		$anfang .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_schulhof_schuljahr_beginn', $beginnt, $beginnm, $beginnj)."</td></tr>";
		$anfang .= "<tr><th>Ende:</th><td>".cms_datum_eingabe('cms_schulhof_schuljahr_ende', $endet, $endem, $endej)."</td></tr>";
	$anfang .= "</table>";

	$anfang .= "<h3>Schlüsselpositionen</h3>";
	$anfang .= "<table class=\"cms_liste\">";
		$anfang .= "<tr><th>Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schulleitungFo\"><span id=\"cms_schulhof_schuljahr_schulleitungF\">".$schulleiter."</span>";
			$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_schulleitung', 'l', $schulleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Stellvertretende Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_stellschulleitungFo\"><span id=\"cms_schulhof_schuljahr_stellschulleitungF\">".$stellschulleiter."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_stellschulleitung', 'l', $stellschulleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Abteilungsleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_abteilungsleitungFo\"><span id=\"cms_schulhof_schuljahr_abteilungsleitungF\">".$abteilungsleiter."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_abteilungsleitung', 'l', $abteilungsleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Vertretungsplanung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_vertretungsplanungFo\"><span id=\"cms_schulhof_schuljahr_vertretungsplanungF\">".$vertretungsplanung."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_vertretungsplanung', 'l', $vertretungsplanunghidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Datenschutz:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_datenschutzFo\"><span id=\"cms_schulhof_schuljahr_datenschutzF\">".$datenschutz."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_datenschutz', 'l', $datenschutzhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sekretariat:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sekretariatFo\"><span id=\"cms_schulhof_schuljahr_sekretariatF\">".$sekretariat."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_sekretariat', 'vx', $sekretariathidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Hausmeister:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_hausmeisterFo\"><span id=\"cms_schulhof_schuljahr_hausmeisterF\">".$hausmeister."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_hausmeister', 'vx', $hausmeisterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sozialarbeit:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sozialarbeitFo\"><span id=\"cms_schulhof_schuljahr_sozialarbeitF\">".$sozialarbeit."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_sozialarbeit', 'vx', $sozialarbeithidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Oberstufenberatung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_oberstufenberatungFo\"><span id=\"cms_schulhof_schuljahr_oberstufenberatungF\">".$oberstufenberater."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_oberstufenberatung', 'l', $oberstufenberaterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Beratungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_beratungslehrerFo\"><span id=\"cms_schulhof_schuljahr_beratungslehrerF\">".$beratungslehrer."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_beratungslehrer', 'l', $beratungslehrerhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Verbindungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_verbindungslehrerFo\"><span id=\"cms_schulhof_schuljahr_verbindungslehrerF\">".$verbindungslehrer."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_verbindungslehrer', 'l', $verbindungslehrerhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Schülersprecher:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schuelersprecherFo\"><span id=\"cms_schulhof_schuljahr_schuelersprecherF\">".$schuelersprecher."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_schuelersprecher', 's', $schuelersprecherhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Elternbeiratsvorsitzende:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_elternbeiratFo\"><span id=\"cms_schulhof_schuljahr_elternbeiratF\">".$elternbeirat."</span>";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_elternbeirat', 'e', $elternbeirathidden);
		$anfang.= "</td></tr>";
	$anfang .= "</table>";
	cms_trennen($dbs);


	return $anfang.(substr($code, 4))."</p>";
}
?>
