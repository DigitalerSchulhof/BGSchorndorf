<?php
function cms_schulhof_klassen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Klassenstundenpläne sehen']) {

    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT id, bezeichnung FROM (SELECT klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge ASC, bezeichnung ASC");
    $sql->bind_param("i", $CMS_BENUTZERSCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($kid, $kbez);
      while ($sql->fetch()) {
        $anzeigename = $kbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Klassen/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
    }
    $sql->close();
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Klassen angelegt</p>';}
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Klassenstundenpläne verfügbar.</p>";
  }
  return $ausgabe;
}
?>
