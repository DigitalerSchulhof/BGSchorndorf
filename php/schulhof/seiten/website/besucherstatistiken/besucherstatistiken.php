<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Besucherstatistiken</h1>";

$bs = "";

if(cms_r("statistik.besucher.website.*"))
  $bs .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_gruppe_website.png');\" href=\"Schulhof/Website/Besucherstatistiken/Website\">Besucherstatistiken - Website</a> ";
if(cms_r("statistik.besucher.schulhof.sehen"))
  $bs .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/besucherstatistiken_schulhof.png');\" href=\"Schulhof/Website/Besucherstatistiken/Schulhof\">Besucherstatistiken - Schulhof</a> ";

if (strlen($bs) > 0)
	$code .= "<p>".$bs."</p>";
else
	$code .= cms_meldung_berechtigung();

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
