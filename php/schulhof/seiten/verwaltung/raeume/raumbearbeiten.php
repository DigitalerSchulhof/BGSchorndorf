<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Raum bearbeiten</h1>

<?php
if (cms_r("schulhof.planung.räume.bearbeiten")) {
	if (isset($_SESSION["RAUMBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/raeume/raumdetails.php');
		echo cms_raum_ausgeben($_SESSION["RAUMBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_raum_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Räume\">Abbrechen</a></p>";
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
