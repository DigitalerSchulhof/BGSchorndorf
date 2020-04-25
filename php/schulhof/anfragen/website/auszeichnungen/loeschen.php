<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}




if (cms_angemeldet() && cms_r("website.auszeichnungen.löschen")) {
	$fehler = false;
	$dbs = cms_verbinden('s');
	$reihenfolge = null;
	// Auszeichnung laden
	$sql = $dbs->prepare("SELECT reihenfolge FROM auszeichnungen WHERE id = ?");
	$sql->bind_param("i", $id);
	if ($sql->execute()) {
		$sql->bind_result($reihenfolge);
		$sql->fetch();
	} else {$fehler = true;}
	$sql->close();

	if ($reihenfolge === null) {$fehler = true;}

	if (!$fehler) {
		// Auszeichnung löschen
		$sql = $dbs->prepare("DELETE FROM auszeichnungen WHERE id = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		// Nachfolgende Auszeichnungen aufrücken lassen
		$sql = $dbs->prepare("UPDATE auszeichnungen SET reihenfolge = reihenfolge-1 WHERE reihenfolge >= ?");
		$sql->bind_param("i", $reihenfolge);
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
