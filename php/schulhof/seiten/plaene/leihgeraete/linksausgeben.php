<?php
function cms_schulhof_leihgeraete_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Leihgeräte sehen']) {

    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihen WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;");
    if ($sql->execute()) {
      $sql->bind_result($lid, $lbez);
      while ($sql->fetch()) {
        $anzeigename = $lbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Leihgeräte/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
    }
    $sql->close();
    cms_trennen($dbs);
    if ($CMS_RECHTE['Organisation']['Leihgeräte anlegen']) {
      $ausgabe .= "<li><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Leihgeräte/Neue_Leihgeräte_anlegen\">+ Neue Leihgeräte anlegen</a></li>";
    }

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Leihgeräte angelegt</p>';}
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Leihgeräte verfügbar.</p>";
  }
  return $ausgabe;
}
?>
