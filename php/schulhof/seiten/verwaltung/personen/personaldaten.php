<?php
function cms_personaldaten_ausgeben($id) {
	$zugriff = true;
	$detailansicht = false;

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = cms_r("schulhof.verwaltung.personen.daten");
		$detailansicht = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;

		$dbs = cms_verbinden('s');
		$fehler = false;

		$sql = "SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(benutzername, '$CMS_SCHLUESSEL') AS benutzername, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email, sessiontimeout, schuljahr, letzteanmeldung, vorletzteanmeldung, nutzerkonten.id AS nutzerkonto FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = $id;";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$profildaten_art = $daten['art'];
				$profildaten_benutzername = $daten['benutzername'];
				$profildaten_titel = $daten['titel'];
				$profildaten_vorname = $daten['vorname'];
				$profildaten_nachname = $daten['nachname'];
				$profildaten_geschlecht = $daten['geschlecht'];
				$profildaten_nutzerkonto = $daten['nutzerkonto'];
				$profildaten_email = $daten['email'];
				$profildaten_sessiontimeout = $daten['sessiontimeout'];
				$profildaten_schuljahr = $daten['schuljahr'];
				$profildaten_letzteanmeldung = $daten['letzteanmeldung'];
				$profildaten_vorletzteanmeldung = $daten['vorletzteanmeldung'];

				/*
				$sql = "SELECT von, anonym FROM umarmungen WHERE an=$id";
				$umarmungen_s = $dbs->query($sql);
				$umarmungen = array();
				while($daten = $umarmungen_s->fetch_assoc()) {
					array_push($umarmungen, array("von" => $daten["von"], "anonym" => $daten["anonym"]));
				}
				*/
				$anzeigename = cms_generiere_anzeigename($profildaten_vorname, $profildaten_nachname, $profildaten_titel);

				if ($profildaten_letzteanmeldung > 0) {
					$letzteanzeige = (date("d.m.Y", $profildaten_letzteanmeldung))." um ".(date("H:i", $profildaten_letzteanmeldung))." Uhr";
				}
				else {$letzteanzeige = "Hat noch nicht stattgefunden";}
				if ($profildaten_vorletzteanmeldung > 0) {
					$vorletzteanzeige = (date("d.m.Y", $profildaten_vorletzteanmeldung))." um ".(date("H:i", $profildaten_vorletzteanmeldung))." Uhr";
				}
				else {$vorletzteanzeige = "Hat noch nicht stattgefunden";}


				if ($profildaten_art == "l") {
					$sql = "SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerkuerzel FROM lehrer WHERE id = $id;";
					$anfrage2 = $dbs->query($sql);
					if ($anfrage2) {
						if ($daten2 = $anfrage2->fetch_assoc()) {
							$profildaten_lehrerkuerzel = $daten2['lehrerkuerzel'];
						}
						else {$fehler = true;}
					}

				}
				$anfrage->free();
			}
		}

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}
		else {
			$status = "offline.png";
			$jetzt = time();
			if ($jetzt < $profildaten_sessiontimeout) {
				$status = "online.png";
			}
			// Persönliche Daten ausgeben
			echo "<h2>Persönliche Daten</h2>";
			echo "<table class=\"cms_liste\">";
				echo "<tr><th>Art:</th><td>";
					if ($profildaten_art == "s") {echo "Schüler";}
					else if ($profildaten_art == "l") {echo "Lehrer";}
					else if ($profildaten_art == "e") {echo "Eltern";}
					else if ($profildaten_art == "v") {echo "Verwaltung";}
				echo "</td></tr>";

				if ((!is_null($profildaten_nutzerkonto)) && (!$detailansicht || cms_r("schulhof.verwaltung.personen.bearbeiten"))) {
					echo "<tr><th>Benutzername:</th><td>$profildaten_benutzername</td></tr>";
				}
				echo "<tr><th>Titel:</th><td>$profildaten_titel</td></tr>";
				echo "<tr><th>Vorname:</th><td>$profildaten_vorname</td></tr>";
				echo "<tr><th>Nachname:</th><td>$profildaten_nachname</td></tr>";
				echo "<tr><th>Geschlecht:</th><td>";
					if ($profildaten_geschlecht == "m") {echo '&#x2642;';}
					else if ($profildaten_geschlecht == "w") {echo '&#x2640;';}
					else if ($profildaten_geschlecht == "u") {echo '&#x26a5;';}
				echo "</td></tr>";
				if ((!is_null($profildaten_nutzerkonto)) && (!$detailansicht || cms_r("schulhof.verwaltung.personen.bearbeiten"))) {
					echo "<tr><th>eMailadresse:</th><td>$profildaten_email</td></tr>";
				}

					if ($detailansicht) {
						$versteckklasse = "cms_schulhof_verwaltung_personen_details_mehrF";
					}
					else {
						$versteckklasse = "cms_schulhof_nutzerkonto_profildaten_mehrF";
					}

					$code = "";
					$versteckt = false;
					if ($profildaten_art == "l") {
						$code .= "<tr class=\"$versteckklasse\"><th>Lehrerkürzel:</th><td>$profildaten_lehrerkuerzel</td></tr>";
						$versteckt = true;
					}
					if ((!is_null($profildaten_nutzerkonto)) && (!$detailansicht || cms_r("schulhof.verwaltung.nutzerkonten.anmeldedetails"))) {
						$code .= "<tr class=\"$versteckklasse\"><th>Letzte Anmeldung:</th><td>$letzteanzeige</td></tr>";
						$code .= "<tr class=\"$versteckklasse\"><th>Vorletzte Anmeldung:</th><td>$vorletzteanzeige</td></tr>";
						$code .= "<tr class=\"$versteckklasse\"><th>Online:</th><td><img src=\"res/icons/klein/$status\"/></td></tr>";
						$versteckt = true;
					}
				echo $code;
			echo "</table>";
			if ($versteckt) {
				if ($detailansicht) {
					echo "<p><span id=\"cms_schulhof_verwaltung_personen_details_mehr\" class=\"cms_button\" onclick=\"cms_schulhof_mehr('cms_schulhof_verwaltung_personen_details')\">Mehr</span> <span id=\"cms_schulhof_verwaltung_personen_details_weniger\" class=\"cms_button\" style=\"display: none;\" onclick=\"cms_schulhof_weniger('cms_schulhof_verwaltung_personen_details')\">Weniger</span> ";
				}
				else {
					echo "<p><span id=\"cms_schulhof_nutzerkonto_profildaten_mehr\" class=\"cms_button\" onclick=\"cms_schulhof_mehr('cms_schulhof_nutzerkonto_profildaten')\">Mehr</span> <span id=\"cms_schulhof_nutzerkonto_profildaten_weniger\" class=\"cms_button\" style=\"display: none;\" onclick=\"cms_schulhof_weniger('cms_schulhof_nutzerkonto_profildaten')\">Weniger</span> ";
				}
			}

			if ($detailansicht && !is_null($profildaten_nutzerkonto)) {
				$schreiben = cms_schreibeberechtigung($dbs, $id);
				if ($schreiben) {
					echo "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$id.")\">Nachricht schreiben</span></p>";
				}
			}
			else if ($detailansicht) {
				echo "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">Nachricht schreiben</span></p>";
			}

			// Buttons



			if ($detailansicht) {
						// Nutzerkonto
				$nutzerkontoaktionen = "";

				if (!is_null($profildaten_nutzerkonto)) {
					if (cms_r("schulhof.verwaltung.nutzerkonten.bearbeiten")) {
						$nutzerkontoaktionen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Personen/Nutzerkonto_bearbeiten\">Benutzerkonto bearbeiten</a></li> ";
					}
				}
				if (cms_r("schulhof.verwaltung.nutzerkonten.einstellungen.[|sehen,ändern]")) {
					$nutzerkontoaktionen .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_personen_einstellungen($id)\">Einstellungen</span></li> ";
				}
				if (!is_null($profildaten_nutzerkonto)) {
					if (cms_r("schulhof.verwaltung.nutzerkonten.löschen")) {
						$nutzerkontoaktionen .= "<li><span class=\"cms_button_nein\" onclick=\"cms_schulhof_verwaltung_nutzerkonto_loeschen_anzeige('$anzeigename', $id)\">Nutzerkonto löschen</span></li>";
					}
				}
				else {
					if (cms_r("schulhof.verwaltung.nutzerkonten.anlegen")) {
						$nutzerkontoaktionen .=  "<li><span class=\"cms_button_ja\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$anzeigename', $id, 'Neues_Nutzerkonto')\">Nutzerkonto anlegen</span></li> ";
					}
				}


				$personenaktionen = "";
				if (cms_r("schulhof.verwaltung.personen.bearbeiten")) {
					$personenaktionen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Personen/Bearbeiten\">Persönliche Daten ändern</a></li> ";
				}
				if (($profildaten_art == "l") && cms_r("schulhof.verwaltung.lehrer.kürzel")) {
					$personenaktionen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Personen/Lehrerkürzel_ändern\">Lehrerkürzel ändern</a></li> ";
				}
				if (cms_r("schulhof.verwaltung.rechte.zuordnen || schulhof.verwaltung.rechte.rollen.zuordnen")) {
					$personenaktionen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Personen/Rollen_und_Rechte\">Rollen und Rechte vergeben</a></li> ";
				}
				if (cms_r("schulhof.verwaltung.personen.löschen")) {
					$personenaktionen .= "<li><span class=\"cms_button_nein\" onclick=\"cms_schulhof_verwaltung_person_loeschen_anzeige('$anzeigename', $id)\">Person löschen</span></li> ";
				}
				if (strlen($personenaktionen) > 0) {
					$personenaktionen = "<ul class=\"cms_aktionen_liste\">".$personenaktionen."</ul>";
				}


				if (strlen($nutzerkontoaktionen) > 0) {
					$code = "<ul class=\"cms_aktionen_liste\">".$nutzerkontoaktionen."</ul>";
				}
				$code .= $personenaktionen;
				if (!is_null($profildaten_nutzerkonto) && cms_r("schulhof.verwaltung.nutzerkonten.bearbeiten")) {
					$code .= "<p class=\"cms_notiz\">Das Passwort kann nur durch die jeweilige Person selbst geändert werden.</p>";
				}

				if (strlen($code) > 0) {
					echo "<h3>Daten ändern</h3>".$code;
				}
			}
			else {

				/*$umarmungen_c = count($umarmungen);

				echo "<br><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Umarmungen\">$umarmungen_c Umarmung".($umarmungen_c != 1?"en":"").($umarmungen_c > 0?" ( ＾◡＾)っ ♡":"")."</a>";*/

				echo "<h3>Daten ändern</h3>";
				echo "<ul class=\"cms_aktionen_liste\">";
					echo "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil/Nutzerkonto_bearbeiten\">Benutzerkonto bearbeiten</a></li> ";
					echo "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil/Passwort_ändern\">Passwort ändern</a></li> ";
					echo  "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Einstellungen\">Einstellungen</a></li> ";
					echo "<li><a class=\"cms_button_wichtig\" href=\"Schulhof/Nutzerkonto/Mein_Profil/Identitätsdiebstahl\">Identitätsdiebstahl melden</a></li> ";
					echo "<li><span class=\"cms_button_nein\" onclick=\"cms_schulhof_verwaltung_nutzerkonto_loeschen_anzeige('$anzeigename', $id)\">Mein Nutzerkonto löschen</span></li>";
				echo "</ul>";

				$personenaktionen = "";
				if (cms_r("schulhof.verwaltung.personen.bearbeiten")) {
					$personenaktionen .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$anzeigename', $id, 'Bearbeiten')\">Persönliche Daten ändern</span></li> ";
				}
				if (($profildaten_art == "l") && cms_r("schulhof.verwaltung.lehrer.kürzel")) {
					$personenaktionen .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$anzeigename', $id, 'Lehrerkürzel_ändern')\">Lehrerkürzel ändern</span></li> ";
				}
				if (cms_r("schulhof.verwaltung.rechte.zuordnen || schulhof.verwaltung.rechte.rollen.zuordnen")) {
					$personenaktionen .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_details_vorbreiten('$anzeigename', $id, 'Rollen_und_Rechte')\">Rollen und Rechte vergeben</span></li> ";
				}
				if (cms_r("schulhof.verwaltung.personen.löschen")) {
					$personenaktionen .= "<li><span class=\"cms_button_nein\" onclick=\"cms_schulhof_verwaltung_person_loeschen_anzeige('$anzeigename', $id)\">Person löschen</span></li> ";
				}
				if (strlen($personenaktionen) > 0) {
					$personenaktionen = "<ul class=\"cms_aktionen_liste\">".$personenaktionen."</ul>";
				}
				echo $personenaktionen;
			}

			if (!is_null($profildaten_nutzerkonto)) {
				// Schuljahre
				echo "<h2>Schuljahr</h2>";

				// Alle Schuljahre ausgeben
				$code = "";
				$sjwahl = false;
				$sjbezakt = "";
				$sjakt = false;

				$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende FROM schuljahre ORDER BY beginn DESC";
				$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe

				$jetzt = time();

				if ($anfrage) {
					while ($daten = $anfrage->fetch_assoc()) {
						$sjid = $daten['id'];
						$sjbez = $daten['bezeichnung'];

						$gewaehlt = "";
						if ($sjid == $profildaten_schuljahr) {
							$gewaehlt = "_aktiv";
							$sjwahl = true;
						}

						// aktuelles Schuljahr ?
						if (($daten['beginn'] <= $jetzt) && ($daten['ende'] >= $jetzt)) {
							$sjbezakt = $sjbez;
							if ($sjid == $profildaten_schuljahr) {
								$sjakt = true;
							}
						}

						if ($detailansicht) {
							$einstellen = "cms_schulhof_verwaltung_person_schuljahr_einstellen($sjid, $id)";
						}
						else {
							$einstellen = "cms_schulhof_nutzerkonto_schuljahr_einstellen($sjid)";
						}

						$code .= "<span class=\"cms_toggle".$gewaehlt."\" onclick=\"".$einstellen."\">".$sjbez."</span> ";
					}
					$anfrage->free();
				}


				if (strlen($code) == 0) {
					echo "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";
				}
				else {
					if (!$sjwahl) {
						echo cms_meldung('info', '<p>Es ist kein Schuljahr ausgewählt. Damit rechts Gruppen und Ansprechpartner angezeigt werden können, muss ein Schuljahr ausgewählt werden.</p>');
					}
					else if (!$sjakt) {
						echo cms_meldung('warnung', '<p>Es ist nicht das aktuelle Schuljahr ausgewählt. Damit die Gruppen und Ansprechpartner aktuell sind, muss das Schuljahr <b>'.$sjbezakt.'</b> ausgewählt werden.</p>');
					}
					echo "<p>".$code."</p>";
				}


			}
		}
		cms_trennen($dbs);
	}
	else {
		echo cms_meldung_berechtigung();
	}
}


function cms_personaldaten_ansprechpartner_ausgeben($id) {
	global $CMS_BENUTZERSCHULJAHR;
	$zugriff = true;
	$detailansicht = false;

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = cms_r("schulhof.verwaltung.nutzerkonten.ansprechpartner");
		$detailansicht = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;

		$dbs = cms_verbinden('s');
		$fehler = false;

		// BENUTZER LADEN
		$sql = "SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, schuljahr FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = $id;";
		$anfrage = $dbs->query($sql);	// TODO: Eingaben der Funktion prüfen

		if ($anfrage) {
			if ($daten = $anfrage->fetch_assoc()) {
				$art = $daten['art'];
				$schuljahr = $daten['schuljahr'];

				if (is_null($schuljahr)) {
					$schuljahr = $CMS_BENUTZERSCHULJAHR;
				}
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		cms_trennen($dbs);

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}
		else {
			$dbs = cms_verbinden('s');
			$code = "";
			$ansprechpartner = "";

			function cms_personaldaten_ansprechpartner_position ($dbs, $position, $schuljahr) {
				global $CMS_SCHLUESSEL;
				$code = "";
				$sql = "SELECT * FROM (SELECT DISTINCT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, nutzerkonten.id AS nutzerkonto FROM schluesselposition JOIN personen ON personen.id = schluesselposition.person LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE schluesselposition.schuljahr = $schuljahr AND position = AES_ENCRYPT('$position', '$CMS_SCHLUESSEL')) AS personen ORDER BY nachname, vorname";
				if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
					while ($daten = $anfrage->fetch_assoc()) {
						$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
						if (!is_null($daten['nutzerkonto'])) {
							$code .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', ".$daten['id'].", 'p')\">$anzeigename</span></li> ";
						}
						else {
							$code  .= "<span class=\"cms_button_passiv\" onclick=\"cms_schulhof_kein_nutzerkonto('$anzeigename')\">$anzeigename</span> ";
						}
					}
					$anfrage->free();
				}
				return $code;
			}

			// SCHULLEITUNG
			$schulleitung = cms_personaldaten_ansprechpartner_position($dbs, 'Schulleitung', $schuljahr);
			$schulleitung .= cms_personaldaten_ansprechpartner_position($dbs, 'Stellvertretende Schulleitung', $schuljahr);
			$schulleitung .= cms_personaldaten_ansprechpartner_position($dbs, 'Abteilungsleitung', $schuljahr);
			$sekretariat = cms_personaldaten_ansprechpartner_position($dbs, 'Sekretariat', $schuljahr);
			// Weitere Positionen
			$vertretungsplanung = cms_personaldaten_ansprechpartner_position($dbs, 'Vertretungsplanung', $schuljahr);
			$sozialarbeit = cms_personaldaten_ansprechpartner_position($dbs, 'Sozialarbeit', $schuljahr);
			$oberstufenberatung = cms_personaldaten_ansprechpartner_position($dbs, 'Oberstufenberatung', $schuljahr);
			$beratungslehrer = cms_personaldaten_ansprechpartner_position($dbs, 'Beratungslehrkräfte', $schuljahr);
			$verbindungslehrer = cms_personaldaten_ansprechpartner_position($dbs, 'Verbindungslehrkräfte', $schuljahr);
			$schuelersprecher = cms_personaldaten_ansprechpartner_position($dbs, 'Schülersprecher', $schuljahr);
			$elternbeirat = cms_personaldaten_ansprechpartner_position($dbs, 'Elternbeiratsvorsitzende', $schuljahr);
			$datenschutz = cms_personaldaten_ansprechpartner_position($dbs, 'Datenschutzbeauftragter', $schuljahr);

			if (strlen($schulleitung) > 0) {$schulleitung = "<h3>Schulleitung</h3><ul class=\"cms_aktionen_liste\">".$schulleitung."</ul>";}
			if (strlen($sekretariat) > 0) {$sekretariat = "<h3>Sekretariat</h3><ul class=\"cms_aktionen_liste\">".$sekretariat."</ul>";}
			if (strlen($sozialarbeit) > 0) {$sozialarbeit = "<h3>Schulsozialarbeit</h3><ul class=\"cms_aktionen_liste\">".$sozialarbeit."</ul>";}
			if (strlen($vertretungsplanung) > 0) {$vertretungsplanung = "<h3>Vertretungsplanung</h3><ul class=\"cms_aktionen_liste\">".$vertretungsplanung."</ul>";}
			if (strlen($oberstufenberatung) > 0) {$oberstufenberatung = "<h3>Oberstufenberatung</h3><ul class=\"cms_aktionen_liste\">".$oberstufenberatung."</ul>";}
			if (strlen($beratungslehrer) > 0) {$beratungslehrer = "<h3>Beratungslehrkräfte</h3><ul class=\"cms_aktionen_liste\">".$beratungslehrer."</ul>";}
			if (strlen($verbindungslehrer) > 0) {$verbindungslehrer = "<h3>Verbindungslehrkräfte</h3><ul class=\"cms_aktionen_liste\">".$verbindungslehrer."</ul>";}
			if (strlen($schuelersprecher) > 0) {$schuelersprecher = "<h3>Schülervertretung</h3><ul class=\"cms_aktionen_liste\">".$schuelersprecher."</ul>";}
			if (strlen($elternbeirat) > 0) {$elternbeirat = "<h3>Elternbeiratsvorsitz</h3><ul class=\"cms_aktionen_liste\">".$elternbeirat."</ul>";}
			if (strlen($datenschutz) > 0) {$datenschutz = "<h3>Datenschutz</h3><ul class=\"cms_aktionen_liste\">".$datenschutz."</ul>";}

			$standard = "";
			$standard = $schulleitung.$sekretariat.$sozialarbeit.$beratungslehrer;

			if ($detailansicht) {
				if ($art == 's') {
					// ELTERN
					$eltern = "";
					$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schuelereltern JOIN personen ON personen.id = schuelereltern.eltern WHERE schuelereltern.schueler = $id) AS personen ORDER BY nachname, vorname";
					if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
						while ($daten = $anfrage->fetch_assoc()) {
							$anzeigename = $daten['vorname']." ".$daten['nachname'];
							if (strlen($daten['titel']) > 0) {
								$anzeigename = $daten['titel']." ".$anzeigename;
							}
							$eltern .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', ".$daten['id'].", 'p')\">$anzeigename</span></li> ";
						}
						$anfrage->free();
					}
					if (strlen($eltern) > 0) {$eltern = "<h3>Eltern</h3><ul class=\"cms_aktionen_liste\">".$eltern."</ul>";}
					$code .= $eltern;
					$code .= $standard;
				}
				else if ($art == 'e') {
					// Kinder
					$kinder = "";
					$sql = "SELECT * FROM (SELECT DISTINCT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM schuelereltern JOIN personen ON personen.id = schuelereltern.schueler WHERE schuelereltern.eltern = $id) AS personen ORDER BY nachname, vorname";
					if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
						while ($daten = $anfrage->fetch_assoc()) {
							$anzeigename = $daten['vorname']." ".$daten['nachname'];
							if (strlen($daten['titel']) > 0) {
								$anzeigename = $daten['titel']." ".$anzeigename;
							}
							$kinder .= "<li><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vor', '', '', ".$daten['id'].", 'p')\">$anzeigename</span></li> ";
						}
						$anfrage->free();
					}
					if (strlen($kinder) > 0) {$kinder = "<h3>Kinder</h3><ul class=\"cms_aktionen_liste\">".$kinder."</ul>";}
					$code .= $kinder;
					$code .= $standard;
				}
				else {
					$code .= $standard;
					$code .= $vertretungsplanung;
					$code .= $verbindungslehrer;
					$code .= $oberstufenberatung;
					$code .= $elternbeirat;
					$code .= $schuelersprecher;
				}
			}
			else {
				if ($art == 's') {
					// KLASSENLEHRER ODER TUTOR
					// OBERSTUFENBERATUNG, FALLS IN DER KURSSSTUFE
					$code .= $verbindungslehrer;
					$code .= $schuelersprecher;
					$code .= $standard;
				}
				else if ($art == 'e') {
					// KLASSENLEHRER IHRER KINDER
					$code .= $elternbeirat;
					$code .= $standard;
					$code .= $verbindungslehrer;
					$code .= $schuelersprecher;
				}
				else if ($art == 'l') {
					$code .= $standard;
					$code .= $vertretungsplanung;
					$oberstufe = false;
					$code .= $oberstufenberatung;
					$code .= $verbindungslehrer;
					$code .= $schuelersprecher;
					$code .= $elternbeirat;
				}
				else {
					$code .= $standard;
					$code .= $vertretungsplanung;
					$code .= $oberstufenberatung;
					$code .= $elternbeirat;
					$code .= $verbindungslehrer;
					$code .= $schuelersprecher;
				}
			}
			// Datenschutz ganz unten
			$code .= $datenschutz;

			if (strlen($code) == 0) {
				$code .= "<p class=\"cms_notiz\">Keine Anprechpartner angelegt.</p>";
			}


			cms_trennen($dbs);
			echo $code;
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}



function cms_personaldaten_benutzerkonto_aendern($id) {

	$zugriff = true;
	$verwaltung = false;

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = cms_r("schulhof.verwaltung.personen.bearbeiten");
		$verwaltung = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;
		$dbs = cms_verbinden('s');
		$fehler = false;

		$sql = "SELECT AES_DECRYPT(benutzername, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL') FROM nutzerkonten WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
			$sql->bind_result($profildaten_benutzername, $profildaten_email);
			if ($sql->fetch()) {}
				else {$fehler = true;}
			$sql->close();
		}
		else {$fehler = true;}

		cms_trennen($dbs);

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}
		else {
			echo "<table class=\"cms_formular\">";
				echo "<tr>";
				echo "<th>Benutzername:</th>";
				echo "<td><input type=\"text\" value=\"$profildaten_benutzername\" ";
				if ($verwaltung) {
					echo "name=\"cms_schulhof_verwaltung_personen_profildaten_benutzername\" id=\"cms_schulhof_verwaltung_personen_profildaten_benutzername\"></td>";
				}
				else {
					echo "name=\"cms_schulhof_nutzerkonto_profildaten_benutzername\" id=\"cms_schulhof_nutzerkonto_profildaten_benutzername\"></td>";
				}
				echo "</tr>";
				echo "<tr>";
					echo "<th>eMailadresse:</th>";
					echo "<td><input type=\"mail\" value=\"$profildaten_email\" ";

					if ($verwaltung) {
						echo "name=\"cms_schulhof_verwaltung_personen_profildaten_email\" id=\"cms_schulhof_verwaltung_personen_profildaten_email\" onkeyup=\"cms_check_mail_wechsel('verwaltung_personen_profildaten_email');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_verwaltung_personen_profildaten_email_icon\"><img src=\"res/icons/klein/richtig.png\"></span></td>";
					}
					else {
						echo "name=\"cms_schulhof_nutzerkonto_profildaten_email\" id=\"cms_schulhof_nutzerkonto_profildaten_email\" onkeyup=\"cms_check_mail_wechsel('nutzerkonto_profildaten_email');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_nutzerkonto_profildaten_email_icon\"><img src=\"res/icons/klein/richtig.png\"></span></td>";
					}
				echo "</tr>";
			echo "</table>";

			$link = "Schulhof/Verwaltung/Personen/Details";
			if ($verwaltung) {
				echo "<input type=\"hidden\" name=\"cms_schulhof_verwaltung_personen_profildaten_benutzer_id\" id=\"cms_schulhof_verwaltung_personen_profildaten_benutzer_id\" value=\"$id\">";
				echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_personen_benutzerkonto_aendern();\">Änderungen speichern</span> ";
			}
			else {
				$link = "Schulhof/Nutzerkonto/Mein_Profil";
				echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_nutzerkonto_benutzerkonto_aendern();\">Änderungen speichern</span> ";
			}
			echo "<a class=\"cms_button_nein\" href=\"$link\">Zurück</a>";
		 	echo "</p>";
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}


function cms_personaldaten_lehrerkuerzel_aendern($id) {
	global $CMS_EINSTELLUNGEN;
	$zugriff = cms_r("schulhof.verwaltung.lehrer.kürzel");
	$verwaltung = false;

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$verwaltung = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;
		$dbs = cms_verbinden('s');
		$fehler = false;
		$keinlehrer = false;

		$sql = "SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') FROM lehrer WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
			$sql->bind_result($profildaten_kuerzel, $profildaten_stundenplan);
			if ($sql->fetch()) {}
				else {$keinlehrer = true;}
			$sql->close();
		}
		else {$fehler = true;}

		cms_trennen($dbs);

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}
		if ($keinlehrer) {
			echo cms_meldung ('fehler', '<p>Der ausgewählte Benutzer ist kein Lehrer.</p>');
		}
		else {
			echo "<table class=\"cms_formular\">";
				echo "<tr>";
				echo "<th>Lehrerkürzel:</th>";
				echo "<td><input type=\"text\" value=\"$profildaten_kuerzel\" ";
				if ($verwaltung) {
					echo "name=\"cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel\" id=\"cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel\"></td>";
				}
				else {
					echo "name=\"cms_schulhof_nutzerkonto_profildaten_lehrerkuerzel\" id=\"cms_schulhof_nutzerkonto_profildaten_lehrerkuerzel\"></td>";
				}
				echo "</tr>";
				if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == "1") {
					if ($verwaltung) {
						echo "<tr><th>Stundenplan:</th><td>".cms_dateiwahl_knopf ("schulhof/stundenplaene", "cms_schulhof_verwaltung_personen_profildaten_stundenplan", "s", "Stundenplan", "-", "download", $profildaten_stundenplan)."</td></tr>";
					}
					else {
						echo "<tr><th>Stundenplan:</th><td>".cms_dateiwahl_knopf ("schulhof/stundenplaene", "cms_schulhof_nutzerkonto_profildaten_stundenplan", "s", "Stundenplan", "-", "download", $profildaten_stundenplan)."</td></tr>";
					}

				}
			echo "</table>";

			if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == "0") {
				if ($verwaltung) {
					echo "<p><input type=\"hidden\" name=\"cms_schulhof_verwaltung_personen_profildaten_stundenplan\" id=\"cms_klasse_stundenplan\" value=\"\"></p>";
				}
				else {
					echo "<p><input type=\"hidden\" name=\"cms_schulhof_nutzerkonto_profildaten_stundenplan\" id=\"cms_klasse_stundenplan\" value=\"\"></p>";
				}

			}

			$link = "Schulhof/Verwaltung/Personen/Details";
			if ($verwaltung) {
				echo "<input type=\"hidden\" name=\"cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel_id\" id=\"cms_schulhof_verwaltung_personen_profildaten_lehrerkuerzel_id\" value=\"$id\">";
				echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_personen_lehrerkuerzel_aendern();\">Änderungen speichern</span> ";
			}
			else {
				$link = "Schulhof/Nutzerkonto/Mein_Profil";
				echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_nutzerkonto_lehrerkuerzel_aendern();\">Änderungen speichern</span> ";
			}

			echo "<a class=\"cms_button_nein\" href=\"$link\">Zurück</a>";
		 	echo "</p>";
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}


function cms_personaldaten_aendern($id) {

	$zugriff = true;
	$verwaltung = false;

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = cms_r("schulhof.verwaltung.personen.bearbeiten");
		$verwaltung = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;
		$dbs = cms_verbinden('s');
		$fehler = false;

		$sql = "SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL') FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($profildaten_art, $profildaten_titel, $profildaten_vorname, $profildaten_nachname, $profildaten_geschlecht, $profildaten_email);
			if ($sql->fetch()) {}
				else {$fehler = true;}
			$sql->close();
		}

		cms_trennen($dbs);


		if ($fehler) {
			echo cms_meldung_unbekannt();
		}
		else {
			$idname = "verwaltung_personen";

			echo "<div class=\"cms_spalte_2\">";
			echo "<div class=\"cms_spalte_i\">";
			echo "<h3>Persönliche Daten</h3>";
			echo "<table class=\"cms_formular\">";
				echo "<tr>";
					echo "<th>Art:</th>";
					echo "<td>";
						if ($profildaten_art == "s") {echo "Schüler";}
						else if ($profildaten_art == "l") {echo "Lehrer";}
						else if ($profildaten_art == "e") {echo "Eltern";}
						else if ($profildaten_art == "v") {echo "Verwaltung";}
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>Titel:</th>";
					echo "<td><input type=\"text\" value=\"$profildaten_titel\" name=\"cms_schulhof_".$idname."_profildaten_titel\" id=\"cms_schulhof_".$idname."_profildaten_titel\"></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>Vorname:</th>";
					echo "<td><input type=\"text\" value=\"$profildaten_vorname\" name=\"cms_schulhof_".$idname."_profildaten_vorname\" id=\"cms_schulhof_".$idname."_profildaten_vorname\"></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>Nachname:</th>";
					echo "<td><input type=\"text\" value=\"$profildaten_nachname\" name=\"cms_schulhof_".$idname."_profildaten_nachname\" id=\"cms_schulhof_".$idname."_profildaten_nachname\"></td>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>Geschlecht:</th>";
					echo "<td><select name=\"cms_schulhof_".$idname."_profildaten_geschlecht\" id=\"cms_schulhof_".$idname."_profildaten_geschlecht\">";
						if ($profildaten_geschlecht == "m") {
							echo '<option selected="selected" value="m">&#x2642;</option>';
							echo '<option value="w">&#x2640;</option>';
							echo '<option value="u">&#x26a5;</option>';
						}
						else if ($profildaten_geschlecht == "w") {
							echo '<option value="m">&#x2642;</option>';
							echo '<option selected="selected" value="w">&#x2640;</option>';
							echo '<option value="u">&#x26a5;</option>';
						}
						else {
							echo '<option value="m">&#x2642;</option>';
							echo '<option value="w">&#x2640;</option>';
							echo '<option selected="selected" value="u">&#x26a5;</option>';
						}
					echo "</select></td>";
				echo "</tr>";
			echo "</table>";

			echo "</div>";
			echo "</div>";


			echo "<div class=\"cms_spalte_2\">";
			echo "<div class=\"cms_spalte_i\">";

			if ($verwaltung) {
				echo "<input type=\"hidden\" name=\"cms_schulhof_verwaltung_personen_profildaten_id\" id=\"cms_schulhof_verwaltung_personen_profildaten_id\" value=\"$id\">";
			}

			echo "</div>";
			echo "</div>";

			echo "<div class=\"cms_clear\"></div>";

			echo "<div class=\"cms_spalte_i\">";
			echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_".$idname."_persoenlich_aendern();\">Änderungen speichern</span> ";
			$link = "Schulhof/Verwaltung/Personen/Details";
			if ($idname == "nutzerkonto") {$link = "Schulhof/Nutzerkonto/Mein_Profil";}
			echo "<a class=\"cms_button_nein\" href=\"$link\">Zurück</a>";
			echo "</p>";
			echo "</div>";
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}



function cms_personenids_aendern($id) {
	$zugriff = true;
	$verwaltung = false;
	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = $CMS_RECHTE['Personen']['Personenids bearbeiten'];
		$verwaltung = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;
		$dbs = cms_verbinden('s');
		$fehler = false;

		$z = $d = $v = null;

		$sql = $dbs->prepare("SELECT COUNT(*), zweitid, drittid, viertid, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE personen.id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($anzahl, $z, $d, $v, $vorname, $nachname, $titel);
			if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($dbs);

		$code = "";

		if ($fehler) {
			$code .= cms_meldung_unbekannt();
		}
		else {
			$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<div class=\"cms_spalte_i\">";
			$code .= "<h3>IDs von ".cms_generiere_anzeigename($vorname, $nachname, $titel)."</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>Schulhof-ID:</th>";
					$code .= "<td><input type=\"text\" value=\"$id\" name=\"cms_personen_shid\" id=\"cms_personen_shid\" disabled=\"disabled\"></td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>Zweit-ID:</th>";
					$code .= "<td><input type=\"text\" value=\"$z\" name=\"cms_personen_zweitid\" id=\"cms_personen_zweitid\"></td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>Dritt-ID:</th>";
					$code .= "<td><input type=\"text\" value=\"$d\" name=\"cms_personen_drittid\" id=\"cms_personen_drittid\"></td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>Viert-ID:</th>";
					$code .= "<td><input type=\"text\" value=\"$v\" name=\"cms_personen_viertid\" id=\"cms_personen_viertid\"></td>";
				$code .= "</tr>";
			$code .= "</table>";

			$code .= "</div>";
			$code .= "</div>";


			$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<div class=\"cms_spalte_i\">";
			$code .= "</div>";
			$code .= "</div>";

			$code .= "<div class=\"cms_clear\"></div>";

			$code .= "<div class=\"cms_spalte_i\">";
			$code .= "<p><span class=\"cms_button\" onclick=\"cms_personen_ids_aendern();\">Änderungen speichern</span> ";
			$code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen\">Zurück</a>";
			$code .= "</p>";
			$code .= "</div>";
		}
	}
	else {
		$code .= cms_meldung_berechtigung();
	}
	echo $code;
}



// Einstellungen ändern
function cms_personaldaten_einstellungen_aendern($id) {
	global $CMS_EINSTELLUNGEN;
	$zugriff = true;
	$verwaltung = false;
	$code = "";

	// Berechtigung prüfen
	if ($id != $_SESSION["BENUTZERID"]) {
		$zugriff = cms_r("schulhof.verwaltung.nutzerkonten.einstellungen.ändern");
		$verwaltung = true;
	}

	if ($zugriff) {
		global $CMS_SCHLUESSEL;
		$dbs = cms_verbinden('s');
		$fehler = false;

		$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(notifikationsmail, '$CMS_SCHLUESSEL') AS notifikationsmail, AES_DECRYPT(postmail, '$CMS_SCHLUESSEL') AS postmail, AES_DECRYPT(postalletage, '$CMS_SCHLUESSEL') AS postalletage, AES_DECRYPT(postpapierkorbtage, '$CMS_SCHLUESSEL') AS postpapierkorbtage, AES_DECRYPT(vertretungsmail, '$CMS_SCHLUESSEL') AS vertretungsmail, AES_DECRYPT(uebersichtsanzahl, '$CMS_SCHLUESSEL') AS uebersichtsanzahl, AES_DECRYPT(inaktivitaetszeit, '$CMS_SCHLUESSEL') AS inaktivitaetszeit, AES_DECRYPT(oeffentlichertermin, '$CMS_SCHLUESSEL') AS oeffentlichertermin, AES_DECRYPT(oeffentlicherblog, '$CMS_SCHLUESSEL') AS oeffentlicherblog, AES_DECRYPT(oeffentlichegalerie, '$CMS_SCHLUESSEL') AS oeffentlichegalerie FROM personen_einstellungen, personen WHERE personen.id = personen_einstellungen.person AND person = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
			$sql->bind_result($vorname, $nachname, $notifikationsmail, $postmail, $postalletage, $postpapierkorbtage, $vertretungsmail, $uebersichtsanzahl, $inaktivitaetszeit, $oeffentlichertermin, $oeffentlicherblog, $oeffentlichegalerie);
			if ($sql->fetch()) {}
				else {$fehler = true;}
			$sql->close();
		}

		cms_trennen($dbs);


		if ($fehler) {
			$code .= cms_meldung_unbekannt();
		}
		else {
			$idname = "nutzerkonto";
			if ($verwaltung) {
				$idname = "verwaltung_personen";
				$code .= "<h2>Nutzerkonto von $vorname $nachname</h2>";
				$code .= "</div>";
			}

			else {
				$code .= "</div>";
			}

			$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<div class=\"cms_spalte_i\">";

			$code .= "<h3>Per eMail benachrichtigen, wenn:</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>neue Nachrichten eingehen</th>";
					$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_postmail", $postmail)."</td>";
				$code .= "</tr>";
				if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == 0) {
					$code .= "<tr>";
						$code .= "<th>neue Vertretungen eingehen</th>";
						$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_vertretungsmail", $vertretungsmail)."</td>";
					$code .= "</tr>";
				}
				$code .= "<tr>";
					$code .= "<th>neue Neuigkeiten eingehen</th>";
					$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_notifikationmail", $notifikationsmail)."</td>";
				$code .= "</tr>";
			$code .= "</table>";

			if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == 1) {
				$code .= "<p><input type=\"hidden\" name=\"cms_schulhof_".$idname."_einstellungen_vertretungsmail\" id=\"cms_schulhof_".$idname."_einstellungen_vertretungsmail\" value=\"0\"></p>";
			}

			$code .= "<h3>Als Neuigkeit werten, wenn:</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>ein öffentlicher Termin erstellt wurde</th>";
					$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_terminoeffentlich", $oeffentlichertermin)."</td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>ein öffentlicher Blogeintrag erstellt wurde</th>";
					$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_blogoeffentlich", $oeffentlicherblog)."</td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>eine öffentliche Galerie erstellt wurde</th>";
					$code .= "<td>".cms_schieber_generieren("schulhof_".$idname."_einstellungen_galerieoeffentlich", $oeffentlichegalerie)."</td>";
				$code .= "</tr>";
			$code .= "</table>";

			if ($verwaltung) {
				$code .= "<input type=\"hidden\" name=\"cms_schulhof_verwaltung_personen_einstellungen_id\" id=\"cms_schulhof_verwaltung_personen_einstellungen_id\" value=\"$id\">";
			}

			$code .= "</div>";
			$code .= "</div>";


			$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<div class=\"cms_spalte_i\">";

			$code .= "<h3>Postfach</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>Nachrichten automatisch löschen nach</th>";
					$code .= "<td><input type=\"number\" class=\"cms_klein\" min=\"1\" max=\"1000\" step=\"1\" name=\"cms_schulhof_".$idname."_einstellungen_alle_tage\" id=\"cms_schulhof_".$idname."_einstellungen_alle_tage\" value=\"$postalletage\" onchange=\"cms_nur_ganzzahl('cms_schulhof_".$idname."_einstellungen_alle_tage', 365)\"> Tagen</td>";
				$code .= "</tr>";
				$code .= "<tr>";
					$code .= "<th>Nachrichten im Papierkorb automatisch löschen nach</th>";
					$code .= "<td><input type=\"number\" class=\"cms_klein\" min=\"0\" max=\"100\" step=\"1\" name=\"cms_schulhof_".$idname."_einstellungen_papierkorb_tage\" id=\"cms_schulhof_".$idname."_einstellungen_papierkorb_tage\" value=\"$postpapierkorbtage\" onchange=\"cms_nur_ganzzahl('cms_schulhof_".$idname."_einstellungen_papierkorb_tage',30)\"> Tagen</td>";
				$code .= "</tr>";
			$code .= "</table>";

			$code .= "<h3>Übersichten</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>Anzahl an Elementen pro Übersicht:</th>";
					$code .= "<td><input type=\"number\" class=\"cms_klein\" min=\"1\" max=\"25\" step=\"1\" name=\"cms_schulhof_".$idname."_einstellungen_uebersichtsanzahl\" id=\"cms_schulhof_".$idname."_einstellungen_uebersichtsanzahl\" value=\"$uebersichtsanzahl\" onchange=\"cms_nur_ganzzahl('cms_schulhof_".$idname."_einstellungen_uebersichtsanzahl', 20)\"></td>";
				$code .= "</tr>";
			$code .= "</table>";



			$code .= "<h3>Profileinstellungen</h3>";
			$code .= "<table class=\"cms_formular\">";
				$code .= "<tr>";
					$code .= "<th>Inaktivitätszeit</th>";
					$code .= "<td><input type=\"number\" class=\"cms_klein\" min=\"5\" max=\"300\" step=\"1\" name=\"cms_schulhof_".$idname."_einstellungen_inaktivitaetszeit\" id=\"cms_schulhof_".$idname."_einstellungen_inaktivitaetszeit\" value=\"$inaktivitaetszeit\" onchange=\"cms_nur_ganzzahl('cms_schulhof_".$idname."_einstellungen_inaktivitaetszeit',30)\"> Minuten</td>";
				$code .= "</tr>";
			$code .= "</table>";

			$code .= "</div>";
			$code .= "</div>";

			$code .= "<div class=\"cms_clear\"></div>";

			$code .= "<div class=\"cms_spalte_i\">";
			$code .= cms_meldung('info', "<p>Die gesamte Kommunikation im Schulhof wird verschlüsselt. Gewöhnliche eMails sind unverschlüsselt. Darum können im Schulhof eintreffende Nachrichten und Notifikationen nicht direkt als eMail weitergeleitet, sondern nur als Benachrichtigung über neue Nachrichten und Notifikationen versendet werden.</p>");

			$code .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_".$idname."_einstellungen_aendern();\">Änderungen speichern</span> ";
			$link = "Schulhof/Verwaltung/Personen/Details";
			if ($idname == "nutzerkonto") {$link = "Schulhof/Nutzerkonto/Mein_Profil";}
			$code .= "<a class=\"cms_button_nein\" href=\"$link\">Zurück</a>";
			$code .= "</p>";
			$code .= "</div>";
		}
	}
	else {
		$code .= cms_meldung_berechtigung();
	}

	return $code;
}


?>
