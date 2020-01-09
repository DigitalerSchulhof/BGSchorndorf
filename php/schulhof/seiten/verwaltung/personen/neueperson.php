<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Person anlegen</h1>

<?php
if (cms_r("schulhof.verwaltung.personen.anlegen")) {
?>



<h3>Persönliche Daten</h3>
<table class="cms_formular">
	<tr><th>Art:</th>
		<td><select name="cms_schulhof_verwaltung_personen_neu_art" id="cms_schulhof_verwaltung_personen_neu_art" onchange="cms_schulhof_verwaltung_personen_neu_art_wechsel();">
			<option value="s">Schüler</option>
			<option value="l">Lehrer</option>
			<option value="e">Eltern</option>
			<option value="v">Verwaltungsangestellte</option>
			<option value="x">Externe</option>
		</select></td>
	</tr>
	<tr><th>Titel:</th><td><input type="text" name="cms_schulhof_verwaltung_personen_neu_titel" id="cms_schulhof_verwaltung_personen_neu_titel"></td></tr>
	<tr><th>Vorname:</th><td><input type="text" name="cms_schulhof_verwaltung_personen_neu_vorname" id="cms_schulhof_verwaltung_personen_neu_vorname"></td></tr>
	<tr><th>Nachname:</th><td><input type="text" name="cms_schulhof_verwaltung_personen_neu_nachname" id="cms_schulhof_verwaltung_personen_neu_nachname"></td></tr>
	<tr><th>Geschlecht:</th>
		<td><select name="cms_schulhof_verwaltung_personen_neu_geschlecht" id="cms_schulhof_verwaltung_personen_neu_geschlecht">
			<option value="-">-</option>
			<option value="m">&#x2642;</option>
			<option value="w">&#x2640;</option>
			<option value="u">&#x26a5;</option>
		</select></td>
	</tr>
</table>

<div id="cms_schulhof_verwaltung_person_neu_sonstiges" style="display: none;">
<h3>Lehrerinformationen</h3>
<table class="cms_formular">
	<tr id="cms_schulhof_verwaltung_personen_neu_lehrerkuerzel_z"><th>Lehrerkürzel:</th><td><input type="text" name="cms_schulhof_verwaltung_personen_neu_lehrerkuerzel" id="cms_schulhof_verwaltung_personen_neu_lehrerkuerzel" value=""></td></tr>
		<tr id="cms_schulhof_verwaltung_personen_neu_stundenplan_z"><th>Stundenplan:</th><td>
		<?php
		echo cms_dateiwahl_knopf ("schulhof/stundenplaene", "cms_schulhof_verwaltung_personen_neu_stundenplan", "s", "Stundenplan", "-", "download", "")
		?>
	</td></tr>
</table>
</div>


<p><span class="cms_button" onclick="cms_schulhof_verwaltung_personen_neu_speichern();">Speichern</span> <a class="cms_button_nein" href="Schulhof/Verwaltung/Personen">Abbrechen</a></p>
</div>
<?php
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
?>
