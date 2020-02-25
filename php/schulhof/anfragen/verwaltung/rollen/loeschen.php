<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.rollen.löschen")) {
	$fehler = false;

	// Die Rolle Administrator darf nicht gelöscht werden.
	if ($id == 0) {$fehler = true;}

	if (!$fehler) {
		// ROLLE LOESCHEN
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("DELETE FROM rollen WHERE id = ?");
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
