<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Gruppen</h1>";

include_once('php/schulhof/anfragen/verwaltung/gruppen/initial.php');

$code .= "<p>";
foreach ($CMS_GRUPPEN as $g) {
  $code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/".cms_textzulink($g).".png');\" href=\"Schulhof/Verwaltung/Gruppen/".cms_textzulink($g)."\">$g</a> ";
}
$code .= "</p>";

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
