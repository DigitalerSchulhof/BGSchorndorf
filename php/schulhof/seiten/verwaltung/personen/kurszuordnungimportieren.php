<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = cms_r("schulhof.verwaltung.personen.zuordnen.kurse");
$code = "";
if ($zugriff) {
  $SCHULJAHRE = "";
  $STUFEN = "";
  $SCHULJAHR = null;
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
      if ($SCHULJAHR === null) {$SCHULJAHR = $sid;}
			$SCHULJAHRE .= "<option value=\"$sid\">$sbez</option>";
		}
	}
	$sql->close();
  if ($SCHULJAHR !== null) {
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
    $sql->bind_param("i", $SCHULJAHR);
  	if ($sql->execute()) {
  		$sql->bind_result($sid, $sbez);
  		while ($sql->fetch()) {
  			$STUFEN .= "<option value=\"$sid\">$sbez</option>";
  		}
  	}
  	$sql->close();
  }


  $code .= "<h1>Kurszuordnungen importieren</h1>";
  $code .= "<table class=\"cms_formular\">";
  $code .= "<tr><th>Schuljahr:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_schuljahr\" id=\"cms_kurszuordnungimport_schuljahr\" onchange=\"cms_kurszuordnungimport_stufen_laden();\">$SCHULJAHRE</select></td></tr>";
  $code .= "<tr><th>Stufe:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_stufe\" id=\"cms_kurszuordnungimport_stufe\">$STUFEN</select></td></tr>";
  $analysieren = "cms_import_analysieren('cms_kurszuordnungimport_csv', 'cms_kurszuordnungimport_trennung', cms_kurszuordnungimport)";
  $code .= "<tr><th>CSV-Daten:</th><td><textarea class=\"cms_textarea cms_code\" id=\"cms_kurszuordnungimport_csv\" name=\"cms_kurszuordnungimport_csv\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></textarea></td></tr>";
  $code .= "<tr><th>Trennzeichen:</th><td><input class=\"cms_klein cms_code\" name=\"cms_kurszuordnungimport_trennung\" type=\"text\" id=\"cms_kurszuordnungimport_trennung\" onkeyup=\"$analysieren\" onchange=\"$analysieren\" value=\";\"> <span class=\"cms_button_wichtig\" onclick=\"$analysieren\">Analysieren</span></td></tr>";
  $code .= "<tr><th>Kurs:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_kurs\" id=\"cms_kurszuordnungimport_kurs\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Tutor:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_tutor\" id=\"cms_kurszuordnungimport_tutor\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Schüler-ID:</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_schueler\" id=\"cms_kurszuordnungimport_schueler\" disabled=\"disabled\">";
  $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
  $code .= "<tr><th>Welche ID?</th><td>";
    $code .= "<select name=\"cms_kurszuordnungimport_idart\" id=\"cms_kurszuordnungimport_idart\">";
  $code .= "<option value=\"sh\">Schulhof-ID</option><option value=\"zweit\" selected=\"selected\">Zweit-ID</option><option value=\"dritt\">Dritt-ID</option><option value=\"viert\">Viert-ID</option></select></td></tr>";
  $code .= "</table>";

  $code .= cms_meldung('info','<h4>Dopplungen</h4><p>Ergeben sich doppelte Einträge durch das Auswählen der Spalten und dadurch ggf. wegfallende Informationen, oder dadurch, dass bereits Datensätze bestehen, so wird der erste Eintrag gespeichert bzw. der bestehende Eintrag beibehalten und alle folgenden verworfen.</p>');

  $code .= cms_meldung('info','<h4>Fehlende Personen udn Kurse</h4><p>Personen oder Kurse, die noch nicht existeren, werden ignoriert.</p>');

	$code .= "<p><span class=\"cms_button\" onclick=\"cms_kurszuordnungimport_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Abbrechen</a></p>";
}
else {
	$code .= "<h1>Personen-IDs importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
