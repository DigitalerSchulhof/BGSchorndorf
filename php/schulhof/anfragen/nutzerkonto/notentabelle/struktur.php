<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/brotkrumen.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen("kurs", "bereiche", "noten");
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID, 0)) {echo "FEHLER";exit;}

if (cms_angemeldet()) {
  $dbs = cms_verbinden("s");
  $sql = "";
  $bereiche = json_decode($bereiche, true);
  $noten = json_decode($noten, true);

  if($bereiche === null || $noten === null) {
    die("FEHLER");
  }

  // Format prüfen
  if(count($bereiche) < 2) {
    die("FEHLER");
  }
  $b = array();
  foreach($bereiche as $bereich) {
    $gew = $bereich["gew"];
    $bez = $bereich["bez"];
    if(!is_numeric($gew) || !cms_check_name($bez)) {
      die("FEHLER");
    }
    $b[] = array($gew, $bez);
  }
  $n = array();
  foreach($noten as $hj) {
    $h = array();
    foreach($hj as $noten) {
      if(!cms_check_ganzzahl($noten)) {
        die("FEHLER");
      }
      $h[] = $noten;
    }
    $n[] = $h;
  }

  $bereiche = json_encode($b);
  $noten =    json_encode($n);
  
  // Ist Mitglied?
  $sql = "SELECT 1 FROM kurse JOIN kursemitglieder ON kurse.id = kursemitglieder.gruppe WHERE kursemitglieder.person = ? AND kurse.id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $CMS_BENUTZERID, $kurs);
  $gefunden = 0;
  $sql->bind_result($gefunden);
  if(!$sql->execute() || !$sql->fetch() || $gefunden == 0) {
    die("BERECHTIGUNG");
  }

  $sql = "REPLACE INTO notentabelle_struktur SET person = ?, kurs = ?, bereiche = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), noten = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("iiss", $CMS_BENUTZERID, $kurs, $bereiche, $noten);
  $sql->execute();

  echo "ERFOLG";
}
else {
  echo "BERECHTIGUNG";
}
?>
