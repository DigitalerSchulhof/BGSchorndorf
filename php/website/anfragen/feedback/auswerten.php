<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['url'])) {$url = htmlentities(cms_texttrafo_e_event($_POST['url']));} else {$url = "";}
if (isset($_POST['header'])) {$header = htmlentities(cms_texttrafo_e_event($_POST['header']));} else {$header = "";}
if (isset($_POST['titel'])) {$titel = htmlentities(cms_texttrafo_e_event($_POST['titel']));} else {$titel = "";}
if (isset($_POST['beschreibung'])) {$beschreibung = htmlentities(cms_texttrafo_e_event($_POST['beschreibung']));} else {$beschreibung = "";}
if (isset($_POST['name'])) {$name = htmlentities(cms_texttrafo_e_event($_POST['name']));} else {$name = "";}
if (isset($_POST['feedback'])) {$feedback = htmlentities(cms_texttrafo_e_event($_POST['feedback']));} else {$feedback = "";}

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_ANGEMELDET = cms_angemeldet();
$fehleraktiv = $CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] == "1";
$feedbackaktiv = $CMS_EINSTELLUNGEN['Feedback aktiv'] == "1";
$fehleranmeldung = $CMS_EINSTELLUNGEN["Fehlermeldung Anmeldung notwendig"] == "0" || ($CMS_EINSTELLUNGEN["Fehlermeldung Anmeldung notwendig"] == "1" && $CMS_ANGEMELDET);
$feedbackanmeldung = $CMS_EINSTELLUNGEN["Feedback Anmeldung notwendig"] == "0" || ($CMS_EINSTELLUNGEN["Feedback Anmeldung notwendig"] == "1" && $CMS_ANGEMELDET);
$fehlerzugriff = $fehleraktiv && $fehleranmeldung;
$feedbackzugriff = $feedbackaktiv && $feedbackanmeldung;

if($titel != "") {
  if ($fehlerzugriff) {
    $dbs = cms_verbinden("s");
    $sql = "SELECT MAX(id) as idM FROM fehlermeldungen";
    $anfrage = $dbs->query($sql);
    if(!$sqld = $anfrage->fetch_assoc())
      $idM = 0;
    else
      $idM = $sqld["idM"]+1;
    $ersteller = "";
    if(isset($_SESSION["BENUTZERID"]))
      $ersteller = $_SESSION["BENUTZERID"];
    $headerS = json_decode(urldecode($header), true);
    unset($headerS["Cookie"]);
    $headerS = cms_array_leserlich($headerS, "\n");
    $sessionS = "Leer";
    if(isset($_SESSION)) {
      $session = $_SESSION;
      unset($session["SESSIONID"]);
      $sessionS = cms_array_leserlich($session, "\n");
    }
    $sql = $dbs->prepare("INSERT INTO fehlermeldungen (id, ersteller, url, titel, beschreibung, header, session, zeitstempel, status, sichtbar) VALUES (?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?,'$CMS_SCHLUESSEL'), AES_ENCRYPT(?,'$CMS_SCHLUESSEL'), ?, ?, ?)");
    $weilreference0 = 0;
    $weilreference1 = 1;
    $weilreferencetime = time();
    $sql->bind_param("issssssiii", $idM, $ersteller, $url, $titel, $beschreibung, $headerS, $sessionS, $weilreferencetime, $weilreference0, $weilreference1);
    $sql->execute();
    $sql->close();
    echo "ERFOLG";
  } else {
	  echo "BERECHTIGUNG";
  }
} elseif($name != "") {
  if($feedbackzugriff) {
    $dbs = cms_verbinden("s");
    $sql = "SELECT MAX(id) as idM FROM feedback";
    $anfrage = $dbs->query($sql);
    if(!$sqld = $anfrage->fetch_assoc())
      $idM = 0;
    else
      $idM = $sqld["idM"]+1;
    $sql = $dbs->prepare("INSERT INTO feedback (id, name, feedback, zeitstempel, sichtbar) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?, ?)");
    $weilreference1 = 1;
    $weilreferencetime = time();
    $sql->bind_param("issii", $idM, $name, $feedback, $weilreferencetime, $weilreference1);
    $sql->execute();
    $sql->close();
    echo "ERFOLG";
  } else {
	  echo "BERECHTIGUNG";
  }
}
?>
