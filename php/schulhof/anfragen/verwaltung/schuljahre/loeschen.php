<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER";exit;}


cms_rechte_laden();

if (cms_angemeldet() && r("schulhof.planung.schuljahre.löschen")) {

	$fehler = false;

	$dbs = cms_verbinden('s');
	if (!$fehler) {
		// SCHULJAHR LOESCHEN
		$schuljahrbeginn = 0;
		$schuljahrende = 0;

		$sql = $dbs->prepare("SELECT beginn, ende FROM schuljahre WHERE id = ?");
	  $sql->bind_param("i", $id);
	  if ($sql->execute()) {
	    $sql->bind_result($schuljahrbeginn, $schuljahrende);
	    if (!$sql->fetch()) {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();
	}

	if (!$fehler) {
		$pfad = '../../../dateien/schulhof/gruppen/';

		// Notifikationen löschen
		foreach ($CMS_GRUPPEN as $art) {
			$artk = cms_textzudb($art);
			$sql = $dbs->prepare("SELECT id FROM $artk WHERE schuljahr = ?");
		  $sql->bind_param("i", $id);
			if ($sql->execute()) {
		    $sql->bind_result($sjid);
		    while($sql->fetch()) {
					if (file_exists($pfad.'/'.$artk."/".$sjid)) {
						cms_dateisystem_ordner_loeschen($pfad.'/'.$artk."/".$sjid);
					}
		    }
		  }
		  $sql->close();
		}


		$sql = $dbs->prepare("DELETE FROM schuljahre WHERE id = ?");
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
