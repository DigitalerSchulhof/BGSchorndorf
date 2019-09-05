<?php
function cms_galerie_link_ausgeben($dbs, $daten, $art, $internvorlink = "") {
	global $CMS_URL, $CMS_GRUPPEN, $CMS_SCHLUESSEL;

	$code = "";
	$bezeichnunglink = cms_textzulink($daten['bezeichnung']);

	$zeiten = cms_galerie_zeiten($daten);

	$code .= "<li>";
	$link = "Website/Galerien/";

	$link .= $zeiten['jahr']."/".$zeiten['monatname']."/".$zeiten['tag']."/$bezeichnunglink";

	if ($daten['genehmigt'] == 0) {$zusatzklasse = " cms_nicht_genehmigt";} else {$zusatzklasse = "";}

	$code .= "<a class=\"cms_galerielink$zusatzklasse\" href=\"$link\"><div class=\"cms_galerielinkinnen\">";
	if ($art == 'liste') {$code .= cms_galerie_kalenderblatterzeugen($daten, $zeiten);}
	$code .= "<p class=\"cms_notiz\">".cms_galerie_dauernotiz($daten, $zeiten)."</p>";

	if ($art == 'artikel') {$code .= "<h3>".$daten['bezeichnung']."</h3>";}

	if (strlen($daten['vorschaubild']) > 0) {
		$code .= "<img class=\"cms_bloglink_vorschaubild\" src=\"".$daten['vorschaubild']."\">";
	}
	if ($art == 'liste') {$code .= "<h3>".$daten['bezeichnung']."</h3>";}
	$code .= "<p>".$daten['beschreibung']."</p>";

	$code .= "<p><span class=\"cms_button\" href=\"$link\">Galerie öffnen ...</span></p>";

	$code .= cms_galerie_zusatzinfo($dbs, $daten);
	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "</div>";
	$code .= "</a>";
	$code .= "</li>";
	return $code;
}

function cms_galerie_zusatzinfo($dbs, $daten) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "";

	if (strlen($daten['autor']) > 0) {$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/autor.png')\">".$daten['autor']."</span> ";}

	$sql = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sqlsolo =
		$sql .= " UNION (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."galerien ON $gk.id = $gk"."galerien.gruppe WHERE galerie = ".$daten['id'].")";
	}
	$sql = substr($sql, 7);
	$sql = "SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/".$daten['icon']."')\">".$daten['bezeichnung']."</span> ";
		}
		$anfrage->free();
	}
	if (strlen($code > 0)) {$code = "<p>".$code."</p>";}
	return $code;
}

function cms_galerie_kalenderblatterzeugen($daten, $zeiten) {
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

function cms_galerie_dauernotiz($daten, $zeiten) {
	return cms_tagname($zeiten['wochentag'])." ".$zeiten['tag'].". ".cms_monatsnamekomplett($zeiten['monat']);
}

function cms_galerie_dauerdetail($daten, $zeiten) {
	return cms_tagnamekomplett($zeiten['wochentag']).", ".$zeiten['tag'].". ".cms_monatsnamekomplett($zeiten['monat'])." ".$zeiten['jahr'];
}

function cms_galerie_zeiten($daten) {
	$zeiten['jahr'] = date('Y', $daten['datum']);
	$zeiten['monat'] = date('m', $daten['datum']);
	$zeiten['monatname'] = cms_monatsnamekomplett($zeiten['monat']);
	$zeiten['tag'] = date('d', $daten['datum']);
	$zeiten['wochentag'] = date('w', $daten['datum']);
	return $zeiten;
}

function cms_galerie_ausgeben($dbs) {
	global $CMS_URL, $CMS_URLGANZ, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_RECHTE, $CMS_GALERIEID;
	$code = "";
	$gefunden = false;
	$fehler = false;

	$jahr = $CMS_URL[2];
	$monat = cms_monatnamezuzahl($CMS_URL[3]);
	$tag = $CMS_URL[4];
	$bez = cms_linkzutext($CMS_URL[5]);
	$datum = mktime(0, 0, 0, $monat, $tag, $jahr);

	if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
	if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}

	if (!$fehler) {
		$galerie = array();

		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE bezeichnung = AES_ENCRYPT('$bez', '$CMS_SCHLUESSEL') AND datum = $datum AND aktiv = 1;";
		if ($anfrage = $dbs->query($sql)) {
			if ($galerie = $anfrage->fetch_assoc()) {
				$gefunden = true;
			}
			else {$fehler = true;}
			$anfrage->free();
	  }
	  else {$fehler = true;}

		if ($gefunden) {
			if ($jahr != date('Y', $galerie['datum'])) {$gefunden = false;}
			if ($monat != date('m', $galerie['datum'])) {$gefunden = false;}
			if ($tag != date('d', $galerie['datum'])) {$gefunden = false;}
		}
		if ($gefunden) {
			$code .= "</div>";
			$zeiten = cms_galerie_zeiten($galerie);
			// Schnellinfos
			$kalender = "<div class=\"cms_termin_detialkalenderblatt\">".cms_galerie_kalenderblatterzeugen($galerie, $zeiten)."</div>";
			$kalender .= "<div class=\"cms_termin_detailinformationen\">".cms_galeriedetailansicht_galerieinfos($dbs, $galerie, $zeiten)."</div>";

			$bilder = array();

			$sql = "SELECT * FROM (SELECT id, galerie, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM galerienbilder WHERE galerie = ".$galerie['id'].") AS x";
			if ($anfrage = $dbs->query($sql)) {
				while ($daten = $anfrage->fetch_assoc()) {
					array_push($bilder, $daten);
				}
				$anfrage->free();
			}

			$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">".$kalender."";

			$code .= "<div style=\"text-align: right;\"><br><h1>".$galerie['bezeichnung']."</h1>";
			$code .= $galerie['beschreibung']."</div></div></div>";
			$code .= "<div class=\"cms_spalte_6\"><div class=\"cms_spalte_i\">";
			if($galerie["vorschaubild"] != "")
				$code .= "<img style=\"border-radius: 1.5px; cursor: pointer;\" onclick=\"cms_link('".$galerie["vorschaubild"]."', true)\" src=\"".$galerie["vorschaubild"]."\">";
			else
				$code .= "<p>Kein Vorschaubild ausgewählt</p>";
			$code .= "</div></div>";

			$code .= "<div style=\"width:100%; float: left\"><div class=\"cms_spalte_i\">";



			$code .= "<div class=\"cms_bilder_spalte\">";
			$c = 0;
			if(count($bilder) < 1) {
				$code .= "<p style=\"padding-left: 30px;\">Keine Bilder ausgewählt</p>";
			}
			foreach($bilder as $bild) {
				$code .= "<div class=\"cms_galerie_bild\"><div class=\"cms_galerie_bild_innen\">";
					$code .= "<img onclick=\"cms_link('".$bild["pfad"]."', true)\"src=\"".$bild["pfad"]."\"><br><p>".$bild["beschreibung"]."</p>";
				$code .= "</div></div>";
				if(++$c%ceil(count($bilder)/3)==0)
					$code .= "</div><div class=\"cms_bilder_spalte\">";
			}

			$code .= "</div></div></div>";

			$code .= "<br><br>".cms_artikel_reaktionen("g", $galerie["id"], "-");
			$CMS_GALERIEID = $galerie["id"];
			$code .= "<div class=\"cms_clear\"></div>";
		}
		else {
			$code .= "<h1>Galerie</h1>";
			$code .= cms_meldung('info', '<h4>Galerie nicht verfügbar</h4><p>Diese Galerie ist derzeit nicht verfügbar. Möglicherweise ist sie inaktiv oder sie existiert nicht oder nicht mehr.</p>');
		}

	}
	else {
		$code .= "<h1>Galerie</h1>";
		$code .= cms_meldung('info', '<h4>Galerie nicht verfügbar</h4><p>Diese Galerie ist derzeit nicht verfügbar. Möglicherweise ist sie inaktiv oder sie existiert nicht oder nicht mehr.</p>');
	}
	$CMS_GALERIEID = $galerie["id"];
	return $code;
}

function cms_galeriedetailansicht_ausgeben($dbs) {
	global $CMS_URL, $CMS_URLGANZ, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_RECHTE, $CMS_GALERIEID;
	$code = "";
	$gefunden = false;
	$fehler = false;

	$jahr = $CMS_URL[2];
	$monat = cms_monatnamezuzahl($CMS_URL[3]);
	$tag = $CMS_URL[4];
	$bez = cms_linkzutext($CMS_URL[5]);
	$datum = mktime(0, 0, 0, $monat, $tag, $jahr);

	if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
	if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
	if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}

	if (!$fehler) {
		$galerie = array();

		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE bezeichnung = AES_ENCRYPT('$bez', '$CMS_SCHLUESSEL') AND datum = $datum AND aktiv = 1;";
		if ($anfrage = $dbs->query($sql)) {
			if ($galerie = $anfrage->fetch_assoc()) {
				$gefunden = true;
			}
			else {$fehler = true;}
			$anfrage->free();
	  }
	  else {$fehler = true;}

		if ($gefunden) {
			if ($jahr != date('Y', $galerie['datum'])) {$gefunden = false;}
			if ($monat != date('m', $galerie['datum'])) {$gefunden = false;}
			if ($tag != date('d', $galerie['datum'])) {$gefunden = false;}
		}
		if ($gefunden) {
			$code .= "</div>";
			$zeiten = cms_galerie_zeiten($galerie);
			// Schnellinfos
			$kalender = "<div class=\"cms_termin_detialkalenderblatt\">".cms_galerie_kalenderblatterzeugen($galerie, $zeiten)."</div>";
			$kalender .= "<div class=\"cms_termin_detailinformationen\">".cms_galeriedetailansicht_galerieinfos($dbs, $galerie, $zeiten)."</div>";

			$bilder = array();

			$sql = "SELECT * FROM (SELECT id, galerie, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM galerienbilder WHERE galerie = ".$galerie['id'].") AS x";
			if ($anfrage = $dbs->query($sql)) {
				while ($daten = $anfrage->fetch_assoc()) {
					array_push($bilder, $daten);
				}
				$anfrage->free();
			}

			$aktionen = "";
			if ($CMS_URL[0] == 'Schulhof') {
				$link = $CMS_URLGANZ;
				$linkl = implode('/', array_slice($CMS_URL,0,2));
				if ($CMS_RECHTE['Website']['Galerien bearbeiten']) {
					$aktionen .= "<span class=\"cms_button\" onclick=\"cms_blogeintraege_bearbeiten_vorbereiten('".$galerie['id']."', '$linkl')\">Galerie bearbeiten</span> ";
				}
				if ($CMS_RECHTE['Organisation']['Galerien genehmigen'] && ($galerie['genehmigt'] == 0)) {
					$aktionen .= "<span class=\"cms_button_ja\" onclick=\"cms_blog_genehmigen('Blogeinträge', '".$galerie['id']."', '$link')\">Blogeintrag genehmigen</span> ";
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blog_ablehnen('Blogeinträge', '".$galerie['id']."', '$linkl')\">Blogeintrag ablehnen</span> ";
				}
				if ($CMS_RECHTE['Website']['Galerien löschen']) {
					$aktionen .= "<span class=\"cms_button_nein\" onclick=\"cms_blogeintraege_loeschen_vorbereiten('".$galerie['id']."', '".$galerie['bezeichnung']."', '$linkl')\">Blogeintrag löschen</span> ";
				}
			}

			$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">".$kalender."";

			$code .= "<div style=\"text-align: right;\"><br><h1>".$galerie['bezeichnung']."</h1>";
			$code .= $galerie['beschreibung']."</div></div>";
			if (strlen($aktionen) > 0) {
				$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\"></div></div>";
				$code .= "<div class=\"cms_spalte_2\" style=\"text-align: right;\"><div class=\"cms_spalte_i\">";
					$code .= "<h3>Aktionen</h3><p>".$aktionen."</p>";
				$code .= "</div></div>";
			}
			$code .= "</div>";
			$code .= "<div class=\"cms_spalte_6\"><div class=\"cms_spalte_i\">";
			if($galerie["vorschaubild"] != "")
				$code .= "<img style=\"border-radius: 1.5px; cursor: pointer;\" onclick=\"cms_link('".$galerie["vorschaubild"]."', true)\" src=\"".$galerie["vorschaubild"]."\">";
			else
				$code .= "<p>Kein Vorschaubild ausgewählt</p>";
			$code .= "</div></div>";

			$code .= "<div style=\"width:100%; float: left\"><div class=\"cms_spalte_i\">";



			$code .= "<div class=\"cms_bilder_spalte\">";
			$c = 0;
			if(count($bilder) < 1) {
				$code .= "<p style=\"padding-left: 30px;\">Keine Bilder ausgewählt</p>";
			}
			foreach($bilder as $bild) {
				$code .= "<div class=\"cms_galerie_bild\"><div class=\"cms_galerie_bild_innen\">";
					$code .= "<img onclick=\"cms_link('".$bild["pfad"]."', true)\"src=\"".$bild["pfad"]."\"><br><p>".$bild["beschreibung"]."</p>";
				$code .= "</div></div>";
				if(++$c%ceil(count($bilder)/3)==0)
					$code .= "</div><div class=\"cms_bilder_spalte\">";
			}

			$code .= "</div></div></div>";

			$code .= "<br><br>".cms_artikel_reaktionen("g", $galerie["id"], "-");


			$CMS_GALERIEID = $galerie["id"];
			$code .= "<div class=\"cms_clear\"></div>";
		}
		else {
			$code .= "<h1>Galeriedetailansicht</h1>";
			$code .= cms_meldung('info', '<h4>Galerie nicht verfügbar</h4><p>Diese Galerie ist derzeit nicht verfügbar. Möglicherweise ist sie inaktiv oder sie existiert nicht oder nicht mehr.</p>');
		}

	}
	else {
		$code .= "<h1>Galeriedetailansicht</h1>";
		$code .= cms_meldung('info', '<h4>Galerie nicht verfügbar</h4><p>Diese Galerie ist derzeit nicht verfügbar. Möglicherweise ist sie inaktiv oder sie existiert nicht oder nicht mehr.</p>');
	}
	$CMS_GALERIEID = $galerie["id"];
	return $code;
}

function cms_galeriedetailansicht_galerieinfos($dbs, $daten, $zeiten) {
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL;
	$code = "<h3>".$daten['bezeichnung']."</h3><ul class=\"cms_termindetails\">";
	$code .= "<li>".cms_galerie_dauerdetail($daten, $zeiten)."<li>";

	if (strlen($daten['autor']) > 0) {
		$code .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/autor.png')\">".$daten['autor']."</span></li>";
	}
	if ($daten['genehmigt'] == 0) {
		$code .= "<li class=\"cms_genehmigungausstehend\">!! ACHTUNG !! <br> Die Galerie ist noch nicht genehmigt!</li>";
	}
	$code .= "</ul>";

	$verknuepfung = "";

	$sql = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sqlsolo =
		$sql .= " UNION (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."galerien ON $gk.id = $gk"."galerien.gruppe WHERE galerie = ".$daten['id'].")";
	}
	$sql = substr($sql, 7);
	$sql = "SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC";
	if ($anfrage = $dbs->query($sql)) {
		while ($gruppen = $anfrage->fetch_assoc()) {
			$verknuepfung .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/".$gruppen['icon']."')\">".$gruppen['bezeichnung']."</span></li>";
		}
		$anfrage->free();
	}
	if (strlen($verknuepfung) > 0) {
		$code .= "<h3>Zugehörige Gruppen</h3><ul class=\"cms_termindetails\">$verknuepfung</ul>";
	}

	return $code;
}
?>
