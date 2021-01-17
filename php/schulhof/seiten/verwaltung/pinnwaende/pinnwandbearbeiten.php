<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Pinnwand bearbeiten</h1>

<?php
if (cms_r("schulhof.information.pinnwände.bearbeiten")) {

	if (isset($_SESSION["PINNWANDBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/pinnwaende/pinnwaendedetails.php');
		echo cms_pinnwaende_ausgeben($_SESSION["PINNWANDBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_pinnwaende_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Pinnwände\">Abbrechen</a></p>";
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
