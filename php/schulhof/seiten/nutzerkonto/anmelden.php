<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1><?php echo s("schulhof.willkommen", array("%schule%" => $CMS_SCHULE_GENITIV));?></h1>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
<h2><?php echo s('schulhof.seite.anmeldung.anmeldung.ueberschrift') ?></h2>

<?php
if (isset($CMS_URL[2])) {
	if ($CMS_URL[2] == "Automatische_Abmeldung") {
		$meldung = s('schulhof.seite.anmeldung.meldung.abmeldung.inaktivitaet.inhalt');
		echo cms_meldung("info", $meldung);
	}
	if ($CMS_URL[2] == "Bis_bald!") {
		$meldung = s('schulhof.seite.anmeldung.meldung.abmeldung.manuell.inhalt');
		echo cms_meldung("erfolg", $meldung);
	}
	if ($CMS_URL[2] == "Zugeschickt!") {
		$meldung = s('schulhof.seite.anmeldung.meldung.passwort.neu.inhalt');
		echo cms_meldung("info", $meldung);
	}
}
?>

<?php
$code = "";
$anmeldung_moeglich = true;
if ($anmeldung_moeglich) {

	// $meldung = s("schulhof.seite.anmeldung.meldung.bugs.kopf");
	// $meldung .= s("schulhof.seite.anmeldung.meldung.bugs.inhalt");
	// $code .= cms_meldung('info', $meldung);

	$code .= s("schulhof.seite.anmeldung.anmeldung.vorwort");

	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>".s('schulhof.seite.anmeldung.anmeldung.formular.benutzername').":</th><td><input name=\"cms_schulhof_anmeldung_bentuzer\" id=\"cms_schulhof_anmeldung_bentuzer\" type=\"text\"></td></tr>";
		$code .= "<tr><th>".s('schulhof.seite.anmeldung.anmeldung.formular.passwort').":</th><td><input name=\"cms_schulhof_anmeldung_passwort\" id=\"cms_schulhof_anmeldung_passwort\" type=\"password\"></td></tr>";
	$code .= "</table>";

	$code .= "<p class=\"cms_notiz\">".s('schulhof.seite.anmeldung.anmeldung.datenschutzhinweis')."</p>";
	$code .= "<p class=\"cms_notiz\">".s('schulhof.seite.anmeldung.anmeldung.cookies')."</p>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_anmelden();\">".s('schulhof.seite.anmeldung.anmeldung.erfolg')."</span> <a class=\"cms_button\" href=\"Schulhof/Passwort_vergessen\">".s('schulhof.seite.anmeldung.anmeldung.vergessen')."</a></p>";
}
else {
	$dbs = cms_verbinden('s');
	$sql = "UPDATE nutzerkonten SET sessiontimeout = 0, sessionid = ''";
	$dbs->query($sql);
	$meldung = s('schulhof.seite.anmeldung.meldung.wartung.kopf');
	$meldung .= s('schulhof.seite.anmeldung.meldung.wartung.inhalt');
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
