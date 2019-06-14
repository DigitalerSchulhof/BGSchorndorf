<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";
$code .= "<h1>Blogeintrag bearbeiten</h1>";
$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

$code .= cms_gesicherteinhalte("cms_schulhof_gruppenblog_eintrag", "l", "gruppen_blogeintrag_bearbeiten", true);

echo $code;
?>
