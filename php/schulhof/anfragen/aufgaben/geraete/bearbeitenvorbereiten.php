<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {$id = '';}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {$art = '';}

$_SESSION["GERAETESTANDORT"] = $id;
$_SESSION["GERAETEART"] = $art;

echo "ERFOLG";
?>
