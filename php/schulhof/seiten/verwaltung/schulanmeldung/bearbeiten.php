<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Anmeldung bearbeiten</h1>

<?php
if (cms_r("schulhof.organisation.schulanmeldung.bearbeiten"))) {
	if (isset($_SESSION['ANMELDUNG BEARBEITEN'])) {
		include_once('php/schulhof/seiten/verwaltung/schulanmeldung/details.php');
		include_once('php/website/seiten/schulanmeldung/navigation.php');
		$code = cms_schulanmeldung_ausgeben($_SESSION['ANMELDUNG BEARBEITEN']);
		echo $code;
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
