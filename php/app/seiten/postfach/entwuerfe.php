<?php
$code = "<h2>Entwürfe</h2>";
if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden.<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

include_once("php/schulhof/seiten/nutzerkonto/postfach/postfilter.php");

$heutet = mktime(23,59,59,date("m"), date("d"), date("Y"));
$letztenMonatt = mktime(0,0,0,0, 0, 2000);
$code .= cms_postfach_filter_ausgeben('entwurf', $letztenMonatt, $heutet, '-', '', 'app');


$code .= "<table class=\"cms_liste cms_postfach_liste\">";
  $code .= "<tr>";
    $code .= "<th></th><th>Absender</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th>";
  $code .= "</tr>";
$code .= "<tbody id=\"cms_postfach_entwurf_liste\">";
  $code .= cms_postfach_nachrichten_listen ('entwurf', '-', 0, $heutet, '', '', '', '', 0, 25, false, 'app');
$code .= "</tbody>";
$code .= "</table>";

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
