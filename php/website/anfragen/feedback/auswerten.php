<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['t'])) {$t = cms_texttrafo_e_event($_POST['t']);} else {$t = "";}
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

    $sql = "SELECT AES_DECRYPT(url, '$CMS_SCHLUESSEL') as url, AES_DECRYPT(header, '$CMS_SCHLUESSEL') as header, AES_DECRYPT(session, '$CMS_SCHLUESSEL') as session FROM fehlermeldungen_daten WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $t);
    $sql->bind_result($url, $header, $session);
    $sql->execute();
    if(!$sql->fetch())
      die("FEHLER");
    $sql->close();

    // Altes aufräumen
    $sql = "DELETE FROM fehlermeldungen_daten WHERE id=?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $t);
    $sql->execute();
    $sql->close();


    $sql = $dbs->prepare("INSERT INTO fehlermeldungen (id, ersteller, url, titel, beschreibung, header, session, zeitstempel, status, sichtbar) VALUES (?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT(?,'$CMS_SCHLUESSEL'), AES_ENCRYPT(?,'$CMS_SCHLUESSEL'), ?, ?, ?)");
    $weilreference0 = 0;
    $weilreference1 = 1;
    $weilreferencetime = time();
    $sql->bind_param("issssssiii", $idM, $ersteller, $url, $titel, $beschreibung, $header, $session, $weilreferencetime, $weilreference0, $weilreference1);
    $sql->execute();
    $sql->close();

    // GitHub Aπ
    $api = "https://api.github.com/repos/oxydon/BGSchorndorf/issues";

    $data = array(
      "title" => $titel,
      "body" => issue_body_machen(),
      "labels" => array(
        "automatisch",
        "problem",
      ),
      "assignees" => array(
        "jeengbe",
      )
    );

    $data = json_encode($data);

    // cURL
    $curl = curl_init();
    $curlConfig = array(
      CURLOPT_URL             => $api,
      CURLOPT_POST            => true,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_HTTPHEADER      => array(
        "Content-Type: application/json",
        "Authorization: token $GITHUB_OAUTH",
        "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
        "Accept: application/vnd.github.v3+json",
      ),
      CURLOPT_POSTFIELDS      => $data,
    );

    curl_setopt_array($curl, $curlConfig);
    $r = curl_exec($curl);
    curl_close($curl);
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

function issue_body_machen() {
  GLOBAL $CMS_SCHLUESSEL, $titel, $beschreibung, $weilreferencetime, $ersteller, $idM, $header, $session, $url;
  $r = "";
  $dbs = cms_verbinden("s");

  $r .= "## $titel\n";
  $beschreibung = explode("\n", $beschreibung);
  foreach($beschreibung as $i => $b)
    $r .= "> $b\n";
  $r .= "\n";
  $r .= "|**Hotel**|Trivago|\n";
  $r .= "|:-:|:-|\n";
  $r .= "|**Zeitpunkt**|".date("d.m.Y H:i:s", $weilreferencetime)."|\n";

  if($beschreibung == "")
    $beschreibung = "Keine Beschreibung vorhanden";
  if($ersteller == "")
    $ersteller = "Unbekannt";
  if($url == "")
    $url = "Keine URL vorhanden";

  $sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id = $ersteller";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      $vorname = $daten['vorname'];
      $nachname = $daten['nachname'];
      $titel = $daten['titel'];
      $ersteller = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
      $fehler = false;
    }
    $anfrage->free();
  }
  $r .= "|**Ersteller**|$ersteller|\n";
  $r .= "|**Interne ID**|$idM|\n";
  $r .= "|**URL**|$url|\n";
  $r .= "|**Header**|";
  $header = explode("\n", $header);
  array_pop($header);
  foreach($header as $i => $h)
    $r .= "`$h`<br>";
  substr($r, 0, -4);
  $r .= "|\n";

  $r .= "|**Session**|";
  $session = explode("\n", $session);
  array_pop($session);
  foreach($session as $i => $s)
    $r .= "`$s`<br>";
  substr($r, 0, -4);
  if(count($session) == 0)
  $r .= "Leer";
  $r .= "|\n\n";
  $r .= "#### Dieses Ticket wurde nach Angaben Dritter automatisch erstellt.";
  return $r;
}
?>
