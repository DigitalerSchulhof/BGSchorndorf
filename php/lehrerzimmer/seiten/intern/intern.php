<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Interne Dienste</h1>";
$code .= "<ul><li><a class=\"cms_button\" href=\"Intern/Schülervertretungsplan\">Schülervertretungsplan</a></li></ul>";
$code .= "<ul><li><a class=\"cms_button\" href=\"Intern/Lehrervertretungsplan\">Lehrervertretungsplan</a></li></ul>";
$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
