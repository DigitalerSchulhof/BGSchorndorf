<?php

	function cms_schulhof_download_ausgeben($e) {
  	// Inaktiv für den Benutzer
  	$code = "";
  	$pfad = $e['pfad'];
  	$titel = $e['titel'];
  	$zusatzklasse = "";
  	$beschreibung = $e['beschreibung'];
  	$dateiname = $e['dateiname'];
  	$event = " onclick=\"cms_download('$pfad')\"";
  	$aktiv = true;
  	if (!is_file($pfad)) {$zusatzklasse = " cms_download_inaktiv"; $event = ""; $aktiv = false;}
  	$dname = explode('/', $pfad);
  	$dname = $dname[count($dname)-1];
  	$endung = explode('.', $dname);
  	$endung = $endung[count($endung)-1];
  	$icon = cms_dateisystem_icon($endung);
  	$code .= "<div class=\"cms_download_anzeige$zusatzklasse\" style=\"background-image: url('res/dateiicons/gross/".$icon."');\"$event>";
    	$code .= "<h2>$titel</h2>";
    	if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
    	$info = "";
    	if (!$aktiv) {$info = "Die Datei existiert nicht mehr.";}
    	if (strlen($info) > 0) {
      	$info = substr($info, 3);
      	$code .= "<p class=\"cms_notiz\">".$info."</p>";
    	}
  	$code .= "</div>";
  	return $code;
	}

	function cms_schulhof_interndownload_ausgeben($e) {
  	$code = "";
  	$pfad = implode("/", array_slice(explode("/", $e['pfad']), 1));
  	$titel = $e['titel'];
  	$zusatzklasse = "";
  	$beschreibung = $e['beschreibung'];
  	$dateiname = $e['dateiname'];
  	$dateigroesse = $e['dateigroesse'];
  	$gruppenid = $e['gruppenid'];
  	$event = " onclick=\"cms_herunterladen('s', 'schulhof', '$gruppenid', '$pfad')\"";
  	$aktiv = true;
  	if (!is_file('dateien/'.$pfad)) {$zusatzklasse = " cms_download_inaktiv"; $event = ""; $aktiv = false;}
  	$dname = explode('/', $pfad);
  	$dname = $dname[count($dname)-1];
  	$endung = explode('.', $dname);
  	$endung = $endung[count($endung)-1];
  	$icon = cms_dateisystem_icon($endung);
  	$code .= "<div class=\"cms_download_anzeige$zusatzklasse\" style=\"background-image: url('res/dateiicons/gross/".$icon."');\"$event>";
    	$code .= "<h2>$titel</h2>";
    	if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
    	$info = "";
    	if (!$aktiv) {$info = "Die Datei existiert nicht mehr.";}
    	if (strlen($info) > 0) {
      	$info = substr($info, 3);
      	$code .= "<p class=\"cms_notiz\">".$info."</p>";
    	}
  	$code .= "</div>";
  	return $code;
	}

	function cms_blogeintrag_zeiten($daten) {
		$zeiten['jahr'] = date('Y', $daten['datum']);
		$zeiten['monat'] = date('m', $daten['datum']);
		$zeiten['monatname'] = cms_monatsnamekomplett($zeiten['monat']);
		$zeiten['tag'] = date('d', $daten['datum']);
		$zeiten['wochentag'] = date('w', $daten['datum']);
		return $zeiten;
	}

	function cms_blogeintragdetailansicht_blogeintraginfos($dbs, $daten, $zeiten) {
		global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
		$code = "<ul class=\"cms_termindetails\">";
		$code .= "<li>".cms_blogeintrag_dauerdetail($daten, $zeiten)."<li>";

		if (strlen($daten['autor']) > 0) {
			$code .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/autor.png')\">".$daten['autor']."</span></li>";
		}
		if ($daten['genehmigt'] == 0) {
			$code .= "<li class=\"cms_genehmigungausstehend\">!! ACHTUNG !! <br> Der Blogeintrag ist noch nicht genehmigt!</li>";
		}
		$code .= "</ul>";

		$verknuepfung = "";
		// Bei öffentlichen Terminen zugehörige Kategorien suchen
		if ($daten['art'] == 'oe') {
			$sql = "";
			$zugehoerigladen = "";
			foreach ($CMS_GRUPPEN as $g) {
				$gk = cms_textzudb($g);
				$sql .= " UNION (SELECT id, '$gk' AS gruppe, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."blogeintraege ON $gk.id = $gk"."blogeintraege.gruppe WHERE blogeintrag = ?)";
			}
			$sql = substr($sql, 7);
			$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC");
			$sql->bind_param("iiiiiiiiiii", $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id'], $daten['id']);
			if (count($CMS_URL)>0) {$link = $CMS_URL[0];}
			else {$link = "Website";}
			if ($sql->execute()) {
				$sql->bind_result($gid, $ggruppe, $gbez, $gicon);
				while ($sql->fetch()) {
					if (strlen($zugehoerigladen) == 0) {$zugehoerigladen = "cms_zugehoerig_laden('cms_zugehoerig_".$daten['id']."', '".$zeiten['jahr']."', '$ggruppe', '$gid', '$link');";}
					$event = " onclick=\"cms_zugehoerig_laden('cms_zugehoerig_".$daten['id']."', '".$zeiten['jahr']."', '$ggruppe', '$gid', '$link')\"";
					$verknuepfung .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/$gicon')\"$event>$gbez</span></li>";
				}
			}
			$sql->close();
			if (strlen($verknuepfung) > 0) {
				$code .= "<h2>Zugehörige Gruppen</h2><ul class=\"cms_termindetails\">$verknuepfung</ul>";
				$code .= "<div class=\"cms_zugehoerig\" id=\"cms_zugehoerig_".$daten['id']."\"></div>";
				$code .= "<script>$zugehoerigladen</script>";
			}
		}

		return $code;
	}

	function cms_blogeintrag_dauerdetail($daten, $zeiten) {
		return cms_tagnamekomplett($zeiten['wochentag']).", ".$zeiten['tag'].". ".cms_monatsnamekomplett($zeiten['monat'])." ".$zeiten['jahr'];
	}

	function cms_beschluss_ausgeben($b, $link = false, $url = "") {
	  if ($link) {
	    $jahr = date('Y', $b['datum']);
	    $monat = cms_monatsnamekomplett(date('m', $b['datum']));
	    $tag = date('d', $b['datum']);
	    $url = $url."/Blog/".$jahr."/".$monat."/".$tag."/".cms_textzulink($b['bezeichnung']);
	  }
	  $code = "";
	  if (($b['pro'] > $b['contra']) && ($b['pro'] > $b['enthaltung'])) {$zusatz = "pro";}
	  else if (($b['contra'] > $b['pro']) && ($b['contra'] > $b['enthaltung'])) {$zusatz = "contra";}
	  else {$zusatz = "enthaltung";}
	  if ($link) {$code .= "<a class=\"cms_beschluss cms_beschluss_$zusatz\" href=\"$url\">";}
	  else {$code .= "<div class=\"cms_beschluss cms_beschluss_$zusatz\">";}
	  $code .= "<h4>".$b['titel']."</h4>";
	  $code .= "<p>".cms_textaustextfeld_anzeigen($b['beschreibung'])."</p>";
	  $code .= "<p class=\"cms_beschluss_stimmen\"><span class=\"cms_beschluss_stimmen_pro\">".$b['pro']."</span><span class=\"cms_beschluss_stimmen_enthaltung\">".$b['enthaltung']."</span><span class=\"cms_beschluss_stimmen_contra\">".$b['contra']."</span> ";
	  if ($b['langfristig'] == 1) {$code .= "<span class=\"cms_beschluss_langfristig\">langfristig</span>";}
	  $code .= "</p>";
	  if ($link) {$code .= "</a>";}
	  else {$code .= "</div>";}
	  return $code;
	}

	$fehler = false;
	$gefunden = false;
	if (($CMS_URL[0] == 'Schulhof') || ($CMS_URL[0] == 'Website')) {
		if (count($CMS_URL) == 6) {
			$jahr = $CMS_URL[2];
			$monat = cms_monatnamezuzahl($CMS_URL[3]);
			$tag = $CMS_URL[4];
			$blogeintragbez = cms_linkzutext($CMS_URL[5]);
			$datum = mktime(0, 0, 0, $monat, $tag, $jahr);
			$tabelle = "blogeintraege";
			$tabelledownload = "blogeintragdownloads";
			$gruppe = "Blogeinträge";
			$vorschaubild = "AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL')";
			$oeffentlichkeit = 'oeffentlichkeit';
			$art = 'oe';
		}
		else if (count($CMS_URL) == 10) {
			$schuljahr = cms_linkzutext($CMS_URL[2]);
			$g = cms_linkzutext($CMS_URL[3]);
			$gk = cms_textzudb($g);
			$gbez = cms_linkzutext($CMS_URL[4]);
			$gruppenid = "";

			$jahr = $CMS_URL[6];
			$monat = cms_monatnamezuzahl($CMS_URL[7]);
			$tag = $CMS_URL[8];
			$blogeintragbez = cms_linkzutext($CMS_URL[9]);
			$datum = mktime(0, 0, 0, $monat, $tag, $jahr);
			$gruppe = cms_linkzutext($CMS_URL[3]);
			if (!cms_valide_gruppe($gruppe)) {$fehler = true;}
			$gk = cms_textzudb($gruppe);
			$tabelle = $gk."blogeintraegeintern";
			$tabelledownload = $gk."blogeintragdownloads";
			$tabellebeschluesse = $gk."blogeintragbeschluesse";
			$vorschaubild = "''";
			$oeffentlichkeit = "'0' AS oeffentlichkeit";
			$art = 'in';
		}

		if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
		if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
		if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}
	}
	$CMS_EINSTELLUNGEN;
	if($art == "in") {
		$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
		// Prüfen, ob diese Gruppe existiert
		if (in_array($g, $CMS_GRUPPEN)) {
			if ($schuljahr == "Schuljahrübergreifend") {
				$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
				$sql->bind_param("s", $gbez);
			}
			else {
				$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
				$sql->bind_param("ss", $gbez, $schuljahr);
			}
			// Schuljahr finden
			if ($sql->execute()) {
				$sql->bind_result($gruppenid, $icon, $sichtbar, $chataktiv, $anzahl);
				if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
				else {$fehler = true;}
			}
			else {$fehler = true;}
			$sql->close();
		}
		else {$fehler = true;}
	}

	if (!$fehler) {
		// Blogeintrag finden
		$blogeintrag = array();
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, $oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, $vorschaubild AS vorschaubild, '$art' AS art, aktiv FROM $tabelle WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ?;");
		$sql->bind_param("si", $blogeintragbez, $datum);
		if ($sql->execute()) {
			$sql->bind_result($bid, $bbez, $bautor, $bdatum, $bgenehmigt, $baktiv, $boeff, $btext, $bvorschau, $bvorschbild, $bart, $aktiv);
			if ($sql->fetch()) {
				$blogeintrag['id'] = $bid;
				$blogeintrag['bezeichnung'] = $bbez;
				$blogeintrag['autor'] = $bautor;
				$blogeintrag['datum'] = $bdatum;
				$blogeintrag['genehmigt'] = $bgenehmigt;
				$blogeintrag['aktiv'] = $baktiv;
				$blogeintrag['oeffentlichkeit'] = $boeff;
				$blogeintrag['text'] = $btext;
				$blogeintrag['vorschau'] = $bvorschau;
				$blogeintrag['vorschaubild'] = $bvorschbild;
				$blogeintrag['art'] = $bart;
				$blogeintrag['aktiv'] = $aktiv;
				$gefunden = true;
			}
		}
		$sql->close();

		if($gefunden) {
			if ($jahr != date('Y', $blogeintrag['datum'])) {$gefunden = false;}
			if ($monat != date('m', $blogeintrag['datum'])) {$gefunden = false;}
			if ($tag != date('d', $blogeintrag['datum'])) {$gefunden = false;}
		}

		if (($gefunden) && ($art == 'oe')) {
			$gefunden = $gefunden && (isset($blogeintrag["aktiv"]) && $blogeintrag["aktiv"]);
			$gefunden = cms_oeffentlich_sichtbar($dbs, 'blogeintraege', $blogeintrag);
		}
		else if ($gefunden) {
			$gruppenrecht = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
			$gefunden = $gefunden && $gruppenrecht['sichtbar'] && ((isset($blogeintrag["aktiv"]) && $blogeintrag["aktiv"]) || $gruppenrecht['blogeintraege']);
		}

		if ($gefunden) {
			$code .= "<p class=\"cms_brotkrumen\">";
			$code .= cms_brotkrumen($CMS_URL, false);
			$code .= "</p>";

			$druckfehler = false;
			$zeiten = cms_blogeintrag_zeiten($blogeintrag);

			// Schnellinfos
			$kalender = "<div class=\"cms_termin_detialkalenderblatt\">";
				$kalender .= "<span class=\"cms_kalenderblaetter\">";
					$kalender .= "<span class=\"cms_kalenderblatt\">";
						$kalender .= "<span class=\"cms_kalenderblatt_i\">";
							$kalender .= "<span class=\"cms_kalenderblatt_monat\">".cms_monatsname($zeiten['monat'])."</span>";
							$kalender .= "<span class=\"cms_kalenderblatt_tagnr\">".$zeiten['tag']."</span>";
							$kalender .= "<span class=\"cms_kalenderblatt_tagbez\">".cms_tagname($zeiten['wochentag'])."</span>";
						$kalender .= "</span>";
					$kalender .= "</span>";
				$kalender .= "</span>";
			$kalender .= "</div>";

			$downloads = array();
			// Downloads suchen
			$sql = $dbs->prepare("SELECT * FROM (SELECT id, blogeintrag, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), dateiname, dateigroesse FROM $tabelledownload WHERE blogeintrag = ?) AS x ORDER BY titel ASC");
			$sql->bind_param("i", $blogeintrag['id']);
			if ($sql->execute()) {
				$sql->bind_result($did, $dbeintrag, $dpfad, $dtitel, $dbeschr, $ddateiname, $ddateigroesse);
				while ($sql->fetch()) {
					$D = array();
					$D['id'] = $did;
					$D['blogeintrag'] = $dbeintrag;
					$D['pfad'] = $dpfad;
					$D['titel'] = $dtitel;
					$D['beschreibung'] = $dbeschr;
					$D['dateiname'] = $ddateiname;
					$D['dateigroesse'] = $ddateigroesse;
					array_push($downloads, $D);
				}
			}
			$sql->close();

			// Beschlüsse laden
			$beschluesse = array();
			if ($art == 'in') {
				$sql = $dbs->prepare("SELECT * FROM (SELECT id, blogeintrag, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), pro, contra, enthaltung FROM $tabellebeschluesse WHERE blogeintrag = ?) AS x ORDER BY titel ASC");
				$sql->bind_param("i", $blogeintrag['id']);
				if ($sql->execute()) {
					$sql->bind_result($bid, $bbeintrag, $btitel, $blangfristig, $bbeschreibung, $bpro, $bcontra, $benthaltung);
					while ($sql->fetch()) {
						$B = array();
						$B['id'] = $bid;
						$B['blogeintrag'] = $bbeintrag;
						$B['titel'] = $btitel;
						$B['langfristig'] = $blangfristig;
						$B['beschreibung'] = $bbeschreibung;
						$B['pro'] = $bpro;
						$B['contra'] = $bcontra;
						$B['enthaltung'] = $benthaltung;
						array_push($beschluesse, $B);
					}
				}
				$sql->close();
			}



			$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">".$kalender."</div></div>";

			$code .= "<div class=\"cms_spalte_23\"><div class=\"cms_spalte_i\">";
				$code .= "<h1 style=\"margin-top: 0\">".$blogeintrag['bezeichnung']."</h1>";
				$code .= cms_blogeintragdetailansicht_blogeintraginfos($dbs, $blogeintrag, $zeiten);
			$code .= "</div></div>";

			$code .= "<div class=\"cms_clear\"></div>";

			$code .= "<div class=\"cms_spalte_i\">";
			$code .= $blogeintrag['text'];
			$code .= "</div>";

			if (count($downloads) > 0) {
				$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Zugehörige Downloads</h2>";
				if ($art == 'oe') {foreach ($downloads as $d) {$code .= cms_schulhof_download_ausgeben($d);}}
				else if ($art == 'in') {
					foreach ($downloads as $d) {
						$d['gruppenid'] = $gruppenid;
						$code .= cms_schulhof_interndownload_ausgeben($d);
					}
				}
				$code .= "</div></div>";
			}
			if (count($beschluesse) > 0) {
				$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
				$code .= "<h2>Zugehörige Beschlüsse</h2>";
				foreach ($beschluesse as $b) {
					$code .= cms_beschluss_ausgeben($b);
				}
				$code .= "</div></div>";
			}

			$code .= "<div class=\"cms_clear\"></div>";
		}
	}
	return $code;

?>
