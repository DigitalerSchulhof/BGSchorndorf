<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Dauerbrenner</h1>";


$zugriff = true; // Mitgliedschaft oder nach außen sichtbar
include_once('php/schulhof/seiten/verwaltung/dauerbrenner/linksausgeben.php');
$code .= cms_dauerbrenner_links_anzeigen();
$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
