<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {
	$fehler = false;
	if (!cms_check_ganzzahl($CMS_BENUTZERID)) {$fehler = true;}
	if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {$fehler = true;}

	if (!$fehler) {

		$_SESSION["POSTLESENID"] = $id;
		$_SESSION["POSTLESENMODUS"] = $modus;

		if ($modus == "eingang") {
			$db = cms_verbinden('p');
			$sql = $db->prepare("UPDATE posteingang_$CMS_BENUTZERID SET gelesen = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') WHERE id = ?");
			$sql->bind_param("i", $id);
			$sql->execute();
			$sql->close();
			cms_trennen($db);
		}

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
