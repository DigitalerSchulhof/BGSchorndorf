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
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];} else {echo "FEHLER"; exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERSCHULJAHR,0)) {echo "FEHLER"; exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.personen.sehen"))) {

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
			$sqljoin = "JOIN klassenmitglieder ON klassenmitglieder.person = personen.id JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE klassen.schuljahr = $CMS_BENUTZERSCHULJAHR";
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

			$sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, nutzerkonten.id AS nutzerkonto, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, sessiontimeout$sqlspalten FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id $sqljoin) AS personen $sqlwhere ORDER BY nachname ASC, vorname ASC");

			if ($sql->execute()) {
				$jetzt = time();
				if (strlen($klasse) > 0) {
					$sql->bind_result($pid, $pnutzer, $part, $ptitel, $pnachname, $pvorname, $pgeschlecht, $psessiontimeout, $pklasse);
				}
				else {
					$sql->bind_result($pid, $pnutzer, $part, $ptitel, $pnachname, $pvorname, $pgeschlecht, $psessiontimeout);
				}

				while ($sql->fetch()) {
					// AUSGABE ZUSAMMENBAUEN
					$anzahl ++;
					// ICON AUS VERSCHIEDENEN ANGABEN ZUSAMMENBASTELN
					$icon = "";
					$vorzeigename = cms_generiere_anzeigename($pvorname, $pnachname, $ptitel);
					if ($part == 'l') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span>';}
					else if ($part == 's') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span>';}
					else if ($part == 'e') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span>';}
					else if ($part == 'v') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltungsangestellte</span><img src="res/icons/klein/verwaltung.png"></span>';}
					else if ($part == 'x') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Externe</span><img src="res/icons/klein/extern.png"></span>';}
					// GESCHLECHTSSYMBOL LADEN
					$geschlecht = "";
					if ($pgeschlecht == 'm') {$geschlecht = "&#x2642;";}
					else if ($pgeschlecht == 'w') {$geschlecht = "&#x2640;";}
					else if ($pgeschlecht == 'u') {$geschlecht = "&#x26a5;";}
					// GEBURTSDATUM UMÄNDERN
					$ausgabe .= "<tr><td>$icon</td><td>$ptitel</td><td>$pnachname</td><td>$pvorname</td><td>$geschlecht</td>";
					$online = "offline.png";
					if ($psessiontimeout > $jetzt) {$online = "online.png";}
					$ausgabe .= "<td>";
					if (cms_r("schulhof.verwaltung.nutzerkonten.anmeldedetails"))) {
						if (!is_null($pnutzer)) {
							$ausgabe .= "<img src=\"res/icons/klein/$online\">";
						}
						else {
							$ausgabe .= "<img src=\"res/icons/klein/rot.png\">";
						}
					}
					$ausgabe .= "</td>";
					// Aktionen anfügen
					$ausgabe .= "<td>";
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $pid)\"><span class=\"cms_hinweis\">Nachricht schreiben</span><img src=\"res/icons/klein/nachricht.png\"></span> ";

					if (cms_r("schulhof.verwaltung.personen.daten"))) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Details')\"><span class=\"cms_hinweis\">Details</span><img src=\"res/icons/klein/details.png\"></span> ";
					}

					if (cms_r("schulhof.verwaltung.personen.bearbeiten"))) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Bearbeiten')\"><span class=\"cms_hinweis\">Person bearbeiten</span><img src=\"res/icons/klein/person_bearbeiten.png\"></span> ";
					}

					$zugriff = (($part == 's') || ($part == 'e')) && cms_r("schulhof.verwaltung.personen.schülereltern"));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Schüler_und_Eltern_verknüpfen')\"><span class=\"cms_hinweis\">Schüler und Eltern verknüpfen</span><img src=\"res/icons/klein/zuordnung.png\"></span> ";
					}

					if (cms_r("schulhof.verwaltung.rechte.zuordnen || schulhof.verwaltung.rechte.rollen.zuordnen"))) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Rollen_und_Rechte')\"><span class=\"cms_hinweis\">Rollen und Rechte vergeben</span><img src=\"res/icons/klein/rollen.png\"></span> ";
					}

					$zugriff = ($part == 'l') && cms_r("schulhof.verwaltung.lehrer.kürzel"));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Lehrerkürzel_ändern')\"><span class=\"cms_hinweis\">Lehrerkürzel ändern</span><img src=\"res/icons/klein/kuerzel.png\"></span> ";
					}

					$zugriff = $CMS_RECHTE['Personen']['Personenids bearbeiten'];
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'IDs_bearbeiten')\"><span class=\"cms_hinweis\">Personenids ändern</span><img src=\"res/icons/klein/ids.png\"></span> ";
					}

					$zugriff = is_null($pnutzer) && cms_r("schulhof.verwaltung.nutzerkonten.anlegen"));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Neues_Nutzerkonto_anlegen')\"><span class=\"cms_hinweis\">Nutzerkonto anlegen</span><img src=\"res/icons/klein/nutzerkonto_neu.png\"></span> ";
					}

					$zugriff = !is_null($pnutzer) && cms_r("schulhof.verwaltung.nutzerkonten.bearbeiten"));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$vorzeigename', $pid, 'Nutzerkonto_bearbeiten')\"><span class=\"cms_hinweis\">Nutzerkonto bearbeiten</span><img src=\"res/icons/klein/nutzerkonto_bearbeiten.png\"></span> ";
					}

					$zugriff = !is_null($pnutzer) && cms_r("schulhof.verwaltung.nutzerkonten.löschen"));
					if ($zugriff) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_verwaltung_nutzerkonto_loeschen_anzeige('$vorzeigename', $pid)\"><span class=\"cms_hinweis\">Nutzerkonto löschen</span><img src=\"res/icons/klein/nutzerkonto_loeschen.png\"></span> ";
					}

					if (cms_r("schulhof.verwaltung.personen.löschen"))) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_verwaltung_person_loeschen_anzeige('$vorzeigename', $pid)\"><span class=\"cms_hinweis\">Person löschen</span><img src=\"res/icons/klein/person_loeschen.png\"></span>";
					}
					$ausgabe .= "</td></tr>";
				}
			}
			$sql->close();
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
