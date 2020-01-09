<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();
// Variablen einlesen, falls übergeben

if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("website.freigeben")) {
	$fehler = false;

  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten');
  if (!in_array($art, $elemente)) {$fehler = true;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
    $sql = "UPDATE ? SET aktiv = '1' WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("si", $art, $id);
		$sql->execute();
		if ($art == 'boxenaussen') {
			$sql = "UPDATE boxen SET aktiv = '1' WHERE boxaussen = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $id);
			$sql->execute();
		}
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
