<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/meldungen.php");

include_once("../../schulhof/seiten/verwaltung/personen/personensuche.php");

session_start();

if (isset($_POST['mitglieder_temp'])) {$mitglieder_temp = $_POST['mitglieder_temp'];} else {$mitglieder_temp = '';}
if (isset($_POST['trennung'])) {$trennung = $_POST['trennung'];} else {$trennung = '';}
if (isset($_POST['gremienname'])) {$gremienname = $_POST['gremienname'];} else {$gremienname = '';}

$fehler = true;

if (cms_angemeldet() && (strlen($mitglieder_temp) > 0)) {

	$fehler = false;

	$code = "";
	$mitgliedercode = "";
	$mitgliederhidden = "";

	$mitglieder_temp = explode('#', $mitglieder_temp);
	if (count($mitglieder_temp) != 2) {$fehler = true;}
	else {
		$mitglieder = $mitglieder_temp[0];
		$mitgliederrechte = $mitglieder_temp[1];

		$dbs = cms_verbinden('s');

		// Suchmuster zusammenbauen
		$suchmuster = "";
		if (strlen($mitglieder) != 0) {
			$mitglieder = explode("|", $mitglieder);
			$mitgliederrechte = explode("-", $mitgliederrechte);

			for ($i = 0; $i <count($mitglieder); $i++) {
				$suchmuster .= $mitglieder[$i].", ";
				$zwischenrecht = explode("|", $mitgliederrechte[$i]);
				$rechte[$mitglieder[$i]]['vorsitz'] = $zwischenrecht[0];
				$rechte[$mitglieder[$i]]['mv'] = $zwischenrecht[1];
				$rechte[$mitglieder[$i]]['sch'] = $zwischenrecht[2];
				$rechte[$mitglieder[$i]]['dho'] = $zwischenrecht[3];
				$rechte[$mitglieder[$i]]['dru'] = $zwischenrecht[4];
				$rechte[$mitglieder[$i]]['dum'] = $zwischenrecht[5];
				$rechte[$mitglieder[$i]]['dlo'] = $zwischenrecht[6];
				$rechte[$mitglieder[$i]]['oan'] = $zwischenrecht[7];
				$rechte[$mitglieder[$i]]['oum'] = $zwischenrecht[8];
				$rechte[$mitglieder[$i]]['olo'] = $zwischenrecht[9];
			}
			$suchmuster = "(".substr($suchmuster, 0, -2).")";
		}
		if (strlen($suchmuster) == 0) {$suchmuster = "()";}

		$sql = "SELECT id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname FROM personen WHERE id IN $suchmuster ORDER BY nachname, vorname ASC;";
		if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
			while ($daten = $anfrage->fetch_assoc()) {
				$mitgliedercode .= cms_personensuche_personrechteerzeugen ('lehrerzimmer_gremien_mitglieder', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], $rechte[$daten['id']], true);
				$mitgliederhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		cms_trennen($dbs);


		$code .= "<table class=\"cms_formular\">";
		$code .= "<thead>";
		$code .= "<tr><th rowspan=\"2\"></th><th rowspan=\"2\">Personen</th><th rowspan=\"2\">Vorsitz</th><th>Mitglieder</th><th>Inhalte</th><th colspan=\"4\">Dateien</th><th colspan=\"3\">Ordner</th><th></th></tr>";
		$code .= "<tr><th>verwalten</th><th>schreiben</th><th>hochladen</th><th>herunterladen</th><th>umbenennen</th><th>löschen</th><th>anlegen</th><th>umbenennen</th><th>löschen</th><th></th></tr>";
		$code .= "<tr id=\"cms_lehrerzimmer_gremien_mitglieder_keine\" style=\"display: none;\"><td class=\"cms_notiz\" colspan=\"12\">- keine Mitglieder -</td></tr>";
		$code .= "</thead>";
		$code .= "<tbody id=\"cms_lehrerzimmer_gremien_mitgliederF\">";
		$code .= $mitgliedercode;
		$code .= "</tbody>";
		$code .= "</table>";

		$code .= "<div class=\"cms_personensuche_feld_aussen\">".cms_personensuche('lehrerzimmer_gremien_mitglieder', 'Mitglieder hinzufügen', $mitgliederhidden, false, true, false, true, false, 1, 'rechtezeile')."</div>";
		$code .= "<p id=\"cms_gremien_bearbeiten_buttons\"><span class=\"cms_button\" onclick=\"cms_lehrerzimmer_gremium_bearbeiten_speichern();\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Lehrerzimmer/Gremien/$gremienname/\">Abbrechen</a></p>";
	}
}

if (!$fehler) {
	echo $code;
}
else {
	echo cms_meldung_bastler();
}
?>
