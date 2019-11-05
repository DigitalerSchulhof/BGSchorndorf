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
$code .= "<script src=\"js/lehrerzimmer/intern.js?v=$CMS_VERSION\"></script><script src=\"js/lehrerzimmer/lehrernetz.js?v=$CMS_VERSION\"></script>";
$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL')");
if ($sql->execute()) {
  $sql->bind_result($kennung);
  $sql->fetch();
}
$sql->close();

if (isset($CMS_URL[2])) {if (($CMS_URL[2] == $kennung) && ($CMS_IMLN)) {$angemeldet = true;}}
$CMS_ONLOAD_EXTERN_EVENTS = "cms_netzcheck('n');var CMS_INTERN_VPLANS = window.setInterval(cms_intern_vplan_laden('l'), 1000);";
$code .= "<input type=\"hidden\" name=\"cms_lvplan_kennung\" id =\"cms_lvplan_kennung\" value=\"".$CMS_URL[2]."\">";
$code .= "<div class=\"cms_vollbild\" id=\"cms_lvplan_vollbild\">";
if ($angemeldet) {
  $code .= "<script src=\"js/schulhof/verwaltung/vertretungsplanung.js?v=$CMS_VERSION\"></script><scipt></script>";
  include_once('php/lehrerzimmer/seiten/intern/vplanladen.php');
  include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
  $code .= cms_vplan_laden($dbs, 'l');
}
else {
  if (isset($CMS_URL[2])) {
    $code .= cms_meldung('fehler', '<h4>Falsche Kennung</h4><p>Die eingegebene Kennung ist nicht korrekt!</p>');
  }
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Kennung:</th><td><input type=\"text\" id=\"cms_intern_kennung\" name=\"cms_intern_kennung\"></td></tr>";
    $code .= "<tr><th></th><td><span onclick=\"cms_intern_vplan('l');\" class=\"cms_button_wichtig\">Bestätigen</span></td></tr>";
  $code .= "</table>";
}

$code .= "</div>";

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
