<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['uid']))         {$uid = $_POST['uid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kid']))         {$kid = $_POST['kid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sichtbar']))    {$sichtbar = $_POST['sichtbar'];}               else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($uid,0) && ($uid != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kid,0) && ($kid != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_toggle($sichtbar)) {cms_anfrage_beenden(); exit;}
if (($uid == '-') && ($kid == '-')) {cms_anfrage_beenden(); exit;}

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
  $fehler = false;

  if ($kid == '-') {
    $kurs = null;
    $lehrer = null;
    $raum = null;
    $beginn = null;
    $ende = null;
    $vplanazeigen = null;
    $vplanbemerkung = null;
    $sql = $dbs->prepare("SELECT tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), vplanart FROM unterricht WHERE id = ?");
    $sql->bind_param("i", $uid);
    if ($sql->execute()) {
      $sql->bind_result($kurs, $beginn, $ende, $lehrer, $raum, $vplanbem, $vplanart);
      $sql->fetch();
    } else {$fehler = true;}
    $sql->close();

    if ($beginn === null) {$fehler = true;}
  }


  if (!$fehler) {
    // Entfall durchführen
    if ($kid != '-') {
      $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET vplananzeigen = ? WHERE id = ?");
      $sql->bind_param("ii", $sichtbar, $kid);
      $sql->execute();
      $sql->close();
    }
    else {
      $kid = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
      $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
      $sql->bind_param("iiiiiiisii", $uid, $kurs, $beginn, $ende, $lehrer, $raum, $sichtbar, $vplanart, $vplanbem, $kid);
      $sql->execute();
      $sql->close();
    }
  	cms_lehrerdb_header(true);
    echo $kid."ERFOLG";
  }
  else {
	   cms_lehrerdb_header(true);
    echo "FEHLER";
  }
  cms_trennen($dbs);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
