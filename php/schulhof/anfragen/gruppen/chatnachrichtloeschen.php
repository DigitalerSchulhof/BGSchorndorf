<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("gruppe", "gruppenid", "id"));
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
$g = $gruppe;
$gid = $gruppenid;
$gk = cms_textzudb($g);

if(!cms_valide_gruppe($gruppe))
  die("FEHLER");
$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['nachrichtloeschen'];

if (cms_angemeldet() && $zugriff) {
  if(!cms_check_ganzzahl($id, 0))
    die("FEHLER");
  $dbs = cms_verbinden("s");
  $sql = "UPDATE $gk"."chat SET loeschstatus = 2 WHERE id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $id);
  $sql->execute();
  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
