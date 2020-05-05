<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$dbs = cms_verbinden('s');
$dbl = cms_verbinden('l');
if ($angemeldet && cms_r("technik.server.netze")) {
  $code  = "<h3>Datenbanken</h3>";
	$code .= "<h4>Lehrer</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Host:</th><td>".cms_generiere_input('cms_netze_host_lsh', $CMS_DBL_HOST)."</td></tr>";
	$code .= "<tr><th>Benutzer:</th><td>".cms_generiere_input('cms_netze_benutzer_lsh', $CMS_DBL_USER)."</td></tr>";
	$code .= "<tr><th>Passwort:</th><td>".cms_generiere_input('cms_netze_passwort_lsh', $CMS_DBL_PASS, "password")."</td></tr>";
	$code .= "<tr><th>Datenbank:</th><td>".cms_generiere_input('cms_netze_datenbank_lsh', $CMS_DBL_DB)."</td></tr>";
	$code .= "</table>";

	$code .= "<h3>Sonstiges</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Schülerserver:</th><td>".cms_generiere_input('cms_netze_schuelerverzeichnis_lsh', $CMS_SH_SERVER)."</td></tr>";
	$code .= "</table>";
  cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
  echo cms_meldung_berechtigung ();
}
cms_trennen($dbl);
cms_trennen($dbs);
?>
