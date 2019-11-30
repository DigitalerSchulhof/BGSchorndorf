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
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo cms_meldung_fehler(); exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if (cms_angemeldet() && $zugriff) {

	$dbs = cms_verbinden('s');
	$kurse = "";
	$sql = "SELECT * FROM (SELECT kurs AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM (SELECT kurs FROM kursklassen WHERE klasse = $klasse) AS x JOIN kurse ON x.kurs = kurse.id) AS y ORDER BY bez ASC";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		while ($daten = $anfrage->fetch_assoc()) {
			$kurse .= "<option value=\"".$daten['id']."\">".$daten['bez']."</option>";
		}
		$anfrage->free();
	}

	if (strlen($kurse) == 0) {
		echo cms_meldung('info', '<h4>Keine Kurse</h4><p>Dieser Klasse sind keine Kurse zugeordnet.</p>');
		echo "<input type=\"hidden\" id=\"cms_vertretungsplan_ziel_kurs\" name=\"cms_vertretungsplan_ziel_kurs\" value=\"-\">";
	}
	else {
		echo "<select id=\"cms_vertretungsplan_ziel_kurs\" name=\"cms_vertretungsplan_ziel_kurs\" onchange=\"cms_vertretungsplan_zieltage_laden()\">".$kurse."</select>";
	}

	cms_trennen($dbs);
}
else {
	echo cms_meldung_berechtigung();;
}
?>
