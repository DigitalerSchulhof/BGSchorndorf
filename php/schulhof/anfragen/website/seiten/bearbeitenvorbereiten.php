<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.seiten.bearbeiten"))) {
	$fehler = false;
	$zuordnung = '';
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("SELECT zuordnung FROM seiten WHERE id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($zuordnung);
    if (!$sql->fetch()) {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();
	cms_trennen($dbs);

	if (!$fehler) {
		$_SESSION["SEITENBEARBEITENZUORDNUNG"] = $zuordnung;
		$_SESSION["SEITENBEARBEITENID"] = $id;
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "FEHLER";
}
?>
