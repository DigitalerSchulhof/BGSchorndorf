<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

if (isset($_SESSION['SESSIONAKTIVITAET'])) {$CMS_SESSIONAKTIVITAET = $_SESSION['SESSIONAKTIVITAET'];} else {echo "FEHLER";exit;}

if (cms_angemeldet()) {
	echo cms_timeout_verlaengern();
}
else {
	echo "BERECHTIGUNG";
}
?>
