<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['pinnwand'])) {$pinnwand = $_POST['pinnwand'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (!cms_check_titel($pinnwand))          {echo "FEHLER"; exit;}

// Pinnwand laden
$dbs = cms_verbinden('s');


// Prüfen, ob der Anschlag zur ausführenden Person gehört
$zugehoerig = false;
if (cms_r("schulhof.information.pinnwände.anschläge.löschen")) {$zugehoerig = true;}
else {
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM pinnwandanschlag JOIN pinnwaende ON pinnwandanschlag.pinnwand = pinnwaende.id WHERE pinnwandanschlag.id = ? AND pinnwaende.bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND pinnwandanschlag.idvon = ?");
	$sql->bind_param("isi", $id, $pinnwand, $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {if ($anzahl == 1) {$zugehoerig = true;}}
	}
	$sql->close();
}

$zugriff = cms_r("schulhof.information.pinnwände.anschläge.löschen") || $zugehoerig;

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (!$fehler) {
		$sql = $dbs->prepare("DELETE FROM pinnwandanschlag WHERE id = ?");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
