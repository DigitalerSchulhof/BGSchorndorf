<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Schuljahr anlegen</h1>
<?php
if (r("schulhof.planung.schuljahre.anlegen")) {

	include_once('php/schulhof/seiten/verwaltung/schuljahre/schuljahrdetails.php');

	echo cms_schuljahr_ausgeben('');
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_schuljahr_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Schuljahre\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>


</div>

<div class="cms_clear"></div>
