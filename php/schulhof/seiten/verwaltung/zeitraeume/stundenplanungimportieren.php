<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];
$code = "";
if ($zugriff) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }
  if (isset($_SESSION['ZEITRAUMSTUNDENPLANIMPORT'])) {
    $ZEITRAUM = $_SESSION['ZEITRAUMSTUNDENPLANIMPORT'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM zeitraeume WHERE id = ?");
    $sql->bind_param('i', $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $zbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Stundenplanung importieren für das Schuljahr $sjbez in Zeitraum $zbez</h1>";
    $code .= "<table class=\"cms_formular\">";
    $analysieren = "cms_import_analysieren('cms_stundenplanung_import_csv', 'cms_stundenplanung_import_trennung', cms_stundenplanung_import)";
    $code .= "<tr><th>CSV-Daten:</th><td><textarea class=\"cms_textarea cms_code\" id=\"cms_stundenplanung_import_csv\" name=\"cms_stundenplanung_import_csv\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></textarea></td></tr>";
    $code .= "<tr><th>Trennzeichen:</th><td><input class=\"cms_klein cms_code\" name=\"cms_stundenplanung_import_trennung\" type=\"text\" id=\"cms_stundenplanung_import_trennung\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></input> <span class=\"cms_button_wichtig\" onclick=\"$analysieren\">Analysieren</span></td></tr>";
    $code .= "<tr><th>Lehrer:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_lehrer\" id=\"cms_stundenplanung_import_lehrer\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Tag:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_tag\" id=\"cms_stundenplanung_import_tag\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Stunde:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_stunde\" id=\"cms_stundenplanung_import_stunde\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Fach:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_fach\" id=\"cms_stundenplanung_import_fach\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Raum:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_raum\" id=\"cms_stundenplanung_import_raum\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Schienen:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_schienen\" id=\"cms_stundenplanung_import_schienen\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Klasse:</th><td>";
      $code .= "<select name=\"cms_stundenplanung_import_klasse\" id=\"cms_stundenplanung_import_klasse\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Zuordnen:</th><td>".cms_schieber_generieren("stundenplanung_import_zuordnen", 0)."</td></tr>";
    $code .= "</table>";

    $code .= "<p><input type=\"hidden\" name=\"cms_stundenplanung_import_schuljahr\" id=\"cms_stundenplanung_import_schuljahr\" value=\"$SCHULJAHR\"></p>";

    $code .= cms_meldung('info','<h4>Dopplungen</h4><p>Ergeben sich doppelte Einträge durch das Auswählen der Spalten und dadurch ggf. wegfallende Informationen, oder dadurch, dass bereits Datensätze bestehen, so wird der erste Eintrag gespeichert bzw. der bestehende Eintrag beibehalten und alle folgenden verworfen.</p>');

    $code .= cms_meldung('info','<h4>Fehlende Kurse</h4><p>Kurse, die noch nicht existeren, werden neu angelegt. Alle Klassenmitglieder der jeweiligen Klassen werden hinzugefügt. Bitte bearbeiten Sie nach, wer wirklich in diesen Kursen ist.</p><p>Fächer, Lehrer, Stufen, Klassen und Räume müssen bereits existieren. Wenn nicht, werden die jeweiligen Zeilen nicht beachtet.</p>');

		$code .= "<p><span class=\"cms_button\" onclick=\"cms_stundenplanung_import_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Fächer\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Stundenplanung in Zeitraum importieren</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Stundenplanung in Zeitraum importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
