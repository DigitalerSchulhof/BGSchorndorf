<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	if ($id != '-') {
		$sql = "DELETE FROM vertretungstexte WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		$sql->execute();
	}
	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
