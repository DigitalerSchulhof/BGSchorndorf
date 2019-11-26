<?php
function cms_gruppen_mitgliedschaften_anzeigen($dbs, $gruppe, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR, $pur = false) {
  global $CMS_SCHLUESSEL, $CMS_GRUPPEN;

  // Gruppe, die nicht existiert - Fehlerrückgabe
  if (!in_array($gruppe, $CMS_GRUPPEN)) {return false;}

  $code = "";
  $gruppek = cms_textzudb($gruppe);

  if ($pur) {
    if ($CMS_BENUTZERSCHULJAHR == '-') {$sqlsj = "schuljahr IS NULL";}
    else {$sqlsj = "schuljahr = $CMS_BENUTZERSCHULJAHR";}
  }
  else {$sqlsj = "(schuljahr IS NULL OR schuljahr = $CMS_BENUTZERSCHULJAHR)";}

  $sqlmitglied = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id FROM $gruppek JOIN $gruppek"."mitglieder ON $gruppek.id = $gruppek"."mitglieder.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND $sqlsj)";
  $sqlaufsicht = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id FROM $gruppek JOIN $gruppek"."aufsicht ON $gruppek.id = $gruppek"."aufsicht.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND $sqlsj)";

  $sql = "SELECT DISTINCT * FROM ($sqlmitglied UNION $sqlaufsicht) AS x ORDER BY bezeichnung ASC";

  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while($daten = $anfrage->fetch_assoc()) {
      $gl = cms_textzulink($gruppe);
      $bl = cms_textzulink($daten['bezeichnung']);
      if ($daten['schuljahrbez'] != null) {$sl = cms_textzulink($daten['schuljahrbez']);}
      else {$sl = "Schuljahrübergreifend";}
      $code .= "<li><a class=\"cms_button\" href=\"Schulhof/Gruppen/$sl/$gl/$bl\">".$daten['bezeichnung']."</a></li> ";
    }
    $anfrage->free();
  }

  if (strlen($code) > 0) {$code = "<ul>".$code."</ul>";}

  return $code;
}
?>
