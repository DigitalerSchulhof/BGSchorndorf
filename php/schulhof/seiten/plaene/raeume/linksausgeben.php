<?php
function cms_schulhof_raeume_links_anzeigen () {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if (cms_r("schulhof.organisation.räume.sehen")) {

    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;");
    if ($sql->execute()) {
      $sql->bind_result($rid, $rbez);
      while ($sql->fetch()) {
        $anzeigename = $rbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Räume/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
    }
    $sql->close();
    cms_trennen($dbs);
    if (cms_r("schulhof.organisation.räume.anlegen")) {
      $ausgabe .= "<li><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Räume/Neuen_Raum_anlegen\">+ Neuen Raum anlegen</a></li>";
    }

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Räume angelegt</p>';}
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Raumpläne verfügbar.</p>";
  }
  return $ausgabe;
}
?>
