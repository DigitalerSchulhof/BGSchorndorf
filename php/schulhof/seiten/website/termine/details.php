<?php
function cms_termin_details_laden($id, $ziel) {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL;
  $code = "";

	$zugriff = false;
	$fehler = false;

  $bez = '';
  $ort = '';
  $beginn = time();
  $ende = time();
  $mehrtaegigt = 0;
  $uhrzeitbt = 0;
  $uhrzeitet = 0;
  $aktiv = 1;
  $ortt = 0;
  $genehmigt = 0;
  $oeffentlichkeit = 3;
  $text = '';
  $zgruppen = "";
  $notifikationen = 1;
  $autor = cms_generiere_anzeigename($CMS_BENUTZERVORNAME,$CMS_BENUTZERNACHNAME,$CMS_BENUTZERTITEL);
  foreach ($CMS_GRUPPEN as $g) {
    // Speichert die IDs der zugeordneten Gruppen
    $zgruppenids[$g] = "";
  }

  // Falls ein bestehender Termin geladen werden soll
  $dbs = cms_verbinden('s');
  if ($id != "-") {

    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, oeffentlichkeit, notifikationen, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($bez, $ort, $beginn, $ende, $mehrtaegigt, $uhrzeitbt, $uhrzeitet, $ortt, $genehmigt, $aktiv, $oeffentlichkeit, $notifikationen, $text);
      if (!$sql->fetch()) {$fehler = true;}
    }
    else {$fehler = true;}
    $sql->close();

    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = $dbs->prepare("SELECT * FROM (SELECT gruppe AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gk."termine JOIN $gk ON ".$gk."termine.gruppe = $gk.id WHERE termin = ?) AS x ORDER BY bezeichnung");
      $sql->bind_param("i", $id);
      if ($sql->execute()) {
        $sql->bind_result($zgid);
  			while ($sql->fetch()) {
          $zgruppenids[$g] .= "|".$zgid;
  			}
  		}
      $sql->close();
    }
  }

  if ((($id == '-') && cms_r("artikel.$oeffentlichkeit.termine.anlegen")) || (($id != '-') && cms_r("artikel.$oeffentlichkeit.termine.bearbeiten"))) {$zugriff = true;}

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();


	if ($angemeldet && $zugriff) {
    $genehmigung = false;
    if (cms_r("artikel.genehmigen.termine")) {$genehmigung = true; $genehmigt = 1;}

    if (!$genehmigung) {
      $code .= cms_meldung ('info', "<h4>Genehmigung erforderlich</h4><p>Bis die Genehmigung erteilt wird, handelt es sich um einen vorläufigen Termin.</p>");
    }
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Art des Termins</h3>";
		$code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Sichtbarkeit:</th><td><select id=\"cms_oeffentlichkeit\" name=\"cms_oeffentlichkeit\">";
    $oeffentlichkeiten = array(
      0 => "Mitglieder der zugeordneten Gruppen",
      1 => "Lehrer",
      2 => "Lehrer und Verwaltung",
      3 => "Gesamter Schulhof",
      4 => "Auf der Website und im Schulhof"
    );
    for ($i=0; $i < count($oeffentlichkeiten); $i++) {
      if ($oeffentlichkeit == $i) {$selected = "selected";} else {$selected = "";}
      $code .= "<option value=\"$i\" $selected>".$oeffentlichkeiten[$i]."</option>";
    }
    $code .= "</select></td></tr>";
    if ($genehmigung) {
      $code .= "<tr><th>Genehmigt:</th><td>".cms_schieber_generieren('termin_genehmigt', $genehmigt)."</td></tr>";
    }
    $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('termin_aktiv', $aktiv)."</td></tr>";
		$code .= "<tr><th>Mehrtägig:</th><td>".cms_schieber_generieren('termin_mehrtaegig', $mehrtaegigt, 'cms_termine_felder();')."</td></tr>";
		$code .= "<tr><th>Beginn Uhrzeit:</th><td>".cms_schieber_generieren('termin_uhrzeitb', $uhrzeitbt, 'cms_termine_felder();')."</td></tr>";
		$code .= "<tr><th>Ende Uhrzeit:</th><td>".cms_schieber_generieren('termin_uhrzeite', $uhrzeitet, 'cms_termine_felder();')."</td></tr>";
		$code .= "<tr><th>Ort:</th><td>".cms_schieber_generieren('termin_ortt', $ortt, 'cms_termine_felder();')."</td></tr>";
    if ($id == '-') {
		  $code .= "<tr><th>Wiederholung:</th><td>".cms_schieber_generieren('termin_wiederholung', 0, 'cms_termine_felder();')."</td></tr>";
    }
		$code .= "</table>";
    if (!$genehmigung) {
      $code .= "<p><input type=\"hidden\" value=\"0\" id=\"cms_termin_genehmigt\" name=\"cms_termin_genehmigt\"></p>";
    }

    $code .= "<h3>Termindaten</h3>";
		$code .= "<table class=\"cms_formular\">";
    $btag = date('d', $beginn);
    $bmonat = date('m', $beginn);
    $bjahr = date('Y', $beginn);
    $bstunde = date('H', $beginn);
    $bminute = date('i', $beginn);
    $etag = date('d', $ende);
    $emonat = date('m', $ende);
    $ejahr = date('Y', $ende);
    $estunde = date('H', $ende);
    $eminute = date('i', $ende);

    $wdhevent = "";
    if ($id == '-') {
      $wdhevent = "cms_termine_wiederholung_aktualisieren();";
    }

    $code .= "<tr><th>Zuordnung:</th><td class=\"cms_gruppensuche_feld_aussen\">";
    $code .= cms_zuordnungsauswahl_generieren($zgruppenids, 'gremien', $CMS_BENUTZERSCHULJAHR);
    $code .= "</td></tr>";
		$code .= "<tr><th>Beginn Datum:</th><td>".cms_datum_eingabe('cms_termin_beginn_datum', $btag, $bmonat, $bjahr, $wdhevent)."</td></tr>";
    $style = "display: none"; if ($uhrzeitet == 1) {$style = "display: table-row";}
		$code .= "<tr class=\"cms_versteckt\" style=\"$style\" id=\"cms_schulhof_termin_beginn_zeit_zeile\"><th>Beginn Uhrzeit:</th><td>".cms_uhrzeit_eingabe('cms_termin_beginn_zeit', $bstunde, $bminute, $wdhevent)."</td></tr>";
    $style = "display: none"; if ($mehrtaegigt == 1) {$style = "display: table-row";}
		$code .= "<tr class=\"cms_versteckt\" style=\"$style\" id=\"cms_schulhof_termin_ende_datum_zeile\"><th>Ende Datum:</th><td>".cms_datum_eingabe('cms_termin_ende_datum', $etag, $emonat, $ejahr, $wdhevent)."</td></tr>";
    $style = "display: none"; if ($uhrzeitet == 1) {$style = "display: table-row";}
		$code .= "<tr class=\"cms_versteckt\" style=\"$style\" id=\"cms_schulhof_termin_ende_zeit_zeile\"><th>Ende Uhrzeit:</th><td>".cms_uhrzeit_eingabe('cms_termin_ende_zeit', $estunde, $eminute, $wdhevent)."</td></tr>";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_termin_bezeichnung\" id=\"cms_termin_bezeichnung\" value=\"$bez\"/></td></tr>";
    $style = "display: none"; if ($ortt == 1) {$style = "display: table-row";}
		$code .= "<tr class=\"cms_versteckt\" style=\"$style\" id=\"cms_schulhof_termin_ort_zeile\"><th>Ort:</th><td><input type=\"text\" name=\"cms_termin_ort\" id=\"cms_termin_ort\" value=\"$ort\"/></td></tr>";
		if ($id == '-') {
      $code .= "<tr class=\"cms_versteckt\" style=\"display: none\" id=\"cms_schulhof_termin_wdh_zeile\"><th>Wiederholung:</th><td>";
      $code .= "<p><select name=\"cms_termin_wdh_art\" id=\"cms_termin_wdh_art\" onchange=\"$wdhevent\">";
        $code .= "<option value=\"t\">täglich</option>";
        $code .= "<option value=\"w\">wöchentlich</option>";
        $code .= "<option value=\"m\">monatlich</option>";
        $code .= "<option value=\"n\">gleicher Wochentag im nächsten Monat</option>";
        $code .= "<option value=\"j\">jährlich</option>";
      $code .= "</select></p>";
      $code .= "<p><input class=\"cms_klein\" type=\"number\" step=\"1\" min=\"1\" max=\"10\" name=\"cms_termin_wdh_anzahl\" id=\"cms_termin_wdh_anzahl\" value=\"2\" onkeyup=\"$wdhevent\" onclick=\"$wdhevent\"/> Wiederholungen</p>";
      $code .= "<input type=\"hidden\" name=\"cms_termin_wdh_datumbeginn\" id=\"cms_termin_wdh_datumbeginn\" value=\"|".mktime(0,0,0,$bmonat, $btag, $bjahr)."\"/>";
      $code .= "<p id=\"cms_termin_wdh_tage\"></p>";
      $code .= "</td></tr>";
    }
		$code .= "</table>";
    $code .= "<h3>Erweiterte Optionen</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Notifikationen senden:<span class=\"cms_hinweis\">Notifikationen werden beim Löschen, Genehmigen und Ablehen immer gesandt.</span></span></th><td>".cms_schieber_generieren('termin_notifikationen', $notifikationen)."</td></tr>";
		$code .= "</table>";
		$code .= "</div></div>";

    $code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";

    $code .= "<h3>Termintext</h3>";
    $code .= cms_webeditor('cms_schulhof_termin_text', $text);

    $code .= "<h3>Zugehörige Downloads</h3>";
    $code .= cms_downloadelemente($dbs, 'termine', $id);

    if (cms_r("website.dateien.hochladen")) {
      $inhalt = "<h3>Websitedateien</h3>";
      $rechte = cms_websitedateirechte_laden();
      $inhalt .= cms_dateisystem_generieren ('website', 'website', 'cms_website_dateien', 's', 'website', '-', $rechte);
      $code .= cms_toggleeinblenden_generieren('cms_websitedateien', "Dateien der Website anzeigen", "Dateien der Website ausblenden", $inhalt, 0);
    }

		$code .= "</div></div>";



		$code .= "<div class=\"cms_clear\"></div>";


		$code .= "<div class=\"cms_spalte_i\">";

    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_termine_neu_speichern('$ziel');\">Speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_termine_bearbeiten_speichern('$ziel');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
    }
		$code .= "</div>";

	}
	else {
		$code .= cms_meldung_berechtigung();
		$code .= "</div>";
	}
  cms_trennen($dbs);
  return $code;
}

?>
