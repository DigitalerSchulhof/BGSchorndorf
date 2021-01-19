<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Passwort ändern</h1>

<table class="cms_formular">
	<tr>
		<th>Altes Passwort:</th>
		<td><input type="password" name="cms_schulhof_nutzerkonto_profildaten_passwort_alt" id="cms_schulhof_nutzerkonto_profildaten_passwort_alt"></td>
	</tr>
	<tr>
		<th>Neues Passwort:</th>
		<td><input type="password" name="cms_schulhof_nutzerkonto_profildaten_passwort" id="cms_schulhof_nutzerkonto_profildaten_passwort" onkeyup="cms_check_passwort_staerke('nutzerkonto_profildaten_passwort')"></td><td><span class="cms_eingabe_icon" id="cms_schulhof_nutzerkonto_profildaten_passwort_staerke_icon"><img src="res/icons/klein/falsch.png"></span></td>
	</tr>
	<tr>
		<th>Passwort wiederholen:</th>
		<td><input type="password" name="cms_schulhof_nutzerkonto_profildaten_passwort_wiederholen" id="cms_schulhof_nutzerkonto_profildaten_passwort_wiederholen" onkeyup="cms_check_passwort_gleich('nutzerkonto_profildaten_passwort')"></td><td><span class="cms_eingabe_icon" id="cms_schulhof_nutzerkonto_profildaten_passwort_gleich_icon"></span></td>
	</tr>
</table>

<p><span class="cms_button" onclick="cms_schulhof_nutzerkonto_passwort_aendern();">Änderungen speichern</span> <a class="cms_button_nein" href="Schulhof/Nutzerkonto/Mein_Profil">Zurück</a></p>

</div>

<div class="cms_clear"></div>
