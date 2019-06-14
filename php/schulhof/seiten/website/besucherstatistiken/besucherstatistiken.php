<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Besucherstatistiken</h1>";

$code .= "<p>";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_gruppe_website.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website\">Besucherstatistiken - Website</a> ";
$code .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_schulhof.png');\" href=\"Schulhof/Website/Besucherstatistiken/Schulhof\">Besucherstatistiken - Schulhof</a> ";
$code .= "</p>";

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
