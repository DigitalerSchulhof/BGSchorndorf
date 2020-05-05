<?php
function cms_blogeintrag_link_ausgeben($dbs, $daten, $art, $internvorlink = "") {
	global $CMS_URL, $CMS_GRUPPEN, $CMS_SCHLUESSEL;
	// Interne Termine werden nicht öffentlich ausgegeben
	if (($CMS_URL[0] == 'Website') && ($daten['art'] != 'oe')) {return "";}

	$code = "";
	$bezeichnunglink = cms_textzulink($daten['bezeichnung']);

	$zeiten = cms_blogeintrag_zeiten($daten);

	if ($art != 'diashow') {
		$code .= "<li>";
	}
	$link = "";
	if ($CMS_URL[0] == 'Website') {$link = "Website/Blog/";}
	else if ($CMS_URL[0] == 'Schulhof') {
		if ($daten['art'] == 'oe') {$link = "Schulhof/Blog/";}
		else if ($daten['art'] == 'in') {$link = $internvorlink."/Blog/";}
	}
	$link .= $zeiten['jahr']."/".$zeiten['monatname']."/".$zeiten['tag']."/$bezeichnunglink";
	if ($daten['genehmigt'] == 0) {$zusatzklasse = " cms_nicht_genehmigt";} else {$zusatzklasse = "";}
	$code .= "<a class=\"cms_bloglink$zusatzklasse\" href=\"$link\"><div class=\"cms_bloglinkinnen\">";
	if ($art == 'liste') {$code .= cms_blogeintrag_kalenderblatterzeugen($daten, $zeiten);}
	$code .= "<p class=\"cms_notiz\">".cms_blogeintrag_dauernotiz($daten, $zeiten)."</p>";

	if ($art == 'artikel') {$code .= "<h3>".$daten['bezeichnung']."</h3>";}

	if (strlen($daten['vorschaubild']) > 0) {
		if ($daten['art'] == 'oe') {$code .= "<img class=\"cms_bloglink_vorschaubild\" src=\"".$daten['vorschaubild']."\">";}
			else {$code .= "<img class=\"cms_bloglink_vorschaubild\" src=\"".cms_generiere_bilddaten($daten['vorschaubild'])."\">";}
	}
	if (($art == 'liste') || ($art == 'diashow')) {$code .= "<h3>".$daten['bezeichnung']."</h3>";}
	$code .= "<p>".$daten['vorschau']."</p>";

	// Prüfen, ob Downloads vorliegen
	$downloadanzahl = 0;
	if ($daten['art'] == 'oe') {
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM blogeintragdownloads WHERE blogeintrag = ?");
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
	$code .= cms_blogeintrag_zusatzinfo($dbs, $daten, $internvorlink);
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "</div>";
	$code .= "</a>";
	if ($art != 'diashow') {
		$code .= "</li>";
	}

	return $code;
}

function cms_blogeintrag_zusatzinfo($dbs, $daten, $internvorlink) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "";

	/*if ($CMS_URL[0] == 'Schulhof') {
		if ($daten['art'] == 'oe') {$hinweis = 'öffentlich';}
		else {$hinweis = 'gruppenintern';}
		$code .= "<span class=\"cms_icon_klein_o cms_gruppen_oeffentlich_art\"><span class=\"cms_hinweis\">$hinweis</span><span class=\"cms_".$daten['art']."\"></span></span>";
	}*/

	if ($daten['art'] == 'in') {
		$gruppeninfos = explode("/", cms_linkzutext($internvorlink));
		$gruppenartbez = $gruppeninfos[count($gruppeninfos)-2];
		$gruppenbez = $gruppeninfos[count($gruppeninfos)-1];
		$code .= "<span class=\"cms_kalender_zusatzinfo cms_kalender_zusatzinfo_intern\">Intern – $gruppenartbez » $gruppenbez</span> ";
	}

	if (strlen($daten['autor']) > 0) {$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/autor.png')\">".$daten['autor']."</span> ";}

	// Bei öffentlichen Terminen zugehörige Kategorien suchen
	if ($daten['art'] == 'oe') {
		$sql = "";
		foreach ($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			$sqlsolo =
			$sql .= " UNION (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."blogeintraege ON $gk.id = $gk"."blogeintraege.gruppe WHERE blogeintrag = ".$daten['id'].")";
		}
		$sql = substr($sql, 7);
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC");
		if ($sql->execute()) {
			$sql->bind_result($kbez, $kicon);
			while ($sql->fetch()) {
				$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/$kicon')\">$kbez</span> ";
			}
		}
		$sql->close();
	}
	if (strlen($code > 0)) {$code = "<p>".$code."</p>";}
	return $code;
}

function cms_blogeintrag_kalenderblatterzeugen($daten, $zeiten) {
	$code = "<span class=\"cms_kalenderblaetter\">";
	$code .= "<span class=\"cms_kalenderblatt\">";
	$code .= "<span class=\"cms_kalenderblatt_i\">";
		$code .= "<span class=\"cms_kalenderblatt_monat\">".cms_monatsname($zeiten['monat'])."</span>";
		$code .= "<span class=\"cms_kalenderblatt_tagnr\">".$zeiten['tag']."</span>";
		$code .= "<span class=\"cms_kalenderblatt_tagbez\">".cms_tagname($zeiten['wochentag'])."</span>";
	$code .= "</span>";
	$code .= "</span>";
	$code .= "</span>";
	return $code;
}

function cms_blogeintrag_dauernotiz($daten, $zeiten) {
	return cms_tagname($zeiten['wochentag'])." ".$zeiten['tag'].". ".cms_monatsnamekomplett($zeiten['monat']);
}

function cms_blogeintrag_dauerdetail($daten, $zeiten) {
	return cms_tagnamekomplett($zeiten['wochentag']).", ".$zeiten['tag'].". ".cms_monatsnamekomplett($zeiten['monat'])." ".$zeiten['jahr'];
}

function cms_blogeintrag_zeiten($daten) {
	$zeiten['jahr'] = date('Y', $daten['datum']);
	$zeiten['monat'] = date('m', $daten['datum']);
	$zeiten['monatname'] = cms_monatsnamekomplett($zeiten['monat']);
	$zeiten['tag'] = date('d', $daten['datum']);
	$zeiten['wochentag'] = date('w', $daten['datum']);
	return $zeiten;
}

function cms_blogeintragdetailansicht_ausgeben($dbs, $gruppenid = "-") {
	global $CMS_URL, $CMS_URLGANZ, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BLOGID;
	$code = "";
	$gefunden = false;
	$fehler = false;

	if (($CMS_URL[0] == 'Schulhof') || ($CMS_URL[0] == 'Website')) {
		if (count($CMS_URL) == 6) {
			$jahr = $CMS_URL[2];
			$monat = cms_monatnamezuzahl($CMS_URL[3]);
			$tag = $CMS_URL[4];
			$blogeintragbez = cms_linkzutext($CMS_URL[5]);
			$datum = mktime(0, 0, 0, $monat, $tag, $jahr);
			$tabelle = "blogeintraege";
			$tabelledownload = "blogeintragdownloads";
			$tabellelink = "blogeintraglinks";
			$gruppe = "Blogeinträge";
			$vorschaubild = "AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL')";
			$oeffentlichkeit = 'oeffentlichkeit';
			$art = 'oe';
		}
		else if (count($CMS_URL) == 10) {
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
			$tabellelink = $gk."blogeintraglinks";
			$tabellebeschluesse = $gk."blogeintragbeschluesse";
			$vorschaubild = "''";
			$oeffentlichkeit = "'0' AS oeffentlichkeit";
			$art = 'in';
		}

		if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
		if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
		if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}
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
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

		if ($gefunden) {	// Nur für Notifikation
			if ($CMS_URL[0] == 'Schulhof') {
				$sql = $dbs->prepare("DELETE FROM notifikationen WHERE person = ? AND art = 'b' AND gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND zielid = ?");
			  $sql->bind_param("isi", $CMS_BENUTZERID, $gruppe, $blogeintrag['id']);
			  $sql->execute();
			  $sql->close();
			}
		}

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
			$code .= "</div>";
			$zeiten = cms_blogeintrag_zeiten($blogeintrag);
			// Schnellinfos
			$kalender = "<div class=\"cms_termin_detialkalenderblatt\">".cms_blogeintrag_kalenderblatterzeugen($blogeintrag, $zeiten)."</div>";
			$kalender .= "<div class=\"cms_termin_detailinformationen\">".cms_blogeintragdetailansicht_blogeintraginfos($dbs, $blogeintrag, $zeiten)."<p>".$blogeintrag["vorschau"]."</p></div>";

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

			// Links laden
			$links = array();
			$sql = $dbs->prepare("SELECT * FROM (SELECT id, blogeintrag, AES_DECRYPT(link, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') FROM $tabellelink WHERE blogeintrag = ?) AS x ORDER BY titel ASC");
			$sql->bind_param("i", $blogeintrag['id']);
			if ($sql->execute()) {
				$sql->bind_result($lid, $lbeintrag, $llink, $ltitel, $lbeschr);
				while ($sql->fetch()) {
					$L = array();
					$L['id'] = $lid;
					$L['blogeintrag'] = $lbeintrag;
					$L['link'] = $llink;
					$L['titel'] = $ltitel;
					$L['beschreibung'] = $lbeschr;
					array_push($links, $L);
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

			// ToDo Infos laden
			$todoinfo = "";
			if($art == 'in') {
				$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
				if($gruppenrechte['blogeintraege'] == '1') {
					$sql = "SELECT * FROM (SELECT AES_DECRYPT(p.art, '$CMS_SCHLUESSEL') as a, COUNT(*) as c FROM {$gk}todoartikel as t JOIN personen as p ON p.id = t.person WHERE t.blogeintrag = ? GROUP BY a) as x WHERE x.c > 0 ORDER BY FIELD(a, 's', 'l', 'e', 'v', 'x')";
					$sql = $dbs->prepare($sql);
					$sql->bind_param("i", $blogeintrag['id']);
					$sql->bind_result($art, $count);
					$sql->execute();
					while ($sql->fetch()) {
						$numeri = array();
						switch($art) {
							case 's':
								$numeri[] = "Schüler";
								$numeri[] = "Schüler";
								break;
							case 'l';
								$numeri[] = "Lehrer";
								$numeri[] = "Lehrer";
								break;
							case 'e';
								$numeri[] = "Eltern";
								$numeri[] = "Eltern";
								break;
							case 'v';
								$numeri[] = "Verwaltungsangestellte(r)";
								$numeri[] = "Verwaltungsangestellte";
								break;
							case 'x';
								$numeri[] = "Externe(r)";
								$numeri[] = "Externe";
								break;
							default:
								break;
						}
						if($count == 1) {
							$todoinfo .= "<b>$count {$numeri['0']}</b>, ";
						} else {
							$todoinfo .= "<b>$count {$numeri['1']}</b>, ";
						}
					}
					$sql->close();
					$todoinfo = substr($todoinfo, 0, -2);
				}
			}

			// Aktionen ermitteln, falls im Schulhof
			$aktionen = "";
			if ($CMS_URL[0] == 'Schulhof') {
				if ($blogeintrag['art'] == 'oe') {
					$link = $CMS_URLGANZ;
					$linkl = implode('/', array_slice($CMS_URL,0,2));
					if (cms_r("artikel.{$blogeintrag['oeffentlichkeit']}.blogeinträge.bearbeiten")) {
						$aktionen .= "<span class=\"cms_button\" onclick=\"cms_blogeintraege_bearbeiten_vorbereiten('".$blogeintrag['id']."', '$linkl')\">Blogeintrag bearbeiten</span> ";
					}
					if (cms_r("artikel.genehmigen.blogeinträge") && ($blogeintrag['genehmigt'] == 0)) {
						$aktionen .= "<span class=\"cms_button_ja\" onclick=\"cms_blog_genehmigen('Blogeinträge', '".$blogeintrag['id']."', '$link')\">Blogeintrag genehmigen</span> ";
						$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blog_ablehnen('Blogeinträge', '".$blogeintrag['id']."', '$linkl')\">Blogeintrag ablehnen</span> ";
					}
					if (cms_r("artikel.{$blogeintrag['oeffentlichkeit']}.blogeinträge.löschen")) {
						$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blogeintraege_loeschen_vorbereiten('".$blogeintrag['id']."', '".$blogeintrag['bezeichnung']."', '$linkl')\">Blogeintrag löschen</span> ";
					}
				}
				else if ($blogeintrag['art'] == 'in') {
					$gruppenrechte = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
					$link = $CMS_URLGANZ;
					$linkl = implode('/', array_slice($CMS_URL,0,6));
					if ($gruppenrechte['blogeintraege'] == '1') {
						$aktionen .= "<span class=\"cms_button\" onclick=\"cms_blogeintraegeintern_bearbeiten_vorbereiten('".$blogeintrag['id']."', '$linkl')\">Blogeintrag bearbeiten</span> ";
					}
					if (($blogeintrag['genehmigt'] == 0) && cms_r("schulhof.gruppen.$gruppe.artikel.blogeinträge.genehmigen")) {
						$aktionen .= "<span class=\"cms_button_ja\" onclick=\"cms_blog_genehmigen('$gruppe', '".$blogeintrag['id']."', '$link')\">Blogeintrag genehmigen</span> ";
						$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blog_ablehnen('$gruppe', '".$blogeintrag['id']."', '$linkl')\">Blogeintrag ablehnen</span> ";
					}
					if ($gruppenrechte['blogeintraege'] == '1') {
						$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blogeintraegeintern_loeschen_vorbereiten('".$blogeintrag['id']."', '$gruppe', '$gruppenid', '".$blogeintrag['bezeichnung']."', '$linkl')\">Blogeintrag löschen</span> ";
					}
				}
			}


			if ((count($downloads) > 0) || (strlen($aktionen) > 0)) {$spaltenart = '2';}
			else {$spaltenart = '34';}

			$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">".$kalender."</div></div>";

			$code .= "<div class=\"cms_spalte_$spaltenart\"><div class=\"cms_spalte_i\">";
			$code .= "<h1>".$blogeintrag['bezeichnung']."</h1>";
			if(strlen($blogeintrag['vorschaubild']) > 0) {
				if ($blogeintrag['art'] == 'oe') {$code .= "<p><img src=\"".$blogeintrag['vorschaubild']."\"></p>";}
				else {$code .= "<p><img src=\"".cms_generiere_bilddaten($blogeintrag['vorschaubild'])."\"></p>";}
			}
			$code .= cms_ausgabe_editor($blogeintrag['text']);

			$code .= "</div></div>";

			if ((count($downloads) > 0) || (strlen($aktionen) > 0) || (count($beschluesse) > 0) || (strlen($todoinfo) > 0) || (count($links) > 0)) {
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
				if (count($links) > 0) {
					$code .= "<h3>Zugehörige Links</h3>";
					foreach ($links as $l) {
						$code .= cms_artikellink_ausgeben($l);
					}
				}
				if (count($beschluesse) > 0) {
					$code .= "<h3>Zugehörige Beschlüsse</h3>";
					foreach ($beschluesse as $b) {
						$code .= cms_beschluss_ausgeben($b);
					}
				}
				if(strlen($todoinfo) > 0) {
					$code .= "ToDo: $todoinfo";
				}
				if (strlen($aktionen) > 0) {
					$code .= "<h3>Aktionen</h3><p>".$aktionen."</p>";
				}
				$code .= "</div></div>";
			}


			$CMS_BLOGID = $blogeintrag["id"];
			$code .= "<div class=\"cms_clear\"></div>";
		}
		else {
			$code .= "<h1>Blogeintragdetailansicht</h1>";
			$code .= cms_meldung('info', '<h4>Blogeintrag nicht verfügbar</h4><p>Dieser Blogeintrag ist derzeit nicht verfügbar. Möglicherweise ist er inaktiv oder er existiert nicht oder nicht mehr.</p>');
		}

	}
	else {
		$code .= "<h1>Blogeintragdetailansicht</h1>";
		$code .= cms_meldung('info', '<h4>Blogeintrag nicht verfügbar</h4><p>Dieser Blogeintrag ist derzeit nicht verfügbar. Möglicherweise ist er inaktiv oder er existiert nicht oder nicht mehr.</p>');
	}
	return $code;
}

function cms_blogeintragdetailansicht_blogeintraginfos($dbs, $daten, $zeiten) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "<h3>".$daten['bezeichnung']."</h3><ul class=\"cms_termindetails\">";
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
			$code .= "<h3>Zugehörige Gruppen</h3><ul class=\"cms_termindetails\">$verknuepfung</ul>";
			$code .= "<div class=\"cms_zugehoerig\" id=\"cms_zugehoerig_".$daten['id']."\"></div>";
			$code .= "<script>$zugehoerigladen</script>";
		}
	}

	return $code;
}



function cms_letzte_blogeintraege_ausgeben($anzahl, $dbs, $art, $CMS_URLGANZ) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERART;
	if (!cms_check_ganzzahl($anzahl, 0)) {return "";}

	$code = "";
	$jetzt = time();

	$mitgliedschaftenblogs = array();

	$sqlm = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sqlm .= " UNION (SELECT DISTINCT blogeintrag AS id FROM $gk"."blogeintraege WHERE gruppe IN (SELECT DISTINCT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID))";
	}
	$sqlm = substr($sqlm, 7);
	$sqlm = "SELECT DISTINCT id FROM ($sqlm) AS x";

	// Öffentliche Termine
	if ($CMS_BENUTZERART == 'l') {$oelimit = 1;}
	else if ($CMS_BENUTZERART == 'v') {$oelimit = 2;}
	else if (($CMS_BENUTZERART == 's') || ($CMS_BENUTZERART == 'e')) {$oelimit = 3;}
	else {$oelimit = 4;}

	$sqloe = "(SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art, '' AS schuljahr, '' AS sjbez, '' AS gbez, '' AS gart FROM blogeintraege WHERE (id IN ($sqlm) OR oeffentlichkeit >= $oelimit) AND (datum < $jetzt) AND aktiv = 1 ORDER BY datum DESC LIMIT $anzahl)";

	$sqlin = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sqlin .= " UNION (SELECT $gk"."blogeintraegeintern.id, AES_DECRYPT($gk"."blogeintraegeintern.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, NULL AS vorschaubild, 'in' AS art, schuljahr, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, '$g' AS gart FROM $gk"."blogeintraegeintern JOIN $gk ON gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID) AND (datum < $jetzt) AND aktiv = 1 ORDER BY datum DESC LIMIT $anzahl)";
	}


	$BLOGS = array();
	$sql = $dbs->prepare("SELECT * FROM ($sqloe $sqlin) AS x ORDER BY datum DESC, bezeichnung ASC LIMIT $anzahl");
	// Blogausgabe erzeugen
	if ($sql->execute()) {
		$sql->bind_result($bid, $bbez, $bautor, $bdatum, $bgenehmigt, $baktiv, $btext, $bvorschau, $bvorschaubild, $bart, $bschuljahr, $bsjbez, $bgbez, $bgart);
		while ($sql->fetch()) {
			$B = array();
			$B['id'] = $bid;
			$B['bezeichnung'] = $bbez;
			$B['autor'] = $bautor;
			$B['datum'] = $bdatum;
			$B['genehmigt'] = $bgenehmigt;
			$B['aktiv'] = $baktiv;
			$B['text'] = $btext;
			$B['vorschau'] = $bvorschau;
			$B['vorschaubild'] = $bvorschaubild;
			$B['art'] = $bart;
			$B['schuljahr'] = $bschuljahr;
			$B['sjbez'] = $bsjbez;
			$B['gbez'] = $bgbez;
			$B['gart'] = $bgart;
			array_push($BLOGS, $B);
		}
	}
	$sql->close();

	foreach ($BLOGS AS $B) {
		if ($B['art'] == 'oe') {
			$code .= cms_blogeintrag_link_ausgeben($dbs, $B, $art, $CMS_URLGANZ);
		}
		else if ($B['art'] == 'in') {
			if (is_null($B['sjbez'])) {$B['sjbez'] = "Schuljahrübergreifend";}
			$vorlink = "Schulhof/Gruppen/".cms_textzulink($B['sjbez'])."/".cms_textzulink($B['gart'])."/".cms_textzulink($B['gbez']);
			$code .= cms_blogeintrag_link_ausgeben($dbs, $B, $art, $vorlink);
		}
	}

	return $code;
}
?>
