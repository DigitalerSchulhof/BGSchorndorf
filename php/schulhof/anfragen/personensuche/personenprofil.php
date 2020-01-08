<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['person'])) {$person = $_POST['person'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($person)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() &&	cms_r("schulhof.verwaltung.personen.sehen"))) {
	$fehler = false;

  $dbs = cms_verbinden('s');
	// Prüfen, ob die Person existiert
	$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM personen WHERE id = ?");
  $sql->bind_param("i", $person);

  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();
  cms_trennen($dbs);

	if (!$fehler) {
    $_SESSION['PERSONENPROFIL'] = $person;
    echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
?>
