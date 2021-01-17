<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($id,0) && ($id !== '-')) {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$_SESSION['STUNDENPLANUNGKURSE'] = $id;

	$dbs = cms_verbinden('s');
	// Lehrer laden
	$sql = $dbs->prepare("SELECT p.id FROM kursemitglieder as k JOIN personen as p ON k.person = p.id WHERE p.art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL') AND k.gruppe = ?");
	$sql->bind_param("i", $id);
	$lid = null;
	$sql->bind_result($lid);
	$sql->execute();
	$sql->fetch();
	if($lid != null) {
		$_SESSION['STUNDENPLANUNGLEHRER'] = $lid;
	}
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
