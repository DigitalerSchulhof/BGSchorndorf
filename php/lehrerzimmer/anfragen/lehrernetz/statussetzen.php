<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../schulhof/funktionen/check.php");
// Variablen einlesen, falls übergeben
if (isset($_POST['status'])) {$status = cms_texttrafo_e_db($_POST['status']);} else {echo "FEHLER";exit;}
if (!cms_check_toggle($status)) {echo "FEHLER";exit;}

session_start();
$_SESSION['IMLN'] = $status;
?>
