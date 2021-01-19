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
if (isset($_POST['status'])) 				{$status = $_POST['status'];} 								else {echo "FEHLER";exit;}

if ((!cms_check_ganzzahl($id, 0)) || (!cms_check_ganzzahl($status, 0,1))) {
  echo "FEHLER";exit;
}

$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
	// WUNSCH EINTRAGEN
  $sql = $dbs->prepare("UPDATE vplanwuensche SET status = ? WHERE id = ?");
  $sql->bind_param("ii", $status, $id);
  $sql->execute();
  $sql->close();

	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
