<?php
function cms_postfach_filter_ausgeben ($modus, $start, $ende, $papierkorb, $fid) {
	global $CMS_BENUTZERID, $CMS_SCHLUESSEL;
	$startT = date("d", $start);
	$startM = date("m", $start);
	$startJ = date("Y", $start);
	$endeT = date("d", $ende);
	$endeM = date("m", $ende);
	$endeJ = date("Y", $ende);
	$code = "";

	// Filter ein und ausblenden
	$code .= "<p><span id=\"cms_postfach_filterknopf\" class=\"cms_button\" onclick=\"cms_togglebutton_anzeigen('cms_postfach_filter', 'cms_postfach_filterknopf', 'Nachrichten filtern', 'Filter auslblenden')\">Nachrichten filtern</span></p>";
	$code .= "<div class=\"cms_filter_ein\" id=\"cms_postfach_filter\" style=\"display: none;\">";
	// Tags laden, falls sie existieren
	$tagcode = "";
	$taghiddencode = "";
	$tagids = "";
	$taganzahl = 0;
	$nachrichtenanzahl = 0;
	$dbp = cms_verbinden('p');
	$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID) AS tags ORDER BY titel ASC;";
	if ($anfrage = $dbp->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$tagcode .= "<span class=\"cms_toggle\" id=\"cms_toggle_postfach_filter_tag".$fid."_".$daten['id']."\" onclick=\"cms_toggle_klasse('cms_toggle_postfach_filter_tag".$fid."_".$daten['id']."', 'cms_toggle_aktiv', 'cms_postfach_filter_tag".$fid."_".$daten['id']."', 'true');cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."');\">".$daten['titel']."</span> ";
			$taghiddencode .= "<input name=\"cms_postfach_filter_tag".$fid."_".$daten['id']."\" id=\"cms_postfach_filter_tag".$fid."_".$daten['id']."\" type=\"hidden\" value=\"0\">";
			$tagids .= "|".$daten['id'];
			$taganzahl++;
		}
		$anfrage->free();
	}
	$sql = "SELECT COUNT(*) AS anzahl FROM post$modus"."_$CMS_BENUTZERID WHERE papierkorb = AES_ENCRYPT('$papierkorb', '$CMS_SCHLUESSEL')";
	if ($anfrage = $dbp->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			$nachrichtenanzahl = $daten['anzahl'];
		}
		$anfrage->free();
	}
	$nachrichtenanzahl = ceil($nachrichtenanzahl / 25);
	cms_trennen($dbp);

	$event = "cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."');";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
			$code .= "<th style=\"width:50%;\"><b>Nachname</b></th>";
			$code .= "<th style=\"width:50%;\"><b>Vorname</b></th>";
		$code .= "</tr>";
		$code .= "<tr>";
			$code .= "<td><input name=\"cms_postfach_filter_nachname".$fid."\" id=\"cms_postfach_filter_nachname".$fid."\" type=\"text\" onkeyup=\"$event\"></td>";
			$code .= "<td><input name=\"cms_postfach_filter_vorname".$fid."\" id=\"cms_postfach_filter_vorname".$fid."\" type=\"text\" onkeyup=\"$event\"></td>";
		$code .= "</tr>";
		$code .= "<tr>";
			$code .= "<th colspan=\"2\"><b>Betreff</b></th>";
		$code .= "</tr>";
		$code .= "<tr>";
			$code .= "<td colspan=\"2\"><input name=\"cms_postfach_filter_betreff".$fid."\" id=\"cms_postfach_filter_betreff".$fid."\" type=\"text\" onkeyup=\"$event\"></td>";
		$code .= "</tr>";
		$code .= "<tr>";
			$code .= "<td colspan=\"2\"><b>Zeitraum</b></td>";
		$code .= "</tr>";
		$code .= "<tr>";
			$code .= "<td colspan=\"2\">".cms_datum_eingabe("cms_postfach_filter_zeitraumv".$fid, $startT, $startM, $startJ, $event)." – ";
			$code .= cms_datum_eingabe("cms_postfach_filter_zeitraumb".$fid, $endeT, $endeM, $endeJ, $event)."</td>";
		$code .= "</tr>";

		if ($taganzahl > 0) {
			$code .= "<tr><th colspan=\"2\"><b>Tags</b></th></tr>";
			$code .= "<tr><td colspan=\"2\">".$tagcode.$taghiddencode."</td></tr>";
		}
	$code .= "</table>";

	$code .= "<input name=\"cms_postfach_filter_tags".$fid."\" id=\"cms_postfach_filter_tags".$fid."\" type=\"hidden\" value=\"$tagids\">";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."');\">Suchen</span></p>";

	$code .= "</div>";

	// Seiten ausgeben
	$code .= "<p id=\"cms_seiten\">";
	for ($i = 1; $i<= $nachrichtenanzahl; $i++) {
		$code .= "<span class=\"cms_button\" onclick=\"cms_postfach_nachrichten_seite('$fid', '$i', '$modus', '$papierkorb');\">$i</span> ";
	}
	$code .= "<input name=\"cms_postfach_filter_limit".$fid."\" id=\"cms_postfach_filter_limit".$fid."\" type=\"hidden\" value=\"25\">";
	$code .= "<input name=\"cms_postfach_filter_nummer".$fid."\" id=\"cms_postfach_filter_nummer".$fid."\" type=\"hidden\" value=\"0\">";
	$code .= "</p>";

	return $code;
}


function cms_postfach_nachrichten_listen ($modus, $papierkorb, $start, $ende, $nachname, $vorname, $betreff, $tags, $nummer, $limit, $entfernt = false) {
	global $CMS_BENUTZERID, $CMS_SCHLUESSEL, $CMS_DBS_DB, $CMS_DBP_DB;

	$db = cms_verbinden('ü');

	$tabellen = $CMS_DBP_DB.".post$modus"."_".$CMS_BENUTZERID;
	$tabelle = $tabellen;
	$grenze = "";
	$jetzt = time();

	// Laden wie lange Daten im Papierkorb gespeichert werden
	$dbs = cms_verbinden('s');
	$speicherdauer = 30;
	$sql = "SELECT AES_DECRYPT(postpapierkorbtage, '$CMS_SCHLUESSEL') AS ptage, AES_DECRYPT(postalletage, '$CMS_SCHLUESSEL') AS atage FROM personen_einstellungen WHERE person = $CMS_BENUTZERID;";
	if ($anfrage = $dbs->query($sql)) {
		if ($daten = $anfrage->fetch_assoc()) {
			if ($papierkorb == 1) {$speicherdauer = $daten['ptage'];}
			else {$speicherdauer = $daten['atage'];}
		}
		$anfrage -> free();
	}
	// Von Tagen zu Sekunden
	$speicherdauer = $speicherdauer*86400;

	$filterbedingungen = "";
	$filterbedingungendanach = "";
	if (strlen($start) > 0) {$filterbedingungen .= "AND zeit > $start ";}
	if (strlen($ende) > 0) {$filterbedingungen .= "AND zeit < $ende ";}
	if (strlen($betreff) > 0) {
		$betreff = cms_texttrafo_e_db($betreff);
		$filterbedingungendanach .= "AND UPPER(CONVERT(betreff USING utf8)) LIKE UPPER('%$betreff%') ";
	}

	// TAGS als Bedinung einfügen
	if (strlen($tags) > 0) {
		$tagfilter = str_replace("|", ",", substr($tags, 1));
		$filterbedingungen .= "AND tag IN ($tagfilter) ";
		$tabellen .= " JOIN $CMS_DBP_DB.postgetagged$modus"."_$CMS_BENUTZERID ON $CMS_DBP_DB.postgetagged$modus"."_$CMS_BENUTZERID.nachricht = ".$tabelle.".id ";
	}

	// Beim Posteingang kann direkt gefiltert werden
	if ($modus == 'eingang') {
		$tabellen .= " LEFT JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON absender = $CMS_DBS_DB.nutzerkonten.id";

		if (strlen($vorname) > 0) {
			$vorname = cms_texttrafo_e_db($vorname);
			$filterbedingungendanach .= "AND UPPER(CONVERT(vorname USING utf8)) LIKE UPPER('$vorname%') ";
		}
		if (strlen($nachname) > 0) {
			$nachname = cms_texttrafo_e_db($nachname);
			$filterbedingungendanach .= "AND UPPER(CONVERT(nachname USING utf8)) LIKE UPPER('$nachname%') ";
		}
	}

	if (strlen($filterbedingungen) > 0) {$filterbedingungen = $filterbedingungen;}
	if (strlen($filterbedingungendanach) > 0) {$filterbedingungendanach = "WHERE ".substr($filterbedingungendanach,4);}
	else {$grenze = "LIMIT $nummer, $limit";}


	if ($modus == 'eingang') {
		$sql = "SELECT * FROM (SELECT absender, $tabelle.id AS id, AES_DECRYPT($tabelle.nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, zeit, AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, papierkorbseit,";
		$sql .= " AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt";
		$sql .= " FROM $tabellen WHERE papierkorb = AES_ENCRYPT('$papierkorb', '$CMS_SCHLUESSEL') $filterbedingungen) AS n $filterbedingungendanach ORDER BY zeit DESC $grenze";
	}
	else if (($modus == 'entwurf') || ($modus == 'ausgang')) {
		$sql = "SELECT * FROM (SELECT empfaenger, $tabelle.id AS id, AES_DECRYPT($tabelle.nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, zeit, papierkorbseit";
		$sql .= " FROM $tabellen WHERE papierkorb = AES_ENCRYPT('$papierkorb', '$CMS_SCHLUESSEL') $filterbedingungen) AS n $filterbedingungendanach ORDER BY zeit DESC $grenze";
	}
	else {
		return cms_meldung_bastler();
	}

	// Nachrichten laden
	$code = "";
	$anzahl = 0;
	if ($anfrage = $db->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$zeigen = true;
			if (($modus != "eingang") && ((strlen($vorname) > 0) || (strlen($nachname) > 0))) {
				$gefunden = false;
				$empfsql = "erstellt < ".$daten['zeit']." ";
				if (strlen($daten['empfaenger']) > 0) {$empfsql = "AND personen.id IN (".str_replace('|', ',', substr($daten['empfaenger'],1)).") ";}
				$personenfilter = "";
				if (strlen($vorname) > 0) {$personenfilter .= "AND UPPER(CONVERT(vorname USING utf8)) LIKE UPPER('$vorname%') ";}
				if (strlen($nachname) > 0) {$personenfilter .= "AND UPPER(CONVERT(nachname USING utf8)) LIKE UPPER('$nachname%') ";}
				$personenfilter = substr($personenfilter, 4);

				$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, erstellt FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE $empfsql) AS personen WHERE $personenfilter;";
				if ($check = $dbs->query($sql)) {
					if ($zahl = $check->fetch_assoc()) {
						if ($zahl['anzahl'] > 0) {$gefunden = true;}
					}
					$check->free();
				}
				if (!$gefunden) {$zeigen = false;}
			}

			// Nachricht ausgeben
			if ($zeigen) {
				$datum = date ("d.m.Y", $daten['zeit']);
				$uhrzeit = date ("H:i", $daten['zeit']);
				$code .= "<tr onmouseover=\"cms_einblenden('cms_postfach_vorschau_".$modus."_".$daten['id']."', 'table-row')\" onmouseout=\"cms_ausblenden('cms_postfach_vorschau_".$modus."_".$daten['id']."')\">";

				$markierungv = "";
				$markierungh = "";
				$anzeigename = "";
				$icon = "";
				// Je nach Postfachordner Absender/Empfänger/Icons laden
				// Prüfen, ob Anhänge vorhanden sind
				$groesse = 0;
				$pfad = "dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$daten['id'];
				if ($entfernt) {$pfad = "../../../".$pfad;}
				if (is_dir($pfad)) {
					$ordner = cms_dateisystem_ordner_info($pfad);
					$groesse = $ordner['groesse'];
				}
				if ($groesse > 0) {$icon = 'postanhang';} else {$icon = 'postnachricht';}
				$icon = "<img src=\"res/icons/klein/$icon.png\">";

				if ($modus == "eingang") {
					// Absender laden
					if ($daten['erstellt'] < $daten['zeit']) {$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);}
					else {$anzeigename = "<i>existiert nicht mehr</i>";}
					if ($daten['gelesen'] == '-') {$markierungv = '<b>'; $markierungh = '</b>';}
				}

				// Empfänger laden
				if ($modus != "eingang") {
					if (strlen($daten['empfaenger']) > 0) {
						$empfaengersql = '('.str_replace('|', ',', substr($daten['empfaenger'], 1)).')';
						$sql = "SELECT * FROM (SELECT COUNT(*) AS anzahl, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id IN $empfaengersql AND erstellt < ".$daten['zeit'].") AS x ORDER BY nachname ASC, vorname ASC;";
						if ($name = $dbs->query($sql)) {
							if ($namdat = $name->fetch_assoc()) {
								$anzeigename = cms_generiere_anzeigename($namdat['vorname'], $namdat['nachname'], $namdat['titel']);
								if ($namdat['anzahl'] > 2) {$anzeigename .= " und ".($anzahl - 1)." weitere";}
								else if ($namdat['anzahl'] == 2) {$anzeigename .= " und ".($anzahl - 1)." weiterer";}
							}
							$name->free();
						}
					}
					else {$anzeigename = "<i>Keiner</i>";}
					if (strlen($anzeigename) == 0) {$anzeigename = "<i>existiert nicht mehr</i>";}
				}

				$code .= "<td>".$icon."</td>";
				$betreffevent = cms_texttrafo_e_event($daten['betreff']);
				$lesen = "cms_postfach_nachricht_lesen('$modus', '".$anzeigename."', '".$betreffevent."', '".$datum."', '".$uhrzeit."', '".$daten['id']."')";
				$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$markierungv.$anzeigename.$markierungh."</td>";
				$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$markierungv.$daten['betreff'].$markierungh."</td>";
				$code .= "<td>".$datum."</td>";
				$code .= "<td>".$uhrzeit."</td>";

				//Speicherfrist als Icon ausgeben
				if ($papierkorb == 1) {$rest = $daten['papierkorbseit'] + $speicherdauer - $jetzt;}
				else {$rest = $daten['zeit'] + $speicherdauer - $jetzt;}
				$hoehe = $rest/$speicherdauer*100;
				$style = 'height: '.$hoehe.'%;';
				if ($rest > 0) {
					$vertage = floor($rest / 86400);
					$rest = $rest - ($vertage*86400);
					$verstunden = floor($rest / 3600);
					$rest = $rest - ($verstunden*3600);
					$verminuten = floor($rest / 60);
					if ($vertage > 0) {
						$verbleibend = "noch ".$vertage." Tage, ".$verstunden." Stunden, ".$verminuten." Minuten";
					}
					else if ($verstunden > 0) {
						$verbleibend = "noch ".$verstunden." Stunden, ".$verminuten." Minuten";
					}
					else if ($verminuten > 0) {
						$verbleibend = "noch ".$verminuten." Minuten";
					}
					else {
						$verbleibend = "nur noch Sekunden";
					}
				}
				else {
					$style = 'height: 100%; background: #000000;';
					$verbleibend = "mit der Abmeldung";
				}

				$speicherfrist = "<span class=\"cms_postfach_papierkorb_aussen\"><span class=\"cms_postfach_papierkorb_innen\" style=\"$style\"></span><span class=\"cms_hinweis\">$verbleibend</span></span>";

				// Aktionen
				$code .= "<td>";
				if ($modus == "eingang") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('antworten', ".$daten['id'].", 'eingang')\"><span class=\"cms_hinweis\">Antworten</span><img src=\"res/icons/klein/postfach_antworten.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('allenantworten', ".$daten['id'].", 'eingang')\"><span class=\"cms_hinweis\">Allen antworten</span><img src=\"res/icons/klein/postfach_allenantworten.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('weiterleiten', ".$daten['id'].", 'eingang')\"><span class=\"cms_hinweis\">Weiterleiten</span><img src=\"res/icons/klein/postfach_weiterleiten.png\"></span> ";
				}
				else if ($modus == "entwurf") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', ".$daten['id'].", 'entwurf')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/postfach_bearbeiten.png\"></span> ";
				}
				else if ($modus == "ausgang") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('erneut', ".$daten['id'].", 'ausgang')\"><span class=\"cms_hinweis\">Erneut versenden</span><img src=\"res/icons/klein/postfach_erneut.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', ".$daten['id'].", 'ausgang')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/postfach_bearbeiten.png\"></span> ";
				}
				$loeschendatum = date('d.m.Y H:i', $daten['zeit']);
				if ($papierkorb == "-") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_postfach_nachricht_papierkorb_anzeige('$modus', '".$betreffevent."', '".$loeschendatum."', ".$daten['id'].")\"><span class=\"cms_hinweis\">In den Papierkorb</span><img src=\"res/icons/klein/papierkorb.png\"></span> ";
				}
				else {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_zuruecklegen('$modus', '".$betreffevent."', '".$loeschendatum."', ".$daten['id'].")\"><span class=\"cms_hinweis\">Zurücklegen</span><img src=\"res/icons/klein/zuruecklegen.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_postfach_nachricht_loeschen_anzeige('$modus', '".$betreffevent."', '".$loeschendatum."', ".$daten['id'].")\"><span class=\"cms_hinweis\">Endgültig löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}
				$code .= $speicherfrist;
				$code .= "</td>";
				$code .= "</tr>";

				// TAGS suchen
				$tagcode = "";
				$sql = "SELECT farbe FROM $CMS_DBP_DB.posttags_$CMS_BENUTZERID JOIN $CMS_DBP_DB.postgetagged$modus"."_$CMS_BENUTZERID ON id = tag WHERE nachricht = ".$daten['id'];
				if ($taga = $db->query($sql)) {
					while ($tagd = $taga->fetch_assoc()) {
						$tagcode .= "<span class=\"cms_tag_klein cms_farbbeispiel_".$tagd['farbe']."\"></span>";
					}
					$taga->free();
				}

				// VORSCHAU
				$code .= "<tr class=\"cms_postfach_vorschau\" id=\"cms_postfach_vorschau_".$modus."_".$daten['id']."\">";
				$code .= "<td colspan=\"6\">";
				// Vorschau
				$nachricht = strip_tags($daten['nachricht']);
				if (strlen($nachricht) > 150) {$nachricht = substr($nachricht,0,150)."...";}
				$code .= "<p class=\"cms_notiz\">".$tagcode." ".$nachricht."</p>";
				$code .= "</td>";
				$code .= "</tr>";
			}
		}
		$anfrage->free();
	}



	cms_trennen($dbs);
	cms_trennen($db);


	if ($code == "") {return "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";}
	else {return $code;}

}
?>
