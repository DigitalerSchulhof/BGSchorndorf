<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Willkommen auf dem Schulhof des <?php echo $CMS_WICHTIG['Schulname Genitiv'];?>!</h1>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
<h2>Passwort vergessen</h2>

<table class="cms_formular">
	<tr><th>Benutzername:</th><td><input name="cms_schulhof_anmeldung_passwortvergessen_bentuzer" id="cms_schulhof_anmeldung_passwortvergessen_bentuzer" type="text"></td></tr>
	<tr><th>eMailadresse:</th><td><input name="cms_schulhof_anmeldung_passwortvergessen_mail" id="cms_schulhof_anmeldung_passwortvergessen_mail" type="text" onkeyup="cms_check_mail_wechsel('cms_schulhof_anmeldung_passwortvergessen_mail');"></td><td><span class="cms_eingabe_icon" id="cms_schulhof_anmeldung_passwortvergessen_mail_icon"></span></td></tr>
	<tr><td colspan="3">
		<p class="cms_notiz">Die eMailadresse, die auch im Schulhof hinterlegt ist.</p>
		<?php
		echo cms_meldung('info', '<h4>Bitte beachten:</h4><p>Das versendete Kennwort ist nur eine Stunde lang gültig und sollte daher sofort nach der Verwendung geändert werden.</p>')
		?>
	</td></tr>
</table>

<p><span class="cms_button_ja" onclick="cms_passwort_vergessen();">Neues Passwort zuschicken</span> <a class="cms_button" href="Schulhof/Anmeldung">zur Anmeldung</a></p>

<script>
document.onkeydown = function(event) {
	var pw = document.getElementById('cms_schulhof_anmeldung_passwortvergessen_mail');
	if ((event.keyCode == 13) && (focus)) {
  	cms_passwort_vergessen();
  }
}
</script>

</div>
</div>

<?php
	include_once('php/schulhof/seiten/neuundinfo.php')
?>

<div class="cms_clear"></div>
