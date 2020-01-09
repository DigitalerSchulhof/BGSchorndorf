<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurse'])) {$kurseids = $_POST['kurse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['SCHIENESCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHIENESCHULJAHR'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHIENEBEARBEITEN'])) {$id = $_SESSION['SCHIENEBEARBEITEN'];} else {echo "FEHLER";exit;}
$bezeichnung = cms_texttrafo_e_db($bezeichnung);
if (!cms_check_titel($bezeichnung)) {echo "FEHLER"; exit;}
if (!cms_check_idfeld($kurseids)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($SCHULJAHR,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.schienen.bearbeiten")) {
	$fehler = false;

	$dbs = cms_verbinden('s');
	// Existiert das Schuljahr
  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
  $sql->bind_param('i', $SCHULJAHR);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
  } else {$fehler = true;}
  $sql->close();

	$kurse = array();
	$kursesuche = "";
	if (strlen($kurseids) > 0) {
		$kurse = explode("|", substr($kurseids, 1));
		$kursesuche = implode(",", $kurse);
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM kurse WHERE schuljahr = ? AND id IN ($kursesuche)");
		$sql->bind_param("i", $SCHULJAHR);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl != count($kurse)) {$fehler = true; echo "KURSE";}
			}
		} else {$fehler = true;}
		$sql->close();
	}


	if (!$fehler) {
		// ZEITRAUM EINTRAGEN
		$sql = $dbs->prepare("UPDATE schienen SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	  $sql->bind_param("si", $bezeichnung, $id);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("DELETE FROM schienenkurse WHERE schiene = ?");
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("INSERT INTO schienenkurse (schiene, kurs) VALUES (?, ?)");
		foreach ($kurse as $k) {
			$sql->bind_param("ii", $id, $k);
			$sql->execute();
		}
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
cms_trennen($dbs);
?>
