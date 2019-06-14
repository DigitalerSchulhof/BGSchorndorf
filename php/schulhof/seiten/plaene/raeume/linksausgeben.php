<?php
function cms_schulhof_raeume_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Räume sehen']) {

    $dbs = cms_verbinden('s');
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $anzeigename = $daten['bezeichnung'];
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Räume/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
      $anfrage->free();
    }
    cms_trennen($dbs);
    if ($CMS_RECHTE['Organisation']['Räume anlegen']) {
      $ausgabe .= "<li><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Räume/Neuen_Raum_anlegen\">+ Neuen Raum anlegen</a></li>";
    }

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">keine Räume angelegt</p>';}
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>
