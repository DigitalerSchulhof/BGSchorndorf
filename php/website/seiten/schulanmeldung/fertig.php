<?php
include_once('php/website/seiten/schulanmeldung/navigation.php');
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
$code = "<div class=\"cms_spalte_i\">";

$code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
$code .= "<h1>Voranmeldung</h1>";

$rueckgabe = cms_voranmeldung_zeit();
if ($rueckgabe['zulaessig']) {

  $code .= "<h2>Fertig!</h2>";

  if ($CMS_VORANMELDUNG["Persönlich nötig"]) {
    $code .= "<p>Vielen Dank für Ihre Voranmeldung. Wir freuen uns, Sie bei der Anmeldung mit Ihrem Kind begrüßen zu dürfen.</p>";
    $inhalt = "<p>Die persönliche Anmeldung an der Schule findet an den folgenden Tagen statt:</p>";
    $inhalt .= "<p><b>".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung persönlich von'])).", den ".date('d', $CMS_VORANMELDUNG['Anmeldung persönlich von']).". ".cms_monatsnamekomplett(date('m', $CMS_VORANMELDUNG['Anmeldung persönlich von']))." ".date('Y', $CMS_VORANMELDUNG['Anmeldung persönlich von'])."</b> bis ";
    $inhalt .= "<b>".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung persönlich bis'])).", den ".date('d', $CMS_VORANMELDUNG['Anmeldung persönlich bis']).". ".cms_monatsnamekomplett(date('m', $CMS_VORANMELDUNG['Anmeldung persönlich bis']))." ".date('Y', $CMS_VORANMELDUNG['Anmeldung persönlich bis'])."</b></p>";
  } else {
    $code .= "<p>Vielen Dank für Ihre Voranmeldung.</p>";
    $inhalt = "<p>Bitte beachten Sie, dass die Schulanmeldung erst <b>mit dem Erhalt der schriftlichen Dokumente verbindlich</b> wird. Sie können die Dokumente zusenden oder direkt in den Schulbriefkasten werfen.</p>";
  }

  $code .= cms_meldung('info', '<h4>Schulanmeldung</h4>'.$inhalt);
  $code .= "<p><a href=\"Website/Voranmeldung\" class=\"cms_button\">zurück zur Voranmeldung</a> <a href=\"Website\" class=\"cms_button\">zurück zur Website</a></p>";
}
else {$code .= $rueckgabe['code'];}


$code .= "</div>";
echo $code;
?>
