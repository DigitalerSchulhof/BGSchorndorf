<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  session_start();

  postLesen(array("typ", "id", "gid", "reaktion"));

  if(!in_array($typ, array("b", "t", "g")))
    die("FEHLER");
  if(!cms_check_ganzzahl($id))
    die("FEHLER");

  if($reaktion == "undefined")
    die("FEHLER");

  if(!cms_angemeldet())
    die("FEHLER");

  $bid = $_SESSION["BENUTZERID"];
  $sql = "SELECT 1 FROM reaktionen WHERE typ = ? AND id = ? AND gruppe = ? AND emoticon = ? AND von = ?";
  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare($sql);
  $sql->bind_param("sissi", $typ, $id, $gid, $reaktion, $bid);
  $sql->bind_result($check);
  $l = true; // Lein tun
  if($sql->execute() && $sql->fetch() && $check == 1) {
    $l = false;  // Lein tun
  }
  // Toggle
  if($l) {
    $sql = "INSERT INTO `reaktionen` (`typ`, `id`, `gruppe`, `emoticon`, `von`) VALUES (?, ?, ?, ?, ?);";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("sisss", $typ, $id, $gid, $reaktion, $bid);
    $sql->execute();
  } else {
    $sql = "DELETE FROM reaktionen WHERE von = ? AND typ = ? AND id = ? AND gruppe = ? AND emoticon = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ssiss", $bid, $typ, $id, $gid, $reaktion);
    $sql->execute();
  }

  die("ERFOLG");
?>
