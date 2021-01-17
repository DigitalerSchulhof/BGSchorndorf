<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}

if (cms_angemeldet()) {
  $dbs = cms_verbinden('s');
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
  	$sql = "DELETE FROM {$gk}todoartikel WHERE person = ?;";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $CMS_BENUTZERID);
		$sql->execute();
		$sql->close();
	}

	$sql = "DELETE FROM todo WHERE person = ?;";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $CMS_BENUTZERID);
	$sql->execute();
	$sql->close();	

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
