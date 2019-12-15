<?php
function cms_schulhof_stufen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Stufenstundenpläne sehen']) {

    $dbs = cms_verbinden('s');
    $sql = "SELECT id, stufe, reihenfolge FROM (SELECT id, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge FROM stufen WHERE schuljahr = $CMS_BENUTZERSCHULJAHR) AS x ORDER BY reihenfolge ASC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $anzeigename = $daten['stufe'];
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
      $anfrage->free();
    }
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Klassenstufen angelegt</p>';}
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Stufenpläne verfügbar.</p>";
  }
  return $ausgabe;
}
?>
