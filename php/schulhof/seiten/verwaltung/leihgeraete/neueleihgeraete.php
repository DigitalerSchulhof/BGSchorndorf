<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Leihgeräte anlegen</h1>

<?php
if (cms_r("schulhof.organisation.leihgeräte.anlegen")) {

	include_once('php/schulhof/seiten/verwaltung/leihgeraete/leihgeraetdetails.php');

	echo cms_leihgeraet_ausgeben('-');
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_leihgeraet_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Leihgeräte\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
