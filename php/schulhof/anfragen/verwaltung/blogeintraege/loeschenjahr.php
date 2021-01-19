<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet()) {
	$fehler = false;
	if (!cms_check_ganzzahl($jahr)) {$fehler = true;}

	if (!$fehler) {
		// BLOGEINTRÄGE LOESCHEN
		$dbs = cms_verbinden('s');
		$jahrbeginn = mktime(0,0,0,1,1,$jahr);
		$jahrende = mktime(0,0,0,1,1,$jahr+1)-1;

		$oeffentlichkeiten = array();
		for($i = 0; $i <= 4; $i++) {
			if(cms_r("artikel.$i.blogeinträge.löschen")) {
				$oeffentlichkeiten[] = $i;
			}
		}

		if(!count($oeffentlichkeiten)) {
			die("BERECHTIGUNG");
		}

		$sql = $dbs->prepare("DELETE FROM blogeintraege WHERE (datum BETWEEN ? AND ?) AND oeffentlichkeit IN (".join(',', $oeffentlichkeiten).")");
	  $sql->bind_param("ii", $jahrbeginn, $jahrende);
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
