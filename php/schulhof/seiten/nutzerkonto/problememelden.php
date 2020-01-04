<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Probleme melden</h1>
</div>

<?php
$spalten = 0;
$code = "";
if (r("schulhof.technik.geräte.probleme")) {
  if (r("schulhof.organisation.räume.sehen")) {$spalten++;}
  if (r("schulhof.organisation.leihgeräte.sehen")) {$spalten++;}
}
if ($CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] || r("schulhof.technik.hausmeisteraufträge.erteilen")) {$spalten++;}


if (r("schulhof.technik.geräte.probleme")) {
  if (r("schulhof.organisation.räume.sehen")) {
    $code .= "<div class=\"cms_spalte_$spalten\"><div class=\"cms_spalte_i\">";
    $code .= "<h3>Probleme in Räumen</h3>";
    $code .= cms_listezuabsatz(cms_schulhof_raeume_links_anzeigen());
    $code .= "</div></div>";
  }
  if (r("schulhof.organisation.leihgeräte.sehen")) {
    $code .= "<div class=\"cms_spalte_$spalten\"><div class=\"cms_spalte_i\">";
    $code .= "<h3>Probleme an Leihgeräten</h3>";
    $code .= cms_listezuabsatz(cms_schulhof_leihgeraete_links_anzeigen());
    $code .= "</div></div>";
  }
}



if ($CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] || r("schulhof.technik.hausmeisteraufträge.erteilen")) {
  $code .= "<div class=\"cms_spalte_$spalten\"><div class=\"cms_spalte_i\">";

  if (r("schulhof.technik.hausmeisteraufträge.erteilen")) {
    $code .= "<h3>Hausmeisteraufträge</h3>";
    $code .= "<p><a class=\"cms_button\" href=\"Schulhof/Hausmeister\">Hausmeisteraufträge erteilen</a></p>";
  }

  if ($CMS_EINSTELLUNGEN['Fehlermeldung aktiv']) {
    $code .= "<h3>Fehler an der Software</h3>";
    $code .= "<p><a class=\"cms_button\" href=\"Website/Feedback\">Softwareprobleme melden</a></p>";
  }

  $code .= "</div></div>";
}

echo $code;
?>




<div class="cms_clear"></div>
