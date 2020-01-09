<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.elemente.newsletter.löschen")) {
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("DELETE FROM newslettertypen WHERE id = ?");
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
