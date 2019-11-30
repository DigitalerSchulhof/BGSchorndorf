<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Schienen anlegen'];
$code = "";
if ($zugriff) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHIENESCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['SCHIENESCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Neue Schiene anlegen für das Schuljahr $sjbez</h1>";
		include_once('php/schulhof/seiten/verwaltung/schienen/schienendetails.php');

		$code .= cms_schiene_ausgeben('-', $SCHULJAHR);
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_schienen_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Schienen\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Neue Schiene anlegen</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Neue Schiene anlegen</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
