<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			else {$nutzerid = '';}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		else {$sessionid = '';}
if (isset($_POST['dbshost'])) 		{$dbshost = $_POST['dbshost'];} 			else {$dbshost = '';}
if (isset($_POST['dbsuser'])) 		{$dbsuser = $_POST['dbsuser'];} 			else {$dbsuser = '';}
if (isset($_POST['dbspass'])) 		{$dbspass = $_POST['dbspass'];} 			else {$dbspass = '';}
if (isset($_POST['dbsdb'])) 		{$dbsdb = $_POST['dbsdb'];} 				else {$dbsdb = '';}
if (isset($_POST['dbsschluessel'])) {$dbsschluessel = $_POST['dbsschluessel'];} else {$dbsschluessel = '';}

include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/check.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");

$art = "";
$angemeldet = cms_angemeldet();

$zugriff = cms_r("technik.server.netze");

if ($angemeldet && $zugriff) {
	$code = "";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Host:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_lhost\" name=\"cms_schulhof_verwaltung_schulnetz_lhost\" value=\"$CMS_DBL_HOST\"></td></tr>";
	$code .= "<tr><th>Benutzer:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_lbenutzer\" name=\"cms_schulhof_verwaltung_schulnetz_lbenutzer\" value=\"$CMS_DBL_USER\"></td></tr>";
	$code .= "<tr><th>Passwort:</th><td><input type=\"password\" id=\"cms_schulhof_verwaltung_schulnetz_lpass\" name=\"cms_schulhof_verwaltung_schulnetz_lpass\" value=\"$CMS_DBL_PASS\"></td></tr>";
	$code .= "<tr><th>Datenbank:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_ldb\" name=\"cms_schulhof_verwaltung_schulnetz_ldb\" value=\"$CMS_DBL_DB\"></td></tr>";
	$code .= "</table>";

	$code .= "<h4>Ausgangsserver</h4>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Ausgangsserver:</th><td><input type=\"text\" id=\"cms_schulhof_verwaltung_schulnetz_shserver\" name=\"cms_schulhof_verwaltung_schulnetz_shserver\" value=\"$CMS_SH_SERVER\"></td></tr>";
	$code .= "<tr><th colspan=\"2\">".cms_meldung('info', '<p>Nur eine Website auf diesem Server ist berechtigt, Informationen vom Lehrerzimmer abzurufen.</p>')."</th></tr>";
	$code .= "</table>";

	echo $code;

	cms_lehrerdb_header(true);
}
else {
	echo "BERECHTIGUNG";
	cms_lehrerdb_header(false);
}
?>
