<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Anmeldungen exportieren</h1>

<?php
if (r("schulhof.organisation.schulanmeldung.exportieren")) {

	$code = "";
	$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Gruppe:</th><td><select id=\"cms_voranmeldung_exportauswahl\" name=\"cms_voranmeldung_exportauswahl\">";
	$code .= "<option value=\"alle\">Alle Datensätze</option>";
	$code .= "<option value=\"auf\">Datensätze aufgenommener Schüler</option>";
	$code .= "<option value=\"aufohne\">Datensätze aufgenommener Schüler ohne Profil</option>";
	$code .= "<option value=\"aufbili\">Datensätze aufgenommener Schüler mit bilingualem Profil</option>";
	$code .= "<option value=\"abgelehnt\">Datensätze nicht aufgenommener Schüler</option>";
	$code .= "</select></td></tr>";
	$code .= "<tr><th>Klassenbezeichnung:</th><td><input id=\"cms_voranmeldung_klasse\" name=\"cms_voranmeldung_klasse\" type=\"text\"></td></tr>";
	$code .= "<tr><th>Ergebnis (CSV):</th><td><textarea id=\"cms_voranmeldung_exportergebnis\" name=\"cms_voranmeldung_exportergebnis\" style=\"height: 300px;\"></textarea></td></tr>";
	$code .= "</table>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schulanmeldung_exportieren();\">Export starten</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Schulanmeldung\">Abbrechen</a></p>";

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
