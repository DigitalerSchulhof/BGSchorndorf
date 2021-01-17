<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tagid'])) {$tagid = $_POST['tagid'];} else {echo "FEHLER"; exit;}
if (isset($_POST['anschalten'])) {$anschalten = $_POST['anschalten'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["POSTLESENID"])) {$id = $_SESSION["POSTLESENID"];} else {if(isset($_POST["id"])) {$id = $_POST["id"];} else {echo "FEHLER"; exit;}}
if (isset($_POST["id"])) {$id = $_POST["id"];} else {if(isset($_SESSION["POSTLESENID"])) {$id = $_SESSION["POSTLESENID"];} else {echo "FEHLER"; exit;}}
if (isset($_POST["modus"])) {$modus = $_POST["modus"];} else {if(isset($_SESSION["POSTLESENMODUS"])) {$modus = $_SESSION["POSTLESENMODUS"];} else {echo "FEHLER"; exit;}}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf')) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$fehler = false;


	$dbp = cms_verbinden('p');
	// Prüfen, ob die angegebene Nachricht dem angemeldeten Benutzer gehört
	$tabelle = 'post'.$modus.'_'.$CMS_BENUTZERID;
	$sql = $dbp->prepare("SELECT COUNT(*) as anzahl FROM $tabelle WHERE id = ?");
	$sql->bind_param("i", $id);
	$anzahl = 0;
	if ($sql->execute()) {
	  $sql->bind_result($anzahl);
	  if (!$sql->fetch()) {
	    $fehler = true;
	  }
	}
	else {$fehler = true;}
	if ($anzahl != 1) {$fehler = true;}
	$sql->close();

	$sql = $dbp->prepare("SELECT COUNT(*) as anzahl FROM posttags_$CMS_BENUTZERID WHERE id = ?");
	$sql->bind_param("i", $tagid);
	$anzahl = 0;
	if ($sql->execute()) {
	  $sql->bind_result($anzahl);
	  if (!$sql->fetch()) {
	    $fehler = true;
	  }
	}
	else {$fehler = true;}
	if ($anzahl != 1) {$fehler = true;}
	$sql->close();


	if (!$fehler) {
		if ($anschalten == 0) {$sql = $dbp->prepare("DELETE FROM postgetagged$modus"."_$CMS_BENUTZERID WHERE tag = ? AND nachricht = ?");}
		else {$sql = $dbp->prepare("INSERT INTO postgetagged$modus"."_$CMS_BENUTZERID (tag, nachricht) VALUES (?, ?);");}
		$sql->bind_param("ii", $tagid, $id);
		$sql->execute();
		$sql->close();
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbp);
}
else {
	echo "BERECHTIGUNG";
}
?>
