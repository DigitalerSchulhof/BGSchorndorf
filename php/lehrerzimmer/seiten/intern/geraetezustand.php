<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Gerätezustand</h1>";

$angemeldet = false;
$kennungda = false;
$code .= "<script src=\"js/lehrerzimmer/intern.js?v=$CMS_VERSION\"></script>";
$sql = "SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('Gerätekennung', '$CMS_SCHLUESSEL')";
if ($anfrage = $dbs->query($sql)) {
  if ($daten = $anfrage->fetch_assoc()) {
    $kennung = $daten['wert'];
    $kennungda = true;
  }
  $anfrage->free();
}

if (isset($CMS_URL[2])) {if (($CMS_URL[2] == $kennung) && ($kennungda)) {$angemeldet = true;}}

if ($angemeldet) {
  include_once('php/lehrerzimmer/seiten/intern/geraetezustandladen.php');
  $code .= "<input type=\"hidden\" name=\"cms_geraetezustand_kennung\" id =\"cms_geraetezustand_kennung\" value=\"".$CMS_URL[2]."\">";
  $code .= "<div class=\"cms_vollbild\" id=\"cms_geraetezustand_vollbild\">";
  $code .= cms_geraetezustand_laden($dbs);
  $CMS_ONLOAD_EXTERN_EVENTS = "var CMS_INTERN_GERAETEZUSTAND = window.setInterval('cms_intern_geraetezustand_laden()', 300000);";
  $code .= "</div>";
}
else {
  if (isset($CMS_URL[2])) {
    $code .= cms_meldung('fehler', '<h4>Falsche Kennung</h4><p>Die eingegebene Kennung ist nicht korrekt!</p>');
  }
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Kennung:</th><td><input type=\"text\" id=\"cms_intern_kennung\" name=\"cms_intern_kennung\"></td></tr>";
    $code .= "<tr><th></th><td><span onclick=\"cms_intern_geraetezustand();\" class=\"cms_button_wichtig\">Bestätigen</span></td></tr>";
  $code .= "</table>";
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
