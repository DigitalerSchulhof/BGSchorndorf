<?php
$code = "<h2>Papierkorb</h2>";
if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden. Neuerungen umfassen vereinfachte Navigation, Darkmode, direktes Öffnen von Seiten, erhöhte Stabilität und mehr!<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

include_once("php/schulhof/seiten/nutzerkonto/postfach/postfilter.php");

$heutet = mktime(23,59,59,date("m"), date("d"), date("Y"));
$letztenMonatt = mktime(0,0,0,0, 0, 2000);

$code .= "<ul class=\"cms_reitermenue\">";
  $code .= "<li><span id=\"cms_reiter_postfach_papierkorb_0\" class=\"cms_reiter_aktiv\" onclick=\"javascript:cms_reiter('postfach_papierkorb', 0,2)\">Posteingang</a></li> ";
  $code .= "<li><span id=\"cms_reiter_postfach_papierkorb_1\" class=\"cms_reiter\" onclick=\"javascript:cms_reiter('postfach_papierkorb', 1,2)\">Entwürfe</a></li> ";
  $code .= "<li><span id=\"cms_reiter_postfach_papierkorb_2\" class=\"cms_reiter\" onclick=\"javascript:cms_reiter('postfach_papierkorb', 2,2)\">Postausgang</a></li>";
$code .= "</ul>";

$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_postfach_papierkorb_0\" style=\"display: block;\">";
$code .= "<div class=\"cms_reitermenue_i\">";
  $code .= cms_postfach_filter_ausgeben ('eingang', $letztenMonatt, $heutet, '1', '0', 'app');
  $code .= "<table class=\"cms_liste cms_postfach_liste\">";
    $code .= "<tr>";
      $code .= "<th></th><th>Absender</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th>";
    $code .= "</tr>";
  $code .= "<tbody id=\"cms_postfach_eingang_liste\">";
    $code .= cms_postfach_nachrichten_listen ('eingang', '1', 0, $heutet, '', '', '', '', 0, 25, false, 'app');
  $code .= "</tbody>";
  $code .= "</table>";

  $code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_postfach_papierkorb_leeren_anzeigen('eingang', 'app')\">Papierkorb »Posteingang« leeren</span></p>";
$code .= "</div>";
$code .= "</div>";

$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_postfach_papierkorb_1\">";
$code .= "<div class=\"cms_reitermenue_i\">";
  $code .= cms_postfach_filter_ausgeben ('entwurf', $letztenMonatt, $heutet, '1', '1', 'app');
  $code .= "<table class=\"cms_liste cms_postfach_liste\">";
    $code .= "<tr>";
      $code .= "<th></th><th>Empfänger</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th>";
    $code .= "</tr>";
  $code .= "<tbody id=\"cms_postfach_entwurf_liste\">";
    $code .= cms_postfach_nachrichten_listen ('entwurf', '1', 0, $heutet, '', '', '', '', 0, 25, false, 'app');
  $code .= "</tbody>";
  $code .= "</table>";

  $code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_postfach_papierkorb_leeren_anzeigen('entwurf', 'app')\">Papierkorb »Entwürfe« leeren</span></p>";
$code .= "</div>";
$code .= "</div>";

$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_postfach_papierkorb_2\">";
$code .= "<div class=\"cms_reitermenue_i\">";
  $code .= cms_postfach_filter_ausgeben ('ausgang', $letztenMonatt, $heutet, '1', '2', 'app');
  $code .= "<table class=\"cms_liste cms_postfach_liste\">";
    $code .= "<tr>";
      $code .= "<th></th><th>Empfänger</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th>";
    $code .= "</tr>";
  $code .= "<tbody id=\"cms_postfach_ausgang_liste\">";
    $code .= cms_postfach_nachrichten_listen ('ausgang', '1', 0, $heutet, '', '', '', '', 0, 25, false, 'app');
  $code .= "</tbody>";
  $code .= "</table>";

  $code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_postfach_papierkorb_leeren_anzeigen('ausgang', 'app')\">Papierkorb »Postausgang« leeren</span></p>";
$code .= "</div>";
$code .= "</div>";

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
