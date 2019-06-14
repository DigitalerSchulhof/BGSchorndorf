<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['recht'])) {$recht = $_POST['recht'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($recht,0)) {echo "FEHLER"; exit;}
if (isset($_POST['anschalten'])) {$anschalten = $_POST['anschalten'];} else {echo "FEHLER"; exit;}
if (!cms_check_toggle($anschalten)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

if (cms_angemeldet() && ($CMS_RECHTE['Personen']['Rechte und Rollen zuordnen'])) {
	$fehler = false;

	if (!isset($_SESSION['PERSONENDETAILS'])) {
		$fehler = true;
	}
	else {
		$person = $_SESSION['PERSONENDETAILS'];
	}

	// Prüfen, ob zu vergebendes Recht existiert
	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM rechte WHERE id = ?;");
  $sql->bind_param("i", $recht);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	// Prüfen, ob zu vergebendes Recht in einer bereits vergebenen Rolle enthalten ist
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM (SELECT DISTINCT recht FROM rollenrechte WHERE rolle IN (SELECT rolle FROM rollenzuordnung WHERE person = ?)) AS rechte WHERE recht = ?");
  $sql->bind_param("ii", $person, $recht);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	cms_trennen($dbs);

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		// Wenn das Recht vergeben werden soll:
		if ($anschalten == 1) {
			// Neues Recht vergeben
			$sql = $dbs->prepare("INSERT INTO rechtzuordnung (person, recht) VALUES (?, ?);");
		  $sql->bind_param("ii", $person, $recht);
		  $sql->execute();
		  $sql->close();
		}
		// Wenn das Recht entfernt werden soll
		else {
			$sql = $dbs->prepare("DELETE FROM rechtzuordnung WHERE person = ? AND recht = ?;");
		  $sql->bind_param("ii", $person, $recht);
		  $sql->execute();
		  $sql->close();
		}
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
