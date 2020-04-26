<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Weiterleitung bearbeiten</h1>";

if (cms_r("website.weiterleiten")) {
  include_once("auswerten.php");

  if (!isset($_SESSION["WEITERLEITUNGID"])) {
        $code .= cms_meldung_bastler();
  } else {
    $code .= cms_weiterleitung_details($_SESSION["WEITERLEITUNGID"]);
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
