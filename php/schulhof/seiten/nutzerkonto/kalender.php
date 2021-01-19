<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

<?php

$code = "";
$code .= "<h1>Persönlicher Kalender</h1>";

include_once('php/schulhof/seiten/termine/termineausgeben.php');
include_once('php/schulhof/seiten/termine/kalenderuebersicht.php');
include_once('php/schulhof/seiten/termine/kalenderansicht.php');

$bastler = false;

if ((!isset($_SESSION["KALENDER"])) || (!isset($_SESSION["KALENDERGRUPPE"])) || (!isset($_SESSION["KALENDERGRUPPEID"])) || (!isset($_SESSION["KALENDERANSICHT"])) || (!isset($_SESSION["KALENDERTAG"])) || (!isset($_SESSION["KALENDERMONAT"])) || (!isset($_SESSION["KALENDERJAHR"])) || (!isset($_SESSION["KALENDERUEBERSICHTMONAT"])) || (!isset($_SESSION["KALENDERUEBERSICHTJAHR"])) || (!isset($_SESSION["KALENDERTERMINEBARBEITEN"]))) {
	$bastler = true;
}

if ($bastler) {
	$code .= cms_meldung_bastler();
}

$code .= "</div>";

if (!$bastler) {
$code .= "<div class=\"cms_spalte_4\">";
$code .= "<div class=\"cms_spalte_i\">";

	$code .= cms_kalenderansichtaktiv_ausgeben("schulhof_nutzerkonto_kalender");
	$code .= cms_kalenderansichtwahl_ausgeben("schulhof_nutzerkonto_kalender");

$code .= "</div>";
$code .= "</div>";

$code .= "<div class=\"cms_spalte_34\">";
	$code .= cms_kalenderuebersichtsfenster_ausgeben("schulhof_nutzerkonto_kalender");
$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";


$code .= cms_kalenderansichtsfenster_ausgeben("schulhof_nutzerkonto_kalender");
}

$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
