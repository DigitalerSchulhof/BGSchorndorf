<?php
$code = "<h2>Hausmeisteraufträge erteilen</h2>";
if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden.<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

include_once("php/schulhof/seiten/hausmeister/hausmeisterauftrag.php");
$code = cms_neuerhausmeisterauftrag($dbs, "app");

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
