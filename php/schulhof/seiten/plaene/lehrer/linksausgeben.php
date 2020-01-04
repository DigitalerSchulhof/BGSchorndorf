<?php
function cms_schulhof_lehrer_links_anzeigen () {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID;
  $ausgabe = "";

  if (r("schulhof.information.pläne.stundenpläne.lehrer")) {

    $dbs = cms_verbinden('s');
    $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname ASC, vorname ASC, kuerzel ASC;");
    if ($sql->execute()) {
      $sql->bind_result($lid, $lvor, $lnach, $ltit, $lkurz);
      while ($sql->fetch()) {
        $anzeigename = cms_generiere_anzeigename($lvor, $lnach, $ltit).' ('.$lkurz.')';
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Lehrer/$anzeigenamelink\">".$anzeigename."</a></li> ";
      }
    }
    $sql->close();
    cms_trennen($dbs);

    if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
    else {$ausgabe = '<p class="cms_notiz">Keine Lehrkräfte angelegt</p>';}
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Lehrerstundenpläne verfügbar.</p>";
  }
  return $ausgabe;
}
?>
