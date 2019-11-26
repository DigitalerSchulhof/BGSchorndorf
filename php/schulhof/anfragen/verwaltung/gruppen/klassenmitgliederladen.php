<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER"; exit;}

$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Gruppen']['Kurse anlegen'] || $CMS_RECHTE['Organisation']['Kurse bearbeiten'];

if (cms_angemeldet() && $zugriff) {

	$code = "";
	$klassen = "(".str_replace('|', ',', substr($klassen, 1)).")";

	if (cms_check_idliste($klassen)) {
		if (strlen($klassen) > 0) {
			// Finde Anzahl an Gruppen
			$sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM klassenmitglieder JOIN";
			$sql .= " personen ON klassenmitglieder.person = personen.id WHERE gruppe IN $klassen) AS x ORDER BY nachname ASC, vorname ASC, titel ASC";

			if ($anfrage = $dbs->query($sql)) {	// Safe weil ID Check	
				while ($daten = $anfrage->fetch_assoc()) {
					$code .= "|".$daten['id'].";".$daten['art'].";".cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
				}
				$anfrage->free();
			}
		}
		echo $code;
	}
	else {echo "FEHLER";}

}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
