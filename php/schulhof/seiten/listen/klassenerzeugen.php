<?php
function cms_listen_klassen_ausgeben($dbs, $klassegewaehlt) {
	global $CMS_SCHLUESSEL;
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$code = "";
	// Klassenpasswörter
	// $passwortcode = "";
	// $beginn = mktime(0, 0, 0, date("n"), date("j"), date("Y") - 18);
	// $ende = mktime(0, 0, 0, date("n"), date("j"), date("Y") - 6);

	// Schüler der Klasse ausgeben
	$liste = "";
	$sql = "SELECT x.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN klassenmitglieder ON personen.id = klassenmitglieder.person WHERE klassenmitglieder.gruppe = $klassegewaehlt AND personen.id NOT IN (SELECT person FROM klassenvorsitz WHERE gruppe = $klassegewaehlt)) AS x LEFT JOIN nutzerkonten ON x.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC, titel ASC";
	$anzahl = 0;
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$nutzerkonto = $daten['nutzerkonto'];
			$anzahl ++;

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/schueler.png\"></td>";
			$liste .= "<td>".$anzahl."</td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";

			// Klassenpasswörter
			// $passwort = rand($beginn, $ende);
			// $passwort = date("d.m.Y", $passwort);
			// $passwortcode .= $nachname.",".$vorname.",".$passwort.";\n";

			$link = "";
			if (!is_null($nutzerkonto) && (in_array($id, $POSTEMPFAENGERPOOL))) {
				$link = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $id)\">Nachricht schreiben</span>";
			}
			else {
				$anzeigename = cms_generiere_anzeigename($vorname, $nachname, $titel);
				$link = "<span class=\"cms_button_passiv\">Nachricht schreiben</span>";
			}
			$liste .= "<td>$link</td>";
			$liste .= "</tr>";

		}
		$anfrage->free();
	}
	if (strlen($liste) == 0) {
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<h3>Schüler</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";
	// Klassenpasswörter
	// $code .= "<p style=\"display: none;\"><textarea rows=\"32\" name=\"nix\" id=\"nix\">$passwortcode</textarea></p>";

	// Klassenlehrer
	$liste = "";
	$sql = "SELECT x.id AS id, vorname, nachname, titel, nutzerkonten.id AS nutzerkonto, kuerzel FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN klassenvorsitz ON personen.id = klassenvorsitz.person LEFT JOIN lehrer ON personen.id = lehrer.id WHERE klassenvorsitz.gruppe = $klassegewaehlt) AS x LEFT JOIN nutzerkonten ON x.id = nutzerkonten.id ORDER BY nachname ASC, vorname ASC, titel ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$id = $daten['id'];
			$vorname = $daten['vorname'];
			$nachname = $daten['nachname'];
			$titel = $daten['titel'];
			$kuerzel = $daten['kuerzel'];
			$nutzerkonto = $daten['nutzerkonto'];

			$liste .= "<tr>";
			$liste .= "<td><img src=\"res/icons/klein/lehrer.png\"></td>";
			$liste .= "<td>".$titel."</td>";
			$liste .= "<td>".$vorname."</td>";
			$liste .= "<td>".$nachname."</td>";
			$liste .= "<td>".$kuerzel."</td>";
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
		$liste = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
	}
	$code .= "<h3>Klassenleitung und Stellvertretung</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Titel</th><th>Vorname</th><th>Nachname</th><th>Kürzel</th><th>Kontakt</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		$code .= $liste;
		$code .= "</tbody>";
	$code .= "</table>";

	return $code;
}
?>
