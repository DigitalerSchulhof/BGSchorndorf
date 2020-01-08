<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['vplanextern'])) {$vplanextern = $_POST['vplanextern'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanschueleraktuell'])) {$vplanschueleraktuell = $_POST['vplanschueleraktuell'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanschuelerfolgetag'])) {$vplanschuelerfolgetag = $_POST['vplanschuelerfolgetag'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanlehreraktuell'])) {$vplanlehreraktuell = $_POST['vplanlehreraktuell'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanlehrerfolgetag'])) {$vplanlehrerfolgetag = $_POST['vplanlehrerfolgetag'];} else {echo "FEHLER";exit;}
if (isset($_POST['lehrerstundenplaene'])) {$lehrerstundenplaene = $_POST['lehrerstundenplaene'];} else {echo "FEHLER";exit;}
if (isset($_POST['klassenstundenplaene'])) {$klassenstundenplaene = $_POST['klassenstundenplaene'];} else {echo "FEHLER";exit;}
if (isset($_POST['raumstundenplaene'])) {$raumstundenplaene = $_POST['raumstundenplaene'];} else {echo "FEHLER";exit;}
if (isset($_POST['buchungsbeginnS'])) {$buchungsbeginnS = $_POST['buchungsbeginnS'];} else {echo "FEHLER";exit;}
if (isset($_POST['buchungsbeginnM'])) {$buchungsbeginnM = $_POST['buchungsbeginnM'];} else {echo "FEHLER";exit;}
if (isset($_POST['buchungsendeS'])) {$buchungsendeS = $_POST['buchungsendeS'];} else {echo "FEHLER";exit;}
if (isset($_POST['buchungsendeM'])) {$buchungsendeM = $_POST['buchungsendeM'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanskennung'])) {$vplanskennung = $_POST['vplanskennung'];} else {echo "FEHLER";exit;}
if (isset($_POST['vplanlkennung'])) {$vplanlkennung = $_POST['vplanlkennung'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.einstellungen"))) {
	$fehler = false;

	if (!cms_check_toggle($vplanextern)) {$fehler = true;}
	if (!cms_check_toggle($lehrerstundenplaene)) {$fehler = true;}
	if (!cms_check_toggle($klassenstundenplaene)) {$fehler = true;}
	if (!cms_check_toggle($raumstundenplaene)) {$fehler = true;}
	if (!cms_check_titel($vplanskennung)) {$fehler = true;}
	if (!cms_check_titel($vplanlkennung)) {$fehler = true;}

	if (!cms_check_ganzzahl($buchungsbeginnS,0,23)) {$fehler = true;}
	if (!cms_check_ganzzahl($buchungsbeginnM,0,59)) {$fehler = true;}
	if (!cms_check_ganzzahl($buchungsendeS,0,23)) {$fehler = true;}
	if (!cms_check_ganzzahl($buchungsendeM,0,59)) {$fehler = true;}

	if ($vplanextern == 1) {
		if (strlen($vplanschueleraktuell) == 0) {$fehler = true;}
		if (strlen($vplanschuelerfolgetag) == 0) {$fehler = true;}
		if (strlen($vplanlehreraktuell) == 0) {$fehler = true;}
		if (strlen($vplanlehrerfolgetag) == 0) {$fehler = true;}
	}
	else {
		$vplanschueleraktuell = '';
		$vplanschuelerfolgetag = '';
		$vplanlehreraktuell = '';
		$vplanlehrerfolgetag = '';
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$einstellungsname = "Vertretungsplan extern";
		$sql->bind_param("ss", $vplanextern, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Vertretungsplan Schüler aktuell";
		$sql->bind_param("ss", $vplanschueleraktuell, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Vertretungsplan Schüler Folgetag";
		$sql->bind_param("ss", $vplanschuelerfolgetag, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Vertretungsplan Lehrer aktuell";
		$sql->bind_param("ss", $vplanlehreraktuell, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Vertretungsplan Lehrer Folgetag";
		$sql->bind_param("ss", $vplanlehrerfolgetag, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Lehrer extern";
		$sql->bind_param("ss", $lehrerstundenplaene, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Raum extern";
		$sql->bind_param("ss", $raumstundenplaene, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Klassen extern";
		$sql->bind_param("ss", $klassenstundenplaene, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Buchungsbeginn Stunde";
		$sql->bind_param("ss", $buchungsbeginnS, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Buchungsbeginn Minute";
		$sql->bind_param("ss", $buchungsbeginnM, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Buchungsende Stunde";
		$sql->bind_param("ss", $buchungsendeS, $einstellungsname);
		$sql->execute();

		$einstellungsname = "Stundenplan Buchungsende Minute";
		$sql->bind_param("ss", $buchungsendeM, $einstellungsname);
		$sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE internedienste SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		$kennungsbez = "VPlanS";
	  $sql->bind_param("ss", $vplanskennung, $kennungsbez);
	  $sql->execute();
		$kennungsbez = "VPlanL";
	  $sql->bind_param("ss", $vplanlkennung, $kennungsbez);
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
