<?php
function cms_postfach_filter_ausgeben ($modus, $start, $ende, $papierkorb, $fid, $app = "nein") {
	global $CMS_BENUTZERID, $CMS_SCHLUESSEL;
	if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {return "";}
	if (($modus != "eingang") && ($modus != "entwurf") && ($modus != "ausgang")) {return "";}

	$startT = date("d", $start);
	$startM = date("m", $start);
	$startJ = date("Y", $start);
	$endeT = date("d", $ende);
	$endeM = date("m", $ende);
	$endeJ = date("Y", $ende);
	$code = "";

	// Filter ein und ausblenden
	$code .= "<p><span id=\"cms_postfach_filterknopf\" class=\"cms_button\" onclick=\"cms_togglebutton_anzeigen('cms_postfach_filter$fid', 'cms_postfach_filterknopf', 'Nachrichten filtern', 'Filter ausblenden')\">Nachrichten filtern</span></p>";
	$code .= "<div class=\"cms_filter_ein\" id=\"cms_postfach_filter$fid\" style=\"display: none;\">";
	// Tags laden, falls sie existieren
	$tagcode = "";
	$taghiddencode = "";
	$tagids = "";
	$taganzahl = 0;
	$nachrichtenanzahl = 0;
	$dbp = cms_verbinden('p');
	$sql = $dbp->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID) AS tags ORDER BY titel ASC;");
	if ($sql->execute()) {	// Safe weil ID Check
		$sql->bind_result($tagid, $tagtitel);
		while ($sql->fetch()) {
			$tagcode .= "<span class=\"cms_toggle\" id=\"cms_toggle_postfach_filter_tag".$fid."_".$tagid."\" onclick=\"cms_toggle_klasse('cms_toggle_postfach_filter_tag".$fid."_".$tagid."', 'cms_toggle_aktiv', 'cms_postfach_filter_tag".$fid."_".$tagid."', 'true');cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."', '$app');\">".$tagtitel."</span> ";
			$taghiddencode .= "<input name=\"cms_postfach_filter_tag".$fid."_".$tagid."\" id=\"cms_postfach_filter_tag".$fid."_".$tagid."\" type=\"hidden\" value=\"0\">";
			$tagids .= "|".$tagid;
			$taganzahl++;
		}
	}
	$sql->close();
	$sql = $dbp->prepare("SELECT COUNT(*) AS anzahl FROM post$modus"."_$CMS_BENUTZERID WHERE papierkorb = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	$sql->bind_param("s", $papierkorb);
	if ($sql->execute()) {
		$sql->bind_result($nachrichtenanzahl);
		$sql->fetch();
	}
	$sql->close();
	$nachrichtenanzahl = ceil($nachrichtenanzahl / 25);
	cms_trennen($dbp);

	$event = "cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."', '$app');";
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
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_postfach_nachrichten_laden('$modus', '$papierkorb', '".$fid."', '$app');\">Suchen</span></p>";

	$code .= "</div>";

	// Seiten ausgeben
	$code .= "<p id=\"cms_seiten\">";
	if($nachrichtenanzahl > 1) {
		for ($i = 1; $i <= $nachrichtenanzahl; $i++) {
			$code .= "<span class=\"cms_button\" onclick=\"cms_postfach_nachrichten_seite('$fid', '$i', '$modus', '$papierkorb', '$app');\">$i</span> ";
		}
	}
	$code .= "<input name=\"cms_postfach_filter_limit".$fid."\" id=\"cms_postfach_filter_limit".$fid."\" type=\"hidden\" value=\"25\">";
	$code .= "<input name=\"cms_postfach_filter_nummer".$fid."\" id=\"cms_postfach_filter_nummer".$fid."\" type=\"hidden\" value=\"0\">";
	$code .= "</p>";

	return $code;
}


function cms_postfach_nachrichten_listen ($modus, $papierkorb, $start, $ende, $nachname, $vorname, $betreff, $tags, $nummer, $limit, $entfernt = false, $app = "nein") {
	global $CMS_BENUTZERID, $CMS_SCHLUESSEL, $CMS_DBS_DB, $CMS_DBP_DB;
	if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {return "";}
	if (($modus != "eingang") && ($modus != "entwurf") && ($modus != "ausgang")) {return "";}

	$db = cms_verbinden('ü');

	$tabellen = $CMS_DBP_DB.".post$modus"."_".$CMS_BENUTZERID;
	$tabelle = $tabellen;
	$grenze = "";
	$jetzt = time();

	// Suchmuster vorbereiten
	if (strlen($vorname) > 0) {$vorname = cms_texttrafo_e_db($vorname)."%";}
	if (strlen($nachname) > 0) {$nachname = cms_texttrafo_e_db($nachname)."%";}
	if (strlen($betreff) > 0) {$betreff = "%".cms_texttrafo_e_db($betreff)."%";}

	// Laden wie lange Daten im Papierkorb gespeichert werden
	$dbs = cms_verbinden('s');
	$speicherdauer = 30;
	$sql = $dbs->prepare("SELECT AES_DECRYPT(postpapierkorbtage, '$CMS_SCHLUESSEL') AS ptage, AES_DECRYPT(postalletage, '$CMS_SCHLUESSEL') AS atage FROM personen_einstellungen WHERE person = ?;");
	$sql->bind_param("i", $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($ptage, $atage);
		$sql->fetch();
		if ($papierkorb == 1) {$speicherdauer = $ptage;}
		else {$speicherdauer = $atage;}
	}
	$sql->close();
	// Von Tagen zu Sekunden
	$speicherdauer = $speicherdauer*86400;

	$filterbedingungen = "";
	$filterbedingungendanach = "";
	if (strlen($start) > 0) {$filterbedingungen .= "AND zeit > ? ";}
	if (strlen($ende) > 0) {$filterbedingungen .= "AND zeit < ? ";}
	if (strlen($betreff) > 0) {
		$filterbedingungendanach .= "AND UPPER(CONVERT(betreff USING utf8)) LIKE UPPER(?) ";
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
			$filterbedingungendanach .= "AND UPPER(CONVERT(vorname USING utf8)) LIKE UPPER(?) ";
		}
		if (strlen($nachname) > 0) {
			$filterbedingungendanach .= "AND UPPER(CONVERT(nachname USING utf8)) LIKE UPPER(?) ";
		}
	}

	if (strlen($filterbedingungendanach) > 0) {$filterbedingungendanach = "WHERE ".substr($filterbedingungendanach,4);}
	else {
		$grenze = "LIMIT ?, ?";
	}

	if ($modus == 'eingang') {
		$sql = "SELECT * FROM (SELECT absender, $tabelle.id AS id, AES_DECRYPT($tabelle.nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, zeit, AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, papierkorbseit,";
		$sql .= " AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt";
		$sql .= " FROM $tabellen WHERE papierkorb = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') $filterbedingungen) AS n $filterbedingungendanach ORDER BY zeit DESC $grenze";
	}
	else if (($modus == 'entwurf') || ($modus == 'ausgang')) {
		$sql = "SELECT * FROM (SELECT empfaenger, $tabelle.id AS id, AES_DECRYPT($tabelle.nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, zeit, papierkorbseit";
		$sql .= " FROM $tabellen WHERE papierkorb = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') $filterbedingungen) AS n $filterbedingungendanach ORDER BY zeit DESC $grenze";
	}
	else {
		return cms_meldung_bastler();
	}

	// Nachrichten laden
	$code = "";
	$anzahl = 0;
	$sql = $db->prepare($sql);


	if ($modus != 'eingang') {
		if (strlen($betreff) > 0) {
			$sql->bind_param("siis", $papierkorb, $start, $ende, $betreff);
		}
		else {
			$sql->bind_param("siiii", $papierkorb, $start, $ende, $nummer, $limit);
		}
	}
	else {
		if ((strlen($betreff) > 0) && (strlen($vorname) > 0) && (strlen($nachname) > 0)) {
			$sql->bind_param("siisss", $papierkorb, $start, $ende, $betreff, $vorname, $nachname);
		}
		else if ((strlen($betreff) > 0) && (strlen($vorname) > 0) && (strlen($nachname) == 0)) {
			$sql->bind_param("siiss", $papierkorb, $start, $ende, $betreff, $vorname);
		}
		else if ((strlen($betreff) > 0) && (strlen($vorname) == 0) && (strlen($nachname) > 0)) {
			$sql->bind_param("siiss", $papierkorb, $start, $ende, $betreff, $nachname);
		}
		else if ((strlen($betreff) == 0) && (strlen($vorname) > 0) && (strlen($nachname) > 0)) {
			$sql->bind_param("siiss", $papierkorb, $start, $ende, $vorname, $nachname);
		}
		else if ((strlen($betreff) > 0) && (strlen($vorname) == 0) && (strlen($nachname) == 0)) {
			$sql->bind_param("siis", $papierkorb, $start, $ende, $betreff);
		}
		else if ((strlen($betreff) == 0) && (strlen($vorname) > 0) && (strlen($nachname) == 0)) {
			$sql->bind_param("siis", $papierkorb, $start, $ende, $vorname);
		}
		else if ((strlen($betreff) == 0) && (strlen($vorname) == 0) && (strlen($nachname) > 0)) {
			$sql->bind_param("siis", $papierkorb, $start, $ende, $nachname);
		}
		else {
			$sql->bind_param("siiii", $papierkorb, $start, $ende, $nummer, $limit);
		}
	}

	// NACHRICHTEN LADEN
	$NACHRICHTEN = array();
	if ($sql->execute()) {
		if ($modus == "eingang") {
			$sql->bind_result($nabsender, $nid, $nnachricht, $nbetreff, $nzeit, $ngelesen, $npapierkorbseit, $nvorname, $nnachname, $ntitel, $nerstellt);
		}
		else {
			$sql->bind_result($nempfaenger, $nid, $nnachricht, $nbetreff, $nzeit, $npapierkorbseit);
		}
		while ($sql->fetch()) {
			$N = array();
			if ($modus == 'eingang') {
				$N['absender'] = $nabsender;
				$N['gelesen'] = $ngelesen;
				if ($nerstellt <= $nzeit) {
					$N['anzeigename'] = cms_generiere_anzeigename($nvorname, $nnachname, $ntitel);
				}
				else {$N['anzeigename'] = "<i>existiert nicht mehr</i>";}
				$N['erstellt'] = $nerstellt;
			}
			else {
				$N['empfaenger'] = $nempfaenger;
				$N['anzeigename'] = "";
			}
			$N['id'] = $nid;
			$N['tags'] = array();
			$N['nachricht'] = $nnachricht;
			$N['betreff'] = $nbetreff;
			$N['zeit'] = $nzeit;
			$N['papierkorbseit'] = $npapierkorbseit;
			$N['zeigen'] = true;
			array_push($NACHRICHTEN, $N);
		}
	}
	$sql->close();

	// Nachrichten entfernen, falls nicht Eingang und Namensfilter aktiv
	if (($modus != "eingang") && ((strlen($vorname) > 0) || (strlen($nachname) > 0))) {
		for ($n=0; $n < count($NACHRICHTEN); $n++) {
			$gefunden = false;
			$empfliste = "";
			// Valide Empfängerliste erstellen
			if (strlen($NACHRICHTEN[$n]['empfaenger']) > 0) {$empfliste = "(".str_replace('|', ',', substr($NACHRICHTEN[$n]['empfaenger'],1)).")";}
			if (cms_check_idliste($empfliste)) {
				$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, erstellt FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE erstellt < ?) AS personen WHERE personen.id IN $empfliste ";
				if (strlen($vorname) > 0) {$sql .= "AND UPPER(CONVERT(vorname USING utf8)) LIKE UPPER(?) ";}
				if (strlen($nachname) > 0) {$sql .= "AND UPPER(CONVERT(nachname USING utf8)) LIKE UPPER(?)";}

				$sql = $dbs->prepare($sql);
				if ((strlen($vorname) > 0) && (strlen($nachname) > 0)) {
					$sql->bind_param("iss", $NACHRICHTEN[$n]['zeit'], $vorname, $nachname);
				}
				else if (strlen($vorname) > 0) {$sql->bind_param("is", $NACHRICHTEN[$n]['zeit'], $vorname);}
				else if (strlen($nachname) > 0) {$sql->bind_param("is", $NACHRICHTEN[$n]['zeit'], $nachname);}

				if ($sql->execute()) {
					$sql->bind_result($checkanzahl);
					$sql->fetch();
					if ($checkanzahl == 0) {$NACHRICHTEN[$n]['zeigen'] = false;}
				}
				$sql->close();
			}
			else {
				$NACHRICHTEN[$n]['zeigen'] = false;
			}
		}
	}

	// Empfänger laden
	if ($modus != 'eingang') {
		for ($n=0; $n < count($NACHRICHTEN); $n++) {
			$gefunden = false;
			$empfliste = "";
			// Valide Empfängerliste erstellen
			if (strlen($NACHRICHTEN[$n]['empfaenger']) > 0) {$empfliste = "(".str_replace('|', ',', substr($NACHRICHTEN[$n]['empfaenger'],1)).")";}
			if (cms_check_idliste($empfliste)) {
				$sql = "SELECT * FROM (SELECT COUNT(*) AS anzahl, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id IN $empfliste AND erstellt < ?) AS x ORDER BY nachname ASC, vorname ASC;";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("i", $NACHRICHTEN[$n]['zeit']);
				if ($sql->execute()) {
					$sql->bind_result($empfanzahl, $empfvorname, $empfnachname, $empftitel);
					$sql->fetch();
					$NACHRICHTEN[$n]['anzeigename'] = cms_generiere_anzeigename($empfvorname, $empfnachname, $empftitel);
					if ($empfanzahl > 2) {$NACHRICHTEN[$n]['anzeigename'] .= " und ".($empfanzahl - 1)." weitere";}
					else if ($empfanzahl == 2) {$NACHRICHTEN[$n]['anzeigename'] .= " und ".($empfanzahl - 1)." weiterer";}
				}
				$sql->close();
			}
			else {$NACHRICHTEN[$n]['anzeigename'] = "<i>Keiner</i>";}
			if (strlen($NACHRICHTEN[$n]['anzeigename']) == 0) {$NACHRICHTEN[$n]['anzeigename'] = "<i>existiert nicht mehr</i>";}
		}
	}

	// TAGS laden
	$sql = $db->prepare("SELECT farbe FROM $CMS_DBP_DB.posttags_$CMS_BENUTZERID JOIN $CMS_DBP_DB.postgetagged$modus"."_$CMS_BENUTZERID ON id = tag WHERE nachricht = ?");
	for ($n=0; $n < count($NACHRICHTEN); $n++) {
		$sql->bind_param("i", $NACHRICHTEN[$n]['id']);
		if ($sql->execute()) {
			$sql->bind_result($tagfarbe);
			while ($sql->fetch()) {
				$NACHRICHTEN[$n]['tags'][] = $tagfarbe;
			}
		}
	}
	$sql->close();


	// Nachrichten ausgeben
	foreach ($NACHRICHTEN as $N) {
		if ($N['zeigen']) {
			$datum = date ("d.m.Y", $N['zeit']);
			$uhrzeit = date ("H:i", $N['zeit']);
			$code .= "<tr>";


			$markierungv = "";
			$markierungh = "";
			$anzeigename = "";
			$icon = "";
			// Je nach Postfachordner Absender/Empfänger/Icons laden
			// Prüfen, ob Anhänge vorhanden sind
			$groesse = 0;
			$pfad = "dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$N['id'];
			if ($entfernt) {$pfad = "../../../".$pfad;}
			if (is_dir($pfad)) {
				$ordner = cms_dateisystem_ordner_info($pfad);
				$groesse = $ordner['groesse'];
			}
			if ($groesse > 0) {$icon = 'postanhang';} else {$icon = 'postnachricht';}
			$icon = "<img src=\"res/icons/klein/$icon.png\">";

			if ($modus == "eingang") {
				if ($N['gelesen'] == '-') {$markierungv = '<b>'; $markierungh = '</b>';}
			}

			$tags = "<div style=\"position: absolute; top: 0; left: -2px;height: 100%\">";
			foreach($N['tags'] as $tag) {
				$tags .= "<span style=\"width: 2px; height: ".(100/count($N['tags']))."%; display: block\" class=\"cms_farbbeispiel_$tag\"></span>";
			}
			$tags .= "</div>";

			$klasse = "";
			if($app != 'app') {
				$klasse .= "cms_multiselect";
			}
			$code .= "<td class=\"$klasse\" style=\"position: relative\">".$tags.$icon."</td>";
			$betreffevent = cms_texttrafo_e_event($N['betreff']);
			$lesen = "cms_postfach_nachricht_lesen('$modus', '".$N['anzeigename']."', '".$betreffevent."', '".$datum."', '".$uhrzeit."', '".$N['id']."', '$app')";
			$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$markierungv.$N['anzeigename'].$markierungh."</td>";
			$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$markierungv.$N['betreff'].$markierungh."</td>";
			$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$datum."</td>";
			$code .= "<td onclick=\"$lesen\" class=\"cms_postfach_nachricht_lesen\">".$uhrzeit."</td>";

			if ($app != 'app') {
				//Speicherfrist als Icon ausgeben
				if ($papierkorb == 1) {$rest = $N['papierkorbseit'] + $speicherdauer - $jetzt;}
				else {$rest = $N['zeit'] + $speicherdauer - $jetzt;}
				$hoehe = $rest/$speicherdauer*100;
				$style = 'height: '.$hoehe.'%;';
				$styleaussen = '';
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
					$styleaussen = 'background: inherit;';
					$verbleibend = "verschwindet mit der Abmeldung";
				}

				$speicherfrist = "<span class=\"cms_postfach_papierkorb_aussen\" style=\"$styleaussen\"><span class=\"cms_postfach_papierkorb_innen\" style=\"$style\"></span><span class=\"cms_hinweis\">$verbleibend</span></span>";

				// Aktionen
				$code .= "<td>";
				if ($modus == "eingang") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('antworten', ".$N['id'].", 'eingang')\"><span class=\"cms_hinweis\">Antworten</span><img src=\"res/icons/klein/postfach_antworten.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('allenantworten', ".$N['id'].", 'eingang')\"><span class=\"cms_hinweis\">Allen antworten</span><img src=\"res/icons/klein/postfach_allenantworten.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('weiterleiten', ".$N['id'].", 'eingang')\"><span class=\"cms_hinweis\">Weiterleiten</span><img src=\"res/icons/klein/postfach_weiterleiten.png\"></span> ";
				}
				else if ($modus == "entwurf") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', ".$N['id'].", 'entwurf')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/postfach_bearbeiten.png\"></span> ";
				}
				else if ($modus == "ausgang") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('erneut', ".$N['id'].", 'ausgang')\"><span class=\"cms_hinweis\">Erneut versenden</span><img src=\"res/icons/klein/postfach_erneut.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', ".$N['id'].", 'ausgang')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/postfach_bearbeiten.png\"></span> ";
				}
				$loeschendatum = date('d.m.Y H:i', $N['zeit']);
				if ($papierkorb == "-") {
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_postfach_nachricht_papierkorb_anzeige('$modus', '".$betreffevent."', '".$loeschendatum."', ".$N['id'].")\"><span class=\"cms_hinweis\">In den Papierkorb</span><img src=\"res/icons/klein/papierkorb.png\"></span> ";
				}
				else {
					$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_schulhof_postfach_nachricht_zuruecklegen('$modus', '".$betreffevent."', '".$loeschendatum."', ".$N['id'].")\"><span class=\"cms_hinweis\">Zurücklegen</span><img src=\"res/icons/klein/zuruecklegen.png\"></span> ";
					$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_postfach_nachricht_loeschen_anzeige('$modus', '".$betreffevent."', '".$loeschendatum."', ".$N['id'].")\"><span class=\"cms_hinweis\">Endgültig löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}
				$code .= $speicherfrist;
				$code .= "<input type=\"hidden\" class=\"cms_nachricht_id\" value=\"{$N['id']}\">";
				$code .= "</td>";
			}
			$code .= "</tr>";
		}
	}

	if($app != 'app' && count($NACHRICHTEN) > 0) {
		$code .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"6\">";
		$code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_multiselect_schulhof_postfach_nachrichten_taggen_anzeigen('$papierkorb', '$modus', '1')\"><span class=\"cms_hinweis\">Alle Nachrichten taggen</span><img src=\"res/icons/klein/tag.png\"></span> ";
		$code .= "<span class=\"cms_aktion_klein\" onclick=\"cms_multiselect_schulhof_postfach_nachrichten_taggen_anzeigen('$papierkorb', '$modus', '0')\"><span class=\"cms_hinweis\">Alle Nachrichten enttaggen</span><img src=\"res/icons/klein/tag.png\"></span> ";
		if ($papierkorb == "-") {
			$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_schulhof_postfach_nachricht_papierkorb_anzeige('$modus')\"><span class=\"cms_hinweis\">Alle in den Papierkorb</span><img src=\"res/icons/klein/papierkorb.png\"></span> ";
		}
		else {
			$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_multiselect_schulhof_postfach_nachricht_zuruecklegen('$modus')\"><span class=\"cms_hinweis\">Alle zurücklegen</span><img src=\"res/icons/klein/zuruecklegen.png\"></span> ";
			$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_schulhof_postfach_nachricht_loeschen_anzeige('$modus')\"><span class=\"cms_hinweis\">Alle endgültig löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
		}
		$code .= "</td></tr>";
	}
	cms_trennen($dbs);
	cms_trennen($db);


	if ($code == "") {return "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";}
	else {return $code;}

}
?>
