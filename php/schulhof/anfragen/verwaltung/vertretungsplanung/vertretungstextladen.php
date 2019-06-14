<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['ziel'])) {$ziel = $_POST['ziel'];} else {echo cms_meldung_fehler(); exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	if (($ziel != 'ausgang') && ($ziel != 'ziel')) {
		$fehler = true;
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
	  $vtextschueler = "";
	  $vtextlehrer = "";
	  $vtextid = "-";
		$tagzeit = mktime(0, 0, 0, $monat, $tag, $jahr);
	  $sql = "SELECT id, AES_DECRYPT(textschueler, '$CMS_SCHLUESSEL') AS s,  AES_DECRYPT(textlehrer, '$CMS_SCHLUESSEL') AS l FROM vertretungstexte WHERE beginn = $tagzeit";
	  if ($anfrage = $dbs->query($sql)) {
	    if ($daten = $anfrage->fetch_assoc()) {
	      $vtextschueler = $daten['s'];
	      $vtextlehrer = $daten['l'];
	      $vtextid = $daten['id'];
	    }
	    $anfrage->free();
	  }

		$code = "<h3>Vertretungstexte</h3>";
		$code .= "<table class=\"cms_formular\">";
      $code .= "<tr>";
        $code .= "<th>Schüler:</th>";
        $code .= "<td><textarea name=\"cms_vertretungsplan_".$ziel."_text_schueler\" id=\"cms_vertretungsplan_".$ziel."_text_schueler\">$vtextschueler</textarea></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Lehrer:</th>";
        $code .= "<td><textarea name=\"cms_vertretungsplan_".$ziel."_text_lehrer\" id=\"cms_vertretungsplan_".$ziel."_text_lehrer\">$vtextlehrer</textarea></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th></th>";
        $code .= "<td><span class=\"cms_button_ja\" onclick=\"cms_vertretungsplan_vertretungstext_speichern('$ziel');\">Speichern</span> <span class=\"cms_button_nein\" onclick=\"cms_vertretungsplan_vertretungstext_loeschen_vorebereiten('$vtextid');\">Löschen</span></td>";
      $code .= "</tr>";
    $code .= "</table>";

		echo $code;

		cms_trennen($dbs);
	}
	else {
		echo cms_meldung_bastler();
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
