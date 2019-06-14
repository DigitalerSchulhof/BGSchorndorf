<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Nutzerkonto ändern</h1>

<?php
// BENUTZERNAME LADEN
include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
cms_personaldaten_benutzerkonto_aendern($CMS_BENUTZERID);
?>

</div>

<div class="cms_clear"></div>
