<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['zweit'])) {$zweit = $_POST['zweit'];} else {echo "FEHLER"; exit;}
if (isset($_POST['dritt'])) {$dritt = $_POST['dritt'];} else {echo "FEHLER"; exit;}
if (isset($_POST['viert'])) {$viert = $_POST['viert'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['PERSONENDETAILS'])) {$shid = $_SESSION['PERSONENDETAILS'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($shid,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($zweit,0) && ($zweit != '')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($dritt,0) && ($dritt != '')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($viert,0) && ($viert != '')) {echo "FEHLER"; exit;}

$zugriff = false;
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Personenids bearbeiten'];


if (cms_angemeldet() && $zugriff) {

	// PROFILDATEN UPDATEN
	$dbs = cms_verbinden('s');

	if (strlen($zweit) > 0) {
		$sql = $dbs->prepare("UPDATE personen SET zweitid = ? WHERE id = ?;");
	  $sql->bind_param("ii", $zweit, $shid);
	}
	else {
		$sql = $dbs->prepare("UPDATE personen SET zweitid = NULL WHERE id = ?;");
	  $sql->bind_param("i", $shid);
	}
  $sql->execute();
  $sql->close();

	if (strlen($dritt) > 0) {
		$sql = $dbs->prepare("UPDATE personen SET drittid = ? WHERE id = ?;");
	  $sql->bind_param("ii", $dritt, $shid);
	}
	else {
		$sql = $dbs->prepare("UPDATE personen SET drittid = NULL WHERE id = ?;");
	  $sql->bind_param("i", $shid);
	}
  $sql->execute();
  $sql->close();

	if (strlen($viert) > 0) {
		$sql = $dbs->prepare("UPDATE personen SET viertid = ? WHERE id = ?;");
	  $sql->bind_param("ii", $viert, $shid);
	}
	else {
		$sql = $dbs->prepare("UPDATE personen SET viertid = NULL WHERE id = ?;");
	  $sql->bind_param("i", $shid);
	}
  $sql->execute();
  $sql->close();

	cms_trennen($dbs);

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
