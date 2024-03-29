<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuljahre</h1>

<?php
if (cms_r("schulhof.planung.schuljahre.[|anlegen,bearbeiten,löschen]")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Beginn</th><th>Ende</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre ORDER BY beginn DESC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($sjid, $sjbez, $sjbeginn, $sjende);
			while ($sql->fetch()) {
				$ausgabe .= "<tr>";
					$hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"$sjid\">";

					$ausgabe .= "<td class=\"cms_multiselect\">$hmeta<img src=\"res/icons/klein/schuljahr.png\"></td>";
					$ausgabe .= "<td>$sjbez</td>";
					$beginn = date('d.m.Y', $sjbeginn);
					$ende = date('d.m.Y', $sjende);
					$ausgabe .= "<td>".$beginn."</td>";
					$ausgabe .= "<td>".$ende."</td>";

					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($sjbez);
					if (cms_r("schulhof.planung.schuljahre.bearbeiten")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_schuljahr_bearbeiten_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.planungszeiträume.anlegen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_stundenplanzeitraeume_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Zeiträume</span><img src=\"res/icons/klein/stundenplanzeitraeume.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.fächer.anlegen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_faecher_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Fächer</span><img src=\"res/icons/klein/faecher.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.profile.anlegen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_profile_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Profile</span><img src=\"res/icons/klein/profile.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.verantwortlichkeiten")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_verantwortlichkeiten_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Verantwortlichkeiten festlegen (Klassen, Stufen, Räume)</span><img src=\"res/icons/klein/verantwortlichkeiten.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.fabrik")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schuljahrfabrik_vorbereiten($sjid);\"><span class=\"cms_hinweis\">Nächstes Schuljahr aus diesem erzeugen</span><img src=\"res/icons/klein/schuljahrfabrik.png\"></span> ";
					}
					if (cms_r("schulhof.planung.schuljahre.löschen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_schuljahr_loeschen_anzeigen('$bezeichnung', $sjid);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
		} else {
			$ausgabe .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"5\">";
			if (cms_r("schulhof.planung.schuljahre.löschen")) {
				$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_schulhof_schuljahr_loeschen_anzeigen();\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
			}
			$ausgabe .= "</tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if (cms_r("schulhof.planung.schuljahre.anlegen")) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Schuljahre/Neues_Schuljahr_anlegen\">+ Neues Schuljahr anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
