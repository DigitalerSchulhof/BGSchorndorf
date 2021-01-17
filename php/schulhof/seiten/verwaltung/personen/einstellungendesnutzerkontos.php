<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Einstellungen des Nutzerkontos ändern</h1>


<?php
// Prüfen, ob ID gesetzt wurden bevor die Seite geladen wird
if (isset($_SESSION['PERSONENDETAILS'])) {
	include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
	echo cms_personaldaten_einstellungen_aendern($_SESSION['PERSONENDETAILS']);
}
else {
	echo cms_meldung_bastler();
	echo "</div>";
}
?>
