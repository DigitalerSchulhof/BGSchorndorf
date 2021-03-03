<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/push.php");

session_start();

$angemeldet = cms_angemeldet();
$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');

  $push = cms_push();

  $pushs = [];
  $ids = [];
  $sql = "SELECT altid, tkurs, tbeginn, tende, vplanart FROM unterrichtkonflikt";
  $sql = $dbs->prepare($sql);
  if ($sql->execute()) {
    $sql->bind_result($altid, $kurs, $beginn, $ende, $vplanart);
    while ($sql->fetch()) {
      $pushs[] = [
        "altid" => $altid,
        "art" => $vplanart
      ];
    }
  }
  $sql->close();

  foreach ($pushs as $p) {
    $sql = "SELECT k.person FROM kursemitglieder k WHERE k.gruppe = (SELECT tkurs FROM unterricht u WHERE u.id = ?)";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $p["altid"]);
    $sql->bind_result($pid);
    $sql->execute();
    while ($sql->fetch()) {
      $ids[] = [
        "pid" => $pid,
        "art" => $p["art"]
      ];
    }
    $sql->close();
  }

  foreach($ids as $id) {
    cms_push_hinzufuegen($dbs, $push, $id["pid"], [
      "typ" => "vplan",
      "art" => $id["art"]
    ]);
  }

  cms_push_senden($dbs, $push);
}
