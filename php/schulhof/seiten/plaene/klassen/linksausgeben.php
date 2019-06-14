<?php
function cms_schulhof_klassen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Klassenstundenpläne sehen']) {

    $dbs = cms_verbinden('s');
    $sql = "SELECT id, bezeichnung FROM (SELECT klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = $CMS_BENUTZERSCHULJAHR) AS x ORDER BY reihenfolge ASC, bezeichnung ASC";

    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $anzeigename = $daten['bezeichnung'];
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Klassen/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
      $anfrage->free();
    }
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">keine Klassen angelegt</p>';}
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>
