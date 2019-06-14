<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/seiten/verwaltung/stundenplanung/zeitraeume/ausgeben.php");

session_start();

$fehler = false;
// Variablen einlesen, falls übergeben
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['spalten'])) {$spalten = $_POST['spalten'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume löschen'];

if (cms_angemeldet() && $zugriff) {

  if (!$fehler) {
  	$dbs = cms_verbinden('s');
    $ausgabe = cms_stundenplanung_zeitraeume_ausgeben($dbs, $jahr);
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
