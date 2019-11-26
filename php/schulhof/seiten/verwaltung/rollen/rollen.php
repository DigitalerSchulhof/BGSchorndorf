<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Rollen</h1>

<?php
$zugriff = $CMS_RECHTE['Personen']['Rollen anlegen'] || $CMS_RECHTE['Personen']['Rollen bearbeiten'] || $CMS_RECHTE['Personen']['Rollen löschen'];
if ($zugriff) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Rechte</th><th>Personen</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(personenart, '$CMS_SCHLUESSEL') AS personenart FROM rollen) AS rollen ORDER BY bezeichnung ASC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine ID
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					//icon
					$icon = "";
					if ($daten['personenart'] == 'l') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span>';}
					else if ($daten['personenart'] == 's') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span>';}
					else if ($daten['personenart'] == 'e') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span>';}
					else if ($daten['personenart'] == 'v') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltung</span><img src="res/icons/klein/verwaltung.png"></span>';}
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/rollen.png\"></span> ".$icon."</td>";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$kategorien = "";
					$rechtezahl = 0;
					$kategorienzahl = 0;
					if ($daten['id'] != 0) {
						$sql = "SELECT * FROM (SELECT DISTINCT AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie FROM rechte, rollenrechte WHERE rechte.id = rollenrechte.recht AND rolle = ".$daten['id'].") AS rechte ORDER BY kategorie ASC";
						if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
							while ($daten2 = $anfrage2->fetch_assoc()) {
								$kategorien .= $daten2['kategorie'].", ";
								$kategorienzahl ++;
							}
							$anfrage2->free();
						}
						$kategorien = substr($kategorien, 0, -2);
						$sql = "SELECT COUNT(*) as anzahl FROM rechte, rollenrechte WHERE rechte.id = rollenrechte.recht AND rolle = ".$daten['id']."";
						if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
							if ($daten2 = $anfrage2->fetch_assoc()) {
								$rechtezahl = $daten2['anzahl'];
							}
							$anfrage2->free();
						}

						if ($rechtezahl > 1) {$rechtezahl .= " Rechte aus ";}
						else {$rechtezahl .= " Recht aus ";}

						if ($kategorienzahl > 1) {$kategorien = $rechtezahl."den Kategorien ".$kategorien;}
						else {$kategorien = $rechtezahl."der Kategorie ".$kategorien;}
					}
					else {$kategorien = "alle";}

					$ausgabe .= "<td>".$kategorien."</td>";

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
					else {$ausgabe .= "<td>nicht zugeordnet</td>";}

					// Aktionen
					$ausgabe .= "<td>";
					if ($daten['id'] != 0) {
						$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
						if ($CMS_RECHTE['Personen']['Rollen bearbeiten']) {
							$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_rolle_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
						}
						if ($CMS_RECHTE['Personen']['Rollen löschen']) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_rolle_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
						}
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
	if ($CMS_RECHTE['Personen']['Rollen anlegen']) {echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Rollen/Neue_Rolle_anlegen\">+ Neue Rolle anlegen</a></p>";}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>

<div class="cms_clear"></div>
