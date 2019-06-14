<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppesingular'])) {$singular = $_POST['gruppesingular'];} else {$singular = '';}
if (isset($_POST['gruppeneu'])) {$neu = $_POST['gruppeneu'];} else {$neu = '';}
if (isset($_POST['gruppeartikel'])) {$artikel = $_POST['gruppeartikel'];} else {$artikel = '';}
if (isset($_POST['blogid'])) {$bid = $_POST['blogid'];} else {$bid = '';}
if (isset($_POST['gruppeid'])) {$gid = $_POST['gruppeid'];} else {$gid = '';}

if (cms_angemeldet()) {
	$_SESSION["BLOGEINTRAGID"] = $bid;
	$_SESSION["BLOGBEARBEITEN"] = $bid;
	$_SESSION["GRUPPEID"] = $gid;
	$_SESSION["GRUPPE"] = $gruppe;
	$_SESSION["GRUPPESINGULAR"] = $singular;
	$_SESSION["GRUPPENEU"] = $neu;
	$_SESSION["GRUPPEARTIKEL"] = $artikel;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
