<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schulanmeldung</h1>

<?php
if (cms_r("schulhof.organisation.schulanmeldung.*")) {
	$eingegangen = 0;
	$aufgenommen = 0;

	$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM voranmeldung_schueler WHERE akzeptiert = AES_ENCRYPT('ja', '$CMS_SCHLUESSEL')");
	if ($sql->execute()) {
		$sql->bind_result($aufgenommen);
		$sql->fetch();
	}
	$sql->close();

	$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM voranmeldung_schueler");
	if ($sql->execute()) {
		$sql->bind_result($eingegangen);
		$sql->fetch();
	}
	$sql->close();

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
	if (cms_r("schulhof.organisation.schulanmeldung.erfassen")) {$aktionen .= "<a class=\"cms_iconbutton cms_button_ja\" style=\"background-image:url('res/icons/gross/dazu.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Neue_Anmeldung\">Neue Anmeldung</a> ";}
	if (cms_r("schulhof.organisation.schulanmeldung.vorbereiten")) {$aktionen .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/einstellungen.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Einstellungen\">Einstellungen</a> ";}
	if (cms_r("schulhof.organisation.schulanmeldung.exportieren")) {$aktionen .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/exportieren.png');\" href=\"Schulhof/Verwaltung/Schulanmeldung/Exportieren\">Exportieren</a> ";}
	if (cms_r("schulhof.oragnisation.schulanmeldung.akzeptieren")) {$aktionen .= "<span class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/drucken.png');\" onclick=\"cms_schulanmeldung_drucken('alle');\">Alle Drucken</span> ";}

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
			$code .= "<tr><th></th><th>Profil</th><th>Vorname</th><th>Nachname</th><th>Geburtsdatum</th><th>Ort</th><th>Eingegangen</th><th></th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody id=\"cms_voranmeldung_schueler\">";
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert, AES_DECRYPT(eingegangen, '$CMS_SCHLUESSEL') AS eingegangen, AES_DECRYPT(kuenftigesprofil, '$CMS_SCHLUESSEL') AS profil FROM voranmeldung_schueler) AS schueler ORDER BY nachname ASC, vorname ASC, geburtsdatum ASC, ort ASC");

		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($said, $savor, $sanach, $sageb, $saort, $saakz, $saeing, $aprofil);
			while ($sql->fetch()) {
				$ausgabe .= "<tr>";
					// 0: Wurde aufgenommen?
					$meta = 0;
					$meta |= (($saakz == "ja") << 0);
					$hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"$said\"><input type=\"hidden\" class=\"cms_multiselect_meta\" value=\"".$meta."\">";

					$ausgabe .= "<td class=\"cms_multiselect\">$hmeta<img src=\"res/icons/klein/schulanmeldung.png\"></td>";
					$ausgabe .= "<td>$aprofil</td>";
					$ausgabe .= "<td>$savor</td>";
					$ausgabe .= "<td>$sanach</td>";
					$ausgabe .= "<td>".date('d', $sageb).". ".cms_monatsnamekomplett(date('m', $sageb))." ".date('Y', $sageb)."</td>";
					$ausgabe .= "<td>$saort</td>";
					$ausgabe .= "<td>".date('d.m.Y', $saeing)." – ".date('H:i', $saeing)."</td>";
					if ($saakz == 'ja') {$icon = "gruen.png";$beschreibung = "aufgenommen";}
					else {$icon = "rot.png"; $beschreibung = "Aufnahme ausstehend";}
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$beschreibung</span><img src=\"res/icons/klein/$icon\"></span> </td>";
					$ausgabe .= "<td>";
					if (cms_r("schulhof.organisation.schulanmeldung.bearbeiten")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulanmeldung_bearbeiten_vorbereiten($said);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if (cms_r("schulhof.organisation.schulanmeldung.akzeptieren")) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulanmeldung_drucken($said);\"><span class=\"cms_hinweis\">Drucken</span><img src=\"res/icons/klein/drucken.png\"></span> ";
						if (($saakz != 'ja')) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulanmeldung_aufnehmen('$savor $sanach', $said);\"><span class=\"cms_hinweis\">Aufnehmen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
						}
						else if (($saakz != 'nein')) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_wichtig\" onclick=\"cms_schulanmeldung_ablehnen('$savor $sanach', $said);\"><span class=\"cms_hinweis\">Ablehnen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
						}
					}
					if (cms_r("schulhof.organisation.schulanmeldung.löschen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulanmeldung_loeschen_anzeigen('$savor $sanach', $said);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}
					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
		}
		$sql->close();

		if (strlen($ausgabe) == 0) {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"8\">- keine Datensätze gefunden -</td></tr>";
		} else {
			$ausgabe .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"8\">";
			$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" data-multiselect-maske=\"1\" onclick=\"cms_multiselect_schulanmeldung_aufnehmen();\"><span class=\"cms_hinweis\">Alle aufnehmen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
			$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_wichtig\" data-multiselect-maske=\"2\" onclick=\"cms_multiselect_schulanmeldung_ablehnen();\"><span class=\"cms_hinweis\">Alle ablehnen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
			if (cms_r("schulhof.organisation.schulanmeldung.löschen")) {
				$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_schulanmeldungen_loeschen_anzeigen();\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
			}
			$ausgabe .= "</tr>";
		}
		$code .= $ausgabe."</tbody>";
	$code .= "</table>";

	if ($eingegangen > 0) {
		if (cms_r("schulhof.organisation.schulanmeldung.löschen")) {$code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_schulanmeldung_alleloeschen_anzeigen()\">Alle Datensätze löschen</span></p>";}
	}

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
