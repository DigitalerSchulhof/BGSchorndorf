<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fächer</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Fächer anlegen'] || $CMS_RECHTE['Organisation']['Fächer bearbeiten'] || $CMS_RECHTE['Organisation']['Fächer löschen'];

if ($zugriff) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Kürzel</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM faecher) AS klassenstufen ORDER BY bezeichnung ASC";

		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/fach.png\"></td>";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$ausgabe .= "<td>".$daten['kuerzel']."</td>";
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					if ($CMS_RECHTE['Organisation']['Fächer bearbeiten']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_faecher_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if ($CMS_RECHTE['Organisation']['Fächer löschen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_faecher_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}
					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
			$anfrage->free();
		}

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"4\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if ($CMS_RECHTE['Organisation']['Fächer anlegen']) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Fächer/Neues_Fach_anlegen\">+ Neues Fach anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
