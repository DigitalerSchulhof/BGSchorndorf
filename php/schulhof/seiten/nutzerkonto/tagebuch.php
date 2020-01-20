<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
if ($CMS_BENUTZERART == 'l') {
  $code = "<h1>Tagebuch</h1>";
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
  if ($CMS_IMLN) {
    $code .= cms_generiere_nachladen("cms_persoenlichestagebuch", "");
  }
  else {
    $code .= cms_meldung_firewall();
  }
  $code .= "</div></div>";


  $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Kurse</h2>";
  $kurse = "";
  $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?) AS x ORDER BY reihenfolge ASC, bezeichnung ASC");
  $sql->bind_param("ii", $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($kid, $kbez, $kreihe);
    if ($CMS_IMLN) {
      while ($sql->fetch()) {
        $kurse .= "<li>".cms_togglebutton_generieren ("cms_tagebuch_kurs_".$kid, $kbez, 1, "")."</li>";
      }
    }
    else {
      while ($sql->fetch()) {
        $kurse .= "<li><span class=\"cms_button_eingeschraenkt\">$kbez</span></li>";
      }
    }
  }
  $sql->close();
  if (strlen($kurse) > 0) {$code .= "<ul class=\"cms_aktionen_liste\">$kurse</ul>";}
  else {$code .= "<p class=\"cms_notiz\">Es steht kein Kurs mit digitalem Tagebuch zur Verfügung.</p>";}

  $code .= "<h2>Optionen</h2>";
  if ($CMS_IMLN) {
    $code .= "<li>".cms_togglebutton_generieren ("cms_tagebuch_freigabe", "Freigegebene Einträge ausblenden", 1, "")."</li>";
  }
  else {
    $code .= "<li><span class=\"cms_button_eingeschraenkt\">Freigegebene Einträge ausblenden</span></li>";
  }

  $code .= "</div></div>";
  $code .= "<div class=\"cms_clear\">";

  echo $code;
}
else {
  echo cms_meldung_berechtigung();
}
?>
</div>
