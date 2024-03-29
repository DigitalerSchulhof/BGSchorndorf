<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}


$zugriff = cms_r("artikel.galerien.löschen");

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	if (!cms_check_ganzzahl($jahr)) {$fehler = true;}

	if (!$fehler) {
		// ROLLE LOESCHEN
		$dbs = cms_verbinden('s');
		$jahrbeginn = mktime(0,0,0,1,1,$jahr);
		$jahrende = mktime(0,0,0,1,1,$jahr+1)-1;

		$sql = $dbs->prepare("DELETE FROM galerien WHERE (datum BETWEEN ? AND ?)");
	  $sql->bind_param("ii", $jahrbeginn, $jahrende);
	  $sql->execute();
	  $sql->close();

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
