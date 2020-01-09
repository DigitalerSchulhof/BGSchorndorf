<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.planungszeiträume.bearbeiten")) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['ZEITRAUMBEARBEITEN'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') FROM zeitraeume JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?");
    $sql->bind_param('i', $_SESSION['ZEITRAUMBEARBEITEN']);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    $code .= "<h1>Zeitraum bearbeiten im Schuljahr $sjbez</h1>";
		include_once('php/schulhof/seiten/verwaltung/zeitraeume/zeitraumdetails.php');

		$code .= cms_zeitraum_ausgeben($_SESSION['ZEITRAUMBEARBEITEN']);
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_zeitraeume_bearbeiten_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Zeiträume\">Abbrechen</a></p>";
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
