<?php
function cms_galerie_details_laden($id, $ziel) {
  global $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL;
  $code = "";

	$zugriff = false;
	$fehler = false;

  if (($CMS_RECHTE['Website']['Galerien anlegen'] && ($id == '-')) || ($CMS_RECHTE['Website']['Galerien bearbeiten'] && ($id != '-'))) {$zugriff = true;}

  $bezeichnung = '';
  $vorschaubild = "";
  $datum = time();
  $aktiv = 1;
  $genehmigt = 0;
  $oeffentlichkeit = 4;
  $beschreibung = '';
  $autor = cms_generiere_anzeigename($CMS_BENUTZERVORNAME,$CMS_BENUTZERNACHNAME,$CMS_BENUTZERTITEL);
  $zus = "";
  foreach ($CMS_GRUPPEN as $g) {
    $zugeordnet[$g] = array();
    $zgruppenids[$g] = "";
  }
  $bilder = array();

  // Falls eine bestehende Galerie geladen werden soll
  $dbs = cms_verbinden('s');
  if ($id != "-") {
	  $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM galerien WHERE id = $id";
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$bezeichnung = $daten['bezeichnung'];
  			$datum = $daten['datum'];
      	$genehmigt = $daten['genehmigt'];
        $aktiv = $daten['aktiv'];
        $oeffentlichkeit = $daten['oeffentlichkeit'];
        $beschreibung = $daten['beschreibung'];
        $vorschaubild = $daten['vorschaubild'];
        $autor = $daten['autor'];
			}
			$anfrage->free();
		}
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = "SELECT * FROM (SELECT gruppe AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gk."galerien JOIN $gk ON ".$gk."galerien.gruppe = $gk.id WHERE galerie = $id) AS x ORDER BY bezeichnung";
      if ($anfrage = $dbs->query($sql)) {
  			while ($daten = $anfrage->fetch_assoc()) {
  				array_push($daten, $zugeordnet[$g]);
          $zgruppenids[$g] .= "|".$daten['id'];
  			}
  			$anfrage->free();
  		}
    }

    $sql = "SELECT AES_DECRYPT(pfad, '$CMS_SCHLUESSEL') as pfad, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') as beschreibung FROM galerienbilder WHERE galerie='$id' ORDER BY id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        array_push($bilder, array("pfad" => $daten["pfad"], "beschreibung" => $daten["beschreibung"]));
      }
    $anfrage->free();
    }else {$fehler = true;}
  }

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();


	if ($angemeldet && $zugriff) {
    $genehmigung = false;
    if ($CMS_RECHTE['Organisation']['Galerien genehmigen']) {$genehmigung = true; $genehmigt = 1;}

    if (!$genehmigung) {
      $code .= cms_meldung ('info', "<h4>Genehmigung erforderlich</h4><p>Bis die Genehmigung erteilt wird, handelt es sich um eine vorläufige Galerie.</p>");
    }
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Art der Galerie</h3>";
		$code .= "<table class=\"cms_formular\">";
/*    $code .= "<tr><th>Sichtbarkeit:</th><td><select id=\"cms_oeffentlichkeit\" name=\"cms_oeffentlichkeit\">";
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
    $code .= "</select></td></tr>";*/
    $code .= "<input type=\"hidden\" id=\"cms_oeffentlichkeit\" name=\"cms_oeffentlichkeit\" value=\"4\"></input>";
    if ($genehmigung) {
      $code .= "<tr><th>Genehmigt:</th><td>".cms_schieber_generieren('galerie_genehmigt', $genehmigt)."</td></tr>";
    }
    $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('galerie_aktiv', $aktiv)."</td></tr>";
		$code .= "</table>";
    if (!$genehmigung) {
      $code .= "<p><input type=\"hidden\" value=\"0\" id=\"cms_galerie_genehmigt\" name=\"cms_galerie_genehmigt\"></p>";
    }

    $code .= "<h3>Galeriedaten</h3>";
		$code .= "<table class=\"cms_formular\">";
    $gtag = date('d', $datum);
    $gmonat = date('m', $datum);
    $gjahr = date('Y', $datum);

    $code .= "<tr><th>Zuordnung:</th><td class=\"cms_gruppensuche_feld_aussen\">";
    $code .= cms_zuordnungsauswahl_generieren($zgruppenids, 'gremien', $CMS_BENUTZERSCHULJAHR);
    $code .= "</td></tr>";
		$code .= "<tr><th>Datum:</th><td>".cms_datum_eingabe('cms_galerie_datum', $gtag, $gmonat, $gjahr)."</td></tr>";
    $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_galerie_bezeichnung\" id=\"cms_galerie_bezeichnung\" value=\"$bezeichnung\"/></td></tr>";
    $code .= "<tr><th>Beschreibung:</th><td><textarea rows=\"10\" name=\"cms_galerie_beschreibung\" id=\"cms_galerie_beschreibung\">$beschreibung</textarea></td></tr>";
    $code .= "<tr><th>Vorschaubild:</th><td>".cms_dateiwahl_knopf('galerien', 'cms_galerie_vorschaubild', 's', 'galerien', '-', 'vorschaubild', $vorschaubild)."</td></tr>";
    $code .= "<tr><th>Autor:</th><td><input type=\"text\" name=\"cms_galerie_autor\" id=\"cms_galerie_autor\" value=\"$autor\"/></td></tr>";
		$code .= "</table>";
		$code .= "</div></div>";
    $code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";

    $code .= "<h3>Bilder</h3>";


    $code .= "<div id=\"cms_bilder\">";

    $anzahl = 0;
    $ids = "";
    foreach($bilder as $bild) {
      $anzahl++;
      $ids .= "|temp$anzahl";
      $bcode = "<table class=\"cms_formular\" id=\"cms_bildtemp$anzahl\"";
        $bcode .= "<tr><th>Datei:</th><td colspan=\"4\"><input id=\"cms_bild_datei_temp$anzahl\" type=\"hidden\" value=\"".$bild["pfad"]."\"><p class=\"cms_notiz cms_vorschau\" id=\"cms_bild_datei_temp".$anzahl."_vorschau\">";
        $bcode .= "<img src=\"".$bild["pfad"]."\"></p><p><span class=\"cms_button\" onclick=\"cms_dateiwahl('s', 'galerien', '-', 'galerien', 'cms_bild_datei_temp$anzahl', 'vorschaubild', '-', '-')\">Bild auswählen</span></p>";
        $bcode .= "<p id=\"cms_bild_datei_temp".$anzahl."_verzeichnis\"></p></td></tr>";

        $bcode .= "<tr><th>Beschreibung: </th><td colspan=\"4\"><textarea name=\"cms_bild_beschreibung_temp$anzahl\" id=\"cms_bild_beschreibung_temp$anzahl\">".$bild["beschreibung"]."</textarea></td></tr>";

        $bcode .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_bild_entfernen('temp$anzahl')\">- Bild entfernen</span></td></tr>";
      $bcode .= "</table>";

      $code .= $bcode;
    }

    $code .= "</div>";


    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_galerie_neues_bild();\">+ Bild hinzufügen</span>";
      $code .= "<input type=\"hidden\" id=\"cms_bilder_anzahl\" name=\"cms_bilder_anzahl\" value=\"$anzahl\">";
      $code .= "<input type=\"hidden\" id=\"cms_bilder_nr\" name=\"cms_bilder_nr\" value=\"$anzahl\">";
      $code .= "<input type=\"hidden\" id=\"cms_bilder_ids\" name=\"cms_bilder_ids\" value=\"$ids\">";
    $code.= "</p>";

    if ($CMS_RECHTE['Website']['Dateien hochladen']) {
      $inhalt = "<h3>Galerienverzeichnis</h3>";
      $rechte = cms_websitedateirechte_laden();
      $inhalt .= cms_dateisystem_generieren ('galerien', 'galerien', 'cms_website_dateien', 's', 'galerien', '-', $rechte);
      $code .= cms_toggleeinblenden_generieren('cms_websitedateien', "Galerienverzeichnis anzeigen", "Galerienverzeichnis ausblenden", $inhalt, 0);
    }

		$code .= "</div></div>";



		$code .= "<div class=\"cms_clear\"></div>";


		$code .= "<div class=\"cms_spalte_i\">";

    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_galerie_neu_speichern('$ziel');\">Speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_galerie_bearbeiten_speichern('$ziel');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
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
