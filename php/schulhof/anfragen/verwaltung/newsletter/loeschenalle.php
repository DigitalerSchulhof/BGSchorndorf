<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben

cms_rechte_laden();

if (cms_angemeldet() && r("website.elemente.newsletter.löschen")) {
	$dbs = cms_verbinden("s");

	$sql = $dbs->prepare("DELETE FROM newslettertypen");
  $sql->execute();
  $sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
