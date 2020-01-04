<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (r("schulhof.planung.schuljahre.fächer.anlegen")) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['FÄCHERSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['FÄCHERSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Fächer importieren für das Schuljahr $sjbez</h1>";
    $code .= "<table class=\"cms_formular\">";
    $analysieren = "cms_import_analysieren('cms_faecher_import_csv', 'cms_faecher_import_trennung', cms_faecher_import)";
    $code .= "<tr><th>CSV-Daten:</th><td><textarea class=\"cms_textarea cms_code\" id=\"cms_faecher_import_csv\" name=\"cms_faecher_import_csv\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></textarea></td></tr>";
    $code .= "<tr><th>Trennzeichen:</th><td><input class=\"cms_klein cms_code\" name=\"cms_faecher_import_trennung\" type=\"text\" id=\"cms_faecher_import_trennung\" onkeyup=\"$analysieren\" onchange=\"$analysieren\"></input> <span class=\"cms_button_wichtig\" onclick=\"$analysieren\">Analysieren</span></td></tr>";
    $code .= "<tr><th>Bezeichnung:</th><td>";
      $code .= "<select name=\"cms_faecher_import_bezeichnung\" id=\"cms_faecher_import_bezeichnung\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Kürzel:</th><td>";
      $code .= "<select name=\"cms_faecher_import_kuerzel\" id=\"cms_faecher_import_kuerzel\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Farbe:</th><td>";
      $code .= "<select name=\"cms_faecher_import_farbe\" id=\"cms_faecher_import_farbe\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "<tr><th>Icon:</th><td>";
      $code .= "<select name=\"cms_faecher_import_icon\" id=\"cms_faecher_import_icon\" disabled=\"disabled\">";
    $code .= "<option value=\"-\">nicht importieren</option></select></td></tr>";
    $code .= "</table>";

    $code .= cms_meldung('info','<h4>Dopplungen</h4><p>Ergeben sich doppelte Einträge durch das Auswählen der Spalten und dadurch ggf. wegfallende Informationen, oder dadurch, dass bereits Datensätze bestehen, so wird der erste Eintrag gespeichert bzw. der bestehende Eintrag beibehalten und alle folgenden verworfen.</p>');

		$code .= "<p><span class=\"cms_button\" onclick=\"cms_faecher_import_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Fächer\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Fächer importieren</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Fächer importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
