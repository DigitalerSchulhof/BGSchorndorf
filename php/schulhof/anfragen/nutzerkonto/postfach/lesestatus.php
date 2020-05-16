<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['gelesen'])) {$gelesen = $_POST['gelesen'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_POST["id"])) {$id = $_POST["id"];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$fehler = false;


	$dbp = cms_verbinden('p');
	// Prüfen, ob die angegebene Nachricht dem angemeldeten Benutzer gehört
	$sql = $dbp->prepare("SELECT COUNT(*) as anzahl FROM posteingang_$CMS_BENUTZERID WHERE id = ?");
	$sql->bind_param("i", $id);
	$anzahl = 0;
	if ($sql->execute()) {
	  $sql->bind_result($anzahl);
	  if (!$sql->fetch()) {
	    $fehler = true;
	  }
	}
	else {$fehler = true;}
	if ($anzahl != 1) {$fehler = true;}
	$sql->close();

	if (!$fehler) {
		$sql = $dbp->prepare("UPDATE posteingang_$CMS_BENUTZERID SET gelesen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		if($gelesen == "0") {
			$gelesen = "-";
		}
		$sql->bind_param("si", $gelesen, $id);
		$sql->execute();
		$sql->close();
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbp);
}
else {
	echo "BERECHTIGUNG";
}
?>
