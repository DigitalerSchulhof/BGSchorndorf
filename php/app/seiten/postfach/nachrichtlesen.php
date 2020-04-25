<?php
$code = "<h2>Nachricht lesen</h2>";

include_once("php/schulhof/seiten/nutzerkonto/postfach/nachrichtlesenfkt.php");
$code .= cms_nachricht_lesen($dbs, "app");

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
