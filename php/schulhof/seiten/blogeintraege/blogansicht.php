<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
include_once('php/schulhof/seiten/downloads/downloads.php');
include_once('php/schulhof/seiten/verwaltung/artikellinks/links.php');

$dbs = cms_verbinden('s');
$code .= cms_blogeintragdetailansicht_ausgeben($dbs);
cms_trennen($dbs);

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;

?>
