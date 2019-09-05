<?php
function cms_schulanmeldung_ausgeben ($id) {
	global $CMS_SCHLUESSEL, $laender, $sprachen, $geschlechter, $religionen, $reliunterrichtangebot, $CMS_ONLOAD_EXTERN_EVENTS, $klassenbezeichnungen, $profile;
	$dbs = cms_verbinden('s');
	$code = "";

	$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();

	$sakzeptiert = 1;
	$svorname = "";
	$srufname = "";
	$snachname = "";
	$sgeburtsdatum = time();
	$sgeburtsort = "";
	$sgeburtsland = "";
	$smuttersprache = "";
	$sverkehrssprache = "";
	$sgeschlecht = "";
	$sreligion = "";
	$sreligionsunterricht = "";
	$sland1 = "";
	$sland2 = "";
	$sstrasse = "";
	$shausnummer = "";
	$splz = "";
	$sort = "";
	$steilort = "";
	$stelefon1 = "";
	$stelefon2 = "";
	$shandy1 = "";
	$shandy2 = "";
	$smail = "";
	$seinschulung = $sgeburtsdatum;
	$svorigeschule = "";
	$svorigeklasse = "";
	$sprofil = "";
	$geburtsdatumgeladen = false;
	$einschulunggeladen = false;
	$ansprechpartner2 = 1;
	$ansprechpartner['eins']['vorname'] = "";
	$ansprechpartner['eins']['nachname'] = "";
	$ansprechpartner['eins']['geschlecht'] = "w";
	$ansprechpartner['eins']['sorgerecht'] = 1;
	$ansprechpartner['eins']['briefe'] = 1;
	$ansprechpartner['eins']['strasse'] = "";
	$ansprechpartner['eins']['hausnummer'] = "";
	$ansprechpartner['eins']['plz'] = "";
	$ansprechpartner['eins']['ort'] = "";
	$ansprechpartner['eins']['teilort'] = "";
	$ansprechpartner['eins']['telefon1'] = "";
	$ansprechpartner['eins']['telefon2'] = "";
	$ansprechpartner['eins']['handy'] = "";
	$ansprechpartner['eins']['mail'] = "";
	$ansprechpartner['zwei']['vorname'] = "";
	$ansprechpartner['zwei']['nachname'] = "";
	$ansprechpartner['zwei']['geschlecht'] = "m";
	$ansprechpartner['zwei']['sorgerecht'] = 1;
	$ansprechpartner['zwei']['briefe'] = 1;
	$ansprechpartner['zwei']['strasse'] = "";
	$ansprechpartner['zwei']['hausnummer'] = "";
	$ansprechpartner['zwei']['plz'] = "";
	$ansprechpartner['zwei']['ort'] = "";
	$ansprechpartner['zwei']['teilort'] = "";
	$ansprechpartner['zwei']['telefon1'] = "";
	$ansprechpartner['zwei']['telefon2'] = "";
	$ansprechpartner['zwei']['handy'] = "";
	$ansprechpartner['zwei']['mail'] = "";

	if ($id != "-") {
		$ansprechpartner2 = 0;
		$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(rufname, '$CMS_SCHLUESSEL') AS rufname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(geburtsort, '$CMS_SCHLUESSEL') AS geburtsort, AES_DECRYPT(geburtsland, '$CMS_SCHLUESSEL') AS geburtsland, AES_DECRYPT(muttersprache, '$CMS_SCHLUESSEL') AS muttersprache, AES_DECRYPT(verkehrssprache, '$CMS_SCHLUESSEL') AS verkehrssprache, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(religion, '$CMS_SCHLUESSEL') AS religion, AES_DECRYPT(religionsunterricht, '$CMS_SCHLUESSEL') AS religionsunterricht, AES_DECRYPT(staatsangehoerigkeit, '$CMS_SCHLUESSEL') AS staatsangehoerigkeit, AES_DECRYPT(zstaatsangehoerigkeit, '$CMS_SCHLUESSEL') AS zstaatsangehoerigkeit, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy1, '$CMS_SCHLUESSEL') AS handy1, AES_DECRYPT(handy2, '$CMS_SCHLUESSEL') AS handy2, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail, AES_DECRYPT(einschulung, '$CMS_SCHLUESSEL') AS einschulung, AES_DECRYPT(vorigeschule, '$CMS_SCHLUESSEL') AS vorigeschule, AES_DECRYPT(vorigeklasse, '$CMS_SCHLUESSEL') AS vorigeklasse, AES_DECRYPT(kuenftigesprofil, '$CMS_SCHLUESSEL') AS kuenftigesprofil, AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert FROM voranmeldung_schueler WHERE id = $id";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$sakzeptiert = $daten['akzeptiert'];
				if ($sakzeptiert == 'ja') {$sakzeptiert = 1;} else {$sakzeptiert = 0;}
				$svorname = $daten['vorname'];
				$srufname = $daten['rufname'];
				$snachname = $daten['nachname'];
				$sgeburtsdatum = $daten['geburtsdatum'];
				$sgeburtsort = $daten['geburtsort'];
				$sgeburtsland = $daten['geburtsland'];
				$smuttersprache = $daten['muttersprache'];
				$sverkehrssprache = $daten['verkehrssprache'];
				$sgeschlecht = $daten['geschlecht'];
				$sreligion = $daten['religion'];
				$sreligionsunterricht = $daten['religionsunterricht'];
				$sland1 = $daten['staatsangehoerigkeit'];
				$sland2 = $daten['zstaatsangehoerigkeit'];
				$sstrasse = $daten['strasse'];
				$shausnummer = $daten['hausnummer'];
				$splz = $daten['plz'];
				$sort = $daten['ort'];
				$steilort = $daten['teilort'];
				$stelefon1 = $daten['telefon1'];
				$stelefon2 = $daten['telefon2'];
				$shandy1 = $daten['handy1'];
				$shandy2 = $daten['handy2'];
				$smail = $daten['mail'];
				$seinschulung = $daten['einschulung'];
				$svorigeschule = $daten['vorigeschule'];
				$svorigeklasse = $daten['vorigeklasse'];
				$sprofil = $daten['kuenftigesprofil'];
				$geburtsdatumgeladen = true;
				$einschulunggeladen = true;
			}
			$anfrage->free();
		}

		$sql = "SELECT id, AES_DECRYPT(nummer, '$CMS_SCHLUESSEL') AS nummer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(sorgerecht, '$CMS_SCHLUESSEL') AS sorgerecht, AES_DECRYPT(briefe, '$CMS_SCHLUESSEL') AS briefe, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy, '$CMS_SCHLUESSEL') AS handy, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail FROM voranmeldung_eltern WHERE schueler = $id";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				if ($daten['nummer'] == 'zwei') {$ansprechpartner2 = 1;}
				$ansprechpartner[$daten['nummer']]['vorname'] = $daten['vorname'];
				$ansprechpartner[$daten['nummer']]['nachname'] = $daten['nachname'];
				$ansprechpartner[$daten['nummer']]['geschlecht'] = $daten['geschlecht'];
				$ansprechpartner[$daten['nummer']]['sorgerecht'] = $daten['sorgerecht'];
				$ansprechpartner[$daten['nummer']]['briefe'] = $daten['briefe'];
				$ansprechpartner[$daten['nummer']]['strasse'] = $daten['strasse'];
				$ansprechpartner[$daten['nummer']]['hausnummer'] = $daten['hausnummer'];
				$ansprechpartner[$daten['nummer']]['plz'] = $daten['plz'];
				$ansprechpartner[$daten['nummer']]['ort'] = $daten['ort'];
				$ansprechpartner[$daten['nummer']]['teilort'] = $daten['teilort'];
				$ansprechpartner[$daten['nummer']]['telefon1'] = $daten['telefon1'];
				$ansprechpartner[$daten['nummer']]['telefon2'] = $daten['telefon2'];
				$ansprechpartner[$daten['nummer']]['handy'] = $daten['handy'];
				$ansprechpartner[$daten['nummer']]['mail'] = $daten['mail'];
			}
			$anfrage->free();
		}
	}

	$sgeburtsdatumT = date('d', $sgeburtsdatum);
  $sgeburtsdatumM = date('m', $sgeburtsdatum);
  if ($geburtsdatumgeladen) {$sgeburtsdatumJ = date('Y', $sgeburtsdatum);}
  else {$sgeburtsdatumJ = date('Y', $sgeburtsdatum) - $CMS_VORANMELDUNG['Anmeldung Eintrittsalter'];}
  $seinschulungT = date('d', $seinschulung);
  $seinschulungM = date('m', $seinschulung);
  if ($einschulunggeladen) {$seinschulungJ = date('Y', $seinschulung);}
  else {$seinschulungJ = date('Y', $seinschulung)-2*$CMS_VORANMELDUNG['Anmeldung Eintrittsalter'] + $CMS_VORANMELDUNG['Anmeldung Einschulungsalter'];}

	$code .= "</div>";

	//Schülerdaten
	$code .= "<div class=\"cms_spalte_i\"><h2>Schülerdaten</h2></div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Persönliche Daten</h3>";
	$code .= "<p>".cms_togglebutton_generieren ('cms_voranemldung_aufgenommen', 'Aufgenommen', $sakzeptiert)."</p>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Vorname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_vorname\" id=\"cms_voranmeldung_schueler_vorname\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_vorname', 'cms_voranmeldung_schueler_rufname')\" value=\"$svorname\"></td></tr>";
		$code .= "<tr><th>Rufname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_rufname\" id=\"cms_voranmeldung_schueler_rufname\" value=\"$srufname\"></td></tr>";
		$code .= "<tr><th>Nachname:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_nachname\" id=\"cms_voranmeldung_schueler_nachname\" value=\"$snachname\"></td></tr>";
		$code .= "<tr><th>Geburtstag:</th><td>".cms_datum_eingabe('cms_vornameldung_schueler_geburtsdatum', $sgeburtsdatumT, $sgeburtsdatumM, $sgeburtsdatumJ)."</td></tr>";
		$code .= "<tr><th>Geburtsort:</th><td><input type=\"text\" name=\"cms_voranmeldung_schueler_geburtsort\" id=\"cms_voranmeldung_schueler_geburtsort\" value=\"$sgeburtsort\"></td></tr>";
		$code .= "<tr><th>Geburtsland:</th><td><select name=\"cms_voranmeldung_schueler_geburtsland\" id=\"cms_voranmeldung_schueler_geburtsland\">";
		foreach ($laender as $l) {
			if ($l['wert'] == $sgeburtsland) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Muttersprache:</th><td><select name=\"cms_voranmeldung_schueler_muttersprache\" id=\"cms_voranmeldung_schueler_muttersprache\">";
		foreach ($sprachen as $s) {
			if ($s['wert'] == $smuttersprache) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$s['wert']."\"$zusatz>".$s['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Verkehrssprache:</th><td><select name=\"cms_voranmeldung_schueler_verkehrssprache\" id=\"cms_voranmeldung_schueler_verkehrssprache\">";
		foreach ($sprachen as $s) {
			if ($s['wert'] == $sverkehrssprache) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$s['wert']."\"$zusatz>".$s['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Geschlecht:</th><td><select name=\"cms_voranmeldung_schueler_geschlecht\" id=\"cms_voranmeldung_schueler_geschlecht\">";
		foreach ($geschlechter as $g) {
			if ($g['wert'] == $sgeschlecht) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
		}
		$code .= "</select></td></tr>";
		$code .= "<tr><th>Religion:</th><td><select name=\"cms_voranmeldung_schueler_religion\" id=\"cms_voranmeldung_schueler_religion\">";
		foreach ($religionen as $r) {
			if ($r['wert'] == $sreligion) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Religionsunterricht:</th><td><select name=\"cms_voranmeldung_schueler_religionsunterricht\" id=\"cms_voranmeldung_schueler_religionsunterricht\">";
		foreach ($reliunterrichtangebot as $r) {
			if ($r['wert'] == $sreligionsunterricht) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$r['wert']."\"$zusatz>".$r['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Staatsangehörigkeit:</th><td><select name=\"cms_voranmeldung_schueler_land1\" id=\"cms_voranmeldung_schueler_land1\">";
		foreach ($laender as $l) {
			if ($l['wert'] == $sland1) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
		$code .= "<tr><th>Zweite Staatsangehörigkeit:</th><td><select name=\"cms_voranmeldung_schueler_land2\" id=\"cms_voranmeldung_schueler_land2\">";
		$code .= "<option value=\"\">Keine</option>";
		foreach ($laender as $l) {
			if ($l['wert'] == $sland2) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
			$code .= "<option value=\"".$l['wert']."\"$zusatz>".$l['bezeichnung']."</option>";
		}
		$code .= "</td></tr>";
	$code .= "</table>";
	$code .= "</div>";
	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Kontaktdaten</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_strasse\" id=\"cms_voranmeldung_schueler_strasse\" class=\"cms_gross\" value=\"$sstrasse\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_strasse', 'cms_voranmeldung_ansprechpartner1_strasse'); cms_uebernehmen('cms_voranmeldung_schueler_strasse', 'cms_voranmeldung_ansprechpartner2_strasse')\"> <input type=\"text\" name=\"cms_voranmeldung_schueler_hausnummer\" id=\"cms_voranmeldung_schueler_hausnummer\" class=\"cms_klein\" value=\"$shausnummer\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_hausnummer', 'cms_voranmeldung_ansprechpartner1_hausnummer'); cms_uebernehmen('cms_voranmeldung_schueler_hausnummer', 'cms_voranmeldung_ansprechpartner2_hausnummer')\"></td></tr>";
	$code .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_postleitzahl\" id=\"cms_voranmeldung_schueler_postleitzahl\" class=\"cms_klein\" value=\"$splz\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_postleitzahl', 'cms_voranmeldung_ansprechpartner1_postleitzahl'); cms_uebernehmen('cms_voranmeldung_schueler_postleitzahl', 'cms_voranmeldung_ansprechpartner2_postleitzahl')\"> <input type=\"text\" name=\"cms_voranmeldung_schueler_ort\" id=\"cms_voranmeldung_schueler_ort\" class=\"cms_gross\" value=\"$sort\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_ort', 'cms_voranmeldung_ansprechpartner1_ort'); cms_uebernehmen('cms_voranmeldung_schueler_ort', 'cms_voranmeldung_ansprechpartner2_ort')\"></td></tr>";
	$code .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_teilort\" id=\"cms_voranmeldung_schueler_teilort\" value=\"$steilort\" onkeyup=\"cms_uebernehmen('cms_voranmeldung_schueler_teilort', 'cms_voranmeldung_ansprechpartner1_teilort'); cms_uebernehmen('cms_voranmeldung_schueler_teilort', 'cms_voranmeldung_ansprechpartner2_teilort')\"></td></tr>";
	$code .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_telefon1\" id=\"cms_voranmeldung_schueler_telefon1\" value=\"$stelefon1\"></td></tr>";
	$code .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_telefon2\" id=\"cms_voranmeldung_schueler_telefon2\" value=\"$stelefon2\"></td></tr>";
	$code .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_handy1\" id=\"cms_voranmeldung_schueler_handy1\" value=\"$shandy1\"></td></tr>";
	$code .= "<tr><th>Handynummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_schueler_handy2\" id=\"cms_voranmeldung_schueler_handy2\" value=\"$shandy2\"></td></tr>";
	$code .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_schueler_mail\" id=\"cms_schulhof_voranmeldung_schueler_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('voranmeldung_schueler_mail');\" value=\"$smail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_schueler_mail_icon\"></span></td></td></tr>";
	$CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('voranmeldung_schueler_mail');";
	$code .= "</table>";

	$code .= "<h3>Schullaufbahn</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Einschulung in Klasse 1:</th><td>".cms_datum_eingabe('cms_vornameldung_schueler_einschulung', $seinschulungT, $seinschulungM, $seinschulungJ)."</td></tr>";
	$code .= "<tr><th>Vorige Schule:</th><td><input type=\"text\" name=\"cms_voranmeldung_vorigeschule\" id=\"cms_voranmeldung_vorigeschule\", value=\"$svorigeschule\"></td></tr>";
	$code .= "<tr><th>Klasse an der letzten Schule:</th><td><select name=\"cms_voranmeldung_klasse\" id=\"cms_voranmeldung_klasse\">";
	foreach($klassenbezeichnungen as $b) {
		if ($svorigeklasse == $CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
		$code .= "<option value=\"".$CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b."\"$zusatz>".$CMS_VORANMELDUNG['Anmeldung Klassenstufe'].$b."</option>";
	}
	$code .= "</select></td></tr>";
	$code .= "<tr><th>Künftiges Profil:</th><td><select name=\"cms_voranmeldung_profil\" id=\"cms_voranmeldung_profil\">";
	foreach ($profile as $p) {
		if ($p['wert'] == $sprofil) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
		$code .= "<option value=\"".$p['wert']."\"$zusatz>".$p['bezeichnung']."</option>";
	}
	$code .= "</select></td></tr>";
	$code .= "</table>";
	$code .= "</div>";
	$code .= "</div>";
	$code .= "<div class=\"cms_clear\"></div>";



	//Ansprechpartner
	$code .= "<div class=\"cms_spalte_i\"><h2>Ansprechpartner</h2></div>";
	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Erster Ansprechpartner</h3>";
	$code .= "<table class=\"cms_formular\">";
	$code .= "<tr><th>Vorname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_vorname\" id=\"cms_voranmeldung_ansprechpartner1_vorname\" value=\"".$ansprechpartner['eins']['vorname']."\"></td></tr>";
	$code .= "<tr><th>Nachname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_nachname\" id=\"cms_voranmeldung_ansprechpartner1_nachname\" value=\"".$ansprechpartner['eins']['nachname']."\"></td></tr>";
	$code .= "<tr><th>Geschlecht:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner1_geschlecht\" id=\"cms_voranmeldung_ansprechpartner1_geschlecht\">";
	foreach ($geschlechter as $g) {
		if ($g['wert'] == $ansprechpartner['eins']['geschlecht']) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
		$code .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
	}
	$code .= "</select></td></tr>";
	$code .= "<tr><th>Sorgeberechtigt:</th><td colspan=\"2\">".cms_schieber_generieren('voranmeldung_ansprechpartner1_sorgerecht', $ansprechpartner['eins']['sorgerecht'])."</td></tr>";
	$code .= "<tr><th>In Briefe integrieren:</th><td colspan=\"2\">".cms_schieber_generieren('voranmeldung_ansprechpartner1_briefe', $ansprechpartner['eins']['briefe'])."</td></tr>";
	$code .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_strasse\" id=\"cms_voranmeldung_ansprechpartner1_strasse\" class=\"cms_gross\" value=\"".$ansprechpartner['eins']['strasse']."\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_hausnummer\" id=\"cms_voranmeldung_ansprechpartner1_hausnummer\" class=\"cms_klein\" value=\"".$ansprechpartner['eins']['hausnummer']."\"></td></tr>";
	$code .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_postleitzahl\" id=\"cms_voranmeldung_ansprechpartner1_postleitzahl\" class=\"cms_klein\" value=\"".$ansprechpartner['eins']['plz']."\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_ort\" id=\"cms_voranmeldung_ansprechpartner1_ort\" class=\"cms_gross\" value=\"".$ansprechpartner['eins']['ort']."\"></td></tr>";
	$code .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_teilort\" id=\"cms_voranmeldung_ansprechpartner1_teilort\" value=\"".$ansprechpartner['eins']['teilort']."\"></td></tr>";
	$code .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_telefon1\" id=\"cms_voranmeldung_ansprechpartner1_telefon1\" value=\"".$ansprechpartner['eins']['telefon1']."\"></td></tr>";
	$code .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_telefon2\" id=\"cms_voranmeldung_ansprechpartner1_telefon2\" value=\"".$ansprechpartner['eins']['telefon2']."\"></td></tr>";
	$code .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner1_handy1\" id=\"cms_voranmeldung_ansprechpartner1_handy1\" value=\"".$ansprechpartner['eins']['handy']."\"></td></tr>";
	$code .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_ansprechpartner1_mail\" id=\"cms_schulhof_voranmeldung_ansprechpartner1_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('voranmeldung_ansprechpartner1_mail');\" value=\"".$ansprechpartner['eins']['mail']."\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_ansprechpartner1_mail_icon\"></span></td></td></tr>";
	$CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('voranmeldung_ansprechpartner1_mail');";
	$code .= "</table>";
	$code .= "</div>";
	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Zweiter Ansprechpartner</h3>";

	$inhalt = "<table class=\"cms_formular\">";
	$inhalt .= "<tr><th>Vorname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_vorname\" id=\"cms_voranmeldung_ansprechpartner2_vorname\" value=\"".$ansprechpartner['zwei']['vorname']."\"></td></tr>";
	$inhalt .= "<tr><th>Nachname:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_nachname\" id=\"cms_voranmeldung_ansprechpartner2_nachname\" value=\"".$ansprechpartner['zwei']['nachname']."\"></td></tr>";
	$inhalt .= "<tr><th>Geschlecht:</th><td colspan=\"2\"><select name=\"cms_voranmeldung_ansprechpartner2_geschlecht\" id=\"cms_voranmeldung_ansprechpartner2_geschlecht\">";
	foreach ($geschlechter as $g) {
		if ($g['wert'] == $ansprechpartner['zwei']['geschlecht']) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
		$inhalt .= "<option value=\"".$g['wert']."\"$zusatz>".$g['bezeichnung']."</option>";
	}
	$inhalt .= "</select></td></tr>";
	$inhalt .= "<tr><th>Sorgeberechtigt:</th><td colspan=\"2\">".cms_schieber_generieren('voranmeldung_ansprechpartner2_sorgerecht',$ansprechpartner['zwei']['sorgerecht'])."</td></tr>";
	$inhalt .= "<tr><th>In Briefe integrieren:</th><td colspan=\"2\">".cms_schieber_generieren('voranmeldung_ansprechpartner2_briefe',$ansprechpartner['zwei']['briefe'])."</td></tr>";
	$inhalt .= "<tr><th>Straße und Hausnummer:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_strasse\" id=\"cms_voranmeldung_ansprechpartner2_strasse\" class=\"cms_gross\" value=\"".$ansprechpartner['zwei']['strasse']."\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_hausnummer\" id=\"cms_voranmeldung_ansprechpartner2_hausnummer\" class=\"cms_klein\" value=\"".$ansprechpartner['zwei']['hausnummer']."\"></td></tr>";
	$inhalt .= "<tr><th>Postleitzahl und Ort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_postleitzahl\" id=\"cms_voranmeldung_ansprechpartner2_postleitzahl\" class=\"cms_klein\" value=\"".$ansprechpartner['zwei']['plz']."\"> <input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_ort\" id=\"cms_voranmeldung_ansprechpartner2_ort\" class=\"cms_gross\" value=\"".$ansprechpartner['zwei']['ort']."\"></td></tr>";
	$inhalt .= "<tr><th>Teilort:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_teilort\" id=\"cms_voranmeldung_ansprechpartner2_teilort\" value=\"".$ansprechpartner['zwei']['teilort']."\"></td></tr>";
	$inhalt .= "<tr><th>Telefonnummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_telefon1\" id=\"cms_voranmeldung_ansprechpartner2_telefon1\" value=\"".$ansprechpartner['zwei']['telefon1']."\"></td></tr>";
	$inhalt .= "<tr><th>Telefonnummer 2:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_telefon2\" id=\"cms_voranmeldung_ansprechpartner2_telefon2\" value=\"".$ansprechpartner['zwei']['telefon2']."\"></td></tr>";
	$inhalt .= "<tr><th>Handynummer 1:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_voranmeldung_ansprechpartner2_handy1\" id=\"cms_voranmeldung_ansprechpartner2_handy1\" value=\"".$ansprechpartner['zwei']['handy']."\"></td></tr>";
	$inhalt .= "<tr><th>Mailadresse:</th><td><input name=\"cms_schulhof_voranmeldung_ansprechpartner2_mail\" id=\"cms_schulhof_voranmeldung_ansprechpartner2_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('voranmeldung_ansprechpartner2_mail');\" value=\"".$ansprechpartner['zwei']['mail']."\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_voranmeldung_ansprechpartner2_mail_icon\"></span></td></td></tr>";
	$CMS_ONLOAD_EXTERN_EVENTS .= "cms_check_mail_wechsel('voranmeldung_ansprechpartner2_mail');";
	$inhalt .= "</table>";

	$code .= cms_toggleeinblenden_generieren ('cms_ansprechpartner2', 'Zweiten Ansprechpartner erstellen', 'Zweiten Ansprechpartner entfernen', $inhalt, $ansprechpartner2);

	$code .= "</div>";
	$code .= "</div>";
	$code .= "<div class=\"cms_clear\"></div>";




	$code .= "<div class=\"cms_spalte_i\">";
	$code .= "<p>";
	if ($id == '-') {
		$code .= "<span class=\"cms_button\" onclick=\"cms_schulanmeldung_neu_speichern()\">Anmeldung speichern</span> ";
	}
	else {
		$code .= "<span class=\"cms_button\" onclick=\"cms_schulanmeldung_bearbeiten_speichern()\">Änderungen übernehmen</span> ";
	}
	$code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Schulanmeldung\">Abbrechen</a>";
	$code .= "</p>";

	cms_trennen($dbs);
	return $code;
}
?>
