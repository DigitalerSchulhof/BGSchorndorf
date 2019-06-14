<?php
function cms_schulhof_stufen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung']) {

    $dbs = cms_verbinden('s');
    $sql = "SELECT id, stufe, reihenfolge FROM (SELECT id, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge FROM klassenstufen WHERE schuljahr = $CMS_BENUTZERSCHULJAHR) AS x ORDER BY reihenfolge ASC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $anzeigename = $daten['stufe'];
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen/$anzeigenamelink\">".$anzeigename."</span></li> ";
      }
      $anfrage->free();
    }
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">keine Klassenstufen angelegt</p>';}
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>
