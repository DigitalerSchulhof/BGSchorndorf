<?php
function cms_schulhof_lehrer_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Lehrerstundenpläne sehen']) {

    $dbs = cms_verbinden('s');
    $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname ASC, vorname ASC, kuerzel ASC;";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']).' ('.$daten['kuerzel'].')';
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Lehrer/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
      $anfrage->free();
    }
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Lehrkräfte angelegt</p>';}
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>
