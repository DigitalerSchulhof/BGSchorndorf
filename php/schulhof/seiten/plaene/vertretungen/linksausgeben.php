<?php
function cms_schulhof_vertretungen_links_anzeigen () {
  global $CMS_RECHTE, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if ($CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung']) {
    $ausgabe .= "<ul>";
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Lehreransicht\">Lehreransicht</a></li> ";
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Schüleransicht\">Schüleransicht</a></li> ";
    $ausgabe .= "</ul>";
  }
  else {
    $ausgabe = cms_meldung_berechtigung();
  }
  return $ausgabe;
}
?>
