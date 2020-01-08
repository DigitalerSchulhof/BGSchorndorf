<?php
function cms_blogeintrag_details_laden($id, $ziel) {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL;
  $code = "";

	$zugriff = false;
	$fehler = false;

  if (($CMS_RECHTE['Website']['Blogeinträge anlegen'] && ($id == '-')) || ($CMS_RECHTE['Website']['Blogeinträge bearbeiten'] && ($id != '-'))) {$zugriff = true;}

  $bez = '';
  $vorschaubild = "";
  $datum = time();
  $aktiv = 1;
  $genehmigt = 0;
  $oeffentlichkeit = 3;
  $notifikationen = 1;
  $text = '';
  $zgruppen = "";
  $autor = cms_generiere_anzeigename($CMS_BENUTZERVORNAME,$CMS_BENUTZERNACHNAME,$CMS_BENUTZERTITEL);
  $zus = "";
  foreach ($CMS_GRUPPEN as $g) {
    // Speichert die Gruppeninformationen
    $zugeordnet[$g] = array();
    // Speichert die IDs der zugeordneten Gruppen
    $zgruppenids[$g] = "";
  }

  // Falls ein bestehender Blogeintrag geladen werden soll
  $dbs = cms_verbinden('s');
  if ($id != "-") {
	  $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, genehmigt, aktiv, oeffentlichkeit, notifikationen, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS zusammenfassung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM blogeintraege WHERE id = ?");

    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($bez, $datum, $genehmigt, $aktiv, $oeffentlichkeit, $notifikationen, $text, $zus, $vorschaubild, $autor);
      if (!$sql->fetch()) {$fehler = true;}
    }
    else {$fehler = true;}
    $sql->close();

    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = "SELECT * FROM (SELECT gruppe AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM ".$gk."blogeintraege JOIN $gk ON ".$gk."blogeintraege.gruppe = $gk.id WHERE blogeintrag = $id) AS x ORDER BY bezeichnung";
      if ($anfrage = $dbs->query($sql)) { // TODO: Eingaben der Funktion prüfen
  			while ($daten = $anfrage->fetch_assoc()) {
  				array_push($daten, $zugeordnet[$g]);
          $zgruppenids[$g] .= "|".$daten['id'];
  			}
  			$anfrage->free();
  		}
    }
  }

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();


	if ($angemeldet && $zugriff) {
    $genehmigung = false;
    if (cms_r("artikel.genehmigen.blogeinträge"))) {$genehmigung = true; $genehmigt = 1;}

    if (!$genehmigung) {
      $code .= cms_meldung ('info', "<h4>Genehmigung erforderlich</h4><p>Bis die Genehmigung erteilt wird, handelt es sich um einen vorläufigen Blogeintrag.</p>");
    }
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Art des Blogeintrags</h3>";
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
      $code .= "<tr><th>Genehmigt:</th><td>".cms_schieber_generieren('blogeintrag_genehmigt', $genehmigt)."</td></tr>";
    }
    $code .= "<tr><th>Aktiv:</th><td>".cms_schieber_generieren('blogeintrag_aktiv', $aktiv)."</td></tr>";
		$code .= "</table>";
    if (!$genehmigung) {
      $code .= "<p><input type=\"hidden\" value=\"0\" id=\"cms_blogeintrag_genehmigt\" name=\"cms_blogeintrag_genehmigt\"></p>";
    }

    $code .= "<h3>Blogeintragdaten</h3>";
		$code .= "<table class=\"cms_formular\">";
    $btag = date('d', $datum);
    $bmonat = date('m', $datum);
    $bjahr = date('Y', $datum);

    $code .= "<tr><th>Zuordnung:</th><td class=\"cms_gruppensuche_feld_aussen\">";
    $code .= cms_zuordnungsauswahl_generieren($zgruppenids, 'gremien', $CMS_BENUTZERSCHULJAHR);
    $code .= "</td></tr>";
		$code .= "<tr><th>Datum:</th><td>".cms_datum_eingabe('cms_blogeintrag_datum', $btag, $bmonat, $bjahr)."</td></tr>";
    $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_blogeintrag_bezeichnung\" id=\"cms_blogeintrag_bezeichnung\" value=\"$bez\"/></td></tr>";
    $code .= "<tr><th>Vorschaubild:</th><td>".cms_dateiwahl_knopf('website', 'cms_blogeintrag_vorschaubild', 's', 'website', '-', 'vorschaubild', $vorschaubild)."</td></tr>";
    $code .= "<tr><th>Zusammenfassung:</th><td><textarea rows=\"10\" name=\"cms_blogeintrag_zusammenfassung\" id=\"cms_blogeintrag_zusammenfassung\">$zus</textarea></td></tr>";
    $code .= "<tr><th>Autor:</th><td><input type=\"text\" name=\"cms_blogeintrag_autor\" id=\"cms_blogeintrag_autor\" value=\"$autor\"/></td></tr>";
		$code .= "</table>";
    $code .= "<h3>Erweiterte Optionen</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Notifikationen senden:<span class=\"cms_hinweis\">Notifikationen werden beim Löschen, Genehmigen und Ablehen immer gesandt.</span></span></th><td>".cms_schieber_generieren('blogeintrag_notifikationen', $notifikationen)."</td></tr>";
		$code .= "</table>";
		$code .= "</div></div>";

    $code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";

    $code .= "<h3>Blogeintragtext</h3>";
    $code .= cms_webeditor('cms_schulhof_blogeintrag_text', $text);

    $code .= "<h3>Zugehörige Downloads</h3>";
    $code .= cms_downloadelemente($dbs, 'blogeintraege', $id);

    if ($CMS_RECHTE['Website']['Dateien hochladen']) {
      $inhalt = "<h3>Websitedateien</h3>";
      $rechte = cms_websitedateirechte_laden();
      $inhalt .= cms_dateisystem_generieren ('website', 'website', 'cms_website_dateien', 's', 'website', '-', $rechte);
      $code .= cms_toggleeinblenden_generieren('cms_websitedateien', "Dateien der Website anzeigen", "Dateien der Website ausblenden", $inhalt, 0);
    }

		$code .= "</div></div>";



		$code .= "<div class=\"cms_clear\"></div>";


		$code .= "<div class=\"cms_spalte_i\">";

    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_blogeintraege_neu_speichern('$ziel');\">Speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_blogeintraege_bearbeiten_speichern('$ziel');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
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
