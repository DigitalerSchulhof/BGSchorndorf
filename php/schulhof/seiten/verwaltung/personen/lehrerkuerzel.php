<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Lehrerkürzel ändern</h1>

<?php
// Prüfen, ob ID gesetzt wurden bevor die Seite geladen wird
if (isset($_SESSION['PERSONENDETAILS'])) {
	include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
	cms_personaldaten_lehrerkuerzel_aendern($_SESSION['PERSONENDETAILS']);
}
else {
	echo cms_meldung_bastler();
}
?>

</div>

<div class="cms_clear"></div>
