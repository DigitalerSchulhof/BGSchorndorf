<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.planungszeiträume.anlegen")) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['ZEITRAUMKLONEN'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(zeitraeume.bezeichnung, '$CMS_SCHLUESSEL') AS zbez FROM zeitraeume JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?");
    $sql->bind_param('i', $_SESSION['ZEITRAUMKLONEN']);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez, $zbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    $code .= "<h1>Zeitraum »".$zbez."« klonen im Schuljahr $sjbez</h1>";
		include_once('php/schulhof/seiten/verwaltung/zeitraeume/zeitraumdetails.php');

    $jetzt = time();
    $beginn = mktime(0,0,0,date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
  	$ende = mktime(0,0,0,date('m', $jetzt)+6, date('d', $jetzt)-1, date('Y', $jetzt));

    $code .= "<h3>Details</h3>";
  	$code .= "<table class=\"cms_formular\">";
  		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraeume_bezeichnung\" id=\"cms_zeitraeume_bezeichnung\" value=\"\"></td></tr>";
  		$code .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe('cms_zeitraeume_beginn', date('d', $beginn), date('m', $beginn), date('Y', $beginn))."</td></tr>";
  		$code .= "<tr><th>Ende:</th><td>".cms_datum_eingabe('cms_zeitraeume_ende', date('d', $ende), date('m', $ende), date('Y', $ende))."</td></tr>";
  	$code .= "</table>";
    $code .= cms_meldung('info', '<h4>Datenübernahme</h4><p>Unterrichtstage und -stunden sowie die Stundenplanung des gewählten Zeitraums werden kopiert.</p>');
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_zeitraeume_klonen_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Zeiträume\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Zeitraum bearbeiten</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Zeitraum bearbeiten</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
