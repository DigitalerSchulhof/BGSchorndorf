<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

<h1>Persönliche Daten ändern</h1>

</div>
<?php
// PERSÖNLICHE DATEN LADEN
include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
cms_personaldaten_aendern($CMS_BENUTZERID);
?>
