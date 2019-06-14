<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Pläne</h1>";


$liste = "";
if ($CMS_RECHTE['Planung']['Schülervertretungsplan sehen'] || $CMS_RECHTE['Planung']['Lehrervertretungsplan sehen']) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen\">Vertretungspläne</a></li>";
}
if ($CMS_RECHTE['Planung']['Lehrerstundenpläne sehen']) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Lehrer\">Lehrerstundenpläne</a></li>";
}
if ($CMS_RECHTE['Planung']['Klassenstundenpläne sehen']) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Klassen\">Klassenstundenpläne</a></li>";
}
if ($CMS_RECHTE['Planung']['Stufenstundenpläne sehen']) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen\">Stufenstundenpläne</a></li>";
}
if ($CMS_RECHTE['Planung']['Räume sehen']) {
	$liste .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Räume\">Raumpläne</a></li>";
}
if ($CMS_RECHTE['Planung']['Leihgeräte sehen']) {
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
