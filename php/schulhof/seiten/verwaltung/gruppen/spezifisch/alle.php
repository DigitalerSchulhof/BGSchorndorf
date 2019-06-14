<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$gruppe = cms_linkzutext($CMS_URL[3]);
$artk = cms_textzudb($gruppe);

$code .= "<h1>$gruppe</h1>";

include_once('php/schulhof/anfragen/verwaltung/gruppen/initial.php');
include_once('php/schulhof/seiten/verwaltung/gruppen/auflisten.php');
$code .= cms_gruppen_verwaltung_listeausgeben ($gruppe, $CMS_RECHTE['Gruppen'][$gruppe.' anlegen'], $CMS_RECHTE['Gruppen'][$gruppe.' bearbeiten'], $CMS_RECHTE['Gruppen'][$gruppe.' löschen']);

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
