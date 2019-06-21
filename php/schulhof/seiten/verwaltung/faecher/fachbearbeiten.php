<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fach bearbeiten</h1>

<?php
$zugriff = $CMS_RECHTE['Planung']['Fächer bearbeiten'];
if ($zugriff) {

	if (isset($_SESSION["FAECHERBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/faecher/faecherdetails.php');
		echo cms_faecher_ausgeben($_SESSION["FAECHERBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_faecher_bearbeiten();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Fächer\">Abbrechen</a></p>";
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
