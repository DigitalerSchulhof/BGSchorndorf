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
    if ($CMS_BENUTZERSCHULJAHR == '-') {$sqlsj = "$gruppek.schuljahr IS NULL";}
    else if ($CMS_BENUTZERSCHULJAHR === null) {$sqlsj = "$gruppek.schuljahr IS NULL";}
    else {$sqlsj = "$gruppek.schuljahr = $CMS_BENUTZERSCHULJAHR";}
  }
  else {
    if ($CMS_BENUTZERSCHULJAHR == '-') {$sqlsj = "$gruppek.schuljahr IS NULL";}
    else if ($CMS_BENUTZERSCHULJAHR === null) {$sqlsj = "$gruppek.schuljahr IS NULL";}
    else {$sqlsj = "($gruppek.schuljahr IS NULL OR $gruppek.schuljahr = $CMS_BENUTZERSCHULJAHR)";}
  }

  if (($gruppe == "Klassen") || ($gruppe == "Kurse")) {
    $sqlsichtbar = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id LEFT JOIN stufen ON stufe = stufen.id WHERE $gruppek.sichtbar >= $limit AND ($sqlsj))";
  }
  else if ($gruppe == "Stufen") {
    $sqlsichtbar = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE $gruppek.sichtbar >= $limit AND ($sqlsj))";
  }
  else {
    $sqlsichtbar = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, 0 AS reihenfolge FROM $gruppek LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE $gruppek.sichtbar >= $limit AND ($sqlsj))";
  }


  if (($gruppe == "Klassen") || ($gruppe == "Kurse")) {
    $sqlmitglied = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek JOIN $gruppek"."mitglieder ON $gruppek.id = $gruppek"."mitglieder.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id LEFT JOIN stufen ON stufe = stufen.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }
  else if ($gruppe == "Stufen") {
    $sqlmitglied = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek JOIN $gruppek"."mitglieder ON $gruppek.id = $gruppek"."mitglieder.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }
  else {
    $sqlmitglied = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, 0 AS reihenfolge FROM $gruppek JOIN $gruppek"."mitglieder ON $gruppek.id = $gruppek"."mitglieder.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }

  if (($gruppe == "Klassen") || ($gruppe == "Kurse")) {
    $sqlaufsicht = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek JOIN $gruppek"."aufsicht ON $gruppek.id = $gruppek"."aufsicht.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id LEFT JOIN stufen ON stufe = stufen.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }
  else if ($gruppe == "Stufen") {
    $sqlaufsicht = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, reihenfolge FROM $gruppek JOIN $gruppek"."aufsicht ON $gruppek.id = $gruppek"."aufsicht.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }
  else {
    $sqlaufsicht = "(SELECT AES_DECRYPT($gruppek.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahrbez, $gruppek.id AS id, 0 AS reihenfolge FROM $gruppek JOIN $gruppek"."aufsicht ON $gruppek.id = $gruppek"."aufsicht.gruppe LEFT JOIN schuljahre ON $gruppek.schuljahr = schuljahre.id WHERE person = $CMS_BENUTZERID AND ($sqlsj))";
  }

  //echo $sqlsichtbar."<br><br>";

  $sql = $dbs->prepare("SELECT DISTINCT * FROM ($sqlsichtbar UNION $sqlmitglied UNION $sqlaufsicht) AS x ORDER BY reihenfolge, bezeichnung ASC");
  if ($sql->execute()) {
    $sql->bind_result($gbez, $gsjbez, $gid, $reihenfolge);
    while($sql->fetch()) {
      $gl = cms_textzulink($gruppe);
      $bl = cms_textzulink($gbez);
      if ($gsjbez != null) {$sl = cms_textzulink($gsjbez);}
      else {$sl = "Schuljahrübergreifend";}
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Gruppen/$sl/$gl/$bl\">$gbez</a> ";
    }
  }
  $sql->close();

  if (strlen($code) > 0) {$code = "<p>".$code."</p>";}

  return $code;
}
?>
