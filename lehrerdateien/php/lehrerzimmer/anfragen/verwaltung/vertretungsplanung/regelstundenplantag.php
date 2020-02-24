<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag']))         {$tag = $_POST['tag'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat']))       {$monat = $_POST['monat'];}                     else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr']))        {$jahr = $_POST['jahr'];}                       else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');
  $jetzt = time();
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $he = mktime(0,0,0,$monat, $tag+1, $jahr)-1;

  // Zusatzstunden löschen
  $sql = $dbs->prepare("DELETE FROM unterricht WHERE tbeginn >= ? AND tende <= ? AND pbeginn IS NULL");
  $sql->bind_param("ii", $hb, $he);
  $sql->execute();
  $sql->close();

  // Zurücksetzung durchführen
  $sql = $dbs->prepare("UPDATE unterricht SET tkurs = pkurs, tbeginn = pbeginn, tende = pende, tlehrer = plehrer, traum = praum, vplananzeigen = '0', vplanart = '-', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE tbeginn >= ? AND tende <= ? AND pbeginn > ? AND (plehrer != tlehrer OR praum != traum OR pkurs != tkurs OR vplananzeigen != '0' OR vplanart != '-' OR vplanbemerkung != AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
  $sql->bind_param("iii", $hb, $he, $jetzt);
  $sql->execute();
  $sql->close();
  cms_lehrerdb_header(true);
  echo "ERFOLG";

  cms_trennen($dbs);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
