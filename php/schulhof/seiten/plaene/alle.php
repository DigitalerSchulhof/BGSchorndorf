<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Pläne</h1>";


$zugriff = $CMS_RECHTE['lehrer'] || $CMS_RECHTE['verwaltung'];

if ($zugriff) {
	$code .= "<ul>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Vertretungen\">Vertretungspläne</a></li>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Lehrer\">Lehrerstundenpläne</a></li>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Klassen\">Klassenstundenpläne</a></li>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Stufen\">Stufenstundenpläne</a></li>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Räume\">Raumpläne</a></li>";
		$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Pläne/Leihgeräte\">Leihgeräte</a></li>";
	$code .= "</ul>";
}
else {
	$code .= cms_meldung_berechtigung();
}

$code .= "</div>";



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
