<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

$id = $_SESSION['BENUTZERID'];

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zeit'])) {$zeit = $_POST['zeit'];} else {echo "FEHLER"; exit;}


if (cms_angemeldet()) {
	// PROFILDATEN UPDATEN
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("DELETE FROM identitaetsdiebstahl WHERE id = ? AND zeit = ?");
	$sql->bind_param("ii", $id, $zeit);
	$sql->execute();
	$sql->close();
	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
