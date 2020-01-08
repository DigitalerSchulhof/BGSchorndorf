<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Gruppenlisten</h1>";

$ausgabe = false;
foreach ($CMS_GRUPPEN as $g) {
  $gk = cms_textzudb($g);
  $sql = "";
  if (cms_r("schulhof.information.listen.gruppen.$g"))) {
    $sql = "SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE (schuljahr IS NULL OR schuljahr = $CMS_BENUTZERSCHULJAHR)) AS x ORDER BY sbez ASC, gbez ASC";
  }
  else if ($CMS_RECHTE['Gruppen'][$g." Listen sehen wenn Mitglied"]) {
    $sql = "SELECT * FROM (SELECT $gk.id AS id, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez FROM $gk JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE (schuljahr IS NULL OR schuljahr = $CMS_BENUTZERSCHULJAHR) AND $gk"."mitglieder.person = $CMS_BENUTZERID) AS x ORDER BY sbez ASC, gbez ASC";
  }
  $gruppenliste = "";
  if (strlen($sql) > 0) {
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($gid, $gbez, $gsjbez);
      while ($sql->fetch()) {
        if (is_null($gsjbez)) {$gsjbez = "Schuljahrübergreifend";}
        $gruppenliste .= "<a class=\"cms_button\" href=\"Schulhof/Listen/Gruppen/".cms_textzulink($g)."/".cms_textzulink($gsjbez)."/".cms_textzulink($gbez)."\">$gbez</a> ";
      }
    }
    $sql->close();
  }

  if (strlen($gruppenliste) > 0) {
    $code .= "<h2>$g</h2><p>".$gruppenliste."</p>";
    $ausgabe = true;
  }
}

if (!$ausgabe) {$code .= cms_meldung_berechtigung();}


$code .= "</div>";

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
