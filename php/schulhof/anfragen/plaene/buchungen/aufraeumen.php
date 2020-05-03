<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if (cms_angemeldet() && cms_r("schulhof.organisation.[|räume,lehrgeräte].löschen")) {
	$fehler = false;

	$dbs = cms_verbinden('s');
	$jetzt = time();
	$sql = $dbs->prepare("DELETE FROM leihenbuchen WHERE ende < ?");
	$sql->bind_param("i", $jetzt);
  $sql->execute();
  $sql->close();
	$jetzt = time();
	$sql = $dbs->prepare("DELETE FROM raeumebuchen WHERE ende < ?");
	$sql->bind_param("i", $jetzt);
  $sql->execute();
  $sql->close();
	echo "ERFOLG";
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
