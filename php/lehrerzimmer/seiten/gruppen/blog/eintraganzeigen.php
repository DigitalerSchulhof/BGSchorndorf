<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";
$code .= "</div>";

$code .= cms_gesicherteinhalte("cms_schulhof_gruppenblog_eintrag", "l", "gruppen_blogeintrag");

$code .= "<div class=\"cms_clear\"></div>";


echo $code;
?>
