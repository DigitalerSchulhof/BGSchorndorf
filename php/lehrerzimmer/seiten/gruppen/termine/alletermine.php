<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";
$code .= "<h1>Alle Termine</h1>";

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

	$gruppe = $_SESSION["KALENDERGRUPPE"];
	$gruppenid = $_SESSION["KALENDERGRUPPEID"];
	$gruppenrechte = cms_gruppenrechte_laden($gruppe, $gruppenid);
	if ($gruppenrechte['mitglied'] && $gruppenrechte['sch']) {
		$code .= "<h4>Aktionen</h4>";
		$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schulhof_termine_neu_vorbereiten('$gruppe', '$gruppenid')\">+ Neuer Termin</span></p>";
	}

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
