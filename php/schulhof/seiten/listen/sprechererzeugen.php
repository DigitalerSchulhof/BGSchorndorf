<?php
function cms_listen_sprecher_ausgeben($dbs) {
	global $CMS_SCHLUESSEL, $CMS_BENUTZERSCHULJAHR;
	$code = "";
	// Schülersprecher
	$liste = "";
	/*personen.id AS id, titel, vorname, nachname, klasse, stufe*/
	$sql = "SELECT DISTINCT person, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, klasse, klassenstufe, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez FROM schluesselposition JOIN klassenschueler ON schluesselposition.person = klassenschueler.schueler JOIN klassen ON klassenschueler.klasse = klassen.id JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE schluesselposition.schuljahr = $CMS_BENUTZERSCHULJAHR AND klassenstufen.schuljahr = $CMS_BENUTZERSCHULJAHR AND position = AES_ENCRYPT('Schülersprecher', '$CMS_SCHLUESSEL')";
	$sql = "SELECT y.id AS id, vorname, nachname, titel, klasse, stufe, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, kbez AS klasse, sbez AS stufe FROM ($sql) AS x JOIN personen ON x.person = personen.id) AS y LEFT JOIN nutzerkonten ON y.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC, titel ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$klasse = $daten['klasse'];
			$stufe = $daten['stufe'];
			$nutzerkonto = $daten['nutzerkonto'];

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/schueler.png\"></td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";
			$liste .= "<td>".$stufe.$klasse."</td>";
			$link = "";
			if (!is_null($nutzerkonto)) {
				$link = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', $id, 'p')\">Nachricht schreiben</span>";
			}
			else {
				$anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
				$link = "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">Nachricht schreiben</span>";
			}
			$liste .= "<td>$link</td>";
			$liste .= "</tr>";

		}
		$anfrage->free();
	}
	if (strlen($liste) == 0) {
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<h3>Schülersprecher</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Klasse / Kurs</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";


	// Klassensprecher
	$liste = "";
	$sql = "SELECT DISTINCT schueler AS person, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, klasse, klassenstufe, reihenfolge, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez FROM klassensprecher JOIN klassen ON klassensprecher.klasse = klassen.id JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE schuljahr = $CMS_BENUTZERSCHULJAHR";
	$sql = "SELECT y.id AS id, vorname, nachname, titel, klasse, stufe, nutzerkonten.id AS nutzerkonto, reihenfolge FROM (SELECT DISTINCT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, kbez AS klasse, reihenfolge, sbez AS stufe FROM ($sql) AS x JOIN personen ON x.person = personen.id) AS y LEFT JOIN nutzerkonten ON y.id = nutzerkonten.id ORDER BY reihenfolge ASC, klasse ASC, nachname ASC, vorname ASC, titel ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$stufe = $daten['stufe'];
			$klasse = $daten['klasse'];
			$nutzerkonto = $daten['nutzerkonto'];

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/schueler.png\"></td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";
			$liste .= "<td>".$stufe.$klasse."</td>";
			$link = "";
			if (!is_null($nutzerkonto)) {
				$link = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', $id, 'p')\">Nachricht schreiben</span>";
			}
			else {
				$anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
				$link = "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">Nachricht schreiben</span>";
			}
			$liste .= "<td>$link</td>";
			$liste .= "</tr>";

		}
		$anfrage->free();
	}
	if (strlen($liste) == 0) {
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<h3>Klassensprecher</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Klasse / Kurs</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";
	return $code;
}
?>
