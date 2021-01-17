<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Einstellungen des Nutzerkontos</h1>

</div>
<?php
// PERSÖNLICHE DATEN LADEN
include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
echo cms_personaldaten_einstellungen_aendern($CMS_BENUTZERID);
?>
