<?php
header("Content-Type: text/css");
$modus = "hell";

if (date("Y-m-d") !== date("Y-04-01")) {
  echo file_get_contents(__DIR__ . "/$modus.css");
  die();
}

$datei = rand(-2, 4);
if ($datei < 1) {
  $datei = __DIR__ . "/$modus.css";
} else {
  $datei = __DIR__ . "/april/$modus$datei.css";
}
echo file_get_contents($datei);
