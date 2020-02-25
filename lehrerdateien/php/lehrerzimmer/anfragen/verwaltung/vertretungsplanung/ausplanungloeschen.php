<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['art'])) 		    {$art = $_POST['art'];} 			                  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['id'])) 		      {$id = $_POST['id'];} 			                    else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");

if (($art != 'l') && ($art != 'r') && ($art != 'k') && ($art != 's')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($id,0)) {cms_anfrage_beenden(); exit;}

$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = cms_r("lehrerzimmer.vertretungsplan.*");

if ($angemeldet && $zugriff) {
  $dbl = cms_verbinden('l');
  if ($art == 'l') {
    $sql = "DELETE FROM ausplanunglehrer WHERE id = ?";
  }
  else if ($art == 'r') {
    $sql = "DELETE FROM ausplanungraeume WHERE id = ?";
  }
  else if ($art == 'k') {
    $sql = "DELETE FROM ausplanungklassen WHERE id = ?";
  }
  else if ($art == 's') {
    $sql = "DELETE FROM ausplanungstufen WHERE id = ?";
  }
  $sql = $dbl->prepare($sql);
  $sql->bind_param("i", $id);
  $sql->execute();
  $sql->close();
  cms_trennen($dbl);
  $code = "ERFOLG";

  cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
