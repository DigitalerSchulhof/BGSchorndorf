<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";

if (isset($_SESSION["GRUPPE"])) {$gruppe = $_SESSION["GRUPPE"];} else {$gruppe = '-';}
if (isset($_SESSION["GRUPPESINGULAR"])) {$gruppesingular = $_SESSION["GRUPPESINGULAR"];} else {$gruppesingular = '-';}
if (isset($_SESSION["GRUPPENEU"])) {$gruppeneu = $_SESSION["GRUPPENEU"];} else {$gruppeneu = '-';}
if (isset($_SESSION["GRUPPEARTIKEL"])) {$gruppeartikel = $_SESSION["GRUPPEARTIKEL"];} else {$gruppeartikel = '-';}

$code .= "<h1>$gruppe</h1>";

if ($gruppe != "-") {
  $code .= cms_lehrerzimmer_gruppen_links_anzeigen ($gruppe, $gruppesingular, $gruppeneu, $gruppeartikel);
}
else {
	$code .= cms_meldung_bastler();
	$code .= "</div>";
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
