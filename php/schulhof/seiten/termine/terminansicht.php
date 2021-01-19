<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

include_once('php/schulhof/seiten/termine/termineausgeben.php');
include_once('php/schulhof/seiten/downloads/downloads.php');


$dbs = cms_verbinden('s');
$code .= cms_termindetailansicht_ausgeben($dbs);
cms_trennen($dbs);

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;

?>
