<?php
$code = "<h2>Probleme melden</h2>";

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
