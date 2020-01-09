<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben

cms_rechte_laden();

if (cms_angemeldet()) {
	if(!cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
		echo "BERECHTIGUNG";
		die();
	}
	$weilreference0 = 0;
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("DELETE FROM auffaelliges;");
  $sql->execute();
	$sql->close();
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
