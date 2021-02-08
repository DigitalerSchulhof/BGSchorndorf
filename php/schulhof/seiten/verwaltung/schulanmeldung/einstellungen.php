<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Einstellungen</h1>

<?php
if (cms_r("schulhof.organisation.schulanmeldung.vorbereiten")) {
	include_once("php/schulhof/seiten/website/editor/editor.php");
	$code = "";

	$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
	$code .= "<h2>Details</h2>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Voranmeldung aktiv</th><td>".cms_generiere_schieber('voranmeldung_aktiv', $CMS_VORANMELDUNG['Anmeldung aktiv'])."</td></tr>";
	$code .= "<tr><th>Beginn der Voranmeldung</th><td>".cms_datum_eingabe('cms_voranmeldung_beginn', date('d', $CMS_VORANMELDUNG['Anmeldung von']), date('m', $CMS_VORANMELDUNG['Anmeldung von']), date('Y', $CMS_VORANMELDUNG['Anmeldung von']))." – ";
	$code .= cms_uhrzeit_eingabe('cms_voranmeldung_beginn', date('H', $CMS_VORANMELDUNG['Anmeldung von']), date('i', $CMS_VORANMELDUNG['Anmeldung von']))."</td></tr>";
	$code .= "<tr><th>Ende der Voranmeldung</th><td>".cms_datum_eingabe('cms_voranmeldung_ende', date('d', $CMS_VORANMELDUNG['Anmeldung bis']), date('m', $CMS_VORANMELDUNG['Anmeldung bis']), date('Y', $CMS_VORANMELDUNG['Anmeldung bis']))." – ";
	$code .= cms_uhrzeit_eingabe('cms_voranmeldung_ende', date('H', $CMS_VORANMELDUNG['Anmeldung bis']), date('i', $CMS_VORANMELDUNG['Anmeldung bis']))."</td></tr>";
	$code .= "<tr><th>Persönliche Anmeldung notwendig</th><td>".cms_generiere_schieber('persoenlich_noetig', $CMS_VORANMELDUNG['Persönlich nötig'], 'cms_voranmeldung_persoenlich_anaus()')."</td></tr>";
	if ($CMS_VORANMELDUNG['Persönlich nötig'] != '1') {
		$zusatzklasse = "class=\"cms_versteckt\"";
	} else {
		$zusatzklasse = "";
	}


	$code .= "<tr id=\"cms_voranmeldung_persoenlich_beginn_f\"$zusatzklasse><th>Beginn der persönlichen Anmeldung</th><td>".cms_datum_eingabe('cms_voranmeldung_persoenlich_beginn', date('d', $CMS_VORANMELDUNG['Anmeldung persönlich von']), date('m', $CMS_VORANMELDUNG['Anmeldung persönlich von']), date('Y', $CMS_VORANMELDUNG['Anmeldung persönlich von']))."</td></tr>";
	$code .= "<tr id=\"cms_voranmeldung_persoenlich_ende_f\"$zusatzklasse><th>Ende der persönlichen Anmeldung</th><td>".cms_datum_eingabe('cms_voranmeldung_persoenlich_ende', date('d', $CMS_VORANMELDUNG['Anmeldung persönlich bis']), date('m', $CMS_VORANMELDUNG['Anmeldung persönlich bis']), date('Y', $CMS_VORANMELDUNG['Anmeldung persönlich bis']))."</td></tr>";
	$code .= "<tr><th>Löschung erfolgt höchstens </th><td><input type=\"text\" name=\"cms_voranmeldung_ueberhang\" id=\"cms_voranmeldung_ueberhang\" value=\"".$CMS_VORANMELDUNG['Anmeldung Überhang Tage']."\" class=\"cms_klein\" onchange=\"cms_nur_ganzzahl('cms_voranmeldung_ueberhang','7','1','1000')\"> Tage nach Anmeldeschluss</td></tr>";
	$code .= "<tr><th>Eintrittsalter an der neuen Schule (Voreinstellung)</th><td><input type=\"text\" name=\"cms_voranmeldung_eintritt\" id=\"cms_voranmeldung_eintritt\" value=\"".$CMS_VORANMELDUNG['Anmeldung Eintrittsalter']."\" class=\"cms_klein\" onchange=\"cms_nur_ganzzahl('cms_voranmeldung_eintritt','10','1','100')\"> Jahre</td></tr>";
	$code .= "<tr><th>Einschulungsalter  (Voreinstellung)</th><td><input type=\"text\" name=\"cms_voranmeldung_einschulung\" id=\"cms_voranmeldung_einschulung\" value=\"".$CMS_VORANMELDUNG['Anmeldung Einschulungsalter']."\" class=\"cms_klein\" onchange=\"cms_nur_ganzzahl('cms_voranmeldung_einschulung','6','1','100')\"> Jahre</td></tr>";
	$code .= "<tr><th>Letzte Klasse</th><td><input type=\"text\" name=\"cms_voranmeldung_klassenstufe\" id=\"cms_voranmeldung_klassenstufe\" value=\"".$CMS_VORANMELDUNG['Anmeldung Klassenstufe']."\" class=\"cms_klein\" onchange=\"cms_nur_ganzzahl('cms_voranmeldung_klassenstufe','4','1','20')\"></td></tr>";
	$code .= "</table>";

	$code .= "<h2>Informationen</h2>";
	$code .= cms_webeditor('cms_voranmeldung_einleitung', $CMS_VORANMELDUNG['Anmeldung Einleitung']);

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schulanmeldung_einstellungen_aendern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Schulanmeldung\">Abbrechen</a></p>";

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
