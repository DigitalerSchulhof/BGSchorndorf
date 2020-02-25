<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

if (isset($_POST['datei'])) {$datei = $_POST['datei'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
  $fehler = false;
	if (is_file("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp/".$datei)) {
    $fehler = !unlink("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp/".$datei);
  }

	if (!$fehler) {
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
