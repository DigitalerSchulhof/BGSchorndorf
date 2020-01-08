<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges"))) {
	$fehler = false;
  if($id === '')
    $fehler = true;
	if(!cms_check_ganzzahl($id, 0))
		$fehler = true;
	if (!$fehler) {
			$_SESSION["AUFFÄLLIGESID"] = $id;
			echo "ERFOLG";
			return;
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
