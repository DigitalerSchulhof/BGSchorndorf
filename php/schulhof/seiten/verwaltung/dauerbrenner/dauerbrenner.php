<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Dauerbrenner</h1>

<?php
if (cms_r("schulhof.information.dauerbrenner.*")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Sichtbarkeit</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx FROM dauerbrenner) AS dauerbrenner ORDER BY bezeichnung ASC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($did, $dbez, $dsichtbars, $dsichtbarl, $dsichtbare, $dsichtbarv, $dsichtbarx);
			while ($sql->fetch()) {
				$ausgabe .= "<tr>";
					$ausgabe .= "<td><img src=\"res/icons/klein/dauerbrenner.png\"></td>";
					$ausgabe .= "<td>$dbez</td>";
					$ausgabe .= "<td>";
					if ($dsichtbarl == 1) {$ausgabe .= cms_generiere_hinweisicon("lehrer", "Lehrer")." ";}
					if ($dsichtbars == 1) {$ausgabe .= cms_generiere_hinweisicon("schueler", "Schüler")." ";}
					if ($dsichtbare == 1) {$ausgabe .= cms_generiere_hinweisicon("elter", "Eltern")." ";}
					if ($dsichtbarv == 1) {$ausgabe .= cms_generiere_hinweisicon("verwaltung", "Verwaltungsangestellte")." ";}
					if ($dsichtbarx == 1) {$ausgabe .= cms_generiere_hinweisicon("extern", "Externe")." ";}
					$ausgabe .= "</td>";
					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($dbez);
					if (cms_r("schulhof.information.dauerbrenner.bearbeiten")) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_dauerbrenner_bearbeiten_vorbereiten($did);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if (cms_r("schulhof.information.dauerbrenner.löschen")) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_dauerbrenner_loeschen_anzeigen($did);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"4\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if (cms_r("schulhof.information.dauerbrenner.anlegen")) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Dauerbrenner/Neuen_Dauerbrenner_anlegen\">+ Neuen Dauerbrenner anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
