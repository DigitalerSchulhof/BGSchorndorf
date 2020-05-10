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
if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {$pfad = '';}
if (isset($_POST['namealt'])) {$namealt = $_POST['namealt'];} else {$namealt = '';}
if (isset($_POST['nameneu'])) {$nameneu = $_POST['nameneu'];} else {$nameneu = '';}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$CMS_BENUTZERID = $_SESSION['BENUTZERID'];

$dbs = cms_verbinden('s');

$notifikation = false;
$zugriff = false;
$fehler = false;
$existiert = false;

$pfadteile = explode('/', $pfad);
if ($bereich == "website") {
	$gruppenrechte = cms_websitedateirechte_laden();
	if ($pfad[0] == "website") {$fehler = true;}
}
else if ($bereich == "titelbilder") {
	$gruppenrechte = cms_titelbilderdateirechte_laden();
	if ($pfad[0] == "titelbilder") {$fehler = true;}
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
else {$fehler = true;}

$zugriff = $gruppenrechte['dateiumbenennen'];


if (cms_angemeldet() && $zugriff) {

	if (strlen($namealt) < 1) {
		$fehler = true;
	}

	if ($namealt == $nameneu) {
		$fehler = true;
	}

	if (preg_match("/^([a-zA-Z0-9_-])*.([a-zA-Z0-9])+$/", $nameneu) < 1) {
		$fehler = true;
	}

	$dbs = cms_verbinden('s');
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
	cms_trennen($dbs);

	//Neue Endung
	$neueteile = explode('.', $nameneu);
	$neueendung = $neueteile[count($neueteile)-1];
	if (!in_array($neueendung, $erlaubteendungen)) {
		$fehler = true;
		echo "ENDUNG";
	}

	if (!$fehler) {
		$pfad = "../../../dateien/".$pfad;
		if (is_file($pfad."/".$namealt)) {
			if (file_exists($pfad."/".$nameneu)) {
				$existiert = true;
			}
			else {
				chmod($pfad."/".$namealt, 0777);
				$fehler = !rename($pfad."/".$namealt, $pfad."/".$nameneu);
				chmod($pfad."/".$nameneu, 0775);
			}
		}
		else {
			$fehler = true;
		}
	}

	if (!$fehler) {
		if ($existiert) {
			echo "EXISTIERT";
		}
		else {
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
				$eintrag['status']    = "b";
				$eintrag['art']       = "d";
				$eintrag['titel']     = $nameneu;
				$eintrag['vorschau']  = "$anzeigepfad/$namealt -&gt; $anzeigepfad/$nameneu";
				$eintrag['link']      = "Schulhof/Gruppen/$gruppensj/".cms_textzulink($g)."/$gruppenbez";

				$CMS_WICHTIG = cms_einstellungen_laden('wichtigeeinstellungen');
		    $CMS_MAIL = cms_einstellungen_laden('maileinstellungen');

				cms_notifikation_senden($dbs, $eintrag, $CMS_BENUTZERID);
			}

			echo "ERFOLG";
		}
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
