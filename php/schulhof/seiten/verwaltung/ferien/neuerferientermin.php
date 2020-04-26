<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Neuer Ferientermin</h1>";

if (cms_r("schulhof.organisation.ferien.anlegen")) {
  if (!isset($_SESSION["FERIENID"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    include_once("php/schulhof/seiten/verwaltung/ferien/details.php");
    $code .= cms_ferien_details_laden($_SESSION["FERIENID"]);
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
