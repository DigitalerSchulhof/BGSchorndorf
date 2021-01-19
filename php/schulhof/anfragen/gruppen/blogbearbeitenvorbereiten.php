<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {$fehler = true;}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER";exit;}


if (cms_angemeldet()) {
	$_SESSION["BLOGEINTRAGINTERNID"] = $id;
	if (!is_null($gruppe) && !is_null($gruppenid)) {
		$_SESSION['INTERNERTERMINGRUPPE'] = $gruppe;
	  $_SESSION['INTERNERTERMINGRUPPENID'] = $gruppenid;
	}
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
