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

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {

	$dbs = cms_verbinden('s');
	// Schuljahr suchen
	$fehler = false;
	$beginn = mktime(0,0,0,$monat,$tag,$jahr);
	$sql = "SELECT id FROM zeitraeume WHERE $beginn BETWEEN beginn AND ende";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$zeitraum = $daten['id'];
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}

	if ($fehler) {echo cms_meldung('info', '<h4>Keine Schulstunden</h4><p>Das eingegebene Datum liegt in keinem verfügbaren Zeitraum.</p>');}
	else {
		$code = "";
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE zeitraum = $zeitraum) AS x ORDER BY bs ASC, bm ASC";
		if ($anfrage = $dbs->query($sql)) {
		 	while ($s = $anfrage->fetch_assoc()) {
				$code .= "<span class=\"cms_button\" onclick=\"cms_vertretungsplan_zeit_uebernehmen('".$s['id']."', '".$s['bs']."', '".$s['bm']."', '".$s['es']."', '".$s['em']."')\">".$s['bez']."</span> ";
			}
			$anfrage->free();
		}
		echo $code;
	}
	cms_trennen($dbs);
}
else {
	echo cms_meldung_berechtigung();;
}
?>
