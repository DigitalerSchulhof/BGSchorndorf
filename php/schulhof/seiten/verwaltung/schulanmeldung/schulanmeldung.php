<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulanmeldung</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Schulanmeldung vorbereiten'] || $CMS_RECHTE['Organisation']['Schulanmeldungen erfassen'] || $CMS_RECHTE['Organisation']['Schulanmeldungen bearbeiten'] || $CMS_RECHTE['Organisation']['Schulanmeldungen exportieren'] || $CMS_RECHTE['Organisation']['Schulanmeldungen löschen'] || $CMS_RECHTE['Organisation']['Schulanmeldungen akzeptieren'];

if ($zugriff) {
	$eingegangen = 0;
	$aufgenommen = 0;

	$sql = "SELECT COUNT(id) AS anzahl FROM voranmeldung_schueler WHERE akzeptiert = AES_ENCRYPT('ja', '$CMS_SCHLUESSEL')";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$aufgenommen = $daten['anzahl'];
		}
		$anfrage->free();
	}

	$sql = "SELECT COUNT(id) AS anzahl FROM voranmeldung_schueler";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$eingegangen = $daten['anzahl'];
		}
		$anfrage->free();
	}

	$code = "</div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Zwischenstand</h2>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<tr><th>Anmeldungen eingegeangen</th><td class=\"cms_zahl\">$eingegangen</td></tr>";
		$code .= "<tr><th>Davon aufgenommen</th><td class=\"cms_zahl\">$aufgenommen</td></tr>";
	$code .= "</table>";
	$code .= "</div></div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$aktionen = "";
	if ($CMS_RECHTE['Organisation']['Schulanmeldungen erfassen']) {$aktionen .= "<a class=\"cms_iconbutton cms_button_ja\" style=\"background-image:url('res/icons/gross/dazu.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Neue_Anmeldung\">Neue Anmeldung</a> ";}
	if ($CMS_RECHTE['Organisation']['Schulanmeldung vorbereiten']) {$aktionen .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/einstellungen.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Einstellungen\">Einstellungen</a> ";}
	if ($CMS_RECHTE['Organisation']['Schulanmeldungen exportieren']) {$aktionen .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/exportieren.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Exportieren\">Exportieren</a> ";}

	if (strlen($aktionen) > 0) {
		$code .= "<h2>Aktionen</h2>";
		$code .= "<p>$aktionen</p>";
	}

	$code .= "</div></div>";

	$code .= "<div class=\"cms_clear\"></div>";

	$code .= "<div class=\"cms_spalte_i\">";
	$code .= "<h2>Anmeldungen</h2>";
	$code .= "<h3>Suchen</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Vorname</th><th>Nachname</th></tr>";
	$code .= "<tr><td><input name=\"cms_vornameldung_vorname\" id=\"cms_vornameldung_vorname\" value=\"\" type=\"text\"></td><td><input name=\"cms_vornameldung_nachname\" id=\"cms_vornameldung_nachname\" value=\"\" type=\"text\"></td></tr>";
	$code .= "</table>";

	$code .= "<h3>Datensätze</h3>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th>Vorname</th><th>Nachname</th><th>Geburtsdatum</th><th>Ort</th><th>Eingegangen</th><th></th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody id=\"cms_voranmeldung_schueler\">";
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert, AES_DECRYPT(eingegangen, '$CMS_SCHLUESSEL') AS eingegangen FROM voranmeldung_schueler) AS schueler ORDER BY nachname ASC, vorname ASC, geburtsdatum ASC, ort ASC";

		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td>".$daten['vorname']."</td>";
					$ausgabe .= "<td>".$daten['nachname']."</td>";
					$ausgabe .= "<td>".date('d', $daten['geburtsdatum']).". ".cms_monatsnamekomplett(date('m', $daten['geburtsdatum']))." ".date('Y', $daten['geburtsdatum'])."</td>";
					$ausgabe .= "<td>".$daten['ort']."</td>";
					$ausgabe .= "<td>".date('d.m.Y', $daten['eingegangen'])." – ".date('H:i', $daten['eingegangen'])."</td>";
					if ($daten['akzeptiert'] == 'ja') {$icon = "gruen.png";$beschreibung = "aufgenommen";}
					else {$icon = "rot.png"; $beschreibung = "Aufnahme ausstehend";}
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$beschreibung</span><img src=\"res/icons/klein/$icon\"></span> </td>";
					$ausgabe .= "<td>";
					if ($CMS_RECHTE['Organisation']['Schulanmeldungen bearbeiten']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulanmeldung_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if ($CMS_RECHTE['Organisation']['Schulanmeldungen akzeptieren']) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulanmeldung_drucken(".$daten['id'].");\"><span class=\"cms_hinweis\">Drucken</span><img src=\"res/icons/klein/drucken.png\"></span> ";
						if (($daten['akzeptiert'] != 'ja')) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulanmeldung_aufnehmen('".$daten['vorname']." ".$daten['nachname']."', ".$daten['id'].");\"><span class=\"cms_hinweis\">Aufnehmen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
						}
						else if (($daten['akzeptiert'] != 'nein')) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_wichtig\" onclick=\"cms_schulanmeldung_ablehnen('".$daten['vorname']." ".$daten['nachname']."', ".$daten['id'].");\"><span class=\"cms_hinweis\">Ablehnen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
						}
					}
					if ($CMS_RECHTE['Organisation']['Schulanmeldungen löschen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulanmeldung_loeschen_anzeigen('".$daten['vorname']." ".$daten['nachname']."', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}
					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
			$anfrage->free();
		}

		if (strlen($ausgabe) == 0) {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"7\">- keine Datensätze gefunden -</td></tr>";
		}
		$code .= $ausgabe."</tbody>";
	$code .= "</table>";

	if ($eingegangen > 0) {
		if ($CMS_RECHTE['Organisation']['Schulanmeldungen löschen']) {$code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_schulanmeldung_alleloeschen_anzeigen()\">Alle Datensätze löschen</span></p>";}
	}

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
