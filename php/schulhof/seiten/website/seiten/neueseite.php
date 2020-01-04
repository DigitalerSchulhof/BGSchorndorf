<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neue Seite anlegen</h1>

<?php
if (r("website.seiten.anlegen")) {

	if (isset($_SESSION['SEITENNEUZUORDNUNG'])) {
		include_once('php/schulhof/seiten/website/seiten/seitendetails.php');
		echo cms_website_seiten_ausgeben('-', $_SESSION['SEITENNEUZUORDNUNG']);
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
