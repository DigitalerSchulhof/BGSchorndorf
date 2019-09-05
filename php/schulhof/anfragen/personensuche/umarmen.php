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

$zugriff = true;

if (cms_angemeldet() && $zugriff) {

  $von = $_SESSION["BENUTZERID"];

  $sql = "SELECT COUNT(*) as c FROM umarmungen WHERE von=$von AND an=$person AND wann BETWEEN ".(time()-5*60)." AND ".time();
  $sql = $dbs->query($sql);
  $letzte = 0;
  if($sql)
    if($sql = $sql->fetch_assoc())
      $letzte = $sql["c"];

  if($letzte > 5)
    die("HALT");

	$sql = $dbs->prepare("INSERT INTO umarmungen (von, an, anonym, wann, gesehen) VALUES(?, ?, ?, ?, ?)");
  $jetzt = time();
  $null = 0;
  $sql->bind_param("iiiii", $von, $person, $anonym, $jetzt, $null);

  $sql->execute();

  $sql->close();

  cms_trennen($dbs);
  if(!$fehler)
    echo "ERFOLG";
  else
	 echo "FEHLER";
}
?>
