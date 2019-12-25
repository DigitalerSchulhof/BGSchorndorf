<div class="cms_spalte_i">
	<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
	<?php
		if (r("schulhof.verwaltung.rechte.rollen.sehen || schulhof.verwaltung.rechte.rollen.erstellen || schulhof.verwaltung.rechte.rollen.bearbeiten || schulhof.verwaltung.rechte.rollen.löschen")) {
	?>

<h1>Rollen</h1>

	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Personen</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rollen) AS rollen ORDER BY id ASC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine ID
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$icon = "";
					if($daten["id"] == 0)
						$icon = " <span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/code_xml.png\"></span>";
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/rollen.png\"></span>$icon</td>";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";

					// Personen mit dieser Rolle suchen
					$sql = "SELECT vorname, nachname, titel FROM (SELECT DISTINCT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN rollenzuordnung ON personen.id = rollenzuordnung.person WHERE rolle = ".$daten['id'].") AS personen ORDER BY nachname, vorname";
					$personen = "";
					if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
						while ($daten2 = $anfrage2->fetch_assoc()) {
							$person = $daten2['vorname']." ".$daten2['nachname'];
							if (strlen($daten2['titel'])>0) {
								$person = $daten2['titel']." ".$person;
							}
							$personen .= ", ".$person;
						}
						$anfrage2->free();
					}
					if ($personen != "") {
						$ausgabe .= "<td>".(substr($personen, 2))."</td>";
					}
					else {$ausgabe .= "<td>Nicht zugeordnet</td>";}

					// Aktionen
					$ausgabe .= "<td>";
					if ($daten['id'] != 0) {
						$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
						if (r("schulhof.verwaltung.rechte.rollen.bearbeiten")) {
							$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_rolle_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
						}
						if (r("schulhof.verwaltung.rechte.rollen.löschen")) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_rolle_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
						}
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
	if (r("schulhof.verwaltung.rechte.rollen.erstellen"))
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Rollen/Neue_Rolle_anlegen\">+ Neue Rolle anlegen</a></p>";

	} else
		echo cms_meldung_berechtigung();
?>
</div>

<div class="cms_clear"></div>
