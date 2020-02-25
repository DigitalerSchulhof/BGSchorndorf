<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Dauerbrenner bearbeiten</h1>

<?php
if (cms_r("schulhof.information.dauerbrenner.bearbeiten")) {

	if (isset($_SESSION["DAUERBRENNERBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/dauerbrenner/dauerbrennerdetails.php');
		include_once('php/schulhof/seiten/website/editor/editor.php');
		echo cms_dauerbrenner_ausgeben($_SESSION["DAUERBRENNERBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_dauerbrenner_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Dauerbrenner\">Abbrechen</a></p>";
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
