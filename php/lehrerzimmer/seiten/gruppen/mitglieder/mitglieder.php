<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ);
$code .= "</p>";
$code .= "<h1>Mitglieder</h1>";

include_once("php/schulhof/seiten/verwaltung/personen/personensuche.php");


if (isset($_SESSION["GRUPPEID"])) {$gruppenid = $_SESSION["GRUPPEID"];} else {$gruppenid = '-';}
if (isset($_SESSION["GRUPPEBEZEICHNUNG"])) {$gruppenbezeichnung = $_SESSION["GRUPPEBEZEICHNUNG"];} else {$gruppenbezeichnung = '-';}
if (isset($_SESSION["GRUPPE"])) {$gruppe = $_SESSION["GRUPPE"];} else {$gruppe = '-';}

if(!cms_check_ganzzahl($gruppenid))
	$gruppenid = "-";

if ($gruppenid != "-") {	// "-" nicht als Fallback verwenden, da oben für anti-sql-inj genutzt
	$gruppek = strtolower($gruppe);
	$zugriff = false;
	$fehler = false;
	// Prüfen, ob das Gremium allgemein sichtbar ist oder ob der Benutzer Mitglied des Gremiums ist

	$gruppenrecht = cms_gruppenrechte_laden($gruppe, $gruppenid);

	if ($gruppenrecht['mitglied'] && $gruppenrecht['mv']) {$zugriff = true;}


	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();

	if ($angemeldet && $zugriff) {
		$gruppenmitgliederrechte = "";


		$vorsitzcode = "";
		$mitgliedercode = "";
		$mitgliederhidden = "";

		// Alle Mitglieder bestimmen
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, vorsitz, mv, sch, dho, dru, dum, dlo, oan, oum, olo FROM ".$gruppek."mitgliedschaften JOIN personen ON ".$gruppek."mitgliedschaften.person = personen.id WHERE gruppe = $gruppenid) AS mitglieder ORDER BY nachname, vorname ASC;";
		if ($anfrage = $dbs->query($sql)) {	// Safe wegen ganzzahlcheck oben
			while ($daten = $anfrage->fetch_assoc()) {
				$rechte['vorsitz'] = $daten['vorsitz'];
				$rechte['mv'] = $daten['mv'];
				$rechte['sch'] = $daten['sch'];
				$rechte['dho'] = $daten['dho'];
				$rechte['dru'] = $daten['dru'];
				$rechte['dum'] = $daten['dum'];
				$rechte['dlo'] = $daten['dlo'];
				$rechte['oan'] = $daten['oan'];
				$rechte['oum'] = $daten['oum'];
				$rechte['olo'] = $daten['olo'];
				$mitgliedercode .= cms_personensuche_personrechteerzeugen ('lehrerzimmer_gruppen_mitglieder', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], $rechte, true);
				$mitgliederhidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}
		cms_trennen($dbs);

		$code .= "<table class=\"cms_formular\">";
		$code .= "<thead>";
		$code .= "<tr><th rowspan=\"2\"></th><th rowspan=\"2\">Personen</th><th rowspan=\"2\">Vorsitz</th><th>Mitglieder</th><th>Inhalte</th><th colspan=\"4\">Dateien</th><th colspan=\"3\">Ordner</th><th></th></tr>";
		$code .= "<tr><th>verwalten</th><th>schreiben</th><th>hochladen</th><th>herunterladen</th><th>umbenennen</th><th>löschen</th><th>anlegen</th><th>umbenennen</th><th>löschen</th><th></th></tr>";
		$code .= "<tr id=\"cms_lehrerzimmer_gruppen_mitglieder_keine\" style=\"display: none;\"><td class=\"cms_notiz\" colspan=\"12\">- keine Mitglieder -</td></tr>";
		$code .= "</thead>";
		$code .= "<tbody id=\"cms_lehrerzimmer_gruppen_mitgliederF\">";
		$code .= $mitgliedercode;
		$code .= "</tbody>";
		$code .= "</table>";

		$code .= "<div class=\"cms_personensuche_feld_aussen\">".cms_personensuche('lehrerzimmer_gruppen_mitglieder', 'Mitglieder hinzufügen', $mitgliederhidden, false, true, false, true, false, 1, 'rechtezeile')."</div>";
		$gruppenbezeichnunglink = str_replace(' ', '_', $gruppenbezeichnung);
		$code .= "<p id=\"cms_gruppen_bearbeiten_buttons\"><span class=\"cms_button\" onclick=\"cms_gruppe_mitgliederverwaltung('$gruppe', '$gruppenbezeichnung');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"Lehrerzimmer/".$gruppe."/$gruppenbezeichnunglink/\">Abbrechen</a></p>";

	}
	else {
		$code .= cms_meldung_berechtigung();
	}
}
else {
	$code .= cms_meldung_bastler();
}

$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
