<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Vertretungspläne</h1>";


$zugriff = true; // Mitgliedschaft oder nach außen sichtbar
include_once('php/schulhof/seiten/plaene/vertretungen/linksausgeben.php');
$code .= cms_schulhof_vertretungen_links_anzeigen();
$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
