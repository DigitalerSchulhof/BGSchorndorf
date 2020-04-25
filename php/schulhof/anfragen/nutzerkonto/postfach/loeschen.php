<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (cms_angemeldet()) {
	$fehler = false;
	if (!cms_check_ganzzahl($CMS_BENUTZERID)) {$fehler = true;}
	if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {$fehler = true;}

	if (!$fehler) {
		$dbp = cms_verbinden('p');
		$tabelle = "post$modus"."_".$CMS_BENUTZERID;

		$sql = $dbp->prepare("DELETE FROM $tabelle WHERE id = ?;");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$id)) {cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$id);}

		cms_trennen($dbp);
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
