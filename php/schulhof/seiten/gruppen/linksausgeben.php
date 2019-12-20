<?php
function cms_gruppen_links_anzeigen($dbs, $gruppe, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR, $pur = false) {
  global $CMS_SCHLUESSEL, $CMS_GRUPPEN;

  // Gruppe, die nicht existiert - Fehlerrückgabe
  if (!in_array($gruppe, $CMS_GRUPPEN)) {return false;}

  $code = "";
  $gruppek = cms_textzudb($gruppe);

  if ($CMS_BENUTZERART == 'l') {$limit = 1;}
  else if ($CMS_BENUTZERART == 'v') {$limit = 2;}
  else {$limit = 3;}

  if ($pur) {
    if ($CMS_BENUTZERSCHULJAHR == '-') {$sqlsj = "schuljahr IS NULL";}
    else {$sqlsj = "schuljahr = $CMS_BENUTZERSCHULJAHR";}
  }
  else {$sqlsj = "schuljahr IS NULL OR schuljahr = $CMS_BENUTZERSCHULJAHR";}

  $sqlsichtbar = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id FROM $gruppek LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE sichtbar >= $limit AND ($sqlsj))";

  $sqlmitglied = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id FROM $gruppek JOIN $gruppek"."mitglieder ON $gruppek.id = $gruppek"."mitglieder.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  $sqlaufsicht = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id FROM $gruppek JOIN $gruppek"."aufsicht ON $gruppek.id = $gruppek"."aufsicht.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";

  $sql = $dbs->prepare("SELECT DISTINCT * FROM ($sqlsichtbar UNION $sqlmitglied UNION $sqlaufsicht) AS x ORDER BY bezeichnung ASC");
  if ($sql->execute()) {
    $sql->bind_result($gbez, $gsjbez, $gid);
    while($sql->fetch()) {
      $gl = cms_textzulink($gruppe);
      $bl = cms_textzulink($gbez);
      if ($gsjbez != null) {$sl = cms_textzulink($gsjbez);}
      else {$sl = "Schuljahrübergreifend";}
      $code .= "<li><a class=\"cms_button\" href=\"Schulhof/Gruppen/$sl/$gl/$bl\">$gbez</a></li> ";
    }
  }
  $sql->close();

  if (strlen($code) > 0) {$code = "<ul>".$code."</ul>";}

  return $code;
}
?>
