<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/seiten/website/blogeintraege/blogeintragsuche.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {$jahr = '';}

$CMS_RECHTE = cms_rechte_laden();

$bearbeiten = $CMS_RECHTE['Website']['Blogeinträge bearbeiten'];
$loeschen = $CMS_RECHTE['Website']['Blogeinträge bearbeiten'];
$anzeigen = $bearbeiten || $loeschen;

$zugriff = $anzeigen;
$fehler =  (!cms_check_ganzzahl($jahr,0));

$spalten = 5;
if ($bearbeiten || $loeschen) {$spalten++;}

if (cms_angemeldet() && $zugriff) {
  if (!$fehler) {
  	$dbs = cms_verbinden('s');
  	$ausgabe = cms_blogeintragverwaltung_suche($dbs, $jahr, $anzeigen, $bearbeiten, $loeschen);
  	cms_trennen($dbs);
  	echo $ausgabe;
  }
  else {
  	echo "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">- Ungültige Anfrage -</td></tr>";
  }
}
else {
	echo "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">- Zugriff verweigert -</td></tr>";
}
?>
