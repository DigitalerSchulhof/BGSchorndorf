<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();
if (isset($_POST['id'])) {$id = cms_texttrafo_e_db($_POST['id']);} else {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($id,0)) && ($id != 'alle')) {echo "FEHLER";exit;}



if (cms_angemeldet() && cms_r("schulhof.organisation.schulanmeldung.akzeptieren")) {
	$_SESSION['ANMELDUNG BEARBEITEN'] = $id;
	$_SESSION['DRUCKANSICHT'] = 'Schulanmeldung';
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
