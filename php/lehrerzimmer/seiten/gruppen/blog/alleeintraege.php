<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";
$code .= "<h1>Alle Blogeinträge</h1>";

$code .= cms_gesicherteinhalte("cms_schulhof_gruppenblog_alleeintraege", "l", "gruppen_blogalleeintraege", true);

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
