<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.organisation.schulanmeldung.löschen")) {
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("DELETE FROM voranmeldung_schueler");
	$sql->execute();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
