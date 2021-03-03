<?php
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/allgemein/funktionen/sql.php");
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/check.php");

session_start();
if(cms_angemeldet()) {
  echo "PONG";
} else {
  echo "PONGOHNEANMELDUNG";
}
?>