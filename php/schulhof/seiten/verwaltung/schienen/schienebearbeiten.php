<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schiene bearbeiten</h1>

<?php
if (cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.schienen.bearbeiten")) {

	if (isset($_SESSION["SCHIENEBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/schienen/schienendetails.php');
		echo cms_schiene_ausgeben($_SESSION["SCHIENEBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_schienen_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Schienen\">Abbrechen</a></p>";
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
