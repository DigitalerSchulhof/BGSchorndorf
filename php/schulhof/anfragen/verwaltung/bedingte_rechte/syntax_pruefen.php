<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

include_once("syntax.php");

session_start();

// Variablen einlesen, falls übergeben
postLesen("bedingung");

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.rechte.bedingt || schulhof.verwaltung.rechte.rollen.bedingt")) {
  // Eingabe überprüfen

  if(cms_bedingt_bedingung_syntax_pruefen($bedingung)) {
    die("ERFOLG");
  } else {
    die("FEHLER");
  }
}
else {
	echo "BERECHTIGUNG";
}
?>
