<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$gruppe = cms_linkzutext($CMS_URL[3]);

$code .= "<h1>$gruppe bearbeiten</h1>";

include_once('php/schulhof/anfragen/verwaltung/gruppen/initial.php');
include_once('php/schulhof/seiten/verwaltung/gruppen/ausgeben.php');
include_once('php/schulhof/seiten/personensuche/personensuche.php');
if (isset($_SESSION['Gruppen']['bearbeiten']['id'])) {
  $dbs = cms_verbinden('s');
  $gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $_SESSION['Gruppen']['bearbeiten']['id'], $CMS_BENUTZERID);
  cms_trennen($dbs);
  $bearbeiten = $gruppenrechte['bearbeiten'] || cms_r("schulhof.gruppen.$gruppe.bearbeiten");
  $code .= cms_gruppen_verwaltung_gruppeneigenschaften ($gruppe, cms_r("schulhof.gruppen.$gruppe.anlegen"), $bearbeiten, $_SESSION['Gruppen']['bearbeiten']['id']);
}
else {
  $code .= cms_meldung_bastler();
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
