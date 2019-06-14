<?php
function cms_zeitraum_ausgeben ($id) {
	global $CMS_SCHLUESSEL;
	$dbs = cms_verbinden('s');

	$jetzt = time();
	$schuljahr = "";
	$schuljahre = array();
	$beginn = $jetzt;
	$ende = $jetzt;
	$aktiv = 0;
	$mo = '1';
	$di = '1';
	$mi = '1';
	$do = '1';
	$fr = '1';
	$sa = '0';
	$so = '0';
	$schulstunden = array();
	$fehler = false;

	// alle Schuljahre laden
	$anzahl = 0;
	$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$schuljahre[$anzahl]['id'] = $daten['id'];
			$schuljahre[$anzahl]['bezeichnung'] = $daten['bezeichnung'];
			$anzahl++;
		}
		$anfrage->free();
	}
	else {$fehler = true;}

	// Ende des Schuljahres laden
	if ($id == '-') {
		// suche aktuelles Schuljahr
		$sql = "SELECT id, ende FROM schuljahre ORDER BY beginn DESC LIMIT 1";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$ende = $daten['ende'];
				$schuljahr = $daten['id'];
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		else {$fehler = true;}
	}
	else {
		$sql = "SELECT * FROM zeitraeume WHERE id = $id";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$schuljahr = $daten['schuljahr'];
				$beginn = $daten['beginn'];
				$ende = $daten['ende'];
				$aktiv = $daten['aktiv'];
				$mo = $daten['mo'];
				$di = $daten['di'];
				$mi = $daten['mi'];
				$do = $daten['do'];
				$fr = $daten['fr'];
				$sa = $daten['sa'];
				$so = $daten['so'];
			}
			else {$fehler = true;}
			$anfrage->free();
		}
		else {$fehler = true;}

		$anzahl = 0;
		$sql = "SELECT * FROM (SELECT id, zeitraum, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS beginnstd, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS beginnmin, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS endestd, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS endemin FROM schulstunden WHERE zeitraum = $id) AS x ORDER BY beginnstd ASC, beginnmin ASC";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$schulstunden[$anzahl]['id'] = $daten['id'];
				$schulstunden[$anzahl]['zeitraum'] = $daten['zeitraum'];
				$schulstunden[$anzahl]['bezeichnung'] = $daten['bezeichnung'];
				$schulstunden[$anzahl]['beginnstd'] = $daten['beginnstd'];
				$schulstunden[$anzahl]['beginnmin'] = $daten['beginnmin'];
				$schulstunden[$anzahl]['endestd'] = $daten['endestd'];
				$schulstunden[$anzahl]['endemin'] = $daten['endemin'];
				$anzahl++;
			}
			$anfrage->free();
		}
		else {$fehler = true;}
	}

	cms_trennen($dbs);

	$code = "";
	if (!$fehler) {
		$code .= "</div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Geltungszeitraum</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th>Schuljahr:</th><td><select id=\"cms_zeitraum_schuljahr\" name=\"cms_zeitraum_schuljahr\">";
			foreach ($schuljahre AS $sj) {
				if ($sj['id'] == $schuljahr) {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option value=\"".$sj['id']."\"$selected>".$sj['bezeichnung']."</option>";
			}
			$code .= "</select></td></tr>";
			$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Aktiv:<span class=\"cms_hinweis\">Inaktive Zeiträume werden im Schulhof nur den zur Stundenplanung berechtigten Personen angezeigt.</span></span></th><td>".cms_schieber_generieren('zeitraum_aktiv', $aktiv)."</td></tr>";
			$code .= "<tr><th>Beginn:</th><td>".cms_datum_eingabe ('cms_zeitraum_beginn', date('d', $beginn), date('m', $beginn), date('Y', $beginn))."</td></tr>";
			$code .= "<tr><th>Ende:</th><td>".cms_datum_eingabe ('cms_zeitraum_ende', date('d', $ende), date('m', $ende), date('Y', $ende))."</td></tr>";
		$code .= "</table>";

		$code .= "<h3>Schultage</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th>Montag:</th><td>".cms_schieber_generieren('zeitraum_mo', $mo)."</td></tr>";
			$code .= "<tr><th>Dienstag:</th><td>".cms_schieber_generieren('zeitraum_di', $di)."</td></tr>";
			$code .= "<tr><th>Mittwoch:</th><td>".cms_schieber_generieren('zeitraum_mi', $mi)."</td></tr>";
			$code .= "<tr><th>Donnerstag:</th><td>".cms_schieber_generieren('zeitraum_do', $do)."</td></tr>";
			$code .= "<tr><th>Freitag:</th><td>".cms_schieber_generieren('zeitraum_fr', $fr)."</td></tr>";
			$code .= "<tr><th>Samstag:</th><td>".cms_schieber_generieren('zeitraum_sa', $sa)."</td></tr>";
			$code .= "<tr><th>Sonntag:</th><td>".cms_schieber_generieren('zeitraum_so', $so)."</td></tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Schulstunden</h3>";

		$code .= "<div id=\"cms_zeitraum_schulstunden\">";
		$anzahl = 0;
		$ids = "";
		for ($i=0; $i<count($schulstunden); $i++) {
			$sid = $schulstunden[$i]['id'];
			$code .= "<table class=\"cms_formular\" id=\"cms_zeitraum_schulstunden_$sid\">";
				$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraum_bezeichnung_$sid\" id=\"cms_zeitraum_bezeichnung_$sid\" value=\"".$schulstunden[$i]['bezeichnung']."\"/></td></tr>";
				$code .= "<tr><th>Beginn:</th><td>".cms_uhrzeit_eingabe('cms_zeitraum_beginn_'.$sid, $schulstunden[$i]['beginnstd'], $schulstunden[$i]['beginnmin'])."</td></tr>";
				$code .= "<tr><th>Ende:</th><td>".cms_uhrzeit_eingabe('cms_zeitraum_ende_'.$sid, $schulstunden[$i]['endestd'], $schulstunden[$i]['endemin'])."</td></tr>";
				$code .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_zeitraum_schulstunden_entfernen('$sid');\">Schulstunde löschen</span></td></tr>";
			$code .= "</table>";
			$anzahl++;
			$ids .= "|".$sid;
		}
		$code .= "</div>";
		$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_zeitraum_neue_schulstunde();\">+ Neue Schulstunde</span>";
			$code .= "<input type=\"hidden\" id=\"cms_zeitraum_schulstunden_anzahl\" name=\"cms_zeitraum_schulstunden_anzahl\" value=\"$anzahl\">";
			$code .= "<input type=\"hidden\" id=\"cms_zeitraum_schulstunden_nr\" name=\"cms_zeitraum_schulstunden_nr\" value=\"$anzahl\">";
			$code .= "<input type=\"hidden\" id=\"cms_zeitraum_schulstunden_ids\" name=\"cms_zeitraum_schulstunden_ids\" value=\"$ids\">";
		$code.= "</p>";

		$code .= "</div></div>";
		$code .= "<div class=\"cms_clear\"></div>";

		$code .= "<div class=\"cms_spalte_i\">";
		if ($id == '-') {
			$code .= "<p><span class=\"cms_button\" onclick=\"cms_stundenplanung_zeitraeume_neu_speichern();\">Speichern</span> ";
		}
		else {
			$code .= cms_meldung('warnung', '<h4>Auswirkungen auf erstellte Stunden</h4><p>Alle Stunden, die außerhalb der neuen Zeitraumgrenzen liegen werden gelöscht.</p><p>Bereits vergangene Stunden und zur Vertretung vorgemerkte Stunden bleiben erhalten.</p><p>Da Zeitraumänderungen Auswirkungen auf viele Unterrichtsstunden haben können, kann eine Zeitraumänderung etwas Zeit in Anspruch nehmen.</p>');
			$code .= "<p><span class=\"cms_button\" onclick=\"cms_stundenplanung_zeitraeume_bearbeiten_speichern();\">Änderungen speichern</span> ";
		}
		$code .= "<a href=\"Schulhof/Verwaltung/Stundenplanung/\" class=\"cms_button_nein\">Abbrechen</a></p></div>";
	}
	else {
		echo cms_meldung_fehler();
	}
	return $code;
}
?>
