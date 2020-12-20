<?php
$code = "<h2>Probleme melden</h2>";
if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden.<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

$menue = "";

if (cms_r("schulhof.technik.geräte.probleme")) {
  $menue .= "<li><a class=\"cms_uebersicht_app_geraetemelden\" href=\"App/Probleme_melden/Geräte\">";
    $menue .= "<h3>Geräte melden</h3>";
    $menue .= "<p>Defekte Geräte den zuständigen Stellen melden</p>";
  $menue .= "</a></li>";
}
if (cms_r("schulhof.technik.hausmeisteraufträge.erteilen")) {
  $menue .= "<li><a class=\"cms_uebersicht_app_hausmeisterauftraege\" href=\"App/Probleme_melden/Hausmeisteraufträge\">";
    $menue .= "<h3>Hausmeisteraufträge erteilen</h3>";
    $menue .= "<p>Hausmeisteraufträge erteilen</p>";
  $menue .= "</a></li>";
}

if (strlen($menue) > 0) {
  $code .= "<ul class=\"cms_uebersicht\">".$menue."</ul>";
}
else {
  $code .= "<p class=\"cms_notiz\">Es stehen momentan keine Dienste zur Verfügung.</p>";
}

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
