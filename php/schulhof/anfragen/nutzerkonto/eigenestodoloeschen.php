<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();
// Variablen einlesen, falls übergeben
postLesen("id");
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}

if (cms_angemeldet()) {
  $dbs = cms_verbinden('s');

	$sql = "DELETE FROM todo WHERE id = ? AND person = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $id, $CMS_BENUTZERID);
	if(!$sql->execute()) {echo "FEHLER";exit;}

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
