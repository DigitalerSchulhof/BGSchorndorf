<?php
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['typ']))   {$typ = $_POST['typ'];}   else {$typ = 'l';}
if (isset($_POST['wert']))   {$wert = $_POST['wert'];}   else {$wert = 0;}

if ($typ == "l") {$_SESSION['IMLN'] = $wert;}
?>
