<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['bid'])) {$bid = $_POST['bid'];} else {$bid = '';}
if (isset($_POST['gid'])) {$gid = $_POST['gid'];} else {$gid = '';}
if (isset($_POST['bez'])) {$bez = $_POST['bez'];} else {$bez = '';}

if (cms_angemeldet()) {
	$_SESSION["BLOGEINTRAGID"] = $bid;
	$_SESSION["BLOGEINTRAGANZEIGENBEZEICHNUNG"] = $bez;
	$_SESSION["GRUPPEID"] = $gid;
	$_SESSION["GRUPPE"] = $gruppe;

	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
