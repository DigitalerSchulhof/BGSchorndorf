<?php
$CMS_GRUPPEN = array();
// Verwaltung
$CMS_GRUPPEN[0] = 'Gremien';
$CMS_GRUPPEN[1] = 'Fachschaften';
// Organisaiton
$CMS_GRUPPEN[2] = 'Klassen';
$CMS_GRUPPEN[3] = 'Kurse';
$CMS_GRUPPEN[4] = 'Stufen';
// Aktivitäten
$CMS_GRUPPEN[5] = 'Arbeitsgemeinschaften';
$CMS_GRUPPEN[6] = 'Arbeitskreise';
$CMS_GRUPPEN[7] = 'Fahrten';
$CMS_GRUPPEN[8] = 'Wettbewerbe';
$CMS_GRUPPEN[9] = 'Ereignisse';
// Sonstige Gruppen
$CMS_GRUPPEN[10] = 'Sonstige Gruppen';

function cms_valide_gruppe($gruppe) {
  global $CMS_GRUPPEN;
  return in_array($gruppe, $CMS_GRUPPEN);
}

function cms_valide_kgruppe($gruppe) {
  global $CMS_GRUPPEN;
  $kgruppen = array();
  foreach ($CMS_GRUPPEN as $g) {
    array_push($kgruppen, cms_textzudb($g));
  }
  return in_array($gruppe, $kgruppen);
}
?>
