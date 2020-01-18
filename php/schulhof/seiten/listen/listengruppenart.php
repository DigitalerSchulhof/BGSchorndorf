<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$gruppenart = $CMS_URL[count($CMS_URL)-1];
$g = cms_linkzutext($gruppenart);
$gk = cms_textzudb($g);
$code .= "<h1>Listen aus $g</h1>";

if (cms_valide_gruppe($g)) {

  $zugriff = false;
  if ($CMS_RECHTE['Gruppen'][$g." Listen sehen"] || $CMS_RECHTE['Gruppen'][$g." Listen sehen wenn Mitglied"]) {$zugriff = true;}

  $sql = "";
  if ($CMS_RECHTE['Gruppen'][$g." Listen sehen"]) {
    if (($g == "Klassen") || ($g == "Kurse")) {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id LEFT JOIN stufen ON stufe = stufen.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?)) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    else if ($g == "Stufen") {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?)) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    else {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, 0 AS reihenfolge FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?)) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    $sql->bind_param("i", $CMS_BENUTZERSCHULJAHR);
  }
  else if ($CMS_RECHTE['Gruppen'][$g." Listen sehen wenn Mitglied"]) {
    if (($g == "Klassen") || ($g == "Kurse")) {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM $gk JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id LEFT JOIN stufen ON stufe = stufen.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?) AND $gk"."mitglieder.person = ?) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    else if ($g == "Stufen") {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge FROM $gk JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?) AND $gk"."mitglieder.person = ?) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    else {
      $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, 0 AS reihenfolge FROM $gk JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE ($gk.schuljahr IS NULL OR $gk.schuljahr = ?) AND $gk"."mitglieder.person = ?) AS x ORDER BY reihenfolge, sbez ASC, gbez ASC");
    }
    $sql->bind_param("ii", $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID);
  }

  $gruppenliste = "";
  if ($zugriff) {
    if ($sql->execute()) {
      $sql->bind_result($gid, $gbez, $sbez, $reihe);
      while ($sql->fetch()) {
        if (is_null($sbez)) {$sbez = "Schuljahrübergreifend";}
        $gruppenliste .= "<a class=\"cms_button\" href=\"Schulhof/Listen/Gruppen/".cms_textzulink($g)."/".cms_textzulink($sbez)."/".cms_textzulink($gbez)."\">$gbez</a> ";
      }
    }
    $sql->close();
  }

  if (strlen($gruppenliste) > 0) {
    $code .= "<h2>$g</h2><p>".$gruppenliste."</p>";
  } else {$code .= cms_meldung_berechtigung();}

}
else {$code .= cms_meldung_berechtigung();}

$code .= "</div>";

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
