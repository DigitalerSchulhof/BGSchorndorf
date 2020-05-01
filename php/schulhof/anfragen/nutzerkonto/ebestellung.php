<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bedarf'])) {$bedarf = $_POST['bedarf'];} else {echo "FEHLER1"; exit;}
if (isset($_POST['bestellnr'])) {$bestellnr = $_POST['bestellnr'];} else {echo "FEHLER2"; exit;}
if (isset($_POST['anz_leihe'])) {$anz_leihe = $_POST['anz_leihe'];} else {echo "FEHLER3"; exit;}
if (isset($_POST['anz_lapu'])) {$anz_lapu = $_POST['anz_lapu'];} else {echo "FEHLER4"; exit;}
if (isset($_POST['anz_lapw'])) {$anz_lapw = $_POST['anz_lapw'];} else {echo "FEHLER5"; exit;}
if (isset($_POST['anz_kombim'])) {$anz_kombim = $_POST['anz_kombim'];} else {echo "FEHLER6"; exit;}
if (isset($_POST['anz_kombig'])) {$anz_kombig = $_POST['anz_kombig'];} else {echo "FEHLER6"; exit;}
if (isset($_POST['anrede'])) {$anrede = $_POST['anrede'];} else {echo "FEHLER7"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER8"; exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER9"; exit;}
if (isset($_POST['hausnr'])) {$hausnr = $_POST['hausnr'];} else {echo "FEHLER10"; exit;}
if (isset($_POST['strasse'])) {$strasse = $_POST['strasse'];} else {echo "FEHLER10"; exit;}
if (isset($_POST['plz'])) {$plz = $_POST['plz'];} else {echo "FEHLER11"; exit;}
if (isset($_POST['ort'])) {$ort = $_POST['ort'];} else {echo "FEHLER12"; exit;}
if (isset($_POST['bedingungen'])) {$bedingungen = $_POST['bedingungen'];} else {echo "FEHLER13"; exit;}
if (isset($_POST['telefon1'])) {$telefon1 = $_POST['telefon1'];} else {echo "FEHLER14"; exit;}
if (isset($_POST['telefon2'])) {$telefon2 = $_POST['telefon2'];} else {echo "FEHLER15"; exit;}
if (isset($_POST['mail1'])) {$mail1 = $_POST['mail1'];} else {echo "FEHLER16"; exit;}
if (isset($_POST['mail2'])) {$mail2 = $_POST['mail2'];} else {echo "FEHLER17"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER18";exit;}
if (!cms_check_ganzzahl($id)) {echo "FEHLER19"; exit;}
if (($bedarf != '0') && ($bedarf != '1') && ($bedarf != '2')) {echo "FEHLER20"; exit;}
if (!cms_check_ganzzahl($bestellnr,0,9999999999)) {echo "FEHLER21"; exit;}
if (($bedarf == '1') || ($bedarf == '2')) {
  if (($anrede != '-') && ($anrede != 'Frau') && ($anrede != 'Herr')) {echo "FEHLER22"; exit;}
  if (strlen($vorname) < 1) {echo "FEHLER23"; exit;}
  if (strlen($nachname) < 1) {echo "FEHLER24"; exit;}
  if (strlen($strasse) < 1) {echo "FEHLER25"; exit;}
  if (strlen($hausnr) < 1) {echo "FEHLER26"; exit;}
  if (!cms_check_ganzzahl($plz, 0, 99999)) {echo "FEHLER27"; exit;}
  if (strlen($ort) < 1) {echo "FEHLER28"; exit;}

	if (strlen($telefon1) < 4) {echo "FEHLER29"; exit;}
	if ($telefon1 != $telefon2) {echo "FEHLER30"; exit;}
	if (!cms_check_mail($mail1)) {echo "FEHLER31"; exit;}
	if ($mail1 != $mail2) {echo "FEHLER"; exit;}
}
if (($bedarf == '1')) {
	if (!cms_check_ganzzahl($anz_lapu,0,5)) {echo "FEHLER32"; exit;}
	if (!cms_check_ganzzahl($anz_lapw,0,5)) {echo "FEHLER33"; exit;}
	if (!cms_check_ganzzahl($anz_kombim,0,5)) {echo "FEHLER34"; exit;}
	if (!cms_check_ganzzahl($anz_kombig,0,5)) {echo "FEHLER35"; exit;}
	if ($bedingungen != 1) {echo "FEHLER36"; exit;}
}
if (($bedarf == '2')) {
	if (!cms_check_ganzzahl($anz_leihe,1,1)) {echo "FEHLER37"; exit;}
}

$dbs = cms_verbinden('s');
$fehler = false;
$sql = $dbs->prepare("DELETE FROM ebestellung WHERE id = ?");
$sql->bind_param("i", $id);
$sql->execute();
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
	// INSERT
	$jetzt = time();
	if ($bedarf == '1') {
		$sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, leihe, laptopubuntu, laptopwindows, kombimittel, kombigut, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, bestellnr, eingegangen) VALUES (?, ?, NULL, ?, ?, ?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?)");
		$sql->bind_param("iiiiiisssssssssisi", $id, $bedarf, $anz_lapu, $anz_lapw, $anz_kombim, $anz_kombig, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort, $telefon1, $mail1, $bedingungen, $bestellnr, $jetzt);
	}
	else if ($bedarf == '2') {
		$sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, leihe, laptopubuntu, laptopwindows, kombimittel, kombigut, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, bestellnr, eingegangen) VALUES (?, ?, ?, NULL, NULL, NULL, NULL, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), NULL, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?)");
		$sql->bind_param("iiissssssssssi", $id, $bedarf, $anz_leihe, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort, $telefon1, $mail1, $bestellnr, $jetzt);
	}
	else {
		$sql = $dbs->prepare("INSERT INTO ebestellung (id, bedarf, leihe, laptopubuntu, laptopwindows, kombimittel, kombigut, anrede, vorname, nachname, strasse, hausnr, plz, ort, telefon, email, bedingungen, bestellnr, eingegangen) VALUES (?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?)");
		$sql->bind_param("iisi", $id, $bedarf, $bestellnr, $jetzt);
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
