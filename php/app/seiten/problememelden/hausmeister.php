<?php
$code = "<h2>Hausmeisteraufträge erteilen</h2>";

include_once("php/schulhof/seiten/hausmeister/hausmeisterauftrag.php");
$code = cms_neuerhausmeisterauftrag($dbs, "app");

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
