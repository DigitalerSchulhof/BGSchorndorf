<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER";exit;}
$gruppek = strtolower($gruppe);

$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();

if (isset($_SESSION['IMLN'])) {$CMS_IMLN = $_SESSION['IMLN'];} else {$CMS_IMLN = false;}

if ($CMS_IMLN) {

	$angemeldet = cms_angemeldet();
	$CMS_RECHTE = cms_rechte_laden();
	$zugriff = $CMS_RECHTE['Organisation'][$gruppe.' löschen'];

	if ($angemeldet && $zugriff) {
		$fehler = false;

		if (!$fehler) {
			$dbs = cms_verbinden('s');
			// Gremium löschen
			$sql = "DELETE FROM $gruppek WHERE id = $id";
			$anfrage = $dbs->query($sql);

			// Mitglieder löschen
			$sql = "DELETE FROM mitgliedschaften WHERE gruppenid = $id AND gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL')";
			$anfrage = $dbs->query($sql);

			// Aufsichten löschen
			$sql = "DELETE FROM aufsichten WHERE gruppenid = $id AND gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL')";
			$anfrage = $dbs->query($sql);

			// Termine löschen
			$sql = "DELETE FROM termine WHERE gruppenid = $id AND gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL')";
			$anfrage = $dbs->query($sql);

			// Blogeintragdownloads löschen
			$sql = "DELETE FROM blogeintragdownloads WHERE blogeintrag IN (SELECT id AS blogeintrag FROM blogeintraege WHERE gruppenid = $id AND gruppe = '$gruppe')";
			$anfrage = $dbs->query($sql);

			// Blogeinträge löschen
			$sql = "DELETE FROM blogeintraege WHERE gruppenid = $id AND gruppe = '$gruppe'";
			$anfrage = $dbs->query($sql);
			cms_trennen($dbs);

			$pfad = '../../../dateien/schulhof/'.$gruppek.'/';
			if ((strlen($id) > 0) && (is_dir($pfad.$id))) {
				chmod($pfad, 0777);
				cms_dateisystem_ordner_loeschen($pfad.$id);
				chmod($pfad, 0775);
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
}
else {echo "FIREWALL";}
?>
