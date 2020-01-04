<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Pinnwände</h1>

<?php
if (r("schulhof.information.pinnwände.[|anlegen,bearbeiten,löschen]")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Sichtbarkeit</th><th>Schreibrecht</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');

		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx, schreibens, schreibenl, schreibene, schreibenv, schreibenx FROM pinnwaende) AS pinnwaende ORDER BY bezeichnung ASC";

		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/pinnwaende.png\"></td>";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$ausgabe .= "<td>";
					if ($daten['sichtbarl'] == 1) {$ausgabe .= cms_generiere_hinweisicon("lehrer", "Lehrer")." ";}
					if ($daten['sichtbars'] == 1) {$ausgabe .= cms_generiere_hinweisicon("schueler", "Schüler")." ";}
					if ($daten['sichtbare'] == 1) {$ausgabe .= cms_generiere_hinweisicon("elter", "Eltern")." ";}
					if ($daten['sichtbarv'] == 1) {$ausgabe .= cms_generiere_hinweisicon("verwaltung", "Verwaltungsangestellte")." ";}
					if ($daten['sichtbarx'] == 1) {$ausgabe .= cms_generiere_hinweisicon("extern", "Externe")." ";}
					$ausgabe .= "</td>";
					$ausgabe .= "<td>";
					if ($daten['schreibenl'] == 1) {$ausgabe .= cms_generiere_hinweisicon("lehrer", "Lehrer")." ";}
					if ($daten['schreibens'] == 1) {$ausgabe .= cms_generiere_hinweisicon("schueler", "Schüler")." ";}
					if ($daten['schreibene'] == 1) {$ausgabe .= cms_generiere_hinweisicon("elter", "Eltern")." ";}
					if ($daten['schreibenv'] == 1) {$ausgabe .= cms_generiere_hinweisicon("verwaltung", "Verwaltungsangestellte")." ";}
					if ($daten['schreibenx'] == 1) {$ausgabe .= cms_generiere_hinweisicon("extern", "Externe")." ";}
					$ausgabe .= "</td>";
					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					if (r("schulhof.information.pinnwände.bearbeiten")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_pinnwaende_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if (r("schulhof.information.pinnwände.löschen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_pinnwaende_loeschen_anzeigen(".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
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
	if (r("schulhof.information.pinnwände.anlegen")) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Pinnwände/Neue_Pinnwand_anlegen\">+ Neue Pinnwand anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
