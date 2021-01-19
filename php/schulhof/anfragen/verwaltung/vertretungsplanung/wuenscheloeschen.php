<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();
if (isset($_POST['id'])) 				    {$id = $_POST['id'];} 								        else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}

$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
	// WUNSCH EINTRAGEN
  $sql = $dbs->prepare("DELETE FROM vplanwuensche WHERE id = ?");
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
