<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) {$titel = $_POST['titel'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorname'])) {$vorname = $_POST['vorname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nachname'])) {$nachname = $_POST['nachname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['geschlecht'])) {$geschlecht = $_POST['geschlecht'];} else {echo "FEHLER"; exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER"; exit;}
if (!cms_check_toggle($modus)) {echo "FEHLER"; exit;}

$zugriff = false;
cms_rechte_laden();

if ($modus == "1") {
	$zugriff = cms_r("schulhof.verwaltung.personen.bearbeiten");
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
}
else {
	$zugriff = true;
	$id = $_SESSION['BENUTZERID'];
}


if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if ($id."" == "-") {$fehler = true;}

	// Pflichteingaben prüfen
	if (strlen($vorname) == 0) {$fehler = true;}
	if (strlen($nachname) == 0) {$fehler = true;}
	if (!cms_check_name($vorname)) {$fehler = true;}
	if (!cms_check_name($nachname)) {$fehler = true;}
	if (!cms_check_nametitel($titel)) {$fehler = true;}
	if (($geschlecht != 'w') && ($geschlecht != "m") && ($geschlecht != "u")) {
		$fehler = true;
	}

	if (!$fehler) {
		// PROFILDATEN UPDATEN
		$dbs = cms_verbinden('s');

		if ($id == $_SESSION['BENUTZERID']) {
			$_SESSION['BENUTZERTITEL'] = $titel;
			$_SESSION['BENUTZERVORNAME'] = $vorname;
			$_SESSION['BENUTZERNACHNAME'] = $nachname;
		}

		$vorname = cms_texttrafo_e_db($vorname);
		$nachname = cms_texttrafo_e_db($nachname);

		$sql = $dbs->prepare("UPDATE personen SET titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), geschlecht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
	  $sql->bind_param("ssssi", $titel, $vorname, $nachname, $geschlecht, $id);
	  $sql->execute();
	  $sql->close();

		cms_trennen($dbs);

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
