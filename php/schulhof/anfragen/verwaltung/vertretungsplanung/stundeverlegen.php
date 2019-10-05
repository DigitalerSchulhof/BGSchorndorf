<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['ausgangsstundeu'])) {$ausgangsstundeu = $_POST['ausgangsstundeu'];} else {echo "FEHLER"; exit;}
if (isset($_POST['ausgangsstundek'])) {$ausgangsstundek = $_POST['ausgangsstundek'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zielzeit'])) {$zielzeit = $_POST['zielzeit'];} else {echo "FEHLER"; exit;}

if ((!cms_check_ganzzahl($ausgangsstundeu,0)) && ($ausgangsstundeu !== '')) {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($ausgangsstundek,0)) && ($ausgangsstundek !== '')) {echo "FEHLER"; exit;}
$tag = substr($zielzeit,0,2);
$monat = substr($zielzeit,3,2);
$jahr = substr($zielzeit,6,4);
$stunde = substr($zielzeit,11,2);
$minute = substr($zielzeit,14,2);
if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($stunde,0,23)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($minute,0,59)) {echo "FEHLER"; exit;}
$beginnzeit = mktime($stunde, $minute, 0, $monat, $tag, $jahr);

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
  $fehler = false;

  // Informationen der Ausgangsstunde laden
  if ($ausgangsstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($ausanzahl, $auskurs, $ausbeginn, $ausende, $auslehrer, $ausraum);
    $sql->fetch();
    if ($ausanzahl != 1) {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Zeit suchen
  if (!$fehler) {
    $sql = "SELECT COUNT(*), beginns, beginnm, endes, endem FROM schulstunden JOIN zeitraeume ON schulstunden.zeitraum = zeitraeume.id JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE schuljahre.beginn <= ? AND schuljahre.ende >= ? AND beginns = ? AND beginnm = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginnzeit, $beginnzeit, $stunde, $minute);
    if ($sql->execute()) {
      $sql->bind_result($stdanzahl, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
      $sql->fetch();
      if ($stdanzahl != 1) {$fehler = true;}
      else {
        $neubeginn = mktime($stdbeginns, $stdbeginnm, 0, $monat, $tag, $jahr);
        $neuende = mktime($stdendes, $stdendem, 0, $monat, $tag, $jahr);
      }
    } else {$fehler = true;}
    $sql->close();
  }

  if (!$fehler) {
    if (date('d.m.Y', $ausbeginn) == date('d.m.Y', $neubeginn)) {
      $art = 'a';
      $bem = "";
    }
    else {
      $art = 'v';
      $bem = 'Verlegt vom '.date('d.m.Y', $ausbeginn);
    }

    if ($ausgangsstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiissi", $auskurs, $neubeginn, $neuende, $auslehrer, $ausraum, $art, $bem, $ausgangsstundek);
      $sql->execute();
    }
    else {
      $neuid = cms_generiere_kleinste_id("unterrichtkonflikt");
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiiissi", $ausgangsstundeu, $auskurs, $neubeginn, $neuende, $auslehrer, $ausraum, $art, $bem, $neuid);
      $sql->execute();
    }
    echo "ERFOLG";
  }
  else {echo "FEHLER";}

}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
