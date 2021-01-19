<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

include_once('php/schulhof/seiten/notifikationen/notifikationen.php');
echo "<h1>Neuigkeiten</h1>";

$notifikationen = cms_notifikationen_ausgeben($dbs, $CMS_BENUTZERID);

if (strlen($notifikationen) > 0) {echo "<ul class=\"cms_neuigkeiten\">$notifikationen</ul>";}
echo "<p><span class=\"cms_button_nein\" onclick=\"cms_notifikationen_loeschen()\">Alle Neuigkeiten schließen</span></p>";

?>
</div>
