<?php

if (isset($_GET['datei'])) {
  if (preg_match("/^[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/", $_GET['datei'])) {
    $file = __dir__ . "/dateien/schulhof/" . $_GET['datei'];
  }
  else {
    die();
  }
} else {
  die();
}

if (!file_exists($file)) {
  http_response_code(418);
  die();
}

include_once("php/allgemein/funktionen/sql.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/check.php");
include_once("php/schulhof/funktionen/meldungen.php");
include_once("php/schulhof/funktionen/generieren.php");

$DBS = cms_verbinden('s');
session_start();


if (cms_angemeldet()) {
  header('Content-Description: File Transfer');
  header("Content-Type: video/mp4");
  header("Content-Length: " . filesize($file));
  readfile($file);
} else {
  http_response_code(403);
  die();
}
?>
