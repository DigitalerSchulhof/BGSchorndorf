<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

$code = "";
if ($zugriff) {
  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginn, ende FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez, $sjbeginn, $sjende);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Folgeschuljahr des Schuljahrs $sjbez erzeugen</h1>";

    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p>Ferner stehen in der Fabrik nicht alle Funktionen zur Verfügung, die bei einer einzelnen Erstellung von Stufen, Klassen und Kursen bereit stehen. So folgt beispielsweise die Benennung der Klassen und Kurse einer vorgegebenen Systematik.</p>');
    echo $code;

    $code = "<h2>Schuljahrdetails</h2>";
    $code .= "<table class=\"cms_formular\">";
      $sjneubeginn = mktime(0,0,0,date('m', $sjbeginn), date('d', $sjbeginn), date('Y', $sjbeginn)+1);
      $sjneuende = mktime(23,59,59,date('m', $sjende), date('d', $sjende), date('Y', $sjende)+1);
      $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_sjfabrik_sjbez\" id=\"cms_sjfabrik_sjbez\" value=\"".date('Y', $sjneubeginn)."-".date('Y', $sjneuende)."\"></td></tr>";
    $code .= "</table>";

    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_generieren();\">+ Neues Schuljahr erzeugen</span></p>";
  }
  else {$code .= "<h1>Stundenplanzeiträume</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Stundenplanzeiträume</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
