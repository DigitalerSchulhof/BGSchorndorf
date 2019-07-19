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

$sql = "SELECT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = $person";
if ($anfrage = $dbs->query($sql)) {
  if ($daten = $anfrage->fetch_assoc()) {
    $art = $daten['art'];
    $fehler = false;
  }
  $anfrage->free();
}
if($fehler)
  exit("FEHLER");

$zugriff = false;
$art == "l" && $CMS_RECHTE['Personen']['Lehrer umarmen'] && $zugriff = true;
$art == "e" && $CMS_RECHTE['Personen']['Eltern umarmen'] && $zugriff = true;
$art == "s" && $CMS_RECHTE['Personen']['Schüler umarmen'] && $zugriff = true;
$art == "v" && $CMS_RECHTE['Personen']['Verwaltungsangestellte umarmen'] && $zugriff = true;

if (cms_angemeldet() && $zugriff) {

  $von = $anonym?-1:$_SESSION["BENUTZERID"];

	$sql = $dbs->prepare("INSERT INTO umarmungen (von, an, wann, gesehen) VALUES(?, ?, ?, ?)");
  $jetzt = time();
  $null = 0;
  $sql->bind_param("iiii", $von, $person, $jetzt, $null);

  $sql->execute();

  $sql->close();

  cms_trennen($dbs);
  if(!$fehler)
    echo "ERFOLG";
  else
	 echo "FEHLER";
}
?>
