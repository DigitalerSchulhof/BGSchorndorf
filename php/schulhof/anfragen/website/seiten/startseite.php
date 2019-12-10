<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Startseite festlegen'];

if (cms_angemeldet() && $zugriff) {

	$fehler = false;

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE seiten SET status = 'a' WHERE status = 's'");
		$sql->execute();
	  $sql->close();
		$sql = $dbs->prepare("UPDATE seiten SET status = 's' WHERE id = ?");
	  $sql->bind_param("i", $id);
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
