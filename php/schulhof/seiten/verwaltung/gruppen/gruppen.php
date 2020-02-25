<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$code .= "<h1>Gruppen</h1>";

include_once('php/schulhof/anfragen/verwaltung/gruppen/initial.php');

$gruppen = "";
foreach ($CMS_GRUPPEN as $gruppe)
  if(cms_r("schulhof.gruppen.$gruppe.[|anlegen,bearbeiten,löschen]"))
    $gruppen .= "<a class=\"cms_iconbutton\" style=\"background-image:url('res/icons/gross/".cms_textzulink($gruppe).".png');\" href=\"Schulhof/Verwaltung/Gruppen/".cms_textzulink($gruppe)."\">$gruppe</a> ";

if (strlen($gruppen) > 0)
	$code .= "<p>".$gruppen."</p>";
else
	$code .= cms_meldung_berechtigung();

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
