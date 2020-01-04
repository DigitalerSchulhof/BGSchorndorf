<?php
function cms_termin_link_ausgeben($dbs, $daten, $internvorlink = "") {
	global $CMS_URL, $CMS_GRUPPEN, $CMS_SCHLUESSEL;
	// Interne Termine werden nicht öffentlich ausgegeben
	if (($CMS_URL[0] == 'Website') &&
	    (($daten['art'] != 'oe') && ($daten['art'] != 'f') && ($daten['art'] != 'b') && ($daten['art'] != 't') && ($daten['art'] != 's')))
		{return "";}

	$code = "";
	$bezeichnunglink = cms_textzulink($daten['bezeichnung']);

	$zeiten = cms_termin_zeiten($daten);

	$code .= "<li>";
	$link = "";
	if ($CMS_URL[0] == 'Website') {$link = "Website/Termine/";}
	else if ($CMS_URL[0] == 'Schulhof') {
		if ($daten['art'] == 'oe') {$link = "Schulhof/Termine/";}
		else if ($daten['art'] == 'in') {$link = $internvorlink."/Termine/";}
	}
	$link .= $zeiten['jahrb']."/".$zeiten['monatnameb']."/".$zeiten['tagb']."/$bezeichnunglink";
	if ($daten['genehmigt'] == 0) {$zusatzklasse = " cms_nicht_genehmigt";} else {$zusatzklasse = "";}

	// Override für Ferien
	if (($daten['art'] == 'f') || ($daten['art'] == 'b') || ($daten['art'] == 't') || ($daten['art'] == 's')) {
		$link = $CMS_URL[0]."/Ferien/".date('Y', $daten['beginn']);
	}

	$code .= "<a class=\"cms_terminlink$zusatzklasse\" href=\"$link\"><div class=\"cms_terminlinkinnen\">";
	$code .= cms_termin_kalenderblatterzeugen($daten, $zeiten);
	$code .= "<p class=\"cms_notiz\">".cms_termin_dauernotiz($daten, $zeiten)."</p>";

	$code .= "<h3>".$daten['bezeichnung']."</h3>";

	// Prüfen, ob Downloads vorliegen
	$downloadanzahl = 0;
	if ($daten['art'] == 'oe') {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM terminedownloads WHERE termin = ?");
		$sql->bind_param("i", $daten['id']);
		if ($sql->execute()) {
			$sql->bind_result($downloadanzahl);
			$sql->fetch();
		}
		$sql->close();
	}

	if ((strlen($daten['text']) > 7) || ($downloadanzahl > 0)) {
		$code .= "<p><span class=\"cms_button\" href=\"$link\">Weiterlesen ...</span></p>";
	}

	$code .= cms_termin_zusatzinfo($dbs, $daten);
	$code .= "</div></a>";
	$code .= "</li>";

	return $code;
}

function cms_termin_zusatzinfo($dbs, $daten) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "";

	/*if ($CMS_URL[0] == 'Schulhof') {
		if ($daten['art'] == 'oe') {$hinweis = 'öffentlich';}
		else {$hinweis = 'gruppenintern';}
		$code .= "<span class=\"cms_icon_klein_o cms_gruppen_oeffentlich_art\"><span class=\"cms_hinweis\">$hinweis</span><span class=\"cms_".$daten['art']."\"></span></span>";
	}*/

	if ($daten['art'] == 'in') {
		$code .= "<span class=\"cms_kalender_zusatzinfo cms_kalender_zusatzinfo_intern\">Intern</span> ";
	}

	if ($daten['ortt'] == 1) {
		$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/ort.png')\">".$daten['ort']."</span> ";
	}
	// Bei öffentlichen Terminen zugehörige Kategorien suchen
	if ($daten['art'] == 'oe') {
		$sql = "";
		foreach ($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			$sqlsolo =
			$sql .= " UNION (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."termine ON $gk.id = $gk"."termine.gruppe WHERE termin = ?)";
		}
		$sql = substr($sql, 7);
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC");
		$sql->bind_param("iiiiiiiiiii", $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id']);
		if ($sql->execute()) {
			$sql->bind_result($tbez, $ticon);
			while ($sql->fetch()) {
				$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/$ticon')\">$tbez</span> ";
			}
		}
		$sql->close();
	}
	if (($daten['art'] == 'f') || ($daten['art'] == 'b') || ($daten['art'] == 't') || ($daten['art'] == 's')) {
		if ($daten['art'] == 'f') {$icon = 'ferien.png'; $feriengruppe = "Ferien";}
		else if ($daten['art'] == 'b') {$icon = 'beweglicherferientag.png'; $feriengruppe = "Bewegliche Ferientage";}
		else if ($daten['art'] == 't') {$icon = 'feiertag.png'; $feriengruppe = "Feiertag";}
		else if ($daten['art'] == 's') {$icon = 'sonderereignis.png'; $feriengruppe = "Sonderereignis";}
		$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/$icon')\">".$feriengruppe."</span> ";
	}
	if (strlen($code > 0)) {$code = "<p>".$code."</p>";}
	return $code;
}

function cms_termin_kalenderblatterzeugen($daten, $zeiten) {
	$code = "<span class=\"cms_kalenderblaetter\">";
	$code .= "<span class=\"cms_kalenderblatt\">";
	$code .= "<span class=\"cms_kalenderblatt_i\">";
		$code .= "<span class=\"cms_kalenderblatt_monat\">".cms_monatsname($zeiten['monatb'])."</span>";
		$code .= "<span class=\"cms_kalenderblatt_tagnr\">".$zeiten['tagb']."</span>";
		$code .= "<span class=\"cms_kalenderblatt_tagbez\">".cms_tagname($zeiten['wochentagb'])."</span>";
	$code .= "</span>";
		/*if ($daten['uhrzeitbt'] == 1) {
			$code .= "<span class=\"cms_kalenderblatt_uhrzet\">";
			if ($daten['uhrzeitet'] == 0) {$code .= "ab ";}
			$code .= date('H:i', $daten['beginn']);
			if (($daten['uhrzeitet'] == 1) && ($daten['mehrtaegigt'] == 0)) {$code .= " – ".date('H:i', $daten['ende']);}
			$code .= "</span>";
		}*/
	$code .= "</span>";
	if ($daten['mehrtaegigt'] == 1) {
		$code .= " <span class=\"cms_kalenderblatt\">";
		$code .= "<span class=\"cms_kalenderblatt_i\">";
			$code .= "<span class=\"cms_kalenderblatt_monat\">".cms_monatsname($zeiten['monate'])."</span>";
			$code .= "<span class=\"cms_kalenderblatt_tagnr\">".$zeiten['tage']."</span>";
			$code .= "<span class=\"cms_kalenderblatt_tagbez\">".cms_tagname($zeiten['wochentage'])."</span>";
		$code .= "</span>";
			/*if ($daten['uhrzeitet'] == 1) {
				$code .= "<span class=\"cms_kalenderblatt_uhrzet\">";
				if ($daten['uhrzeitbt'] == 0) {$code .= "bis ";}
				$code .= date('H:i', $daten['ende'])."</span>";
			}*/
		$code .= "</span>";
	}
	$code .= "</span>";
	return $code;
}

function cms_termin_dauernotiz($daten, $zeiten) {
	$code = cms_tagname($zeiten['wochentagb'])." ".$zeiten['tagb'].". ".cms_monatsnamekomplett($zeiten['monatb']);
	if ($daten['uhrzeitbt'] == 1) {
		if ($daten['uhrzeitet'] == 0) {$code .= " ab";}
		$code .= " ".date('H:i', $daten['beginn']);
	}
	if ($daten['mehrtaegigt'] == 1) {
		$code .= " ".cms_tagname($zeiten['wochentage'])." ".$zeiten['tage'].". ".cms_monatsnamekomplett($zeiten['monate']);
		if ($daten['uhrzeitet'] == 1) {
			if ($daten['uhrzeitbt'] == 0) {$code .= " bis";}
			$code .= " ".date('H:i', $daten['ende']);
		}
	}
	else if (($daten['uhrzeitbt'] == 1) && ($daten['uhrzeitet'] == 1)) {
		$code .= " – ".date('H:i', $daten['ende']);
	}
	if (($daten['uhrzeitbt'] == 0) && ($daten['uhrzeitet'] == 0)) {$code .= " ganztägig";}
	return $code;
}

function cms_termin_dauerdetail($daten, $zeiten) {
	$code = cms_tagnamekomplett($zeiten['wochentagb']).", ".$zeiten['tagb'].". ".cms_monatsnamekomplett($zeiten['monatb'])." ".$zeiten['jahrb'];
	if ($daten['uhrzeitbt'] == 1) {
		if ($daten['uhrzeitet'] == 0) {$code .= " ab";}
		$code .= " ".date('H:i', $daten['beginn']);
	}
	if ($daten['mehrtaegigt'] == 1) {
		$code .= "<br>".cms_tagnamekomplett($zeiten['wochentage']).", ".$zeiten['tage'].". ".cms_monatsnamekomplett($zeiten['monate'])." ".$zeiten['jahre'];
		if ($daten['uhrzeitet'] == 1) {
			if ($daten['uhrzeitbt'] == 0) {$code .= " bis";}
			$code .= " ".date('H:i', $daten['ende']);
		}
	}
	else if (($daten['uhrzeitbt'] == 1) && ($daten['uhrzeitet'] == 1)) {
		$code .= " – ".date('H:i', $daten['ende']);
	}
	if (($daten['uhrzeitbt'] == 0) && ($daten['uhrzeitet'] == 0)) {$code .= "<br>ganztägig";}
	return $code;
}

function cms_termin_zeiten($daten) {
	$zeiten['jahrb'] = date('Y', $daten['beginn']);
	$zeiten['monatb'] = date('m', $daten['beginn']);
	$zeiten['monatnameb'] = cms_monatsnamekomplett($zeiten['monatb']);
	$zeiten['tagb'] = date('d', $daten['beginn']);
	$zeiten['wochentagb'] = date('w', $daten['beginn']);
	$zeiten['jahre'] = date('Y', $daten['ende']);
	$zeiten['monate'] = date('m', $daten['ende']);
	$zeiten['monatnamee'] = cms_monatsnamekomplett($zeiten['monate']);
	$zeiten['tage'] = date('d', $daten['ende']);
	$zeiten['wochentage'] = date('w', $daten['ende']);
	return $zeiten;
}

function cms_termindetailansicht_ausgeben($dbs, $gruppenid = "-") {
	global $CMS_URL, $CMS_URLGANZ, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_RECHTE, $CMS_TERMINID;
	$code = "";
	$gefunden = false;

	if (($CMS_URL[0] == 'Schulhof') || ($CMS_URL[0] == 'Website')) {
		if (count($CMS_URL) == 6) {
			$jahr = $CMS_URL[2];
			$monat = cms_monatnamezuzahl($CMS_URL[3]);
			$tag = $CMS_URL[4];
			$terminbez = cms_linkzutext($CMS_URL[5]);
			$datumb = mktime(0, 0, 0, $monat, $tag, $jahr);
			$datume = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;
			$tabelle = "termine";
			$tabelledownload = "terminedownloads";
			$gruppe = "Termine";
			$oeffentlichkeit = 'oeffentlichkeit';
			$art = 'oe';
		}
		else if (count($CMS_URL) == 10) {
			$jahr = $CMS_URL[6];
			$monat = cms_monatnamezuzahl($CMS_URL[7]);
			$tag = $CMS_URL[8];
			$terminbez = cms_linkzutext($CMS_URL[9]);
			$datumb = mktime(0, 0, 0, $monat, $tag, $jahr);
			$datume = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;
			$gruppe = cms_linkzutext($CMS_URL[3]);
			if (!cms_valide_gruppe($gruppe)) {$fehler = true;}
			$gk = cms_textzudb($gruppe);
			$tabelle = $gk."termineintern";
			$tabelledownload = $gk."termineinterndownloads";
			$oeffentlichkeit = "'0' AS oeffentlichkeit";
			$art = 'in';
		}

		if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
		if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
		if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}
	}

	// Termin finden
	$termin = array();
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, $oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, '$art' AS art, aktiv FROM $tabelle WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (beginn BETWEEN ? AND ?);");
	$sql->bind_param("sii", $terminbez, $datumb, $datume);
	if ($sql->execute()) {
		$sql->bind_result($termin['id'], $termin['bezeichnung'], $termin['ort'], $termin['beginn'], $termin['ende'], $termin['mehrtaegigt'], $termin['uhrzeitbt'], $termin['uhrzeitet'], $termin['ortt'], $termin['genehmigt'], $termin['aktiv'], $termin['oeffentlichkeit'], $termin['text'], $termin['art'], $termin['aktiv']);
		if ($sql->fetch()) {$gefunden = true;}
		else {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

	if($gefunden) {	// Nur für Notifikation
		if ($CMS_URL[0] == 'Schulhof') {
			$sql = $dbs->prepare("DELETE FROM notifikationen WHERE person = ? AND art = 't' AND gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND zielid = ?");
			$sql->bind_param("isi", $CMS_BENUTZERID, $gruppe, $termin['id']);
			$sql->execute();
			$sql->close();
		}
	}

	$gefunden = $gefunden && isset($termin["aktiv"]) && $termin["aktiv"];

	if ($gefunden) {
		if ($jahr != date('Y', $termin['beginn'])) {$gefunden = false;}
		if ($monat != date('m', $termin['beginn'])) {$gefunden = false;}
		if ($tag != date('d', $termin['beginn'])) {$gefunden = false;}
	}

	if ($gefunden) {
		$gefunden = cms_oeffentlich_sichtbar($dbs, 'termine', $termin);
	}

	if ($gefunden) {
		$code .= "</div>";
		$zeiten = cms_termin_zeiten($termin);
		// Schnellinfos
		$kalender = "<div class=\"cms_termin_detialkalenderblatt\">".cms_termin_kalenderblatterzeugen($termin, $zeiten)."</div>";
		$kalender .= "<div class=\"cms_termin_detailinformationen\">".cms_termindetailansicht_termininfos($dbs, $termin, $zeiten)."</div>";

		$downloads = array();
		// Downloads suchen
		$sql = "SELECT * FROM (SELECT id, termin, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, dateiname, dateigroesse FROM $tabelledownload WHERE termin = ".$termin['id'].") AS x ORDER BY titel ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				array_push($downloads, $daten);
			}
			$anfrage->free();
		}

		$aktionen = "";
		if ($CMS_URL[0] == "Schulhof") {
			if ($termin['art'] == 'oe') {
				$link = $CMS_URLGANZ;
				$linkl = $CMS_URL[0]."/".$CMS_URL[1];
				if (@$CMS_RECHTE['Website']['Termine bearbeiten']) {
					$aktionen .= "<span class=\"cms_button\" onclick=\"cms_termine_bearbeiten_vorbereiten('".$termin['id']."', '$linkl')\">Termin bearbeiten</span> ";
				}
				if (r("artikel.genehmigen.termine") && ($termin['genehmigt'] == 0)) {
					$aktionen .= "<span class=\"cms_button_ja\" onclick=\"cms_termin_genehmigen('Termine', '".$termin['id']."', '$link')\">Termin genehmigen</span> ";
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_termin_ablehnen('Termine', '".$termin['id']."', '$linkl')\">Termin ablehnen</span> ";
				}
				if ($CMS_RECHTE['Website']['Termine löschen']) {
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_termine_loeschen_vorbereiten('".$termin['id']."', '".$daten['bezeichnung']."', '$linkl')\">Termin löschen</span> ";
				}
			}
			else if ($termin['art'] == 'in') {
				$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
				$link = $CMS_URLGANZ;
				$linkl = implode('/', array_slice($CMS_URL,0,6));
				if ($gruppenrechte['termine'] == '1') {
					$aktionen .= "<span class=\"cms_button\" onclick=\"cms_termineintern_bearbeiten_vorbereiten('".$termin['id']."', '$linkl')\">Termin bearbeiten</span> ";
				}
				if (r("schulhof.gruppen.$gruppe.artikel.termine.genehmigen") && ($termin['genehmigt'] == 0)) {
					$aktionen .= "<span class=\"cms_button_ja\" onclick=\"cms_termin_genehmigen('$gruppe', '".$termin['id']."', '$link')\">Termin genehmigen</span> ";
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_termin_ablehnen('$gruppe', '".$termin['id']."', '$linkl')\">Termin ablehnen</span> ";
				}
				if ($gruppenrechte['termine'] == '1') {
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_termineintern_loeschen_vorbereiten('".$termin['id']."', '$gruppe', '$gruppenid', '".$termin['bezeichnung']."', '$linkl')\">Termin löschen</span> ";
				}
			}
		}

		if ((count($downloads) > 0) || (strlen($aktionen) > 0)) {$spaltenart = '2';}
		else {$spaltenart = '34';}

		$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">".$kalender."</div></div>";

		$code .= "<div class=\"cms_spalte_$spaltenart\"><div class=\"cms_spalte_i\">";
		$code .= "<h1>".$termin['bezeichnung']."</h1>";

		$code .= cms_ausgabe_editor($termin['text']);

		$code .= cms_artikel_reaktionen("t", $termin["id"], $gruppenid);
		$code .= "</div></div>";

		if ((count($downloads) > 0) || (strlen($aktionen) > 0)) {
			$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
			if (count($downloads) > 0) {
				$code .= "<h3>Zugehörige Downloads</h3>";
				if ($art == 'oe') {foreach ($downloads as $d) {$code .= cms_schulhof_download_ausgeben($d);}}
				else if ($art == 'in') {
					foreach ($downloads as $d) {
						$d['gruppenid'] = $gruppenid;
						$code .= cms_schulhof_interndownload_ausgeben($d);
					}
				}
			}
			if (strlen($aktionen) > 0) {
				$code .= "<h3>Aktionen</h3><p>".$aktionen."</p>";
			}
			$code .= "</div></div>";
		}

		$code .= "<div class=\"cms_clear\"></div>";
		$CMS_TERMINID = $termin["id"];
	}
	else {
		$code .= "<h1>Termindetailansicht</h1>";
		$code .= cms_meldung('info', '<h4>Termin nicht verfügbar</h4><p>Dieser Termin ist derzeit nicht verfügbar. Möglicherweise ist er inaktiv oder er existiert nicht oder nicht mehr.</p>');
	}
	return $code;
}

function cms_termindetailansicht_termininfos($dbs, $daten, $zeiten) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "<h3>".$daten['bezeichnung']."</h3><ul class=\"cms_termindetails\">";
	$code .= "<li>".cms_termin_dauerdetail($daten, $zeiten)."<li>";

	if ($daten['ortt'] == 1) {
		$code .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/ort.png')\">".$daten['ort']."</span></li>";
	}
	if ($daten['genehmigt'] == 0) {
		$code .= "<li class=\"cms_genehmigungausstehend\">!! ACHTUNG !! <br> Der Termin ist noch nicht genehmigt!</li>";
	}
	$code .= "</ul>";

	$verknuepfung = "";
	// Bei öffentlichen Terminen zugehörige Kategorien suchen
	if ($daten['art'] == 'oe') {
		$sql = "";
		$zugehoerigladen = "";
		foreach ($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			$sql .= " UNION (SELECT id, '$gk' AS gruppe, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."termine ON $gk.id = $gk"."termine.gruppe WHERE termin = ?)";
		}
		$sql = substr($sql, 7);
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC");
		$sql->bind_param("iiiiiiiiiii", $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id']);
		if (count($CMS_URL)>0) {$link = $CMS_URL[0];}
		else {$link = "Website";}
		if ($sql->execute()) {
			$sql->bind_result($gid, $ggruppe, $gbez, $gicon);
			while ($sql->fetch()) {
				if (strlen($zugehoerigladen) == 0) {$zugehoerigladen = "cms_zugehoerig_laden('cms_zugehoerig_".$daten['id']."', '".$zeiten['jahrb']."', '$ggruppe', '$gid', '$link');";}
				$event = " onclick=\"cms_zugehoerig_laden('cms_zugehoerig_".$daten['id']."', '".$zeiten['jahrb']."', '$ggruppe', '$gid', '$link')\"";
				$verknuepfung .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/$gicon')\"$event>$gbez</span></li>";
			}
		}
		$sql->close();
		if (strlen($verknuepfung) > 0) {
			$code .= "<h3>Zugehörige Gruppen</h3><ul class=\"cms_termindetails\">$verknuepfung</ul>";
			$code .= "<div class=\"cms_zugehoerig\" id=\"cms_zugehoerig_".$daten['id']."\"></div>";
			$code .= "<script>$zugehoerigladen</script>";
		}
	}

	return $code;
}

function cms_nachste_termine_ausgeben($anzahl) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERART;
	$code = "";
	$jetzt = time();

	// Öffentliche Termine
	if ($CMS_BENUTZERART == 'l') {$oeffentlichkeitslevel = 1;}
	else if ($CMS_BENUTZERART == 'v') {$oeffentlichkeitslevel = 2;}
	else if (($CMS_BENUTZERART == 's') || ($CMS_BENUTZERART == 'e')) {$oeffentlichkeitslevel = 3;}
	else {$oeffentlichkeitslevel = 4;}
	$sqltermine = "SELECT id, '' AS gruppenart, '' AS gruppe, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine ";
	$sqltermine .= "WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit >= $oeffentlichkeitslevel AND ende > $jetzt ORDER BY beginn ASC, ende ASC LIMIT ".$anzahl;

	$sqlgruppen = "";
	$sqlintern = "";
	// Für alle Gruppen suchen, ob Mitgliedschaftentermine enthalten sind

	foreach ($CMS_GRUPPEN AS $g) {
		$gk = cms_textzudb($g);
		$sqlgruppen .= " UNION SELECT DISTINCT termin FROM $gk"."termine WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID UNION SELECT gruppe FROM $gk"."aufsicht WHERE person = $CMS_BENUTZERID) AND aktiv = 1";

		//$sqlgruppen .= " UNION (SELECT DISTINCT termin FROM ((SELECT id FROM $gk JOIN $gk"."mitglieder ON $gk.id = $gk"."mitglieder.gruppe WHERE person = $CMS_BENUTZERID) UNION (SELECT id FROM $gk JOIN $gk"."aufsicht ON $gk.id = $gk"."aufsicht.gruppe WHERE person = $CMS_BENUTZERID)) AS x";
		//$sqlgruppen .= " JOIN $gk"."termine ON x.id = $gk"."termine.gruppe JOIN termine ON $gk"."termine.termin = termine.id WHERE ende > $jetzt ORDER BY beginn ASC, ende ASC LIMIT $anzahl)";

		$sqlintern .= " UNION (SELECT id, '$g' AS gruppenart, $gk"."termineintern.gruppe AS gruppe, 'in' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, '0' AS oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM $gk"."termineintern WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID UNION SELECT gruppe FROM $gk"."aufsicht WHERE person = $CMS_BENUTZERID) AND ende > $jetzt LIMIT $anzahl)";
	}


	$sqlintern = substr($sqlintern, 7);

	$sqlgruppen = "SELECT termine.id AS id, '' AS gruppenart, '' AS gruppe, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE id IN (".substr($sqlgruppen,7).") AND ende > $jetzt ORDER BY beginn ASC, ende ASC LIMIT $anzahl";

	$dbs = cms_verbinden('s');
	$sqlferien = "SELECT id, '' AS gruppenart, '' AS gruppe, art, 1 AS genehmigt, bezeichnung, '' AS ort, beginn, ende, mehrtaegigt, 0 AS uhrzeitbt, 0 AS uhrzeitet, 0 AS ortt, 4 AS oeffentlichkeit, '' AS text FROM ferien WHERE ende > $jetzt LIMIT ".$anzahl;
	$sql = "SELECT DISTINCT * FROM (($sqlgruppen) UNION ($sqltermine) UNION ($sqlferien) UNION $sqlintern) AS x ORDER BY beginn ASC, ende ASC LIMIT ".$anzahl;

	if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen ($anzahl)
		include_once('php/schulhof/seiten/termine/termineausgeben.php');
		while ($daten = $anfrage->fetch_assoc()) {
			$internvorlink = "";
			if ($daten['art'] == 'in') {
				$g = $daten['gruppenart'];
				$gk = cms_textzudb($g);
				$gid = $daten['gruppe'];
				$sql = "SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = $gid";
				if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
					if ($daten2 = $anfrage2->fetch_assoc()) {
						$schuljahrbez = $daten2['sbez'];
						$gbez = $daten2['gbez'];
						if (is_null($schuljahrbez)) {$schuljahrbez = "Schuljahrübergreifend";}
						$internvorlink = "Schulhof/Gruppen/".cms_textzulink($schuljahrbez)."/".cms_textzulink($g)."/".cms_textzulink($gbez);
					}
					$anfrage2->free();
				}
			}
			$code .= cms_termin_link_ausgeben($dbs, $daten, $internvorlink);
		}
		$anfrage->free();
	}
	cms_trennen($dbs);
	return $code;
}
?>
