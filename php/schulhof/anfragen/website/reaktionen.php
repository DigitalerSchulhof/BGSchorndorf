<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  session_start();

  postLesen("typ");
  postLesen("id");
  postLesen("reaktion");

  if(!in_array($typ, array("b", "t", "g")))
    die("FEHLER");
  if(!cms_check_ganzzahl($id))
    die("FEHLER");

  if($reaktion == "undefined")
    die("FEHLER");

  $sql = "SELECT von FROM reaktionen WHERE typ = ? AND id = ? AND emoticon = ?";
  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare($sql);
  $sql->bind_param("sis", $typ, $id, $reaktion);
  $l = false;
  if($sql->execute()) {
    $sql->bind_result($ips);
    $l = false;
    if (!$sql->fetch()) {$ips = ""; $l = true;}
  }

  $ips = explode(" ", $ips);

  $ip = getUserIpAddr();
  $ip = md5($ip);

  // Toggle
  if(in_array($ip, $ips))
    $ips = array_diff($ips, array($ip));
  else
    array_push($ips, $ip);

  $ips = join(" ", $ips);

  if($l) {
    $sql = "INSERT INTO `reaktionen` (`typ`, `id`, `emoticon`, `von`) VALUES (?, ?, ?, ?);";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("siss", $typ, $id, $reaktion, $ips);
    $sql->execute();
  } else {
    $sql = "UPDATE reaktionen SET von = ? WHERE typ = ? AND id = ? and emoticon = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ssis", $ips, $typ, $id, $reaktion);
    $sql->execute();
  }

  die("ERFOLG");
?>
