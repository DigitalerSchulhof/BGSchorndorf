<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$gruppenart = $CMS_URL[count($CMS_URL)-2];
$g = cms_linkzutext($gruppenart);
$gk = cms_textzudb($g);
$schuljahr = $CMS_URL[count($CMS_URL)-1];
$sj = cms_linkzutext($schuljahr);

if ($sj == 'Schuljahrübergreifend') {
  $code .= "<h1>Listen aus $g schuljahrübergreifend</h1>";
  $sjsuche = "schuljahr IS NULL";
}
else {
  $code .= "<h1>Listen aus $g im Schuljahr $sj</h1>";
  $sjsuche = "schuljahre.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
}


if (cms_valide_gruppe($g)) {
  $sql = "";
  if (r("schulhof.information.listen.gruppen.$g")) {
    $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $sjsuche) AS x ORDER BY gbez ASC");
  }
  else if ($CMS_RECHTE['Gruppen'][$g." Listen sehen wenn Mitglied"]) {
    $sql = $dbs->prepare("SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez FROM $gk JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $sjsuche AND $gk"."mitglieder.person = $CMS_BENUTZERID) AS x ORDER BY sbez ASC, gbez ASC");
  }

  $gruppenliste = "";

  if ($CMS_RECHTE['Gruppen'][$g." Listen sehen"] || $CMS_RECHTE['Gruppen'][$g." Listen sehen wenn Mitglied"]) {
    if ($sj != 'Schuljahrübergreifend') {
      $sql->bind_param("s", $sj);
    }
    if ($sql->execute()) {
      $sql->bind_result($id, $gbez);
      while ($sql->fetch()) {
        $gruppenliste .= "<a class=\"cms_button\" href=\"Schulhof/Listen/Gruppen/".cms_textzulink($g)."/".cms_textzulink($sj)."/".cms_textzulink($gbez)."\">".$gbez."</a> ";
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
