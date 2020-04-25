<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

postLesen("ziel");

if (cms_angemeldet() && cms_r("website.weiterleiten")) {
	$_SESSION['WEITERLEITUNGZIEL'] = cms_texttrafo_e_db($ziel);
	unset($_SESSION['WEITERLEITUNGID']);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
