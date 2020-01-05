<div class="cms_spalte_4">
<div class="cms_spalte_i">
<?php
include_once('php/schulhof/seiten/verwaltung/stundenplanung/navigation.php');
echo cms_stundenplanung_navigation();
?>
</div>
</div>

<div class="cms_spalte_34">
<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

<h1>Stundenplanung</h1>

<?php
include_once('php/schulhof/seiten/verwaltung/stundenplanung/zeitraeume/ausgeben.php');

if (r("schulhof.planung.schuljahre.planungszeiträume.[|anlegen,bearbeiten,löschen]")) {
  $code = "";

  $schuljahrcode = "";
  $alleids = "";
  $jahrescode = "";
  $jetzt = time();

  $dbs = cms_verbinden('s');
  $schuljahre = array();
  $spalten = 3;
  $aktionen = false;
  if(r("schulhof.planung.schuljahre.planungszeiträume.[|bearbeiten,löschen] || schulhof.planung.schuljahre") || // FIXME: Recht hinzufügen
      $CMS_RECHTE['Planung']['Stundenpläne anlegen'] || $CMS_RECHTE['Planung']['Stundenpläne bearbeiten'] || $CMS_RECHTE['Planung']['Stundenpläne löschen']) {
    $aktionen = true;
    $spalten++;
  }

  // SQL für die gesuchten Schuljahre zusammenbauen
  $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre ORDER BY beginn DESC";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      array_push($schuljahre, $daten);
      $alleids .= "|".$daten['id'];
      if (($jetzt < $daten['ende']) && ($jetzt > $daten['beginn'])) {
        $zklasse = '_aktiv';
        $jahrescode = cms_stundenplanung_zeitraeume_ausgeben($dbs, $daten['id']);
      }
      else {$zklasse = "";}
      $schuljahrcode .= "<span id=\"cms_zeitraum_".$daten['id']."\" class=\"cms_toggle$zklasse\" onclick=\"cms_zeitraum_jahr_laden('".$daten['id']."', '$spalten')\">".$daten['bezeichnung']."</span> ";
    }
    $anfrage->free();
  }
  cms_trennen($dbs);

  if (strlen($schuljahrcode) > 0) {$schuljahrcode = "<p>".$schuljahrcode;}
  else {$schuljahrcode = "<p class=\"cms_notiz\">Keine Schuljahre angelegt.";}
  $code .= $schuljahrcode."<input type=\"hidden\" name=\"cms_zeitraum_ids\" id=\"cms_zeitraum_ids\" value=\"$alleids\"></p>";

  if (strlen($jahrescode) > 0) {
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th>Zeitraum</th><th>Aktiv</th>";
      if ($aktionen) {$code .= "<th>Aktionen</th>";}
      $code .= "</tr><tbody id=\"cms_zeitraum_schulstunden_jahr\">";
      $code .= $jahrescode;
    $code .= "</tbody></table>";
  }

  if (r("schulhof.planung.schuljahre.planungszeiträume.anlegen")) {
    $code .= "<p><a href=\"Schulhof/Verwaltung/Stundenplanung/Neuer_Zeitraum\" class=\"cms_button_ja\">+ Neuen Zeitraum anlegen</a></p>";
  }

  echo $code;
}
else {
  echo cms_meldung_berechtigung();
}
?>
</div>
</div>
<div class="cms_clear"></div>
