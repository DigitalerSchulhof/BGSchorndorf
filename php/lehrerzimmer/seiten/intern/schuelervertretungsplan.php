<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Schülervertretungsplan</h1>";

$angemeldet = false;
$kennung = null;
$code .= "<script src=\"js/lehrerzimmer/intern.js?v=$CMS_VERSION\"></script>";
$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanS', '$CMS_SCHLUESSEL')");
if ($sql->execute()) {
  $sql->bind_result($kennung);
  $sql->fetch();
}
$sql->close();

if (isset($CMS_URL[2])) {if (($CMS_URL[2] == $kennung) && ($CMS_IMLN)) {$angemeldet = true;}}

if ($angemeldet) {
  include_once('php/lehrerzimmer/seiten/intern/vplanladen.php');
  $code .= "<input type=\"hidden\" name=\"cms_svplan_kennung\" id =\"cms_svplan_kennung\" value=\"".$CMS_URL[2]."\">";
  $code .= "<div class=\"cms_vollbild\" id=\"cms_svplan_vollbild\">";
  include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
  $code .= cms_vplan_laden($dbs, 's');
  $CMS_ONLOAD_EXTERN_EVENTS = "cms_netzcheck();var CMS_INTERN_VPLANS = window.setInterval('cms_intern_vplan_laden('s')', 60000);";
  $code .= "</div>";
}
else {
  if (isset($CMS_URL[2])) {
    $code .= cms_meldung('fehler', '<h4>Falsche Kennung</h4><p>Die eingegebene Kennung ist nicht korrekt!</p>');
  }
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Kennung:</th><td><input type=\"text\" id=\"cms_intern_kennung\" name=\"cms_intern_kennung\"></td></tr>";
    $code .= "<tr><th></th><td><span onclick=\"cms_intern_vplan('s');\" class=\"cms_button_wichtig\">Bestätigen</span></td></tr>";
  $code .= "</table>";
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
