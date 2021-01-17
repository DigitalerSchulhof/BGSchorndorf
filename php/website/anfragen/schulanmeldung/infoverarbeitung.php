<?php
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['verbindlichkeit'])) {$verbindlichkeit = $_POST['verbindlichkeit'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gleichbehandlung'])) {$gleichbehandlung = $_POST['gleichbehandlung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['datenschutz'])) {$datenschutz = $_POST['datenschutz'];} else {echo "FEHLER"; exit;}
if (isset($_POST['cookies'])) {$cookies = $_POST['cookies'];} else {echo "FEHLER"; exit;}

$fehler = false;
// Pflichteingaben prüfen
if (($verbindlichkeit != 1) || ($gleichbehandlung != 1) || ($datenschutz != 1) || ($cookies != 1)) {
	$fehler = true;
}

if (!$fehler) {
	$_SESSION["VORANMELDUNG_VERBINDLICHKEIT"] = 1;
	$_SESSION["VORANMELDUNG_GLEICHBEHANDLUNG"] = 1;
	$_SESSION["VORANMELDUNG_DATENSCHUTZ"] = 1;
	$_SESSION["VORANMELDUNG_COOKIES"] = 1;

  if (isset($_SESSION["VORANMELDUNG_FORTSCHRITT"])) {
    if ($_SESSION["VORANMELDUNG_FORTSCHRITT"] < 0) {$_SESSION["VORANMELDUNG_FORTSCHRITT"] = 0;}
  }
  else {
    $_SESSION["VORANMELDUNG_FORTSCHRITT"] = 0;
  }
	echo "ERFOLG";
}
else {
	echo "FEHLER";
}
?>
