<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Schulanmeldungen löschen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	$sql = "DELETE FROM voranmeldung_schueler";
	$dbs->query($sql);	// Safe weil keine Einfgabe
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
