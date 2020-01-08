<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neuen Raum anlegen</h1>

<?php
if (cms_r("schulhof.planung.räume.anlegen"))) {

	include_once('php/schulhof/seiten/verwaltung/raeume/raumdetails.php');

	echo cms_raum_ausgeben('-');
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_raum_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Räume\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
