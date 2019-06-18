<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Listen</h1>";

include_once('php/schulhof/seiten/listen/linksausgeben.php');
$code .= cms_listen_links_anzeigen('personen');
$code .= "</div>";

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
