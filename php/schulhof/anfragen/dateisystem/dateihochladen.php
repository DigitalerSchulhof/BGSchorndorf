<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';

session_start();

if (isset($_POST['bereich'])) {$bereich = $_POST['bereich'];} else {$bereich = '';}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['urheberrecht'])) {$urheberrecht = $_POST['urheberrecht'];} else {$urheberrecht = '';}
if (isset($_POST['skalieren'])) {$skalieren = $_POST['skalieren'];} else {$skalieren = '';}
if (isset($_POST['skalierengroesse'])) {$skalierengroesse = $_POST['skalierengroesse'];} else {$skalierengroesse = '';}
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {$pfad = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$CMS_BENUTZERID = $_SESSION['BENUTZERID'];
$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

$dbs = cms_verbinden('s');

$notifikation = false;
$zugriff = false;
$fehler = false;
$skaliert = false;

$pfadteile = explode('/', $pfad);
if ($bereich == "website") {
	$gruppenrechte = cms_websitedateirechte_laden();
	if ($pfadteile[0] != "website") {$fehler = true;}
}
else if ($bereich == "titelbilder") {
	$gruppenrechte = cms_titelbilderdateirechte_laden();
	if ($pfadteile[0] != "titelbilder") {$fehler = true;}
}
else if ($bereich == "schulhof") {
	// Gruppe ermitteln
	if (count($pfadteile) < 4) {$fehler = true;}
	else {
		$gruppe = strtoupper(substr($pfadteile[2],0,1)).substr($pfadteile[2],1);
		if ($gruppe == "Sonstigegruppen") {$gruppe = "Sonstige Gruppen";}
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $id);
		if ($pfadteile[3] != $id) {$fehler = true;}
		$notifikation = true;
	}
}
else if ($bereich == "anhang") {
	// Gruppe ermitteln
	if (count($pfadteile) < 5) {$fehler = true;}
	else {
		if (($pfadteile[0] == "schulhof") && ($pfadteile[1] == "personen") && ($pfadteile[2] == $CMS_BENUTZERID) && ($pfadteile[3] == 'postfach') && ($pfadteile[4] == 'temp')) {
			$gruppenrechte['dateiupload'] = true;
		}
		else {$gruppenrechte['dateiupload'] = false;}
	}
}
else {$fehler = true; $gruppenrechte['dateiupload'] = false;}

$zugriff = $gruppenrechte['dateiupload'];

if (cms_angemeldet() && $zugriff) {

	$fehlercode = "";

	if ($urheberrecht != 1) {
		$fehler = true;
	}

	if (($skalieren != 1) && ($skalieren != 0)) {$fehler = true;}
	if ($skalieren == 1) {
		if (!cms_check_ganzzahl($skalierengroesse)) {$fehler = true;}
		else if ($skalierengroesse < 1) {$fehler = true;}
	};

	if (!isset($_FILES['datei'])) {$fehler = true;}
	else {
		$dateiname = cms_dateiname_erzeugen($_FILES['datei']['name']);
		$groesse = $_FILES['datei']['size'];
		$temppfad = $_FILES['datei']['tmp_name'];
		$endung = explode('.', $dateiname);
		$endung = end($endung);
		$endung = strtolower($endung);
	}

	// Falls bisher alles passt, überprüfe die Datei
	if (!$fehler) {

		// Dateiendungen laden und prüfen
		$erlaubteendungen = array();
		$sql = $dbs->prepare("SELECT AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung FROM zulaessigedateien WHERE zulaessig = AES_ENCRYPT('1', '$CMS_SCHLUESSEL');");
		if ($sql->execute()) {
			$sql->bind_result($endung);
			while ($sql->fetch()) {
				array_push($erlaubteendungen, $endung);
			}
		}
		else {$fehler = false;}
		$sql->close();

		if (!in_array($endung, $erlaubteendungen)) {$fehlercode .= "ENDUNG"; $fehler = true;}

		// Prüfen, ob die neue Datei existiert
		if (is_file("../../../dateien/".$pfad."/".$dateiname)) {$fehlercode .= "DOPPELT"; $fehler = true;}

		// Prüfen, ob der Dateiname nur erlaubte Zeichen enthält
		if (!cms_check_dateiname($dateiname)) {$fehlercode .= "ZEICHEN"; $fehler = true;}

		if ($groesse > $CMS_EINSTELLUNGEN['Maximale Dateigröße']) {$fehlercode .= "GROESSE"; $fehler = true;}

		// UPLOAD DURCHFÜHREN
		if (!$fehler) {
			$pfad = "../../../dateien/".$pfad;
			if (is_dir($pfad)) {
				chmod($pfad, 0777);
				$istbild = false;
				if (getimagesize($temppfad)) {$istbild = true;}
				if (($skalieren == 1) && ($istbild)) {
					$skaliert = cms_bild_skalieren ($temppfad, $pfad."/".$dateiname, $skalierengroesse);
					if (!$skaliert) {$fehler = !move_uploaded_file($temppfad, $pfad."/".$dateiname);}
				}
				else {
					$skaliert = true;
					$fehler = !move_uploaded_file($temppfad, $pfad."/".$dateiname);
				}

				if (($pfadteile[0] != 'titelbilder') && ($pfadteile[0] != 'website') && !$fehler) {
					cms_dateisystem_datei_verschluesseln($pfad."/".$dateiname);
					cms_dateisystem_datei_verschluesseln_aufraeumen($pfad."/".$dateiname);
				}
				chmod($pfad, 0775);
			}
			else {
				$fehler = true;
			}
		}

	}
	if (!$fehler) {
		if($notifikation) {
			$g = $gruppe;
			$gid = $id;
			$gk = cms_textzudb($g);
			$sql = $dbs->prepare("SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') as grbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = ?");
			$sql->bind_param("i", $gid);
			$gruppensj = "Schuljahrübergreifend";
			$gruppenbez = "";
			if ($sql->execute()) {
				$sql->bind_result($sjbez, $grbez);
				if ($sql->fetch()) {
					if (!is_null($sjbez)) {$gruppensj = cms_textzulink($sjbez);}
					$gruppenbez = cms_textzulink($grbez);
				}
				else {$fehler = true;}
			}
			else {$fehler = true;}
			$sql->close();

			$anzeigepfad = $pfad;
			if(substr_count($anzeigepfad, "/") < 8) {
				$anzeigepfad = "";
			} else {
				$anzeigepfad = substr($pfad, strposX($anzeigepfad, "/", 8));
			}

			$eintrag = array();
			$eintrag['gruppe']    = $g;
			$eintrag['gruppenid'] = $gid;
			$eintrag['zielid']    = null;
			$eintrag['status']    = "n";
			$eintrag['art']       = "d";
			$eintrag['titel']     = $dateiname;
			$eintrag['vorschau']  = "$anzeigepfad/$dateiname";
			$eintrag['link']      = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($g)."/$gruppenbez";

			$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
	    $CMS_MAIL = cms_einstellungen_laden('maileinstellungen');

			cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
		}
		if (($skalieren == 1) && (!$skaliert)) {echo "UNSKALIERT";}
		else {echo "ERFOLG";}
	}
	else {
		if (strlen($fehlercode) == 0) {$fehlercode = "FEHLER";}
		echo $fehlercode;
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
