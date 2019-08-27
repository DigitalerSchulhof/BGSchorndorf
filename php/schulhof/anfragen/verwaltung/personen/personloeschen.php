<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/anfragen/verwaltung/personen/personloeschenfkt.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id, 0)) {echo "FEHLER";exit;}


$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Personen löschen'];

if (cms_angemeldet() && $zugriff) {
	$dbs = cms_verbinden('s');
	$dbp = cms_verbinden('p');
	echo cms_verwaltung_personloeschen ($dbs, $dbp, $id);
	cms_trennen($dbp);
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
