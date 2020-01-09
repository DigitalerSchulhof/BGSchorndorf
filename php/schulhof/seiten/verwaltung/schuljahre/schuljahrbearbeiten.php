<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schuljahr bearbeiten</h1>
<?php
if (cms_r("schulhof.planung.schuljahre.bearbeiten")) {
	// PERSÖNLICHE DATEN LADEN
	if (isset($_SESSION["SCHULJAHREBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/schuljahre/schuljahrdetails.php');
		echo cms_schuljahr_ausgeben($_SESSION["SCHULJAHREBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_schuljahr_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Schuljahre\">Abbrechen</a></p>";
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
