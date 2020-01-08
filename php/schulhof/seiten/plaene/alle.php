<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Pläne</h1>";


$liste = "";
if (cms_r("schulhof.information.pläne.stundenpläne.vertretungen.*"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen\">Vertretungspläne</a></li>";
}
if (cms_r("schulhof.information.pläne.stundenpläne.lehrer"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Lehrer\">Lehrerstundenpläne</a></li>";
}
if (cms_r("schulhof.information.pläne.stundenpläne.klassen"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Klassen\">Klassenstundenpläne</a></li>";
}
if (cms_r("schulhof.information.pläne.stundenpläne.stufen"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen\">Stufenstundenpläne</a></li>";
}
if (cms_r("schulhof.information.pläne.stundenpläne.räume"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Räume\">Raumstundenpläne</a></li>";
}
if (cms_r("schulhof.organisation.leihgeräte.sehen"))) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Leihgeräte\">Leihgeräte</a></li>";
}

if (strlen($liste) > 0) {
	$code .= "<ul>".$liste."</ul>";
}
else {
	$code .= cms_meldung_berechtigung();
}

$code .= "</div>";



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
