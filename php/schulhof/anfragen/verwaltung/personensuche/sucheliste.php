<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['schueler'])) {$schueler = $_POST['schueler'];} else {echo "FEHLER"; exit;}
if (isset($_POST['eltern'])) {$eltern = $_POST['eltern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['verwaltung'])) {$verwaltung = $_POST['verwaltung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['extern'])) {$extern = $_POST['extern'];} else {echo "FEHLER"; exit;}
if (isset($_POST['nname'])) {$nname = $_POST['nname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vname'])) {$vname = $_POST['vname'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();

$zugriff = $CMS_RECHTE['Personen']['Personen sehen'];


if (cms_angemeldet() && $zugriff) {

	if (cms_check_suchtext($vname) && cms_check_suchtext($nname) && cms_check_suchtext($klasse)) {
		$dbs = cms_verbinden('s');

		// Zusammenbauen der Bedingung
		$sqlwhere = '';
		$sqljoin = '';
		$sqlspalten = '';

		/* PERSONENART
		 * Wurde eine Art hinzugefügt wird bei einer weiteren Art ein OR benötigt
		 * Die Variable aenderung speichert, ob bereits ein Kriterium hinzugefügt wurde
		 */
		$aenderung = false;
		if (($schueler == 1) || ($eltern == 1) || ($lehrer == 1) || ($verwaltung == 1) || ($extern == 1)) {
			$aenderung = true;

			$sqlwhere .= "(art IN (";
			if ($schueler == 1) {$sqlwhere .= "'s',";}
			if ($eltern == 1) {$sqlwhere .= "'e',";}
			if ($lehrer == 1) {$sqlwhere .= "'l',";}
			if ($verwaltung == 1) {$sqlwhere .= "'v',";}
			if ($extern == 1) {$sqlwhere .= "'x',";}
			$sqlwhere = substr($sqlwhere,0,-1);
			$sqlwhere .= "))";
		}

		/* NACHNAME
	     * Die Variable aenderung gibt an, ob es bereits eine Klausel in sqlwhere gibt
		 */
		 if (strlen($nname) > 0) {
			 if ($aenderung) {
				 $sqlwhere .= " AND ";
			 }
			 $nname = cms_texttrafo_e_db($nname);
			 $sqlwhere .= "UPPER(CONVERT(nachname USING utf8)) LIKE UPPER('$nname%')";
			 $aenderung = true;
		 }

		/* VORNAME
	     * Die Variable aenderung gibt an, ob es bereits eine Klausel in sqlwhere gibt
		 */
		 if (strlen($vname) > 0) {
			 if ($aenderung) {
				 $sqlwhere .= " AND ";
			 }
			 $vname = cms_texttrafo_e_db($vname);
			 $sqlwhere .= "UPPER(CONVERT(vorname USING utf8)) LIKE UPPER('$vname%')";
			 $aenderung = true;
		}

		if (strlen($klasse) > 0) {
			$sqlspalten = ", AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez";
			$sqljoin = "JOIN klassenmitglieder ON klassenmitglieder.person = personen.id JOIN klassen ON klassenmitglieder.gruppe = klassen.id";
			if ($aenderung) {
				$sqlwhere .= " AND ";
			}
			$klasse = cms_texttrafo_e_db($klasse);
			$sqlwhere .= "UPPER(CONVERT(klassenbez USING utf8)) LIKE UPPER('$klasse%')";
			$aenderung = true;
		}

		$ausgabe = "";
		$anzahl = 0;

		if (strlen($sqlwhere) > 0) {
			$sqlwhere = "WHERE ".$sqlwhere;

			$sql = "SELECT * FROM (SELECT personen.id AS id, nutzerkonten.id AS nutzerkonto, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, sessiontimeout$sqlspalten FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id $sqljoin) AS personen $sqlwhere ORDER BY nachname ASC, vorname ASC";

			$anfrage = $dbs->query($sql);

			if ($anfrage) {
				$jetzt = time();
				while ($daten = $anfrage->fetch_assoc()) {
					// AUSGABE ZUSAMMENBAUEN
					$anzahl ++;
					// ICON AUS VERSCHIEDENEN ANGABEN ZUSAMMENBASTELN
					$icon = "";
					$vorzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
					if ($daten['art'] == 'l') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span>';}
					else if ($daten['art'] == 's') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span>';}
					else if ($daten['art'] == 'e') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span>';}
					else if ($daten['art'] == 'v') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltungsangestellte</span><img src="res/icons/klein/verwaltung.png"></span>';}
					else if ($daten['art'] == 'x') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Externe</span><img src="res/icons/klein/extern.png"></span>';}
					// GESCHLECHTSSYMBOL LADEN
					$geschlecht = "";
					if ($daten['geschlecht'] == 'm') {$geschlecht = "&#x2642;";}
					else if ($daten['geschlecht'] == 'w') {$geschlecht = "&#x2640;";}
					else if ($daten['geschlecht'] == 'u') {$geschlecht = "&#x26a5;";}
					// GEBURTSDATUM UMÄNDERN
					$ausgabe .= "<tr><td>$icon</td><td>".$daten['titel']."</td><td>".$daten['nachname']."</td><td>".$daten['vorname']."</td><td>$geschlecht</td>";
					$online = "offline.png";
					if ($daten['sessiontimeout'] > $jetzt) {$online = "online.png";}
					$ausgabe .= "<td>";
					if ($CMS_RECHTE['Personen']['Anmeldedetails sehen']) {
						if (!is_null($daten['nutzerkonto'])) {
							$ausgabe .= "<img src=\"res/icons/klein/$online\">";
						}
						else {
							$ausgabe .= "<img src=\"res/icons/klein/rot.png\">";
						}
					}
					$ausgabe .= "</td>";
					// Aktionen anfügen
					// Anzeigename
					$ausgabe .= "<td>";
					if (strlen($daten['titel']) > 0) {$anzeigename = $daten['titel']."_".$daten['vorname']."_".$daten['nachname'];}
					else {$anzeigename = $daten['vorname']."_".$daten['nachname'];}
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$daten['id'].")\"><span class=\"cms_hinweis\">Nachricht schreiben</span><img src=\"res/icons/klein/nachricht.png\"></span> ";

					$zugriff = $CMS_RECHTE['Personen']['Persönliche Daten sehen'];
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Details')\"><span class=\"cms_hinweis\">Details</span><img src=\"res/icons/klein/details.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Personen bearbeiten'];
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Bearbeiten')\"><span class=\"cms_hinweis\">Person bearbeiten</span><img src=\"res/icons/klein/person_bearbeiten.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Schüler und Eltern verknüpfen'] && (($daten['art'] == 's') || ($daten['art'] == 'e'));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Schüler_und_Eltern_verknüpfen')\"><span class=\"cms_hinweis\">Schüler und Eltern verknüpfen</span><img src=\"res/icons/klein/zuordnung.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Rechte und Rollen zuordnen'];
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Rollen_und_Rechte')\"><span class=\"cms_hinweis\">Rollen und Rechte vergeben</span><img src=\"res/icons/klein/rollen.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Lehrerkürzel ändern'] && ($daten['art'] == 'l');
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Lehrerkürzel_ändern')\"><span class=\"cms_hinweis\">Lehrerkürzel ändern</span><img src=\"res/icons/klein/kuerzel.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten anlegen'] && is_null($daten['nutzerkonto']);
					if ($zugriff) {
						$vorname = cms_texttrafo_e_event($daten['vorname']);
						$nachname = cms_texttrafo_e_event($daten['nachname']);
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Neues_Nutzerkonto_anlegen')\"><span class=\"cms_hinweis\">Nutzerkonto anlegen</span><img src=\"res/icons/klein/nutzerkonto_neu.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten bearbeiten'] && !is_null($daten['nutzerkonto']);
					if ($zugriff) {
						$vorname = cms_texttrafo_e_event($daten['vorname']);
						$nachname = cms_texttrafo_e_event($daten['nachname']);
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', ".$daten['id'].", 'Nutzerkonto_bearbeiten')\"><span class=\"cms_hinweis\">Nutzerkonto bearbeiten</span><img src=\"res/icons/klein/nutzerkonto_bearbeiten.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten löschen'] && !is_null($daten['nutzerkonto']);
					if ($zugriff) {
						$vorname = cms_texttrafo_e_event($daten['vorname']);
						$nachname = cms_texttrafo_e_event($daten['nachname']);
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_verwaltung_nutzerkonto_loeschen_anzeige('$vorzeigename', ".$daten['id'].")\"><span class=\"cms_hinweis\">Nutzerkonto löschen</span><img src=\"res/icons/klein/nutzerkonto_loeschen.png\"></span> ";
					}
					$zugriff = $CMS_RECHTE['Personen']['Personen löschen'];
					if ($zugriff) {
						$vorname = cms_texttrafo_e_event($daten['vorname']);
						$nachname = cms_texttrafo_e_event($daten['nachname']);
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_verwaltung_person_loeschen_anzeige('$vorzeigename', ".$daten['id'].")\"><span class=\"cms_hinweis\">Person löschen</span><img src=\"res/icons/klein/person_loeschen.png\"></span>";
					}
					$ausgabe .= "</td></tr>";
				}
				$anfrage->free();
			}
		}
		if ($anzahl == 0) {
			$ausgabe .= "<tr><td class=\"cms_notiz\" colspan=\"7\">- keine Datensätze gefunden -</td></tr>";
		}
		echo $ausgabe;
		cms_trennen($dbs);
	}
	else {
		echo "<tr><td class=\"cms_notiz\" colspan=\"7\">- Ungültige Zeichen in Suchtexten -</td></tr>";
	}
}
else {
	echo "<tr><td class=\"cms_notiz\" colspan=\"7\">- Zugriff verweigert -</td></tr>";
}
?>
