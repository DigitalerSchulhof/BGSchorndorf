<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Personen auf dem Schulhof</h1>

<?php
if ($CMS_RECHTE['Personen']['Personen sehen']) {
?>
	<h4>Filter</h4>
	<table class="cms_formular">
		<tr>
			<th>Nachname</th>
			<th>Vorname</th>
			<th>Klasse</th>
			<th>Art der Person</th>
		</tr>
		<tr>
			<td><input name="cms_personenliste_nname"  id="cms_personenliste_nname" type="text" onkeyup="cms_personenliste_laden();"></td>
			<td><input name="cms_personenliste_vname"  id="cms_personenliste_vname" type="text" onkeyup="cms_personenliste_laden();"></td>
			<td><input name="cms_personenliste_klasse" id="cms_personenliste_klasse" type="text" onkeyup="cms_personenliste_laden();"></td>
			<td>
				<?php
				echo cms_togglebutton_generieren ('cms_personenliste_s', 'Schüler', '0', "cms_personenliste_laden();")." ";
				echo cms_togglebutton_generieren ('cms_personenliste_l', 'Lehrer', '0', "cms_personenliste_laden();")." ";
				echo cms_togglebutton_generieren ('cms_personenliste_e', 'Eltern', '0', "cms_personenliste_laden();")." ";
				echo cms_togglebutton_generieren ('cms_personenliste_v', 'Verwaltungsangestellte', '0', "cms_personenliste_laden();")." ";
				echo cms_togglebutton_generieren ('cms_personenliste_x', 'Externe', '0', "cms_personenliste_laden();");
				?>
			</td>
		</tr>
	</table>
	<p><span class="cms_button" onclick="cms_personenliste_laden();">Suchen</span></p>


	<h4>Personen</h4>
	<table class="cms_liste">
		<thead>
			<tr><th><img src="res/icons/klein/leer.png"></th><th>Titel</th><th>Nachname</th><th>Vorname</th><th></th><th></th><th>Aktionen</th></tr>
		</thead>
		<tbody id="cms_personenliste">
			<tr><td class="cms_notiz" colspan="7">- keine Datensätze gefunden -</td></tr>
		</tbody>
	</table>
<?php
	if ($CMS_RECHTE['Personen']['Personen anlegen']) {echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Personen/Neue_Person_anlegen\">+ Neue Person anlegen</a></p>";}
	// if ($CMS_RECHTE['Personen']['Personen löschen'] && !$CMS_IMLN) {echo cms_meldung('firewall', '<h4>Firewall</h4><p>Personen können nur aus dem Lehrernetz gelöscht werden. Andernfalls ist eine vollständige Löschung nicht möglich.</p>');}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>

<div class="cms_clear"></div>