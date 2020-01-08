<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['benutzername'])) {$benutzername = $_POST['benutzername'];} else {echo "FEHLER";exit;}
if (isset($_POST['mail'])) {$mail = $_POST['mail'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}

$zugriff = false;
cms_rechte_laden();

if ($modus == "1") {
	$zugriff = cms_r("schulhof.verwaltung.personen.bearbeiten"));
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
}
else {
	$zugriff = true;
	$id = $CMS_BENUTZERID;
}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($benutzername) < 6) {$fehler = true;}
	if (!cms_check_mail($mail)) {$fehler = true;}

	$dbs = cms_verbinden('s');
	$benutzername = cms_texttrafo_e_db($benutzername);
	$mail = cms_texttrafo_e_db($mail);

	$anzahl = "-";
	$sql = $dbs->prepare("SELECT count(id) AS anzahl FROM nutzerkonten WHERE benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	$sql->bind_param("si", $benutzername, $id);

	if ($sql->execute()) {
	  $sql->bind_result($anzahl);
	  if ($sql->fetch()) {if ($anzahl != '0') {$fehler = true;}}
		else {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();


	if (!$fehler) {
		// PROFILDATEN UPDATEN
		$sql = $dbs->prepare("UPDATE nutzerkonten SET benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");

		$sql->bind_param("ssi", $benutzername, $mail, $id);
		$sql->execute();
		$sql->close();


		if ($id == $_SESSION['BENUTZERID']) {$_SESSION['BENUTZERNAME'] = $benutzername;}

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
