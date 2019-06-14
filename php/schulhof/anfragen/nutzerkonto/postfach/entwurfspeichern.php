<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../allgemein/funktionen/mail.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['empfaenger'])) {$empfaenger = $_POST['empfaenger'];} else {echo "FEHLER";exit;}
if (isset($_POST['betreff'])) {$betreff = $_POST['betreff'];} else {echo "FEHLER";exit;}
if (isset($_POST['nachricht'])) {$nachricht = $_POST['nachricht'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {
	$fehler = false;

	if (!cms_check_ganzzahl($CMS_BENUTZERID)) {$fehler = true;}

	if (!$fehler) {
		$id = cms_generiere_kleinste_id("postentwurf_$CMS_BENUTZERID", 'p');
		$dbp = cms_verbinden('p');
		$jetzt = time();
		$nachricht = cms_texttrafo_e_db($nachricht);
		$betreff = cms_texttrafo_e_db($betreff);
		$sql = $dbp->prepare("UPDATE postentwurf_$CMS_BENUTZERID SET absender = ?, empfaenger = ?, zeit = ?, betreff = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachricht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') WHERE id = ?;");
	  $sql->bind_param("isissi", $CMS_BENUTZERID, $empfaenger,$jetzt,$betreff,$nachricht,$id);
	  $sql->execute();
	  $sql->close();

		// Anhänge kopieren
		if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$id)) {
			cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$id);
		}
		mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$id, 0775);
		cms_dateisystem_ordner_kopieren("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", "../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/entwuerfe/".$id);

		// temp-Ordner leeren
		cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp");
		mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", 0775);

		cms_trennen($dbp);

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
