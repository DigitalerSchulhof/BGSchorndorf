<?php
function cms_schulhof_vertretungen_links_anzeigen () {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
  $ausgabe = "";

  if (cms_r("schulhof.information.pläne.stundenpläne.vertretungen.lehrer"))) {
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Lehreransicht\">Lehreransicht</a></li> ";
  }
  if (cms_r("schulhof.information.pläne.stundenpläne.vertretungen.schüler"))) {
    $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen/Schüleransicht\">Schüleransicht</a></li> ";
  }

  if (strlen($ausgabe) > 0) {
    $ausgabe = "<ul>".$ausgabe."</ul>";
  }
  else {
    $ausgabe = "<p class=\"cms_notiz\">Keine Vertretungspläne verfügbar.</p>";
  }
  return $ausgabe;
}
?>
