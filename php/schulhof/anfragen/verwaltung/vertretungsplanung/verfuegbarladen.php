<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/grundfunktionen.php");
include_once("../../schulhof/seiten/verwaltung/vertretungsplanung/tagladen.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['monat'])) {$monat = $_POST['monat'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['jahr'])) {$jahr = $_POST['jahr'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['bs'])) {$bs = $_POST['bs'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['bm'])) {$bm = $_POST['bm'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['es'])) {$es = $_POST['es'];} else {echo cms_meldung_fehler(); exit;}
if (isset($_POST['em'])) {$em = $_POST['em'];} else {echo cms_meldung_fehler(); exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$sjfehler = false;
	$beginn = mktime($bs,$bm,0,$monat,$tag,$jahr);
	$ende = mktime($es,$em,0,$monat,$tag,$jahr);

	if ($beginn > $ende) {echo cms_meldung('fehler', '<h4>Fehlerhafte Zeit</h4><p>Das eingegebene Ende liegt zeitlich vor dem Beginn.</p>');$fehler = true;}
	if ($beginn == $ende) {echo cms_meldung('fehler', '<h4>Fehlerhafte Zeit</h4><p>Der eingegebene Beginn und das Ende sind identisch.</p>');$fehler = true;}

	$dbs = cms_verbinden('s');
	// Schuljahr suchen
	$sql = "SELECT id FROM schuljahre WHERE $beginn BETWEEN beginn AND ende";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$schuljahr = $daten['id'];
		} else {$sjfehler = true;}
		$anfrage->free();
	} else {$sjfehler = true;}

	if ($sjfehler) {echo cms_meldung('info', '<h4>Kein Schuljahr</h4><p>Das eingegebene Datum liegt in keinem verfügbaren Schuljahr.</p>'); $fehler = true;}
	else {
		$code = "<h3>Verfügbare Lehrkräfte</h3>";
		$lehrer = "";
		$sql = "SELECT tlehrkraft AS id FROM tagebuch_$schuljahr WHERE (tende BETWEEN $beginn AND $ende) OR (tbeginn BETWEEN $beginn AND $ende) OR (tbeginn < $beginn AND tende > $ende)";
		$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.id NOT IN ($sql)) AS x ORDER BY nachname ASC, vorname ASC, kuerzel ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
		 	while ($l = $anfrage->fetch_assoc()) {
				$lehrer .= "<span class=\"cms_button\" onclick=\"cms_vertretungsplan_lehrer_uebernehmen('".$l['id']."')\">".cms_generiere_anzeigename($l['vorname'], $l['nachname'], $l['titel'])." (".$l['kuerzel'].")</span> ";
			}
			$anfrage->free();
		}
		if (strlen($lehrer) > 0) {$code .= "<p>".$lehrer."</p>";}
		else {$code .= "<p><i>In dieser Zeit sind keine Lehrkräfte verfügbar.</i></p>";}

		$code .= "<h3>Verfügbare Räume</h3>";
		$raueme = "";
		$sql = "SELECT traum AS id FROM tagebuch_$schuljahr WHERE (tende BETWEEN $beginn AND $ende) OR (tbeginn BETWEEN $beginn AND $ende) OR (tbeginn < $beginn AND tende > $ende)";
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM raeume WHERE id NOT IN ($sql)) AS x ORDER BY bez ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
		 	while ($r = $anfrage->fetch_assoc()) {
				$raueme .= "<span class=\"cms_button\" onclick=\"cms_vertretungsplan_raum_uebernehmen('".$r['id']."')\">".$r['bez']."</span> ";
			}
			$anfrage->free();
		}
		if (strlen($raueme) > 0) {$code .= "<p>".$raueme."</p>";}
		else {$code .= "<p><i>In dieser Zeit sind keine Räume verfügbar.</i></p>";}

		echo $code;
	}
	cms_trennen($dbs);
}
else {
	echo cms_meldung_berechtigung();;
}
?>
