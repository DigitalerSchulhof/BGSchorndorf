<?php
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

if (isset($_POST['pfad'])) {$pfad = $_POST['pfad'];} else {echo "FEHLER"; exit;}

if (!cms_check_pfad($pfad)) {echo "FEHLER";exit;}

$pfad = 'dateien/'.$pfad;
$pfadteile = explode('/', $pfad);
$name = $pfadteile[count($pfadteile)-1];
$dateiteile = explode(".", $name);
$endung = ".".implode('.', array_slice($dateiteile, 1));
$sessionid = cms_generiere_sessionid();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');

$erlaubt = cms_dateicheck('../../../'.$pfad);

if ($erlaubt == 'datei') {

	if (($pfadteile[1] != 'website') && ($pfadteile[1] != 'titelbilder')) {
		$zufallsname = cms_generiere_sessionid();
		$zielpfad = 'dateien/download/'.$zufallsname.$endung;
		cms_dateisystem_datei_entschluesseln('../../../'.$pfad, '../../../'.$zielpfad);
		$pfad = $zielpfad;
	}

	$_SESSION['DOWNLOADPFAD'] = $pfad;
	$_SESSION['DOWNLOADNAME'] = $name;
	$_SESSION['DOWNLOADGROESSE'] = filesize('../../../'.$pfad);
	$_SESSION['DOWNLOADSESSION'] = $sessionid;
	echo $sessionid;
}
else if ($erlaubt == 'ordner') {
	$zufallsname = cms_generiere_sessionid();
	$archiv = 'dateien/download/'.$zufallsname.'.zip';

	$zip = new ZipArchive;
	$zip->open('../../../'.$archiv, ZipArchive::CREATE);
	if (($pfadteile[1] != 'website') && ($pfadteile[1] != 'titelbilder')) {
		cms_generiereZip_entschluesselt($pfad, '', 3, $zip, 'dateien/download/'.$zufallsname);
	}
	else {
		cms_generiereZip($pfad, '', 3, $zip);
	}
	$zip->close();
	cms_dateisystem_ordner_loeschen('../../../dateien/download/'.$zufallsname);

	$_SESSION['DOWNLOADPFAD'] = $archiv;
	$_SESSION['DOWNLOADNAME'] = $name.'.zip';
	$_SESSION['DOWNLOADGROESSE'] = filesize('../../../'.$archiv);
	$_SESSION['DOWNLOADSESSION'] = $sessionid;
	echo $sessionid;
}
else {
	echo "BERECHTIGUNG";
}
?>
