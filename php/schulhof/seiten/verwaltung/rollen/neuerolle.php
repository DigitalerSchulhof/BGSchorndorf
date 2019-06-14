<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Rolle anlegen</h1>
<?php
$zugriff = $CMS_RECHTE['Personen']['Rollen anlegen'];
if ($zugriff) {

	include_once('php/schulhof/seiten/verwaltung/rollen/rollendetails.php');

	echo cms_rolle_ausgeben('');
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_rolle_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Rollen/\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>


</div>

<div class="cms_clear"></div>
