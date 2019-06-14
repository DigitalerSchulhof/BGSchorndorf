<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['ansicht'])) {$ansicht = $_POST['ansicht'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ansichtP'])) {$ansichtP = $_POST['ansichtP'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ansichtO'])) {$ansichtO = $_POST['ansichtO'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ansichtF'])) {$ansichtF = $_POST['ansichtF'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$id = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}
if (($ansicht != 'tag') && ($ansicht != 'woche') && ($ansicht != 'monat') && ($ansicht != 'jahr')) {echo "FEHLER";exit;}
if (!cms_check_toggle($ansichtP)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($ansichtO)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($ansichtF)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet()) {

  $ansichtT = 0;
  $ansichtW = 0;
  $ansichtM = 0;
  $ansichtJ = 0;
  if ($ansicht == 'tag') {$ansichtT = 1;}
  if ($ansicht == 'woche') {$ansichtW = 1;}
  if ($ansicht == 'monat') {$ansichtM = 1;}
  if ($ansicht == 'jahr') {$ansichtJ = 1;}

  $_SESSION["KALENDERANSICHTTAG"]         = $ansichtT;
	$_SESSION["KALENDERANSICHTWOCHE"]       = $ansichtW;
	$_SESSION["KALENDERANSICHTMONAT"]       = $ansichtM;
	$_SESSION["KALENDERANSICHTJAHR"]        = $ansichtJ;
	$_SESSION["KALENDERANSICHT"]            = $ansicht;
	$_SESSION["KALENDERTERMINEPERSOENLICH"] = $ansichtP;
	$_SESSION["KALENDERTERMINEOEFFENTLICH"] = $ansichtO;
	$_SESSION["KALENDERTERMINEFERIEN"]      = $ansichtF;
	//$_SESSION["KALENDERTERMINESICHTBAR"]    = '0';
	//foreach ($CMS_GRUPPEN as $g) {
		//$_SESSION["KALENDERGRUPPEN: ".$g]     = '0';
	//}

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
