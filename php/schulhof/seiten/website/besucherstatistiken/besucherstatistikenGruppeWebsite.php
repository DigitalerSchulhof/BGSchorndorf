<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Besucherstatistiken - Website</h1>";

$code .= "<p>";
if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'])
  $code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_website.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website/Website\">Besucherstatistiken - Website</a> ";
if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'])
  $code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_galerien.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website/Galerien\">Besucherstatistiken - Galerien</a> ";
if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'])
  $code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_blogeintraege.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website/Blogeinträge\">Besucherstatistiken - Blogeinträge</a> ";
if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'])
  $code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_termine.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website/Termine\">Besucherstatistiken - Termine</a> ";
$code .= "</p>";

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
