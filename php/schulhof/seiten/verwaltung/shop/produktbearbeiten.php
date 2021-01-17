<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Produkt bearbeiten</h1>

<?php
if (cms_r("shop.produkte.bearbeiten")) {

	if (isset($_SESSION["PRODUKTBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/shop/produktdetails.php');
		include_once('php/schulhof/seiten/website/editor/editor.php');
		echo cms_produkt_ausgeben($_SESSION["PRODUKTBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_produkt_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Produkte\">Abbrechen</a></p>";
		}
	else {
		echo cms_meldung_bastler();
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
