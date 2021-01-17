<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Postfach</h1>
</div>

<?php
echo "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");
echo "</div></div>";

$code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
$code .= "<h2>Neue Nachricht</h2>";

include_once("php/schulhof/seiten/nutzerkonto/postfach/neuenachrichtfkt.php");
$code .= cms_neue_nachricht($dbs);

$code .= "</div></div>";

$code .= "<div class=\"cms_clear\"></div>";
echo $code;
?>
