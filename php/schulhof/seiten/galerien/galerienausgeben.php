<?php
function cms_galerie_link_ausgeben($dbs, $daten, $art) {
	global $CMS_URL, $CMS_GRUPPEN, $CMS_SCHLUESSEL;

	$code = "";
	$bezeichnunglink = cms_textzulink($daten['bezeichnung']);

	$zeiten = cms_galerie_zeiten($daten);

	$code .= "<li>";

	$link = "";
	if ($CMS_URL[0] == 'Website') {$link = "Website/Galerien/";}
	else if ($CMS_URL[0] == 'Schulhof') {
		$link = "Schulhof/Galerien/";
	}
	$link .= $zeiten['jahr']."/".$zeiten['monatname']."/".$zeiten['tag']."/$bezeichnunglink";

	if ($daten['genehmigt'] == 0) {$zusatzklasse = " cms_nicht_genehmigt";} else {$zusatzklasse = "";}

	$code .= "<a class=\"cms_galerielink$zusatzklasse\" href=\"$link\"><div class=\"cms_galerielinkinnen\">";
	if ($art == 'liste') {$code .= cms_galerie_kalenderblatterzeugen($daten, $zeiten);}
	$code .= "<p class=\"cms_notiz\">".cms_galerie_dauernotiz($daten, $zeiten)."</p>";

	if ($art == 'artikel') {$code .= "<h3>".$daten['bezeichnung']."</h3>";}

	if (strlen($daten['vorschaubild']) > 0) {
		$code .= "<img class=\"cms_galerienlink_vorschaubild\" src=\"".$daten['vorschaubild']."\">";
	}
	if ($art == 'liste') {$code .= "<h3>".$daten['bezeichnung']."</h3>";}
	$code .= "<p>".$daten['beschreibung']."</p>";

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

	// Bei öffentlichen Terminen zugehörige Kategorien suchen
	$sql = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sql .= " UNION (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."galerien ON $gk.id = $gk"."galerien.gruppe WHERE galerie = ".$daten['id'].")";
	}
	$sql = substr($sql, 7);
	$dbs = cms_verbinden("s");
	$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY bezeichnung ASC");
	if ($sql->execute()) {
		$sql->bind_result($bez, $icon);
		while ($sql->fetch()) {
			$code .= "<span class=\"cms_kalender_zusatzinfo\" style=\"background-image:url('res/gruppen/klein/$icon')\">$bez</span> ";
		}
	}
	$sql->close();

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

function cms_galeriedetailansicht_ausgeben($dbs) {
	global $CMS_URL, $CMS_URLGANZ, $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BLOGID;
	$code = "";
	$gefunden = false;
	$fehler = false;

	if (count($CMS_URL) == 6) {
		$jahr = $CMS_URL[2];
		$monat = cms_monatnamezuzahl($CMS_URL[3]);
		$tag = $CMS_URL[4];
		$bez = cms_linkzutext($CMS_URL[5]);
		$datum = mktime(0, 0, 0, $monat, $tag, $jahr);

		if (!cms_check_ganzzahl($jahr,0)) {$fehler = true;}
		if (!cms_check_ganzzahl($monat,1,12)) {$fehler = true;}
		if (!cms_check_ganzzahl($tag,1,31)) {$fehler = true;}
	}
	else {$fehler = true;}

	if (!$fehler) {
		$galerie = array();

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ? AND aktiv = 1;");
		$sql->bind_param("si", $bez, $datum);
		if ($sql->execute()) {
	    $sql->bind_result($gid, $gbez, $gautor, $gdatum, $ggenehmigt, $gaktiv, $goeff, $gtext, $gvorschbild);
	    if ($sql->fetch()) {
				$galerie['id'] = $gid;
				$galerie['bezeichnung'] = $gbez;
				$galerie['autor'] = $gautor;
				$galerie['datum'] = $gdatum;
				$galerie['genehmigt'] = $ggenehmigt;
				$galerie['aktiv'] = $gaktiv;
				$galerie['oeffentlichkeit'] = $goeff;
				$galerie['beschreibung'] = $gtext;
				$galerie['vorschaubild'] = $gvorschbild;
				$gefunden = true;
			}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
		$sql->close();

		if ($gefunden) {	// Nur für Notifikation
			if ($CMS_URL[0] == 'Schulhof') {
				$sql = $dbs->prepare("DELETE FROM notifikationen WHERE person = ? AND art = 'g' AND zielid = ?");
				$sql->bind_param("ii", $CMS_BENUTZERID, $galerie['id']);
				$sql->execute();
				$sql->close();
			}
		}

		$gefunden = $gefunden && isset($galerie["aktiv"]) && $galerie["aktiv"];

		if ($gefunden) {
			if ($jahr != date('Y', $galerie['datum'])) {$gefunden = false;}
			if ($monat != date('m', $galerie['datum'])) {$gefunden = false;}
			if ($tag != date('d', $galerie['datum'])) {$gefunden = false;}

			$code .= "</div>";
			$zeiten = cms_galerie_zeiten($galerie);
			// Schnellinfos
			$kalender = "<div class=\"cms_termin_detialkalenderblatt\">".cms_galerie_kalenderblatterzeugen($galerie, $zeiten)."</div>";
			$kalender .= "<div class=\"cms_termin_detailinformationen\">".cms_galeriedetailansicht_galerieinfos($dbs, $galerie, $zeiten)."</div>";

			$inhalte = array();

			$sql = "SELECT id, galerie, AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') AS pfad, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM galerienbilder WHERE galerie = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $galerie['id']);
			if ($sql->execute()) {
				$sql->bind_result($gbid, $gbgal, $gbpfad, $gbbesch);
				while ($sql->fetch()) {
					$icode = "<img src=\"$gbpfad\">";
					if (strlen($gbbesch) > 0) {$icode .= "<p class=\"cms_wechselbilder_galerie_unterschrift\">$gbbesch</p>";}
					array_push($inhalte, $icode);
				}
				$sql->close();
			}

			$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">".$kalender."</div></div>";

			$code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
			$code .= "<h1>".$galerie['bezeichnung']."</h1>";
			$code .= "<p>".$galerie['beschreibung']."</p>";

			$code .= cms_wechselbilder_generieren($inhalte);

			// Bilder Ende
			$CMS_GALERIEID = $galerie["id"];

			$code .= "</div></div>";
			$code .= "<div class=\"cms_clear\"></div>";


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
	global $CMS_GRUPPEN, $CMS_SCHLUESSEL, $CMS_URL;
	$code = "<h3>".$daten['bezeichnung']."</h3><ul class=\"cms_termindetails\">";
	$code .= "<li>".cms_galerie_dauerdetail($daten, $zeiten)."<li>";

	if (strlen($daten['autor']) > 0) {
		$code .= "<li><span class=\"cms_termindetails_zusatzinfo\" style=\"background-image:url('res/icons/oegruppen/autor.png')\">".$daten['autor']."</span></li>";
	}
	if ($daten['genehmigt'] == 0) {
		$code .= "<li class=\"cms_genehmigungausstehend\">!! ACHTUNG !! <br> Der Blogeintrag ist noch nicht genehmigt!</li>";
	}
	$code .= "</ul>";

	$verknuepfung = "";
	// Bei öffentlichen Terminen zugehörige Kategorien suchen
	$sql = "";
	$zugehoerigladen = "";
	foreach ($CMS_GRUPPEN as $g) {
		$gk = cms_textzudb($g);
		$sql .= " UNION (SELECT id, '$gk' AS gruppe, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk JOIN $gk"."galerien ON $gk.id = $gk"."galerien.gruppe WHERE galerie = ?)";
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

	return $code;
}
?>
