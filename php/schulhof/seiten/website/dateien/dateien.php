<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Dateien</h1>

<?php
if (cms_r("website.dateien.*")) {
  include_once('php/schulhof/funktionen/dateisystem.php');
  $rechte['dateiupload']      = cms_r("website.dateien.hochladen");
  $rechte['dateiumbenennen']  = cms_r("website.dateien.umbenennen");
  $rechte['dateiloeschen']    = cms_r("website.dateien.löschen");
  $rechte['dateidownload']    = true;
	$code = cms_dateisystem_generieren ('website', 'website', 'cms_website_dateien', 's', 'website', '-', $rechte);
	echo $code;
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
?>

<div class="cms_clear"></div>
