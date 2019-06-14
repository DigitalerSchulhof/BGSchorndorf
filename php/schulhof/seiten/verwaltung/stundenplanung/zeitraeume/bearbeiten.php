<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

<h1>Zeitraum bearbeiten</h1>
<?php
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'];
if ($zugriff) {
	// PERSÖNLICHE DATEN LADEN
	if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/zeitraeume/details.php');
		echo cms_zeitraum_ausgeben($_SESSION["STUNDENPLANZEITRAUM"]);
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
