<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  session_start();

  postLesen("typ");
  postLesen("id");
  postLesen("gid");
  postLesen("reaktion");

  if(!in_array($typ, array("b", "t", "g")))
    die("FEHLER");
  if(!cms_check_ganzzahl($id))
    die("FEHLER");

  if($reaktion == "undefined")
    die("FEHLER");

  if(!cms_angemeldet())
    die("FEHLER");

  $sql = "SELECT von FROM reaktionen WHERE typ = ? AND id = ? AND gruppe = ? AND emoticon = ?";
  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare($sql);
  $sql->bind_param("siss", $typ, $id, $gid, $reaktion);
  $l = false;
  if($sql->execute()) {
    $sql->bind_result($von);
    $l = false;
    if (!$sql->fetch()) {$von = ""; $l = true;}
  }

  $von = explode(" ", $von);
  $bid = $_SESSION["BENUTZERID"];

  // Toggle
  if(in_array($bid, $von))
    $von = array_diff($von, array($bid));
  else
    array_push($von, $bid);

  $von = join(" ", $von);

  if($l) {
    $sql = "INSERT INTO `reaktionen` (`typ`, `id`, `gruppe`, `emoticon`, `von`) VALUES (?, ?, ?, ?, ?);";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("sisss", $typ, $id, $gid, $reaktion, $von);
    $sql->execute();
  } else {
    $sql = "UPDATE reaktionen SET von = ? WHERE typ = ? AND id = ? and gruppe = ? and emoticon = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ssiss", $von, $typ, $id, $gid, $reaktion);
    $sql->execute();
  }

  die("ERFOLG");
?>
