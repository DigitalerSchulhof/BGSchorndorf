<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuljahre</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Schuljahre anlegen'] || $CMS_RECHTE['Organisation']['Schuljahre bearbeiten'] || $CMS_RECHTE['Organisation']['Schuljahre löschen'];

if ($zugriff) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Beginn</th><th>Ende</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre) AS schuljahre ORDER BY beginn DESC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/schuljahr.png\"></td>";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$beginn = date('d.m.Y', $daten['beginn']);
					$ende = date('d.m.Y', $daten['ende']);
					$ausgabe .= "<td>".$beginn."</td>";
					$ausgabe .= "<td>".$ende."</td>";

					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					if ($CMS_RECHTE['Organisation']['Schuljahre bearbeiten']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_schuljahr_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if ($CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_stundenplanzeitraeume_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Zeiträume</span><img src=\"res/icons/klein/stundenplanzeitraeume.png\"></span> ";
					}
					if ($CMS_RECHTE['Planung']['Fächer anlegen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_faecher_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Fächer</span><img src=\"res/icons/klein/faecher.png\"></span> ";
					}
					if ($CMS_RECHTE['Planung']['Profile anlegen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_profile_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Profile</span><img src=\"res/icons/klein/profile.png\"></span> ";
					}
					if ($CMS_RECHTE['Planung']['Verantwortlichkeiten festlegen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_verantwortlichkeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Verantwortlichkeiten festlegen (Klassen, Stufen, Räume)</span><img src=\"res/icons/klein/verantwortlichkeiten.png\"></span> ";
					}
				 	if ($CMS_RECHTE['Planung']['Schuljahrfabrik']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schuljahrfabrik_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Nächstes Schuljahr aus diesem erzeugen</span><img src=\"res/icons/klein/schuljahrfabrik.png\"></span> ";
					}
					if ($CMS_RECHTE['Organisation']['Schuljahre löschen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_schuljahr_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
			$anfrage->free();
		}

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if ($CMS_RECHTE['Organisation']['Schuljahre anlegen']) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Schuljahre/Neues_Schuljahr_anlegen\">+ Neues Schuljahr anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
