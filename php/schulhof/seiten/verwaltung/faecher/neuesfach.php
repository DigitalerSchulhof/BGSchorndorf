<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.fächer.anlegen")) {
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
    $code .= "<h1>Neues Fach anlegen für das Schuljahr $sjbez</h1>";
		include_once('php/schulhof/seiten/verwaltung/faecher/faecherdetails.php');

		$code .= cms_faecher_ausgeben('-');
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_faecher_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Fächer\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Neues Fach anlegen</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Neues Fach anlegen</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
