<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neuen Dauerbrenner anlegen</h1>

<?php
if (r("schulhof.information.dauerbrenner.anlegen")) {

	include_once('php/schulhof/seiten/verwaltung/dauerbrenner/dauerbrennerdetails.php');
	include_once('php/schulhof/seiten/website/editor/editor.php');

	echo cms_dauerbrenner_ausgeben('-');
	echo "<p><span class=\"cms_button\" onclick=\"cms_dauerbrenner_neu_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Dauerbrenner\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
