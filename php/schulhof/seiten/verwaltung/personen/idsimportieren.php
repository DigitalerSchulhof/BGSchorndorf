<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Personen']['Personenids importieren'];
$code = "";
if ($zugriff) {
  $code .= "<h1>Personen-IDs importieren</h1>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Personenart:</th><td>";
    $code .= "<select name=\"cms_personenimport_art\" id=\"cms_personenimport_art\">";
  $code .= "<option value=\"s\">Schüler</option><option value=\"l\">Lehrer</option><option value=\"e\">Eltern</option><option value=\"v\">Verwaltungsangestellte</option><option value=\"x\">Externe</option></select></td></tr>";
  $code .= "<tr><th>ID-Slot:</th><td>";
    $code .= "<select name=\"cms_personenimport_idslot\" id=\"cms_personenimport_idslot\">";
  $code .= "<option value=\"zweitid\">Zweit-ID</option><option value=\"drittid\">Dritt-ID</option><option value=\"viertid\">Viert-ID</option></select></td></tr>";
  $analysieren = "cms_import_analysieren('cms_personenimport_csv', 'cms_personenimport_trennung', cms_personenimport)";
  $code .= "<tr><th>CSV-Daten:</th><td><textarea class=\"cms_textarea cms_code\" id=\"cms_personenimport_csv\" name=\"cms_personenimport_csv\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></textarea></td></tr>";
  $code .= "<tr><th>Trennzeichen:</th><td><input class=\"cms_klein cms_code\" name=\"cms_personenimport_trennung\" type=\"text\" id=\"cms_personenimport_trennung\" onkeyup=\"$analysieren\" onchange=\"$analysieren\" value=\";\"> <span class=\"cms_button_wichtig\" onclick=\"$analysieren\">Analysieren</span></td></tr>";
  $code .= "<tr><th>ID:</th><td>";
    $code .= "<select name=\"cms_personenimport_id\" id=\"cms_personenimport_id\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Vorname, Nachname:</th><td>";
    $code .= "<select name=\"cms_personenimport_vornach\" id=\"cms_personenimport_vornach\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Nachname, Vorname:</th><td>";
    $code .= "<select name=\"cms_personenimport_nachvor\" id=\"cms_personenimport_nachvor\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Nachname:</th><td>";
    $code .= "<select name=\"cms_personenimport_nach\" id=\"cms_personenimport_nach\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Vorname:</th><td>";
    $code .= "<select name=\"cms_personenimport_vor\" id=\"cms_personenimport_vor\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "</table>";

  $code .= cms_meldung('info','<h4>Dopplungen</h4><p>Ergeben sich doppelte Einträge durch das Auswählen der Spalten und dadurch ggf. wegfallende Informationen, oder dadurch, dass bereits Datensätze bestehen, so wird der erste Eintrag gespeichert bzw. der bestehende Eintrag beibehalten und alle folgenden verworfen.</p>');

  $code .= cms_meldung('info','<h4>Fehlende Personen</h4><p>Personen, die noch nicht existeren, werden ignoriert.</p>');

  $code .= cms_meldung('warnung','<h4>ID-Dopplungen</h4><p>Nach dem Import können importierte IDs gegebenenfalls doppelt vorliegen! Für die Eindeutigkeit der IDs ist der Anwender verantwortlich!</p>');

	$code .= "<p><span class=\"cms_button\" onclick=\"cms_personenimport_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";
}
else {
	$code .= "<h1>Personen-IDs importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
