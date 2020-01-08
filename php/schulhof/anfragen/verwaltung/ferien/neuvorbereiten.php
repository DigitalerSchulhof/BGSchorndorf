<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
cms_rechte_laden();
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];

if (cms_angemeldet() && cms_r("schulhof.organisation.ferien.anlegen"))) {
	$_SESSION["FERIENID"] = '-';
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
