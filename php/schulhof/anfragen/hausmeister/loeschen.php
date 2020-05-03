<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) 	{$id = $_POST['id'];} 		else {echo "FEHLER";exit;}


$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

if (cms_angemeldet() && cms_r("schulhof.technik.hausmeisteraufträge.löschen")) {
	$fehler = false;

	$dbs = cms_verbinden('s');

	if (!$fehler) {
    // AUFTRAG ÄNDERN
		$jetzt = time();
		$sql = $dbs->prepare("DELETE FROM hausmeisterauftraege WHERE id = ?");
		$sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();
		$sql = $dbs->prepare("DELETE FROM notifikationen WHERE gruppe = AES_ENCRYPT('Hausmeister', '$CMS_SCHLUESSEL') AND zielid = ?");
		$sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
