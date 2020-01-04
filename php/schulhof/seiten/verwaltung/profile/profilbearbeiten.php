<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Profil bearbeiten</h1>

<?php
if (r("schulhof.planung.schuljahre.fächer.bearbeiten")) {

	if (isset($_SESSION["PROFILBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/profile/profiledetails.php');
		echo cms_profile_ausgeben($_SESSION["PROFILBEARBEITEN"]);
		echo "<p><span class=\"cms_button\" onclick=\"cms_profile_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Profile\">Abbrechen</a></p>";
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
