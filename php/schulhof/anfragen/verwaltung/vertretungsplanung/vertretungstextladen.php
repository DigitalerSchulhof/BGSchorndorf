<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($tag,1,31)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden('s');
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $VERTRETUNGSTEXTL = "";
  $VERTRETUNGSTEXTS = "";

  // Vertretungstext laden
  $sql = $dbs->prepare("SELECT art, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE zeit = ?");
  $sql->bind_param("i", $hb);
  if ($sql->execute()) {
    $sql->bind_result($art, $inhalt);
    while ($sql->fetch()) {
      if ($art == 'l') {$VERTRETUNGSTEXTL = $inhalt;}
      if ($art == 's') {$VERTRETUNGSTEXTS = $inhalt;}
    }
  }
  $sql->close();
  cms_trennen($dbs);

  $code = "<table class=\"cms_formular\">";
  $code .= "<tr><th>Vertretungstext Schüler:</th><td><textarea name=\"cms_vplan_vtext_schueler\" id=\"cms_vplan_vtext_schueler\">$VERTRETUNGSTEXTS</textarea></td></tr>";
  $code .= "<tr><th>Vertretungstext Lehrer:</th><td><textarea name=\"cms_vplan_vtext_lehrer\" id=\"cms_vplan_vtext_lehrer\">$VERTRETUNGSTEXTL</textarea></td></tr>";
  $code .= "</table>";
  $code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_vtexte_speichern()\">Vertretungstexte speichern</span></p>";
  echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
cms_trennen($dbs);
?>
