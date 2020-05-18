<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($status,0,3)) {echo "FEHLER"; exit;}

if (cms_angemeldet() && cms_r("shop.bestellungen.verarbeiten")) {

	$fehler = false;

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE ebestellung SET status = ? WHERE id = ?");
	  $sql->bind_param("ii", $status, $id);
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
