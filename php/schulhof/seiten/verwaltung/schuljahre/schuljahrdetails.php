<?php
function cms_schuljahr_ausgeben ($schuljahr) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');
	$code = "";
	$altekategorie = "";

	$bezeichnung = "";
	$schulleiterhidden = "";
	$stellschulleiterhidden = "";
	$abteilungsleiterhidden = "";
	$sekretariathidden = "";
	$sozialarbeithidden = "";
	$oberstufenberaterhidden = "";
	$beratungslehrerhidden = "";
	$schuelersprecherhidden = "";
	$verbindungslehrerhidden = "";
	$elternbeirathidden = "";
	$vertretungsplanunghidden = "";
	$datenschutzhidden = "";
	$hausmeisterhidden = "";
	$jetzt = time();
	$beginnj = date('Y', $jetzt);
	$beginnm = '09';
	$beginnt = '01';
	$endej = date('Y', $jetzt)+1;
	$endem = '08';
	$endet = '31';

	if ($schuljahr != "") {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre WHERE id = ?");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung, $beginn, $ende);
			if ($sql->fetch()) {
				$beginnj = date('Y', $beginn);
				$beginnm = date('m', $beginn);
				$beginnt = date('d', $beginn);
				$endej = date('Y', $ende);
				$endem = date('m', $ende);
				$endet = date('d', $ende);
			}
		}
		$sql->close();

		// Zugeordnete Personen laden
		// Schulleiter
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Schulleitung', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$schulleiterhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Stellvertrender Schulleiter
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Stellvertretende Schulleitung', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$stellschulleiterhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Abteilungsleiter
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Abteilungsleitung', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$abteilungsleiterhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Sekretariat
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Sekretariat', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$sekretariathidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Vertretungsplanung
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Vertretungsplanung', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$vertretungsplanunghidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Schulsozialarbeit
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Sozialarbeit', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$sozialarbeithidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Oberstufenberatung
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Oberstufenberatung', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$oberstufenberaterhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Beratungslehrer
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Beratungslehrkräfte', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$beratungslehrerhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Verbindungslehrer
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Verbindungslehrkräfte', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$verbindungslehrerhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Schülersprecher
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Schülersprecher', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$schuelersprecherhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Elternbeirat
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Elternbeiratsvorsitzende', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$elternbeirathidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Datenschutz
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Datenschutzbeauftragter', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$datenschutzhidden .= "|".$ssperson;
			}
		}
		$sql->close();
		// Hausmeister
		$sql = $dbs->prepare("SELECT DISTINCT person FROM schluesselposition WHERE schuljahr = ? AND position = AES_ENCRYPT('Hausmeister', '$CMS_SCHLUESSEL')");
		$sql->bind_param("i", $schuljahr);
		if ($sql->execute()) {
			$sql->bind_result($ssperson);
			while ($sql->fetch()) {
				$hausmeisterhidden .= "|".$ssperson;
			}
		}
		$sql->close();

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
		$anfang .= "<tr><th>Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schulleitungFo\">";
			$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_schulleitung', 'l', $schulleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Stellvertretende Schulleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_stellschulleitungFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_stellschulleitung', 'l', $stellschulleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Abteilungsleitung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_abteilungsleitungFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_abteilungsleitung', 'l', $abteilungsleiterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Vertretungsplanung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_vertretungsplanungFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_vertretungsplanung', 'l', $vertretungsplanunghidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Datenschutz:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_datenschutzFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_datenschutz', 'l', $datenschutzhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sekretariat:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sekretariatFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_sekretariat', 'vx', $sekretariathidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Hausmeister:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_hausmeisterFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_hausmeister', 'vx', $hausmeisterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Sozialarbeit:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_sozialarbeitFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_sozialarbeit', 'vx', $sozialarbeithidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Oberstufenberatung:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_oberstufenberatungFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_oberstufenberatung', 'l', $oberstufenberaterhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Beratungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_beratungslehrerFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_beratungslehrer', 'l', $beratungslehrerhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Verbindungslehrkräfte:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_verbindungslehrerFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_verbindungslehrer', 'l', $verbindungslehrerhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Schülersprecher:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_schuelersprecherFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_schuelersprecher', 's', $schuelersprecherhidden);
		$anfang.= "</td></tr>";

		$anfang .= "<tr><th>Elternbeiratsvorsitzende:</th><td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_schuljahr_elternbeiratFo\">";
				$anfang .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuljahr_elternbeirat', 'e', $elternbeirathidden);
		$anfang.= "</td></tr>";
	$anfang .= "</table>";
	cms_trennen($dbs);


	return $anfang.(substr($code, 4))."</p>";
}
?>
