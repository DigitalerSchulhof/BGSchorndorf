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
if (isset($_POST['anonym'])) {$anonym = $_POST['anonym'];} else {echo "FEHLER"; exit;}
$dbs = cms_verbinden('s');
$anonym = $anonym === "true";
$CMS_RECHTE = cms_rechte_laden();

$fehler = true;

$sql = $dbs->prepare("SELECT 0 FROM personen WHERE id = ?");
$sql->bind_param("i", $person);
$sql->bind_result($fehler);
$sql->execute() && $sql->fetch();

if($fehler)
  exit("FEHLER");

$zugriff = true;

if (cms_angemeldet() && $zugriff) {

  $von = $_SESSION["BENUTZERID"];

  $letzte = 0;
  $sql = "SELECT COUNT(*) FROM umarmungen WHERE von=? AND an=? AND wann BETWEEN ".(time()-10*60)." AND ".time();
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $von, $person);
  $sql->bind_result($letzte);
  $sql->execute();
  $sql->fetch();
  $sql->close();

  if($letzte > 3) // Mehr als (n+1) Umarmungen in den letzten m Minuten
    die("HALT");

  $id = cms_generiere_kleinste_id("umarmungen");
  $jetzt = time();

  $sql = $dbs->prepare("UPDATE umarmungen SET von = ?, an = ?, anonym = ?, wann = ? WHERE id = ?");
  $sql->bind_param("iiiii", $von, $person, $anonym, $jetzt, $id);
  $sql->execute();

  $sql->close();

  cms_trennen($dbs);
  if(!$fehler)
    echo "ERFOLG";
  else
	 echo "FEHLER";
}
?>
