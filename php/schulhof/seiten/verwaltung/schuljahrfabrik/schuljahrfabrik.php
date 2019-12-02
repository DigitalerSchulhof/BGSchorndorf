<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

$code = "";
if ($zugriff) {
  $code .= "<h1>Schuljahrfabrik</h1>";
  $code .= "<h2>Teilschritte</h2>";
  $code .= "<p>";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_grundlagen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen\">Grundlagen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_profile\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\">Profile</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}
echo $code;
?>
</div>
<div class="cms_clear"></div>
