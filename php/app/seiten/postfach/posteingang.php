<?php
$code = "<h2>Posteingang</h2>";

include_once("php/schulhof/seiten/nutzerkonto/postfach/postfilter.php");

$heutet = mktime(23,59,59,date("m"), date("d"), date("Y"));
$letztenMonatt = mktime(0,0,0,0, 0, 2000);
$code .= cms_postfach_filter_ausgeben('eingang', $letztenMonatt, $heutet, '-', '', 'app');


$code .= "<table class=\"cms_liste cms_postfach_liste\">";
  $code .= "<tr>";
    $code .= "<th></th><th>Absender</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th>";
  $code .= "</tr>";
$code .= "<tbody id=\"cms_postfach_eingang_liste\">";
  $code .= cms_postfach_nachrichten_listen ('eingang', '-', 0, $heutet, '', '', '', '', 0, 25, false, 'app');
$code .= "</tbody>";
$code .= "</table>";

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
