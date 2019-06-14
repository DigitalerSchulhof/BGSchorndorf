<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Listen</h1>";

$code .= "<ul>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Lehrer\">Lehrer</a></li>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Verwaltungspersonal\">Verwaltungspersonal</a></li>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Klassen_und_Kurse\">Klassen und Kurse</a></li>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Stufen\">Stufen</a></li>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Klassen-_und_Kurssprecher\">Klassen- und Kurssprecher</a></li>";
	$code .= "<li><a class=\"cms_button\" href=\"Schulhof/Listen/Elternvertretung\">Elternvertretung</a></li>";
$code .= "</ul>";

$code .= "</div>";



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
