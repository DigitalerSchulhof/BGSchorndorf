<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Neue Auszeichnung</h1>";

$zugriff = $CMS_RECHTE['Website']['Auszeichnungen anlegen'];

if ($zugriff) {
  include_once("php/schulhof/seiten/website/auszeichnungen/details.php");
  $code .= cms_auszeichnung_details_laden('-');
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
