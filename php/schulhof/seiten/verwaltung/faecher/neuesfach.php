<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neues Fach anlegen</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Fächer anlegen'];
if ($zugriff) {

	include_once('php/schulhof/seiten/verwaltung/faecher/faecherdetails.php');

	echo cms_faecher_ausgeben('-');
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_faecher_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Fächer\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
