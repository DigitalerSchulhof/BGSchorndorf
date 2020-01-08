<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Titelbilder</h1>

<?php
if (!cms_r("website.titelbilder.*"))) {
  echo cms_meldung_berechtigung();
}
else {
  $code = "";
  if (cms_r("website.titelbilder.hochladen"))) {
    $meldetext = "<h4>Optimale Voraussetzungen</h4><p>Damit die Bilder von angemessener Qualität sind und damit sich beim Wechsel der Bilder keine hässlichen Ränder ergeben sollten sie angemessen breit (mindestens 2000 Pixel) und alle gleich hoch (zum Beispiel 250 Pixel) sein.</p><p>Außerdem sollten hier nur Bilder hochgeladen werden.</p>";
    $code .= cms_meldung('info', $meldetext);
  }

  include_once('php/schulhof/funktionen/dateisystem.php');
  $rechte = cms_titelbilderdateirechte_laden();
	$code .= cms_dateisystem_generieren ('titelbilder', 'titelbilder', 'cms_titelbilder_dateien', 's', 'titelbilder', '-', $rechte);

  echo $code;
}
?>

</div>
<div class="cms_clear"></div>
