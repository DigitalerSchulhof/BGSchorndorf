<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Schülervertretungsplan</h1>";

$angemeldet = false;
$kennung = null;
if (!$angemeldet) {
  $code .= "<script>var CMS_LN_DA = '$CMS_LN_DA';</script>";
}
$code .= "<script src=\"js/lehrerzimmer/intern.js?v=$CMS_VERSION\"></script>";
$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanS', '$CMS_SCHLUESSEL')");
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
  $CMS_ONLOAD_EXTERN_EVENTS = "CMS_IMLN = false;var CMS_INTERN_VPLANS = window.setInterval(function () {cms_intern_vplan_laden('s');}, 300000);window.setTimeout(function () {cms_intern_vplan_laden('s');}, 1000);window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_SH = window.setInterval(function () {cms_vplan_verschieben_sh()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);window.setTimeout(function () {CMS_INTERN_VPLANL_SCHIEBEN_SM = window.setInterval(function () {cms_vplan_verschieben_sm()}, cms_vplanschiebengeschwindigkeit)}, cms_vplanschiebenwarten);";

  $code .= "<input type=\"hidden\" name=\"cms_svplan_kennung\" id =\"cms_svplan_kennung\" value=\"".$CMS_URL[2]."\">";
  $code .= "<div class=\"cms_vollbild\" id=\"cms_svplan_vollbild\">";
  include_once('php/lehrerzimmer/seiten/intern/vplanladen.php');
  include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
  $code .= "<div id=\"cms_svplan_heute\">";
  $code .= "<div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...</p></div>";
  $code .= "</div>";
  $code .= "<div id=\"cms_svplan_morgen\">";
  $code .= "<div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...</p></div>";
  $code .= "</div>";
  $code .= "<script>cms_intern_vplan_laden('s');</script>";
  $code .= "<div class=\"cms_clear\"></div>";
}
else {
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Kennung:</th><td><input type=\"text\" id=\"cms_intern_kennung\" name=\"cms_intern_kennung\"></td></tr>";
    $code .= "<tr><th></th><td><span onclick=\"cms_intern_vplan('s');\" class=\"cms_button_wichtig\">Bestätigen</span></td></tr>";
  $code .= "</table>";
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
