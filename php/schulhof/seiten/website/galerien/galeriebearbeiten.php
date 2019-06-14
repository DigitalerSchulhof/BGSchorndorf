<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Galerie bearbeiten</h1>";

$zugriff = $CMS_RECHTE['Website']['Galerien bearbeiten'];

if ($zugriff) {
  $code .= cms_meldung('warnung', '<h4>Öffentlich</h4><p>Der Zugriff auf die jeweiligen Texte kann zwar gemäß der Sichtbarkeitseinstellungen gewährleistet werden. Bilder und andere Dateien jedoch sind (wenn man den Link kennt) aus dem Internet öffentlich erreichbar, auch wenn der Link zu ihnen nicht angegeben wird!</p><p>Es ist zwar unwahrscheinlich, dass diese Dateien gefunden werden, aber es könnte passieren.</p><p>Diese Funktion ist nur für Inhalte gedacht, die grundsätzlich öffentlich sein sollen.</p>');

  include_once("php/schulhof/seiten/verwaltung/personen/personensuche.php");
  include_once("php/schulhof/seiten/verwaltung/gruppen/zuordnungen.php");
  include_once("php/schulhof/seiten/website/galerien/details.php");
  include_once("php/schulhof/seiten/website/editor/editor.php");

  if (!isset($_SESSION["GALERIEID"]) || !isset($_SESSION["GALERIEZIEL"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    $code .= cms_galerie_details_laden($_SESSION["GALERIEID"], $_SESSION["GALERIEZIEL"]);
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
