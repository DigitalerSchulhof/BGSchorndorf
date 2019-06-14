<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['diebstahl'])) {$diebstahl = $_POST['diebstahl'];} else {echo "FEHLER";exit;}
if (isset($_POST['passwortalt'])) {$passwortalt = $_POST['passwortalt'];} else {echo "FEHLER";exit;}
if (isset($_POST['passwort1'])) {$passwort1 = $_POST['passwort1'];} else {echo "FEHLER";exit;}
if (isset($_POST['passwort2'])) {$passwort2 = $_POST['passwort2'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}


if (cms_angemeldet()) {
	$fehler = false;

	// Pflichteingaben prüfen
	if ($diebstahl != '1') {$fehler = true;}
	if (strlen($passwortalt) < 1) {$fehler = true;}
	if (strlen($passwort1) < 1) {$fehler = true;}
	if ($passwort1 != $passwort2) {$fehler = true;}

	$dbs = cms_verbinden('s');

	// SALT holen
	$sql = $dbs->prepare("SELECT AES_DECRYPT(salt, '$CMS_SCHLUESSEL') AS salt FROM nutzerkonten WHERE id = ?");
	$sql->bind_param("i", $id);
	if ($sql->execute()) {
	  $sql->bind_result($salt);
	  if (!$sql->fetch()) {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

	if (!$fehler) {
		$passwortaltsalted = $passwortalt.$salt;
		$passwortsalted = $passwort1.$salt;
		$passwortaltsalted = cms_texttrafo_e_db($passwortaltsalted);

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM nutzerkonten WHERE id = ? AND passwort = SHA1(?);");
		$sql->bind_param("is", $id, $passwortaltsalted);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		// PROFILDATEN UPDATEN
		$passwortsalted = cms_texttrafo_e_db($passwortsalted);
		$sql = $dbs->prepare("UPDATE nutzerkonten SET passwort = SHA1(?), passworttimeout = 0 WHERE id = ?;");
		$sql->bind_param("si", $passwortsalted, $id);
		$sql->execute();
		$sql->close();

		$jetzt = time();
		$sql = $dbs->prepare("INSERT INTO identitaetsdiebstahl (id, zeit) VALUES (?, ?)");
		$sql->bind_param("ii", $id, $jetzt);
		$sql->execute();
		$sql->close();

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
