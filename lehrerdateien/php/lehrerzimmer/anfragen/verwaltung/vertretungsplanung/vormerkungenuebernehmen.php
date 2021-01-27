<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  // KONFLIKTE LADEN
  $SONDEREINSAETZE = array();
  $AENDERUNGEN = array();
  $sql = "SELECT altid, tkurs, tbeginn, tende, tlehrer, traum, vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt";
  $sql = $dbs->prepare($sql);
  if ($sql->execute()) {
    $sql->bind_result($altid, $kurs, $beginn, $ende, $lehrer, $raum, $vplananzeigen, $vplanart, $vplanbem);
    while ($sql->fetch()) {
      $k = array();
      $k['kurs'] = $kurs;
      $k['beginn'] = $beginn;
      $k['ende'] = $ende;
      $k['lehrer'] = $lehrer;
      $k['raum'] = $raum;
      $k['vplananzeigen'] = $vplananzeigen;
      $k['vplanart'] = $vplanart;
      $k['vplanbem'] = $vplanbem;
      if ($altid !== null) {
        $k['id'] = $altid;
        array_push($AENDERUNGEN, $k);
      }
      else {
        array_push($SONDEREINSAETZE, $k);
      }
    }
  }
  $sql->close();

  foreach ($SONDEREINSAETZE AS $k) {
    $id = cms_generiere_kleinste_id('unterricht', 's');
    $sql = "UPDATE unterricht SET pkurs = null, pbeginn = null, pende = null, plehrer = null, praum = null, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiiiiissi", $k['kurs'], $k['beginn'], $k['ende'], $k['lehrer'], $k['raum'], $k['vplananzeigen'], $k['vplanart'], $k['vplanbem'], $id);
    $sql->execute();
    $sql->close();

    // Neu ins Tagebuch übernehmen wenn die Stufe ein Tagebuch führt
    $eintragen = false;
    $sql = $dbs->prepare("SELECT tagebuch FROM stufen JOIN kurse ON kurse.stufe = stufen.id AND kurse.id = ?");
    $sql->bind_param("i", $k['kurs']);
    if ($sql->execute()) {
      $sql->bind_result($tagebuch);
      $sql->fetch();
      if ($tagebuch == 1) {$eintragen = true;}
    }
    $sql->close();
    if ($eintragen) {
      $sql = $dbs->prepare("INSERT INTO tagebuch (id) VALUE (?)");
      $sql->bind_param("i", $id);
      $sql->execute();
      $sql->close();
    }
  }
  // Löschen
  $sql = "DELETE FROM unterrichtkonflikt WHERE altid IS NULL";
  $sql = $dbs->prepare($sql);
  $sql->execute();
  $sql->close();


  foreach ($AENDERUNGEN AS $k) {
    $sql = "UPDATE unterricht SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanart = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiiiiissi", $k['kurs'], $k['beginn'], $k['ende'], $k['lehrer'], $k['raum'], $k['vplananzeigen'], $k['vplanart'], $k['vplanbem'], $k['id']);
    $sql->execute();
    $sql->close();

    // Neu ins Tagebuch übernehmen wenn die Stufe ein Tagebuch führt
    $eintragen = false;
    $sql = $dbs->prepare("SELECT tagebuch FROM stufen JOIN kurse ON kurse.stufe = stufen.id AND kurse.id = ?");
    $sql->bind_param("i", $k['kurs']);
    if ($sql->execute()) {
      $sql->bind_result($tagebuch);
      $sql->fetch();
      if ($tagebuch == 1) {$eintragen = true;}
    }
    $sql->close();
    if ($eintragen) {
      $sql = $dbs->prepare("DELETE FROM tagebuch WHERE id = ?");
      $sql->bind_param("i", $k['id']);
      $sql->execute();
      $sql->close();
      if ($k['vplanart'] == 'e') {
        $sql = $dbl->prepare("DELETE FROM tagebuch WHERE id = ?");
        $sql->bind_param("i", $k['id']);
        $sql->execute();
        $sql->close();
        $sql = $dbl->prepare("INSERT INTO tagebuch (id, inhalt, freigabe) VALUE (?, AES_ENCRYPT('Entfall', '$CMS_SCHLUESSEL'), 1)");
      } else {
        $sql = $dbs->prepare("INSERT INTO tagebuch (id) VALUE (?)");
      }
      $sql->bind_param("i", $k['id']);
      $sql->execute();
      $sql->close();
    }
  }
  $sql = "DELETE FROM unterrichtkonflikt";
  $sql = $dbs->prepare($sql);
  $sql->execute();
  $sql->close();

  cms_trennen($dbl);
  cms_trennen($dbs);

  cms_lehrerdb_header(true);
  echo "ERFOLG";
}
else {

	cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
