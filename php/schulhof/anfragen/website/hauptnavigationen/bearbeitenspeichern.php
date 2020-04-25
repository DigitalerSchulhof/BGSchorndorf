<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['h_ebene'])) {$h_ebene = $_POST['h_ebene'];} else {echo "FEHLER"; exit;}
if (isset($_POST['h_ebenenzusatze'])) {$h_ebenenzusatze = $_POST['h_ebenenzusatze'];} else {echo "FEHLER"; exit;}
if (isset($_POST['h_ebenenzusatzs'])) {$h_ebenenzusatzs = $_POST['h_ebenenzusatzs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['h_tiefe'])) {$h_tiefe = $_POST['h_tiefe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['s_ebene'])) {$s_ebene = $_POST['s_ebene'];} else {echo "FEHLER"; exit;}
if (isset($_POST['s_ebenenzusatze'])) {$s_ebenenzusatze = $_POST['s_ebenenzusatze'];} else {echo "FEHLER"; exit;}
if (isset($_POST['s_ebenenzusatzs'])) {$s_ebenenzusatzs = $_POST['s_ebenenzusatzs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['s_tiefe'])) {$s_tiefe = $_POST['s_tiefe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['f_ebene'])) {$f_ebene = $_POST['f_ebene'];} else {echo "FEHLER"; exit;}
if (isset($_POST['f_ebenenzusatze'])) {$f_ebenenzusatze = $_POST['f_ebenenzusatze'];} else {echo "FEHLER"; exit;}
if (isset($_POST['f_ebenenzusatzs'])) {$f_ebenenzusatzs = $_POST['f_ebenenzusatzs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['f_tiefe'])) {$f_tiefe = $_POST['f_tiefe'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("website.navigation")) {
	$fehler = false;

	if (($h_ebene != 'd') && ($h_ebene != 'u') && ($h_ebene != 's') && ($h_ebene != 'e')) {$fehler = true;}
	if (($s_ebene != 'd') && ($s_ebene != 'u') && ($s_ebene != 's') && ($s_ebene != 'e')) {$fehler = true;}
	if (($f_ebene != 'd') && ($f_ebene != 'u') && ($f_ebene != 's') && ($f_ebene != 'e')) {$fehler = true;}

	if (($h_tiefe != '0') && ($h_tiefe != '1') && ($h_tiefe != '2') && ($h_tiefe != '3') && ($h_tiefe != '4')) {$fehler = true;}
	if (($s_tiefe != '0') && ($s_tiefe != '1') && ($s_tiefe != '2') && ($s_tiefe != '3') && ($s_tiefe != '4')) {$fehler = true;}
	if (($f_tiefe != '0') && ($f_tiefe != '1') && ($f_tiefe != '2') && ($f_tiefe != '3') && ($f_tiefe != '4')) {$fehler = true;}

	if ($h_ebene == 'e') {$h_ebenenzusatz = $h_ebenenzusatze;}
	else if ($h_ebene == 's') {$h_ebenenzusatz = $h_ebenenzusatzs;}
	else {$h_ebenenzusatz = 0;}
	if (!cms_check_ganzzahl($h_ebenenzusatz,0)) {$fehler = true;}
	if (($h_ebene != 'e') && ($h_ebene != 's')) {$h_ebenenzusatz = null; $h_tiefe = 0;}

	if ($s_ebene == 'e') {$s_ebenenzusatz = $s_ebenenzusatze;}
	else if ($s_ebene == 's') {$s_ebenenzusatz = $s_ebenenzusatzs;}
	else {$s_ebenenzusatz = 0;}
	if (!cms_check_ganzzahl($s_ebenenzusatz,0)) {$fehler = true;}
	if (($s_ebene != 'e') && ($s_ebene != 's')) {$s_ebenenzusatz = null; $s_tiefe = 0;}

	if ($f_ebene == 'e') {$f_ebenenzusatz = $f_ebenenzusatze;}
	else if ($f_ebene == 's') {$f_ebenenzusatz = $f_ebenenzusatzs;}
	else {$f_ebenenzusatz = 0;}
	if (!cms_check_ganzzahl($f_ebenenzusatz,0)) {$fehler = true;}
	if (($f_ebene != 'e') && ($f_ebene != 's')) {$f_ebenenzusatz = null; $f_tiefe = 0;}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE navigationen SET ebene = ?, ebenenzusatz = ?, tiefe = ? WHERE art = ?");
		$art = 'h';
	  $sql->bind_param("ssss", $h_ebene, $h_ebenenzusatz, $h_tiefe, $art);
	  $sql->execute();
		$art = 's';
	  $sql->bind_param("ssss", $s_ebene, $s_ebenenzusatz, $s_tiefe, $art);
	  $sql->execute();
		$art = 'f';
	  $sql->bind_param("ssss", $f_ebene, $f_ebenenzusatz, $f_tiefe, $art);
	  $sql->execute();
		$sql->close();
		cms_trennen($dbs);
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
