<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Rolle bearbeiten</h1>
<?php
$zugriff = $CMS_RECHTE['Personen']['Rollen bearbeiten'];
if ($zugriff) {
	// PERSÖNLICHE DATEN LADEN
	if (isset($_SESSION["ROLLEBEARBEITEN"])) {
		include_once('php/schulhof/seiten/verwaltung/rollen/rollendetails.php');
		echo cms_rolle_ausgeben($_SESSION["ROLLEBEARBEITEN"]);
		}
	else {
		echo cms_meldung_bastler();
	}
	echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_rolle_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Rollen/\">Abbrechen</a></p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
