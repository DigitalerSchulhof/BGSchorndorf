<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (($art != 'a') && ($art != 'k1') && ($art != 'k2') && ($art != 'l') && ($art != 'r') && ($art != 'ks')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = false;
if ($art == 'a') {
	$zugriff = $CMS_RECHTE['Planung']['Ausplanungen durchführen'];
}
else if (($art == 'k1') || ($art == 'k2') || ($art == 'l') || ($art == 'r') || ($art == 'ks')) {
	$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];
}

if (cms_angemeldet() && $zugriff) {
	$zeit = mktime(0,0,0,$monat,$tag,$jahr);
	if ($art == 'a') {
		$praefix = 'Ausplanungen';
		$_SESSION['VPlanKonflikte1T'] = date('d', $zeit);
		$_SESSION['VPlanKonflikte1M'] = date('m', $zeit);
		$_SESSION['VPlanKonflikte1J'] = date('Y', $zeit);
	}
	if ($art == 'k1') {
		$praefix = 'VPlanKonflikte1';
		$_SESSION['AusplanungenT'] = date('d', $zeit);
		$_SESSION['AusplanungenM'] = date('m', $zeit);
		$_SESSION['AusplanungenJ'] = date('Y', $zeit);
	}
	if ($art == 'k2') {$praefix = 'VPlanKonflikte2';}
	if ($art == 'l') {$praefix = 'VPlanWocheLehrer';}
	if ($art == 'r') {$praefix = 'VPlanWocheRaeume';}
	if ($art == 'ks') {$praefix = 'VPlanWocheKlassenStufen';}
	$_SESSION[$praefix.'T'] = date('d', $zeit);
	$_SESSION[$praefix.'M'] = date('m', $zeit);
	$_SESSION[$praefix.'J'] = date('Y', $zeit);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
