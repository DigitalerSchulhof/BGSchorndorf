<?php
function cms_schulhof_vertretungen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['Planung']['Lehrervertretungsplan sehen']) {
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Lehreransicht\">Lehreransicht</a></li> ";
  }
  if ($CMS_RECHTE['Planung']['Schülervertretungsplan sehen']) {
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Schüleransicht\">Schüleransicht</a></li> ";
  }
    
  if ($ausgabe > 0) {
    $ausgabe = "<ul>".$ausgabe."</ul>";
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>