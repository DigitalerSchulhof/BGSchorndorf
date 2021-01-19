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

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung")) {
	$_SESSION['DRUCKANSICHT'] = 'Vertretungsplan';
	$_SESSION['DRUCKVPLANDATUMV'] = mktime(0,0,0,$monat, $tag, $jahr);
	$_SESSION['DRUCKVPLANDATUMB'] = mktime(0,0,0,$monat, $tag+1, $jahr)-1;
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
