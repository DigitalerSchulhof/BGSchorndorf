<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Auszeichnung bearbeiten</h1>";

if (cms_r("website.auszeichnungen.bearbeiten")) {
  include_once("php/schulhof/seiten/website/auszeichnungen/details.php");

  if (!isset($_SESSION["AUSZEICHNUNGBEAREITENID"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    $code .= cms_auszeichnung_details_laden($_SESSION["AUSZEICHNUNGBEAREITENID"]);
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
