<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['postmail'])) {$postmail = $_POST['postmail'];} else {echo "FEHLER";exit;}
if (isset($_POST['postalletage'])) {$postalletage = $_POST['postalletage'];} else {echo "FEHLER";exit;}
if (isset($_POST['postpapierkorbtage'])) {$postpapierkorbtage = $_POST['postpapierkorbtage'];} else {echo "FEHLER";exit;}
if (isset($_POST['notifikationsmail'])) {$notifikationsmail = $_POST['notifikationsmail'];} else {echo "FEHLER";exit;}
if (isset($_POST['vertretungsmail'])) {$vertretungsmail = $_POST['vertretungsmail'];} else {echo "FEHLER";exit;}
if (isset($_POST['uebersichtsanzahl'])) {$uebersichtsanzahl = $_POST['uebersichtsanzahl'];} else {echo "FEHLER";exit;}
if (isset($_POST['inaktivitaetszeit'])) {$inaktivitaetszeit = $_POST['inaktivitaetszeit'];} else {echo "FEHLER";exit;}
if (isset($_POST['terminoeffentlich'])) {$terminoeffentlich = $_POST['terminoeffentlich'];} else {echo "FEHLER";exit;}
if (isset($_POST['blogoeffentlich'])) {$blogoeffentlich = $_POST['blogoeffentlich'];} else {echo "FEHLER";exit;}
if (isset($_POST['galerieoeffentlich'])) {$galerieoeffentlich = $_POST['galerieoeffentlich'];} else {echo "FEHLER";exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (($modus != '0') && ($modus != '1')) {echo "FEHLER"; exit;}

$zugriff = false;
$CMS_RECHTE = cms_rechte_laden();

if ($modus == "1") {
	$zugriff = $CMS_RECHTE['Personen']['Persönliche Einstellungen ändern'];
	if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
	if (!cms_check_ganzzahl($id)) {echo "FEHLER"; exit;}
}
else {
	$zugriff = true;
	$id = $CMS_BENUTZERID;
}

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if (!cms_check_toggle($postmail)) {$fehler = true;}
	if (!cms_check_toggle($notifikationsmail)) {$fehler = true;}
	if (!cms_check_toggle($vertretungsmail)) {$fehler = true;}
	if (!cms_check_toggle($terminoeffentlich)) {$fehler = true;}
	if (!cms_check_toggle($blogoeffentlich)) {$fehler = true;}
	if (!cms_check_toggle($galerieoeffentlich)) {$fehler = true;}
	if (!cms_check_ganzzahl($postalletage,1,1000)) {$fehler = true;}
	if (!cms_check_ganzzahl($postpapierkorbtage,0,100)) {$fehler = true;}
	if (!cms_check_ganzzahl($uebersichtsanzahl,1,25)) {$fehler = true;}
	if (!cms_check_ganzzahl($inaktivitaetszeit,1,300)) {$fehler = true;}

	if (!$fehler) {
		if ($postalletage < 1) {$fehler = true;}
		if ($postpapierkorbtage < 1) {$fehler = true;}
		if (($uebersichtsanzahl < 1) || ($uebersichtsanzahl > 20)) {$fehler = true;}
		if ($inaktivitaetszeit < 5) {$fehler = true;}
	}


	if (!$fehler) {
		// Tage runden
		$postalletage = floor($postalletage);
		$postpapierkorbtage = floor($postpapierkorbtage);
		$uebersichtsanzahl = floor($uebersichtsanzahl);
		$inaktivitaetszeit = floor($inaktivitaetszeit);

		// PROFILDATEN UPDATEN
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("UPDATE personen_einstellungen SET postmail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), postalletage = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), postpapierkorbtage = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), notifikationsmail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vertretungsmail = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), uebersichtsanzahl = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), inaktivitaetszeit = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), oeffentlichertermin = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), oeffentlicherblog = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), oeffentlichegalerie = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE person = $id");
		$sql->bind_param("ssssssssss", $postmail, $postalletage, $postpapierkorbtage, $notifikationsmail, $vertretungsmail, $uebersichtsanzahl, $inaktivitaetszeit, $terminoeffentlich, $blogoeffentlich, $galerieoeffentlich);
		$sql->execute();
		$sql->close();
		cms_trennen($dbs);

		if ($modus != 1) {
			$_SESSION['BENUTZERUEBERSICHTANZAHL'] = $uebersichtsanzahl;
			$_SESSION['SESSIONAKTIVITAET'] = $inaktivitaetszeit;
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
