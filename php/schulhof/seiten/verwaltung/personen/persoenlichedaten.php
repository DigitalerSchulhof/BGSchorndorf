<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Person bearbeiten</h1>

</div>
<?php
// PERSÖNLICHE DATEN LADEN
if (isset($_SESSION['PERSONENDETAILS'])) {
	include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
	cms_personaldaten_aendern($_SESSION['PERSONENDETAILS']);
}
else {
	echo "<div class=\"cms_spalte_i\">";
	echo cms_meldung_bastler();
	echo "</div>";
}
?>
