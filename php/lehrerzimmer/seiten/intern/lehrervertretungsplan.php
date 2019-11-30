<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Lehrervertretungsplan</h1>";

$angemeldet = false;
$kennung = null;
if (!$angemeldet) {
  $code .= "<script>var CMS_LN_DA = '$CMS_LN_DA';</script>";
}
$code .= "<script src=\"js/lehrerzimmer/intern.js?v=$CMS_VERSION\"></script>";
$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL')");
if ($sql->execute()) {
  $sql->bind_result($kennung);
  $sql->fetch();
}
$sql->close();

if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];}
else {$CMS_IMLN = false;}

if (isset($CMS_URL[2])) {if (($CMS_URL[2] == $kennung) && ($CMS_IMLN)) {$angemeldet = true;}}

if (isset($CMS_URL[2])) {
  $code .= "<script src=\"js/schulhof/verwaltung/scrollen.js?v=$CMS_VERSION\"></script>";
  $CMS_ONLOAD_EXTERN_EVENTS = "var CMS_INTERN_VPLANL = window.setInterval(function () {cms_intern_vplan_laden('l');}, 300000);window.setTimeout(function () {cms_intern_vplan_laden('l');}, 1000);window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LH = window.setInterval(function () {cms_vplan_verschieben_lh()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LG = window.setInterval(function () {cms_vplan_verschieben_lg()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_LM = window.setInterval(function () {cms_vplan_verschieben_lm()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);";
  $code .= "<input type=\"hidden\" name=\"cms_lvplan_kennung\" id =\"cms_lvplan_kennung\" value=\"".$CMS_URL[2]."\">";
  $code .= "<div class=\"cms_vollbild\" id=\"cms_lvplan_vollbild\">";

  $code .= "<script src=\"js/schulhof/verwaltung/vertretungsplanung.js?v=$CMS_VERSION\"></script>";
  include_once('php/lehrerzimmer/seiten/intern/vplanladen.php');
  include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
  $code .= "<div id=\"cms_lvplan_heute\">";
  $code .= "<div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...</p></div>";
  $code .= "</div>";
  $code .= "<div id=\"cms_lvplan_geraete\">";
  $code .= "<div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...</p></div>";
  $code .= "</div>";
  $code .= "<div id=\"cms_lvplan_morgen\">";
  $code .= "<div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...</p></div>";
  $code .= "</div>";
  $code .= "<script>cms_intern_vplan_laden('l');</script>";
  $code .= "<div class=\"cms_clear\"></div>";
  $code .= "</div>";
}
else {
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Kennung:</th><td><input type=\"text\" id=\"cms_intern_kennung\" name=\"cms_intern_kennung\"></td></tr>";
    $code .= "<tr><th></th><td><span onclick=\"cms_intern_vplan('l');\" class=\"cms_button_wichtig\">Bestätigen</span></td></tr>";
  $code .= "</table>";
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
