<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

if (isset($_SESSION['SESSIONAKTIVITAET'])) {$CMS_SESSIONAKTIVITAET = $_SESSION['SESSIONAKTIVITAET'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (cms_angemeldet()) {
	$fehler = false;

	$dbs = cms_verbinden();
	$sql = "SELECT sessiontimeout FROM nutzerkonten WHERE id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($sessiont);
		if ($sql->fetch()) {
			echo $sessiont;
		} else {$fehler = true;}
	} else {$fehler = true;}
	$sql->close();
	cms_trennen($dbs);

	if ($fehler) {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
