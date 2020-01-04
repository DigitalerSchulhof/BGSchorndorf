<?php
// Unterseiten einer bestimmten Seite ausgeben (ebene = s)
function cms_navigation_ausgeben_unterseite($dbs, $start, $tiefe, $pfad, $art = '', $untermenuebegonnen = false) {
	global $CMS_URL, $CMS_EINSTELLUNGEN, $CMS_ANGEMELDET;
	$code = "";
	$finale = "";


	if (isset($CMS_URL[1])) {$bereich = $CMS_URL[1];}
	else {$bereich = "";}
	if (isset($CMS_URL[2])) {$seite = $CMS_URL[2];}
	else {$seite = "";}

	if (($bereich != "Seiten") && ($bereich != 'Bearbeiten')) {$bereich = "Seiten";}
	if (($seite != "Alt") && ($seite != 'Aktuell') && ($seite != 'Neu')) {$seite = "Aktuell";}

	if ($bereich != 'Bearbeiten') {$sqlzusatz = "AND status = 'a'";} else {$sqlzusatz = "";}

	// Suche Unterseiten
	$sql = "SELECT id, bezeichnung, art FROM seiten WHERE zuordnung = '$start' $sqlzusatz ORDER BY position ASC";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
		while ($daten = $anfrage->fetch_assoc()) {
			$neuuntermenuebegonnen = false;
			$pfadbez = str_replace(' ', '_', $daten['bezeichnung']);
			if (strlen($pfad)>0) {$neuerpfad = $pfad."/".$pfadbez;}
			else {$neuerpfad = $pfadbez;}
			if (($daten['art'] == 'b') || ($daten['art'] == 'g') || ($daten['art'] == 't')) {
				$seite = date('Y');
				$monat = cms_monatsnamekomplett(date('n'));
				if ($daten['art'] == 'b') {$bereich = 'Blog';}
				if ($daten['art'] == 'g') {$bereich = 'Galerien';}
				if ($daten['art'] == 't') {$bereich = 'Termine';}
				$code .= "<li><a href=\"Website/$bereich/$seite/$monat\">".$daten['bezeichnung']."</a>";
				if ($tiefe > 0) {
					global $CMS_SCHLUESSEL;
					$tabelle = strtolower($bereich);
					$jahrb = null;
					$jahre = null;
					if ($daten['art'] == 't') {$sql = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";}
					else if ($daten['art'] == 'b') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM blogeintraege WHERE aktiv = 1";}
					else if ($daten['art'] == 'g') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM galerien WHERE aktiv = 1";}
					// Jahre ausgeben
					if ($a = $dbs->query($sql)) {	// Safe weil keine Eingabe
						if ($j = $a->fetch_assoc()) {
							if (!is_null($j['beginn'])) {
								$jahrb = date('Y', $j['beginn']);
								$jahre = date('Y', $j['ende']);
							}
						}
						$a->free();
					}
					$jahrcode = "";
					if (!is_null($jahrb) && !is_null($jahre)) {
						for ($i=$jahre; $i>=$jahrb; $i--) {
							$jahrcode.= "<li><a href=\"Website/$bereich/$i/$monat\">".$i."</a></li>";
						}
						if (strlen($jahrcode) > 0) {
							$code .= "<div class=\"cms_naviuntermenue\"><ul>$jahrcode";
							$neuuntermenuebegonnen = true;
						}
					}
				}
			}
			else {
				if (($bereich != "Seiten") && ($bereich != 'Bearbeiten')) {$bereich = "Seiten";}
				if (($seite != "Alt") && ($seite != 'Aktuell') && ($seite != 'Neu')) {$seite = "Aktuell";}
				$code .= "<li><a href=\"Website/$bereich/$seite/$neuerpfad\">".$daten['bezeichnung']."</a>";
			}
			if ($tiefe > 0) {
				$neuetiefe = $tiefe;
				if ($tiefe != 4) {$neuetiefe--;}
				$code .= cms_navigation_ausgeben_unterseite($dbs, $daten['id'], $neuetiefe, $neuerpfad, '', $neuuntermenuebegonnen);
			}
			$code .= "</li>";
		}
	}

	if (isset($CMS_URL[1])) {
		if (($CMS_URL[1] == 'Bearbeiten') && r("website.seiten.anlegen")) {
			$code .= "<li><span class=\"cms_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('$start');\">+ Neue Seite</span></li>";
		}
	}


	if ($art == 'f') {
		$code .= "<li><a href=\"Website/Datenschutz\">Datenschutz</a></li>";
		$code .= "<li><a href=\"Website/Impressum\">Impressum</a></li>";
		if(($CMS_EINSTELLUNGEN['Feedback aktiv'] == "1" && ($CMS_EINSTELLUNGEN['Feedback Anmeldung notwendig'] == "0" || ($CMS_EINSTELLUNGEN['Feedback Anmeldung notwendig'] == "1" && $CMS_ANGEMELDET))) || ($CMS_EINSTELLUNGEN['Fehlermeldung aktiv'] == "1" && ($CMS_EINSTELLUNGEN['Fehlermeldung Anmeldung notwendig'] == "0" || ($CMS_EINSTELLUNGEN['Fehlermeldung Anmeldung notwendig'] == "1" && $CMS_ANGEMELDET))))
			$code .= "<li class=\"cms_footer_feedback\"><a href=\"Website/Feedback\">Fehler melden</a></li>";
	}

	if (strlen($code) > 0) {
		if ($art == 'h') {
			$finale .= "<ul class=\"cms_hauptnavigation\" id=\"cms_kopfnavigation\">";
		}
		else if ($art == 'f') {
			$finale .= "<ul class=\"cms_fussnavigation\" id=\"cms_fussnavigation\">";
		}
		else if ($art == 's') {
			$finale .= "<ul class=\"cms_navigation\" id=\"cms_sidebarnavigation\">";
		}
		else if (!$untermenuebegonnen) {
			$finale .= "<div class=\"cms_naviuntermenue\"><ul>";
		}

		$finale .= $code."</ul>";
		if (($art != 'h') && ($art != 'f') && ($art != 's')) {
			$finale .= "</div>";
		}
	}
	else if ($untermenuebegonnen) {
		$finale .= "</ul></div>";
	}
	return $finale;
}

function cms_zeitnavigation_ausgeben($dbs, $art) {
	global $CMS_URL;
	$code = "";
	$gefunden = false;

	$linkart = $CMS_URL[1];
	if ($CMS_URL[1] == 'Blog') {
		$sql = "SELECT MAX(datum) as max, MIN(datum) AS min FROM blogeintraege WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4";
	}
	else if ($CMS_URL[1] == 'Termine') {
		$sql = "SELECT MAX(ende) as max, MIN(beginn) AS min FROM termine WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4";
	}
	else if ($CMS_URL[1] == 'Galerien') {
		$sql = "SELECT MAX(datum) as max, MIN(datum) AS min FROM galerien WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4";
	}
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			if($daten["max"] != NULL && $daten["min"] != NULL) {
				$minjahr = date('Y', $daten['min']);
				$maxjahr = date('Y', $daten['max']);
				$gefunden = true;
			}
		}
		$anfrage->free();
	}

	if ($gefunden) {
		if (isset($CMS_URL[2])) {$jahr = $CMS_URL[2];}
		else {$jahr = date('Y');}
		if (isset($CMS_URL[3])) {$monat = cms_monatnamezuzahl($CMS_URL[3]);}
		else {$monat = date('m');}

		for ($i = $maxjahr; $i>=$minjahr; $i--) {
			if ($i == $jahr) {$zusatzj = " class=\"cms_navigation_aktiveseite\"";}
			else {$zusatzj = "";}
			$code .= "<li><a$zusatzj href=\"Website/$linkart/$i\">$i</a>";
			if ($i == $jahr) {
				$code .= "<div class=\"cms_naviuntermenue\"><ul>";
				for ($j=1; $j<=12; $j++) {
					$monatname = cms_monatsnamekomplett($j);
					if ($j == $monat) {$zusatzm = " class=\"cms_navigation_aktiveseite\"";}
					else {$zusatzm = "";}
					$code.= "<li><a$zusatzm href=\"Website/$linkart/$i/$monatname\">$monatname</a></li>";
				}
				$code .= "</ul></div>";
			}
			$code .= "</li>";
		}
	}

	if (strlen($code) > 0) {
		if ($art == 's') {
			$code = "<ul class=\"cms_navigation\" id=\"cms_sidebarnavigation\">".$code."</ul>";
		}
	}

	return $code;
}

// Finde die ID der Oberseite der Seite auf Ebene $ebene im angegebenen Pfad
function cms_navigation_oberseiteid($dbs, $pfad, $ebene) {
	if (!is_array($pfad)) {$pfad = explode('/', $pfad);}

	$neuerpfad = "";
	for ($i = 0; $i <= $ebene-1; $i++) {
		if (isset($pfad[$i])) {
			$neuerpfad .= "/".$pfad[$i];
		}
	}

	if (strlen($neuerpfad) > 0) {
		$neuerpfad = explode('/', substr($neuerpfad, 1));
		$oberseite = cms_seitendetails_erzeugen($dbs, $neuerpfad);
		if (isset($oberseite['id'])) {return $oberseite['id'];}
		else {return false;}
	}
	else {
		return '-';
	}
}

// Funktion Navigationsebene ausgeben
function cms_navigationsebene_ausgeben($dbs, $pfad, $gesamtpfad, $oberseite, $tiefe, $art = '', $durchgang = 1, $untermenuebegonnen = false) {
	global $CMS_URL;
	$CMS_ZUSATZ = implode('/', array_slice($CMS_URL,3));

	$bereich = $CMS_URL[1];
	$seite = $CMS_URL[2];
	if (($bereich != "Seiten") && ($bereich != 'Bearbeiten')) {$bereich = "Seiten";}
	if (($seite != "Alt") && ($seite != 'Aktuell') && ($seite != 'Neu')) {$seite = "Aktuell";}

	if ($bereich != 'Bearbeiten') {$sqlzusatz = "AND status = 'a'";} else {$sqlzusatz = "";}

	$code = "";
	$finale = "";
	if ($oberseite == '-') {$sql = "SELECT id, bezeichnung, zuordnung, art FROM seiten WHERE zuordnung IS NULL $sqlzusatz ORDER BY position";}
	else {$sql = "SELECT id, bezeichnung, zuordnung, art FROM seiten WHERE zuordnung = '$oberseite' $sqlzusatz ORDER BY position";}
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		while ($daten = $anfrage->fetch_assoc()) {
			$untermneueneubegonnen = false;
			$pfadbez = str_replace(' ', '_', $daten['bezeichnung']);
			if (strlen($pfad)>0) {$neuerpfad = $pfad."/".$pfadbez;}
			else {$neuerpfad = $pfadbez;}

			if (($daten['art'] == 'b') || ($daten['art'] == 'g') || ($daten['art'] == 't')) {
				$seite = date('Y');
				$monat = cms_monatsnamekomplett(date('n'));
				if ($daten['art'] == 'b') {$bereich = 'Blog';}
				if ($daten['art'] == 'g') {$bereich = 'Galerien';}
				if ($daten['art'] == 't') {$bereich = 'Termine';}
				if ($bereich == $CMS_URL[1]) {$zklasse = " class=\"cms_navigation_aktiveseite\"";} else {$zklasse = "";}
				$code .= "<li><a$zklasse href=\"Website/$bereich/$seite/$monat\">".$daten['bezeichnung']."</a>";

				if (($tiefe > 0) && ($CMS_URL[1] == $bereich) && ((is_int(intval($CMS_URL[2]))) || ($CMS_URL[2] != '-'))) {
					global $CMS_SCHLUESSEL;
					$tabelle = strtolower($bereich);
					$jahrb = null;
					$jahre = null;
					if ($daten['art'] == 't') {$sql = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";}
					else if ($daten['art'] == 'b') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM blogeintraege WHERE aktiv = 1";}
					else if ($daten['art'] == 'g') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM galerien WHERE aktiv = 1";}
					// Jahre ausgeben
					if ($a = $dbs->query($sql)) {	// Safe weil keine Eingabe
						if ($j = $a->fetch_assoc()) {
							if (!is_null($j['beginn'])) {
								$jahrb = date('Y', $j['beginn']);
								$jahre = date('Y', $j['ende']);
							}
						}
						$a->free();
					}
					if (!is_null($jahrb) && !is_null($jahre)) {
						$jahrcode = "";
						for ($i=$jahre; $i>=$jahrb; $i--) {
							if ($i == $CMS_URL[2]) {$zklasse = " class=\"cms_navigation_aktiveseite\"";} else {$zklasse = "";}
							$jahrcode.= "<li><a$zklasse href=\"Website/$bereich/$i/$monat\">".$i."</a></li>";
						}
						if (strlen($jahrcode) > 0) {
							$code .= "<div class=\"cms_naviuntermenue\"><ul>$jahrcode";
							$untermneueneubegonnen = true;
						}
					}
				}
			}
			else {
				if (($bereich != "Seiten") && ($bereich != 'Bearbeiten')) {$bereich = "Seiten";}
				if (($seite != "Alt") && ($seite != 'Aktuell') && ($seite != 'Neu')) {$seite = "Aktuell";}
				if ($neuerpfad == substr($gesamtpfad, 0, strlen($neuerpfad))) {$zklasse = " class=\"cms_navigation_aktiveseite\"";} else {$zklasse = "";}
				$code .= "<li><a$zklasse href=\"Website/$bereich/$seite/$neuerpfad\">".$daten['bezeichnung']."</a>";
			}

			$impfad = preg_match("/".str_replace('/', '\/', $neuerpfad)."/", $gesamtpfad);
			if (($tiefe > 0) && $impfad) {
				$neuetiefe = $tiefe;
				if ($tiefe != 4) {$neuetiefe--;}
				$code .= cms_navigationsebene_ausgeben($dbs, $neuerpfad, $gesamtpfad, $daten['id'], $neuetiefe, '', $durchgang+1, $untermneueneubegonnen);
			}
			$code .= "</li>";
		}
	}

	if (($tiefe > 0) && (($CMS_URL[1] == 'Blog') || ($CMS_URL[1] == 'Termine') || ($CMS_URL[1] == 'Galerien')) &&
			(strlen($code) == 0) && ((is_int(intval($CMS_URL[2]))) || ($CMS_URL[2] != '-')) && !$untermenuebegonnen) {
		global $CMS_SCHLUESSEL;
		$jahrb = null;
		$jahre = null;
		$monat = cms_monatsnamekomplett(date('n'));
		if ($CMS_URL[1] == 'Termine') {$sql = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";}
		else if ($CMS_URL[1] == 'Blog') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM blogeintraege WHERE aktiv = 1";}
		else if ($CMS_URL[1] == 'Galerien') {$sql = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM galerien WHERE aktiv = 1";}
		// Jahre ausgeben
		if ($a = $dbs->query($sql)) {	// Safe weil keine Eingabe
			if ($j = $a->fetch_assoc()) {
				if (!is_null($j['beginn'])) {
					$jahrb = date('Y', $j['beginn']);
					$jahre = date('Y', $j['ende']);
				}
			}
			$a->free();
		}
		if (!is_null($jahrb) && !is_null($jahre)) {
			$jahrcode = "";
			for ($i=$jahre; $i>=$jahrb; $i--) {
				if ($i == $CMS_URL[2]) {$zklasse = " class=\"cms_navigation_aktiveseite\"";} else {$zklasse = "";}
				$jahrcode.= "<li><a$zklasse href=\"Website/$CMS_URL[1]/$i/$monat\">".$i."</a></li>";
			}
			if (strlen($jahrcode) > 0) {
				$code .= "<ul class=\"cms_navigation\" id=\"cms_sidebarnavigation\">$jahrcode</ul>";
				$untermneueneubegonnen = true;
			}
		}
	}

	if (($CMS_URL[1] == 'Bearbeiten') && r("website.seiten.anlegen")) {
		$code .= "<li><span class=\"cms_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('$oberseite');\">+ Neue Seite</span></li>";
	}

	if ($art == 'f') {
		$code .= "<li><a href=\"Website/Datenschutz\">Datenschutz</a></li>";
		$code .= "<li><a href=\"Website/Impressum\">Impressum</a></li>";
	}

	if (strlen($code) > 0) {
		if ($art == 'h') {
			$finale .= "<ul class=\"cms_hauptnavigation\" id=\"cms_kopfnavigation\">";
		}
		else if ($art == 'f') {
			$finale .= "<ul class=\"cms_fussnavigation\" id=\"cms_fussnavigation\">";
		}
		else if ($art == 's') {
			$finale .= "<ul class=\"cms_navigation\" id=\"cms_sidebarnavigation\">";
		}
		else if (!$untermenuebegonnen) {
			$finale .= "<div class=\"cms_naviuntermenue\"><ul>";
		}

		$finale .= $code."</ul>";
		if (($art != 'h') && ($art != 's')) {
			$finale .= "</div>";
		}
	}
	else if ($untermenuebegonnen) {
		$finale .= "</ul></div>";
	}

	return $finale;
}


function cms_navimenue_monat_unterseiten($dbs, $art, $jahr, $monat) {
	$code = "";

	if ($art == 'b') {
		$bereich = 'Blog';
		$mname = cms_monatsnamekomplett($monat);
		$monatsbeginn = mktime(0,0,0,$monat, 1, $jahr);
		$monatsende = mktime(0,0,0,$monat+1,1,$jahr)-1;
		$sql = "SELECT titelaktuell FROM blogeintraege WHERE aktiv = '1' AND (datumaktuell BETWEEN $monatsbeginn AND $monatsende) ORDER BY datumaktuell ASC, titelaktuell ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$titellink = str_replace(' ', '_', $daten['titelaktuell']);
				$code .= "<li><a href=\"Website/$bereich/$jahr/$mname"."/".$titellink."\">".$daten['titelaktuell']."</a></li>";
			}
			$anfrage->free();
		}
		if (strlen($code) > 0) {
			$code = "<div class=\"cms_naviuntermenue\"><ul>$code</ul></div>";
		}
	}
	return $code;
}

// Unterseiten einer bestimmten Ebene des aktuellen Pfades ausgeben (ebene = e)
function cms_navigation_ausgeben_ebene($dbs, $start, $tiefe, $pfad, $art = '') {
	$code = "";

	$oberseite = cms_navigation_oberseiteid($dbs, $pfad, $start);
	if (is_array($pfad)) {
		$pfad = implode("/", $pfad);
	}

	$pfadzuroberseite = cms_seitenpfad_id_erzeugen($dbs, $oberseite);
	$pfadzuroberseite = cms_seitenpfadlink_erzeugen($pfadzuroberseite);
	return cms_navigationsebene_ausgeben($dbs, $pfadzuroberseite, $pfad, $oberseite, $tiefe, $art);
}

// Navigation laden
function cms_navigation_ausgeben ($id) {
	global $CMS_URL, $CMS_GERAET;
	$zusatz = implode('/', array_slice($CMS_URL, 3));

	$dbs = cms_verbinden('s');
	$nav = "-";
	if ((($id == 'h') && ($CMS_GERAET == 'P')) || ($id == 's') || ($id == 'f')) {
		$sql = "SELECT * FROM navigationen WHERE art = '$id'";
	}
	else {
		$sql = "SELECT * FROM navigationen WHERE id = $id";
	}
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		if ($daten = $anfrage->fetch_assoc()) {$nav = $daten;}
		$anfrage->free();
	}
	$code = "";

	if ($nav != '-') {
		if ($nav['ebene'] == 's') {
			$pfad = cms_seitenpfadlink_erzeugen(cms_seitenpfad_id_erzeugen($dbs, $nav['ebenenzusatz']));
			$code = cms_navigation_ausgeben_unterseite($dbs, $nav['ebenenzusatz'], $nav['tiefe'], $pfad, $nav['art']);
		}
		if ($nav['ebene'] == 'e') {
			global $CMS_SEITENDETAILS;
			if (($CMS_URL[1] == 'Blog') || ($CMS_URL[1] == 'Galerien') || ($CMS_URL[1] == 'Termine')) {
				$zusatz = cms_seitenpfadlink_erzeugen(cms_seitenpfad_id_erzeugen($dbs, $CMS_SEITENDETAILS['id']));
				$zusatz = explode("/", $zusatz);
			}
			$code = cms_navigation_ausgeben_ebene($dbs, $nav['ebenenzusatz'], $nav['tiefe'], $zusatz, $nav['art']);
		}
		if ($nav['ebene'] == 'd') {
			$dieseebene = count($zusatz)-1;
			$code = cms_navigation_ausgeben_ebene($dbs, $dieseebene, 1, $zusatz, $nav['art']);
		}
		if ($nav['ebene'] == 'u') {
			$dieseebene = count($zusatz);
			$code = cms_navigation_ausgeben_ebene($dbs, $dieseebene, 0, $zusatz, $nav['art']);
		}
	}

	if ($id == 'h') {
		$code .= cms_mobilnavigation($dbs);
	}
	cms_trennen($dbs);
	return $code;
}

function cms_mobilnavigation($dbs) {
	$code = "";
	$code .= "<span id=\"cms_mobilnavigation\" onclick=\"cms_einblenden('cms_mobilmenue_a')\">";
	$code .= "<span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span>";
	$code .= "</span>";
	$code .= "<div id=\"cms_mobilmenue_a\" style=\"display:none;\"><div id=\"cms_mobilmenue_i\">";
	$code .= "<p class=\"cms_mobilmenue_knoepfe\"><a class=\"cms_button\" href=\"Website/Start\">Website</a> <a class=\"cms_button\" href=\"Schulhof/Nutzerkonto\">Schulhof</a></p>";
	$code .= "<p class=\"cms_mobilmenue_knoepfe\"><a class=\"cms_button_ja\" href=\"Website/Start\">Startseite</a> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('cms_mobilmenue_a')\">Schließen</span></p>";

	$code .= "<div class=\"cms_websitesuche\"><input type=\"text\" placeholder=\"Suchen ...\" name=\"cms_websitesuche_mobil_suchbegriff\" id=\"cms_websitesuche_mobil_suchbegriff\" onkeyup=\"cms_websuche_suchen('cms_websitesuche_mobil_suchbegriff', 'cms_websitesuche_mobil_ergebnisse')\">";
	$code .= "<div id=\"cms_websitesuche_mobil_ergebnisse\">";
		$code .= "<span class=\"cms_button_nein cms_websitesuche_schliessen\" onclick=\"cms_websuche_schliessen('cms_websitesuche_mobil_suchbegriff', 'cms_websitesuche_mobil_ergebnisse')\">×</span>";
		$code .= "<div id=\"cms_websitesuche_mobil_ergebnisse_inhalt\">";
			$code .= "<p class=\"cms_notiz\">Bitte warten...</p>";
		$code .= "</div>";
	$code .= "</div></div>";


	if (isset($_SESSION['MOBILNAVIGATION'])) {
		$navicode = $_SESSION['MOBILNAVIGATION'];
	}
	else {
		$navicode = "";
		$fehler = false;

		$navicode .= "<div id=\"cms_mobilmenue_seiten\">";
		// Hauptnavigation laden
		$sql = "SELECT * FROM navigationen WHERE art = 'h'";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			if ($daten = $anfrage->fetch_assoc()) {
				$navi = $daten;
			}
			else {$fehler = true;}
			$anfrage->free();
		}

		if (!$fehler) {
			if (($navi['ebene'] == 'e') || ($navi['ebene'] == 'd') || ($navi['ebene'] == 'u')) {
				$sql = "SELECT bezeichnung, id FROM seiten WHERE zuordnung = '-' ORDER BY position";
				if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
					while ($seite = $anfrage->fetch_assoc()) {
						$bezlink = str_replace(' ', '_', $seite['bezeichnung']);
						$navicode .= "<h3>".$seite['bezeichnung']."</h3>";
						$navicode .= "<div id=\"cms_mobilmenue_seite_".$seite['id']."\">";
						$navicode .= cms_mobilnavigation_oberseite($dbs, $seite['id']);
						$navicode .= "</div>";
					}
					$anfrage->free();
				}
			}
			else if ($navi['ebene'] == 's') {
				$sql = "SELECT id FROM seiten WHERE id = '".$navi['ebenenzusatz']."' ORDER BY position";
				if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
					while ($seite = $anfrage->fetch_assoc()) {
						$navicode .= "<div id=\"cms_mobilmenue_seite_".$seite['id']."\">";
						$navicode .= cms_mobilnavigation_oberseite($dbs, $seite['id']);
						$navicode .= "</div>";
					}
					$anfrage->free();
				}
			}
		}
		$navicode .= "</div>";

		if (isset($_SESSION['DSGVO_EINWILLIGUNG_A'])) {
			if ((isset($_SESSION['MOBILNAVIGATION'])) && ($_SESSION['DSGVO_EINWILLIGUNG_A'])) {
				$_SESSION['MOBILNAVIGATION'] = $navicode;
			}
		}
	}

	$code .= $navicode;
	$code .= "</div></div>";

	return $code;
}

function cms_mobilnavigation_oberseite($dbs, $oberseite) {
	$code = "";

	$pfadzurseite = cms_seitenpfadlink_erzeugen(cms_seitenpfad_id_erzeugen($dbs, $oberseite));
	if (strlen($pfadzurseite) != 0) {$pfadzurseite .= "/";}
	$pfadzurseite = "Website/Seiten/Aktuell/".$pfadzurseite;

	$jetzt = time();
	$aktjahr = date('Y', $jetzt);
	$aktmonat = cms_monatsnamekomplett(date('n', $jetzt));

	$sql = "SELECT bezeichnung, id, art FROM seiten WHERE zuordnung = '$oberseite' ORDER BY position";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Eingaben der Funktion prüfen
		while ($seite = $anfrage->fetch_assoc()) {
			// Klassische Seite oder Menüpunkt
			if (($seite['art'] == 's') || ($seite['art'] == 'm')) {
				$bezlink = str_replace(' ', '_', $seite['bezeichnung']);
				// Prüfen, ob Unterseiten existieren
				$seite['unterseiten'] = 0;
				$sql = "SELECT COUNT(*) AS unterseiten FROM seiten WHERE zuordnung = '".$seite['id']."'";
				if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
					if ($daten = $anfrage2->fetch_assoc()) {$seite['unterseiten'] = $daten['unterseiten'];}
					$anfrage2->free();
				}
				$code .= "<li><a href=\"".$pfadzurseite.$bezlink."\">".$seite['bezeichnung']."</a>";
				if ($seite['unterseiten'] > 0) {
					$code .= "<span id=\"cms_mobilmenue_knopf_".$seite['id']."\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_aendern('".$seite['id']."')\">&#8628;</span>";
					$code .= "<div id=\"cms_mobilmenue_seite_".$seite['id']."\" style=\"display:none;\"></div>";
				}
				$code .= "</li>";
			}
			else if (($seite['art'] == 'b') || ($seite['art'] == 'g') || ($seite['art'] == 't')) {
				if ($seite['art'] == 'b') {$seite['art'] = 'Blog';}
				else if ($seite['art'] == 'g') {$seite['art'] = 'Galerien';}
				else {$seite['art'] = 'Termine';}
				$code .= "<li><a href=\"Website/".$seite['art']."/$aktjahr/$aktmonat\">".$seite['bezeichnung']."</a>";
				$code .= "<span id=\"cms_mobilmenue_knopf_".$seite['id']."\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_aendern('".$seite['id']."')\">&#8628;</span>";
				$code .= "<div id=\"cms_mobilmenue_seite_".$seite['id']."\" style=\"display:none;\"></div>";
				$code .= "</li>";
			}

		}
		$anfrage->free();
	}
	if (strlen($code) > 0) {$code = "<ul>".$code."</ul>";}
	return $code;
}
?>
