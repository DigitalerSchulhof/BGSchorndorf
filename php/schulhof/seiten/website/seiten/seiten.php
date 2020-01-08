<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Seiten</h1>

<?php
if (cms_r("website.seiten.*"))) {

	include_once('php/schulhof/seiten/website/seiten/seitenbaum.php');

	$code = "<table class=\"cms_liste\">";
		$code .= "<tr><th></th><th>Bezeichnung</th><th>Status</th><th>Aktionen</th></tr>";
		$dbs = cms_verbinden('s');
		$code .= cms_seitenbaum_ausgeben($dbs, '-', 1, true);
		cms_trennen($dbs);
	$code .= "</table>";

	if (cms_r("website.seiten.anlegen"))) {
		$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('-')\">+ Neue Seite anlegen</span></p>";
	}

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
<div class="cms_clear"></div>
