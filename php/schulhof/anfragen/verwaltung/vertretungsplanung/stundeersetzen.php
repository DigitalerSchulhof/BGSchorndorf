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
if (isset($_POST['zielstundeu'])) {$zielstundeu = $_POST['zielstundeu'];} else {echo "FEHLER"; exit;}
if (isset($_POST['zielstundek'])) {$zielstundek = $_POST['zielstundek'];} else {echo "FEHLER"; exit;}

if ((!cms_check_ganzzahl($ausgangsstundeu,0)) && ($ausgangsstundeu !== '')) {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($ausgangsstundek,0)) && ($ausgangsstundek !== '')) {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($zielstundeu,0)) && ($zielstundeu !== '')) {echo "FEHLER"; exit;}
if ((!cms_check_ganzzahl($zielstundek,0)) && ($zielstundek !== '')) {echo "FEHLER"; exit;}

if (($ausgangsstundeu == $zielstundeu) && ($ausgangsstundek == $zielstundek)) {echo "FEHLER"; exit;}

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


  // Informationen der Zielstunde laden
  if ($zielstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($zieanzahl, $ziekurs, $ziebeginn, $zieende, $zielehrer, $zieraum);
    $sql->fetch();
    if ($zieanzahl != 1) {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();


  if (!$fehler) {
    if (date('d.m.Y', $ausbeginn) == date('d.m.Y', $ziebeginn)) {
      $art = 'a';
      $bem = "";
    }
    else {
      $art = 'v';
      $bem = 'Verlegt vom '.date('d.m.Y', $ausbeginn);
    }

    // Zielstunde entfallen lassen
    if ($zielstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = null, tende = null, tlehrer = ?, traum = ?, vplanart = 'e', vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiii", $ziekurs, $zielehrer, $zieraum, $zielstundek);
      $sql->execute();
    }
    else {
      $neuid = cms_generiere_kleinste_id("unterrichtkonflikt");
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = null, tende = null, tlehrer = ?, traum = ?, vplanart = 'e', vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiii", $zielstundeu, $ziekurs, $zielehrer, $zieraum, $neuid);
      $sql->execute();
    }

    // Ausgangsstunde verlegen
    if ($ausgangsstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiissi", $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $art, $bem, $ausgangsstundek);
      $sql->execute();
    }
    else {
      $neuid = cms_generiere_kleinste_id("unterrichtkonflikt");
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiiissi", $ausgangsstundeu, $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $art, $bem, $neuid);
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
