<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Seite bearbeiten</h1>

<?php
$zugriff = $CMS_RECHTE['Website']['Seiten bearbeiten'];
if ($zugriff) {
	if ((isset($_SESSION['SEITENBEARBEITENID'])) && ((isset($_SESSION['SEITENBEARBEITENZUORDNUNG']) || (is_null($_SESSION['SEITENBEARBEITENZUORDNUNG']))))) {
		include_once('php/schulhof/seiten/website/seiten/seitendetails.php');
		echo cms_website_seiten_ausgeben($_SESSION['SEITENBEARBEITENID'], $_SESSION['SEITENBEARBEITENZUORDNUNG']);
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