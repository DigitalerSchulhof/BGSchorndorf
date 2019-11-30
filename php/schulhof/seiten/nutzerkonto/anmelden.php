<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Willkommen auf dem Schulhof des <?php echo $CMS_SCHULE_GENITIV;?>!</h1>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
<h2>Anmeldung</h2>

<?php
if (isset($CMS_URL[2])) {
	if ($CMS_URL[2] == "Automatische_Abmeldung") {
		$meldung = '<h4>Automatische Abmeldung nach Inaktivität</h4><p>Aus Sicherheitsgründen wurde nach langer Inaktivität eine Abmeldung durchgeführt.</p>';
		echo cms_meldung("info", $meldung);
	}
	if ($CMS_URL[2] == "Bis_bald!") {
		$meldung = '<h4>Erfolgreiche Abmeldung</h4><p>Die Abmeldung war erfolgreich. Bis bald!</p>';
		echo cms_meldung("erfolg", $meldung);
	}
	if ($CMS_URL[2] == "Zugeschickt!") {
		$meldung = '<h4>Neues Passwort</h4><p>Ein Passwort wurde an die hinterlegte eMailadresse geschickt. Dieses Passwort ist nur eine Stunde gültig. Das Passwort sollte direkt nach der Anmeldung geändert werden!</p>';
		echo cms_meldung("info", $meldung);
	}
}
?>

<?php
$code = "";
$anmeldung_moeglich = true;
if ($anmeldung_moeglich) {

	// $meldung = "<h4>Vertretungsplan</h4>";
	// $meldung .= "<p>Bis auf Weiteres sind im Schulhof keine Vertretungen hinterlegt. An der Lösung des Problems wird gearbeitet.</p>";
	// $code .= cms_meldung('info', $meldung);

	$code .= "<p>Um den Schulhof zu betreten, ist eine Anmeldung nötig ...</p>";

	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Benutzername:</th><td><input name=\"cms_schulhof_anmeldung_bentuzer\" id=\"cms_schulhof_anmeldung_bentuzer\" type=\"text\"></td></tr>";
		$code .= "<tr><th>Passwort:</th><td><input name=\"cms_schulhof_anmeldung_passwort\" id=\"cms_schulhof_anmeldung_passwort\" type=\"password\"></td></tr>";
	$code .= "</table>";

	$code .= "<p class=\"cms_notiz\"><b>Datenschutzhinweis:</b> Im Schulhof werden Daten anders verarbeitet, als auf der normalen Website. Was gespeichert und wie die Daten verarbeitet werden ist der <a href=\"Website/Datenschutz\">Datenschutzseite</a> zu entnehmen.</p>";
	$code .= "<p class=\"cms_notiz\">Mit der Anmeldung wird automatisch Einwilligung A erteilt.</p>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_anmelden();\">Anmelden</span> <a class=\"cms_button\" href=\"Schulhof/Passwort_vergessen\">Passwort vergessen?</a></p>";
}
else {
	$dbs = cms_verbinden('s');
	$sql = "UPDATE nutzerkonten SET sessiontimeout = 0, sessionid = ''";
	$dbs->query($sql);
	$meldung = "<h4>Wartung des Schulhofs</h4>";
	$meldung .= "<p>Der Schulhof wird momentan gewartet. Daher sind keine Anmeldungen möglich. Aktive Benutzer wurden abgemeldet.</p>";
	$code .= cms_meldung('bauarbeiten', $meldung);
}
echo $code;
?>


<script>
document.onkeydown = function(event) {
	var pw = document.getElementById('cms_schulhof_anmeldung_passwort');
	if ((event.keyCode == 13) && (focus)) {
  	cms_anmelden();
  }
}
</script>

</div>
</div>

<?php
	include_once('php/schulhof/seiten/neuundinfo.php')
?>

<div class="cms_clear"></div>
