<?php
function cms_schulhof_stufen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Stufenstundenpläne sehen']) {

    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT id, stufe, reihenfolge FROM (SELECT id, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge ASC");
    $sql->bind_param("i", $CMS_BENUTZERSCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($sid, $sbez, $sreihe);
      while ($sql->fetch()) {
        $anzeigename = $sbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
    }
    $sql->close();
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
