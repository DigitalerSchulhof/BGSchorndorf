<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Leihgeräte bearbeiten</h1>

<?php
if (cms_r("schulhof.organisation.leihgeräte.bearbeiten"))) {

	if (isset($_SESSION["LEIHGERAETBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/leihgeraete/leihgeraetdetails.php');
		echo cms_leihgeraet_ausgeben($_SESSION["LEIHGERAETBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_leihgeraet_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Leihgeräte\">Abbrechen</a></p>";
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
