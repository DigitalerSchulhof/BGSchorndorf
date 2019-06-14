<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Dateien</h1>

<?php
$zugriff = $CMS_RECHTE['Website']['Dateien hochladen'] || $CMS_RECHTE['Website']['Dateien umbenennen'] || $CMS_RECHTE['Website']['Dateien löschen'] ||  $CMS_RECHTE['Website']['Ordner anlegen'] ||  $CMS_RECHTE['Website']['Ordner umbenennen'] ||  $CMS_RECHTE['Website']['Ordner löschen'];

if ($zugriff) {
  include_once('php/schulhof/funktionen/dateisystem.php');
  $rechte['dateiupload'] = $CMS_RECHTE['Website']['Dateien hochladen'];
  $rechte['dateiumbenennen'] = $CMS_RECHTE['Website']['Dateien umbenennen'];
  $rechte['dateiloeschen'] = $CMS_RECHTE['Website']['Dateien löschen'];
  $rechte['dateidownload'] = true;
	$code = cms_dateisystem_generieren ('website', 'website', 'cms_website_dateien', 's', 'website', '-', $rechte);
	echo $code;
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
?>

<div class="cms_clear"></div>
