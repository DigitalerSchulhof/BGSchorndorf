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
$bannbis = $banndauer = -1;
postLesen(array("bannbis", "banndauer"), false);
if (isset($_SESSION['BENUTZERID'])) {$person = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
$g = $gruppe;
$gid = $gruppenid;
$gk = cms_textzudb($g);
$jetzt = time();

if(!cms_valide_gruppe($gruppe))
  die("FEHLER");
if($bannbis != -1)
  if(!cms_check_ganzzahl($bannbis, 0))
    die("FEHLER");
  else
    ;
else
  if($banndauer != -1)
    $bannbis = $jetzt + $banndauer;
  else
    die("FEHLER");  // Weder noch angegeben



$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $g, $gid, $person);

$zugriff = $CMS_GRUPPENRECHTE['nutzerstummschalten'];

if (cms_angemeldet() && $zugriff) {
  if(!cms_check_ganzzahl($id, 0))
    die("FEHLER");
  $dbs = cms_verbinden("s");

  $p = -1;
  // Sender
  $sql = "SELECT person FROM $gk"."chat WHERE id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $id);
  $sql->bind_result($p);
  if(!$sql->execute() || !$sql->fetch() || $p == -1)
    die("FEHLER");

  $sql = "UPDATE $gk"."mitglieder SET chatbannbis = ?, chatbannvon = ? WHERE person = ? AND gruppe = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("iiii", $bannbis, $person, $p, $gid);
  $sql->execute();

  echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
