<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/seiten/website/galerien/galeriesuche.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {$jahr = '';}



$fehler =  (!cms_check_ganzzahl($jahr,0));

if (cms_angemeldet() && cms_r("artikel.galerien.*")) {
  if (!$fehler) {
  	$dbs = cms_verbinden('s');
  	$ausgabe = cms_galerieverwaltung_suche($dbs, $jahr);
  	cms_trennen($dbs);
  	echo $ausgabe;
  }
  else {
  	echo "<tr><td class=\"cms_notiz\" colspan=\"7\">- Ungültige Anfrage -</td></tr>";
  }
}
else {
	echo "<tr><td class=\"cms_notiz\" colspan=\"7\">- Zugriff verweigert -</td></tr>";
}
?>
