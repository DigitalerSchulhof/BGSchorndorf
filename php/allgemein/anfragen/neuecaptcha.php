<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/captcha.php");
session_start();
// Variablen einlesen, falls übergeben
postLesen("alteuuid");
unset($_SESSION["SPAMSCHUTZ_".$alteuuid]);
echo cms_captcha_generieren();
?>
