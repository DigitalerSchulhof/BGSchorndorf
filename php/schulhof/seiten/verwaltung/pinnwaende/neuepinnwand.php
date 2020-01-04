<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Pinnwand anlegen</h1>

<?php
if (r("schulhof.information.pinnwände.anlegen")) {

	include_once('php/schulhof/seiten/verwaltung/pinnwaende/pinnwaendedetails.php');

	echo cms_pinnwaende_ausgeben('-');
	echo "<p><span class=\"cms_button\" onclick=\"cms_pinnwaende_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Pinnwände\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
