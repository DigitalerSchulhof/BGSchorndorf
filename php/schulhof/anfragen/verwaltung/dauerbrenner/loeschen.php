<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Dauerbrenner löschen'];

if (cms_angemeldet() && $zugriff) {

	$fehler = false;

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("DELETE FROM dauerbrenner WHERE id = ?");
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