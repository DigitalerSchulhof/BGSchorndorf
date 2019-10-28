<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['ort'])) {$ort = $_POST['ort'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_POST['bemerkung'])) {$bemerkung = cms_texttrafo_e_db($_POST['bemerkung']);} else {echo "FEHLER"; exit;}
if (isset($_POST['anzeigen'])) {$anzeigen = $_POST['anzeigen'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($lehrer,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($kurs,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($raum,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($stunde,0) && ($stunde != '-')) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($anzeigen)) {echo "FEHLER"; exit;}
if (($ort != 'a') && ($ort != 't')) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $fehler = false;
  // Kursexistenz prüfen und Schuljahr laden
  $sql = $dbs->prepare("SELECT COUNT(*), schuljahr FROM kurse WHERE id = ?");
  $sql->bind_param("i", $kurs);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $SCHULJAHR);
    if ($sql->fetch()) {
      if ($anzahl != 1) {$fehler = true;}
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Unterrichtsexistenz prüfen
  if (!$fehler) {
    if ($ort == 't') {$tabelle = "unterrichtkonflikt";}
    else {$tabelle = "unterricht";}
    $sql = $dbs->prepare("SELECT COUNT(*) FROM $tabelle WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Lehrerexistenz prüfen
  if (!$fehler) {
    $sql = $dbs->prepare("SELECT COUNT(*) FROM lehrer WHERE id = ?");
    $sql->bind_param("i", $lehrer);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Raumexistenz prüfen
  if (!$fehler) {
    $sql = $dbs->prepare("SELECT COUNT(*) FROM raeume WHERE id = ?");
    $sql->bind_param("i", $raum);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Schulstundenexistenz prüfen
  if ((!$fehler) && ($stunde != '-')) {
    $sql = $dbs->prepare("SELECT COUNT(*), beginns, beginnm, endes, endem FROM schulstunden WHERE id = ? AND zeitraum IN (SELECT id FROM zeitraeume WHERE schuljahr = ? AND beginn <= ? AND ende >= ?)");
    $sql->bind_param("iiii", $stunde, $SCHULJAHR, $hb, $hb);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $STDBEGINNS, $STDBEGINNM, $STDENDES, $STDENDEM);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  if (!$fehler) {

    if ($stunde == '-') {
      $tbeginn = null;
      $tende = null;
      if ($ort == 'a') {$sql = "SELECT tbeginn, tende FROM unterricht WHERE id = ?";}
      else {$sql = "SELECT tbeginn, tende FROM unterrichtkonflikt WHERE id = ?";}
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i", $id);
      if ($sql->execute()) {
        $sql->bind_result($tbeginn, $tende);
        $sql->fetch();
      }
      $sql->close();
      $art = 'e';
    }
    else {
      $tbeginn = mktime($STDBEGINNS, $STDBEGINNM, 0, $monat, $tag, $jahr);
      $tende = mktime($STDENDES, $STDENDEM, 0, $monat, $tag, $jahr)-1;
      $art = 'a';
    }
    // Stunde eintragen
    if ($ort == 'a') {
      $nid = cms_generiere_kleinste_id('unterrichtkonflikt');
      if ($art != 'e') {
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
        $sql->bind_param("iiiiiiissi", $id, $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $nid);
      }
      else {
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
        $sql->bind_param("iiiiiiissi", $id, $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $nid);
      }
      $sql->execute();
    }
    else {
      $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
      $sql->bind_param("iiiiiissi", $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $id);
      $sql->execute();
    }
    echo "ERFOLG";
  }
  else {
    echo "FEHLER";
  }

  cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
