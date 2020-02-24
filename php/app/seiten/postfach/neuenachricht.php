<?php
$code = "<h2>Neue Nachricht</h2>";

include_once("php/schulhof/seiten/nutzerkonto/postfach/neuenachrichtfkt.php");
$code .= cms_neue_nachricht($dbs, 'app');

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
