<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Hauptnavigationen</h1>

<?php
if (r("website.navigation")) {
	$code = "</div>";

	include_once('php/schulhof/seiten/website/navigationen/navigationendetails.php');
	include_once('php/schulhof/seiten/website/seiten/seitenwahl.php');

	$dbs = cms_verbinden('s');
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Kopfzeile</h2>";
	$code .= cms_navigation_ausgeben_bearbeiten ($dbs, 'cms_navigation_hauptnavigation_h', 'h');


	$code .= "<h2>Sidebar</h2>";
	$code .= cms_navigation_ausgeben_bearbeiten ($dbs, 'cms_navigation_hauptnavigation_s', 's');
	$code .= "</div></div>";



	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h2>Fußzeile</h2>";
	$code .= cms_navigation_ausgeben_bearbeiten ($dbs, 'cms_navigation_hauptnavigation_f', 'f');
	$code .= "</div></div>";
	cms_trennen($dbs);

	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_website_hauptnavigationen_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
	$code .= "</div>";
	echo $code;
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
?>

<div class="cms_clear"></div>
