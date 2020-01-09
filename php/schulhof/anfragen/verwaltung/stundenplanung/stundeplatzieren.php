<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) 												{$tag = $_POST['tag'];} 															else {echo "FEHLER";exit;}
if (isset($_POST['stunde'])) 											{$stunde = $_POST['stunde'];} 												else {echo "FEHLER";exit;}
if (isset($_SESSION["STUNDENPLANUNGSCHULJAHR"])) 	{$schuljahr = $_SESSION["STUNDENPLANUNGSCHULJAHR"];} 	else {echo "FEHLER";exit;}
if (isset($_SESSION["STUNDENPLANUNGZEITRAUM"])) 	{$zeitraum = $_SESSION["STUNDENPLANUNGZEITRAUM"];} 		else {echo "FEHLER";exit;}
if (isset($_SESSION['STUNDENPLANUNGKURSE'])) 			{$kurs = $_SESSION['STUNDENPLANUNGKURSE'];} 					else {echo "FEHLER";exit;}
if (isset($_SESSION['STUNDENPLANUNGLEHRER'])) 		{$lehrer = $_SESSION['STUNDENPLANUNGLEHRER'];} 				else {echo "FEHLER";exit;}
if (isset($_SESSION['STUNDENPLANUNGRAUM'])) 			{$raum = $_SESSION['STUNDENPLANUNGRAUM'];} 						else {echo "FEHLER";exit;}
if (isset($_SESSION['STUNDENPLANUNGRYTHMUS'])) 		{$rythmus = $_SESSION['STUNDENPLANUNGRYTHMUS'];} 			else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($tag,1,7)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($stunde,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($schuljahr,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($zeitraum,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($kurs,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($lehrer,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($raum,0)) {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($rythmus,0,26)) {echo "FEHLER";exit;}


cms_rechte_laden();

$dbs = cms_verbinden('s');
if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
	$fehler = false;

	// Prüfen: Zeitraum im richtigen Schuljahr
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE id = ? AND schuljahr = ?");
	$sql->bind_param("ii", $zeitraum, $schuljahr);
	if ($sql->execute()) {
		$tage = array();
	  $sql->bind_result($anzahl, $tage[1], $tage[2], $tage[3], $tage[4], $tage[5], $tage[6], $tage[7], $anzrythmen);
	  if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
			if ($tage[$tag] != 1) {$fehler = true; echo 1;}
			if ((($anzrythmen == 1) && ($rythmus != 0)) || ($rythmus > $anzrythmen)) {$fehler = true;}
		} else {$fehler = true;echo 3;}
	} else {$fehler = true;echo 4;}
	$sql->close();

	// Prüfen: Stunde im richtigen Zeitraum
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schulstunden WHERE id = ? AND zeitraum = ?");
		$sql->bind_param("ii", $stunde, $zeitraum);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	// Prüfen: Kurs im richtigen Schuljahr
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM kurse WHERE id = ? AND schuljahr = ?");
		$sql->bind_param("ii", $kurs, $schuljahr);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	// Prüfen: Lehrer existiert
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL') AND id = ?");
		$sql->bind_param("i", $lehrer);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	// Prüfen: Raum existiert
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM raeume WHERE id = ? AND verfuegbar = '1'");
		$sql->bind_param("i", $raum);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	// Prüfen: Stunde existiert schon
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM regelunterricht WHERE schulstunde = ? AND tag = ? AND rythmus = ? AND kurs = ? AND lehrer = ? AND raum = ?");
		$sql->bind_param("iiiiii", $stunde, $tag, $rythmus, $kurs, $lehrer, $raum);
		if ($sql->execute()) {
		  $sql->bind_result($anzahl);
		  if ($sql->fetch()) {if ($anzahl != 0) {$fehler = true; echo "DOPPELT";}} else {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	}

	if (!$fehler) {
		$id = cms_generiere_kleinste_id('regelunterricht');
		$sql = $dbs->prepare("UPDATE regelunterricht SET schulstunde = ?, tag = ?, rythmus = ?, kurs = ?, lehrer = ?, raum = ? WHERE id = ?");
	  $sql->bind_param("iiiiiii", $stunde, $tag, $rythmus, $kurs, $lehrer, $raum, $id);
	  $sql->execute();
	  $sql->close();
		echo "ERFOLG";
	}
	else {echo "FEHLER";}

}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
