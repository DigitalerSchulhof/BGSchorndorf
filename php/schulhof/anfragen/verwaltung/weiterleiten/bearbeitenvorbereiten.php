<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

postLesen("id");

if (cms_angemeldet() && cms_r("website.weiterleiten")) {
	unset($_SESSION['WEITERLEITUNGZIEL']);
	$_SESSION['WEITERLEITUNGID'] = $id;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
