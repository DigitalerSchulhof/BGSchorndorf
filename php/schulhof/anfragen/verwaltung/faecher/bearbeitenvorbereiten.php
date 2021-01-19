<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {$fehler = true;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.fächer.bearbeiten")) {
	$_SESSION["FAECHERBEARBEITEN"] = $id;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
