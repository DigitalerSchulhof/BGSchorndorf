<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bedarf'])) {$bedarf = $_POST['bedarf'];} else {echo "FEHLER"; exit;}
if (isset($_POST['preis'])) {$preis = $_POST['preis'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon1'])) {$telefon1 = $_POST['telefon1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['telefon2'])) {$telefon2 = $_POST['telefon2'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail1'])) {$mail1 = $_POST['mail1'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mail2'])) {$mail2 = $_POST['mail2'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
if (($bedarf != '0') && ($bedarf != '1') && ($bedarf != '2')) {echo "FEHLER"; exit;}
if (($bedarf == '1') || ($bedarf == '2')) {
	if (strlen($telefon1) < 4) {echo "FEHLER"; exit;}
	if ($telefon1 != $telefon2) {echo "FEHLER"; exit;}
	if (!cms_check_mail($mail1)) {echo "FEHLER"; exit;}
	if ($mail1 != $mail2) {echo "FEHLER"; exit;}
}
if (($bedarf == '1')) {
	if (!cms_check_ganzzahl($preis,1)) {echo "FEHLER"; exit;}
}

$dbs = cms_verbinden('s');
$fehler = false;
$sql = $dbs->prepare("SELECT COUNT(*) FROM ebedarf WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
	if ($sql->fetch()) {
		if ($anzahl != 0) {$fehler = true;}
	}
	else {$fehler = true;}
}
$sql->close();
$sql = $dbs->prepare("SELECT COUNT(*) FROM nutzerkonten WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($anzahl);
	if ($sql->fetch()) {
		if ($anzahl != 1) {$fehler = true;}
	}
	else {$fehler = true;}
}
$sql->close();


if (!$fehler) {
	// PROFILDATEN UPDATEN
	$jetzt = time();
	if (($bedarf == '1') || ($bedarf == '2')) {
		if ($bedarf == '2') {$preis = 0;}
		$sql = $dbs->prepare("INSERT INTO ebedarf (id, bedarf, betrag, telefon, emailadresse, eingegangen) VALUES (?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?)");
		$sql->bind_param("iisssi", $id, $bedarf, $preis, $telefon1, $mail1, $jetzt);
	}
	else {
		$sql = $dbs->prepare("INSERT INTO ebedarf (id, bedarf, betrag, telefon, emailadresse, eingegangen) VALUES (?, ?, NULL, NULL, NULL, ?)");
		$sql->bind_param("iii", $id, $bedarf, $jetzt);
	}
	$sql->execute();

	$sql->close();

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
