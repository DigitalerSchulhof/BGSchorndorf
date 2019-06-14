<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Identitätsdiebstahl</h1>
<?php
echo cms_meldung("warnung", "<h4>Identitätsdiebstahl</h4><p>Wenn dieser Vorgang abgeschlossen wird, wird eine Sicherheitlücke gemeldet. Eine solche Meldung zieht vieles nach sich. Eine leichtfertige Meldung sollte unterbleiben und ist vergleichbar mit dem Ziehen der Notbremse in einem fahrenden Zug ohne triftigen Grund.</p><p><b>Ein Identitätsdiebstahl muss gemeldet werden, wenn einer vorliegt. Aber nicht leichtfertig.</b></p>");
?>

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
	<tr>
		<th>Erklärung</th>
		<td colspan="2"><p>Mir ist bewusst, dass ich gerade einen Identitätsdiebstahl melde.</p><p><?php echo cms_schieber_generieren('schulhof_nutzerkonto_profildaten_diebstahl', '0');?></p></td>
	</tr>
</table>

<p><span class="cms_button_wichtig" onclick="cms_nutzerkonto_identitaetsdiebstahl();">Identitätsdiebstahl melden</span> <a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil/Passwort_ändern">Passwort ohne Identitätsdiebstahl ändern</a> <a class="cms_button_nein" href="Schulhof/Nutzerkonto">Zurück</a></p>

</div>

<div class="cms_clear"></div>
