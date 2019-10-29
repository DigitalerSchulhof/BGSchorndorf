<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/rechte/rechte.php");

session_start();

// Variablen einlesen, falls übergeben
postLesen("rechte");
cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.verwaltung.rechte.zuordnen")) {
	$fehler = false;
	$rechte = explode(",", $rechte);
	if (!isset($_SESSION['PERSONENDETAILS'])) {
		die("FEHLER");
	}
	else {
		$person = $_SESSION['PERSONENDETAILS'];
	}

	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("DELETE FROM rechtezuordnung WHERE person = ?");
  $sql->bind_param("i", $person);
	$sql->execute();

	$sql = "INSERT INTO rechtezuordnung (`person`, `recht`) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("is", $person, $recht);
	foreach($rechte as $recht) {
		$sql->execute();
	}

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
