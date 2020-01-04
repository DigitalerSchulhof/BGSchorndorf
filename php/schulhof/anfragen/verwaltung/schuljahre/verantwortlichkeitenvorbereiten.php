<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Verantwortlichkeiten festlegen'];

if (cms_angemeldet() && $zugriff) {
	$_SESSION["VERANTWORTLICHKEITENSCHULJAHR"] = $id;
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
