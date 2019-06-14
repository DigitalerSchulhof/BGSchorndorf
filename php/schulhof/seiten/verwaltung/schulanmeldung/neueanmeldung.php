<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Anmeldung</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Schulanmeldungen erfassen'];

if ($zugriff) {
	include_once('php/schulhof/seiten/verwaltung/schulanmeldung/details.php');
	include_once('php/website/seiten/schulanmeldung/navigation.php');
	$code = cms_schulanmeldung_ausgeben('-');

	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
