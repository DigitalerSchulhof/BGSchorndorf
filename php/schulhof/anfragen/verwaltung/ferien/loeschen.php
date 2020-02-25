<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {$fehler = true;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.organisation.ferien.löschen")) {
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("DELETE FROM ferien WHERE id = ?");
  $sql->bind_param("i", $id);
  $sql->execute();
  $sql->close();
	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
