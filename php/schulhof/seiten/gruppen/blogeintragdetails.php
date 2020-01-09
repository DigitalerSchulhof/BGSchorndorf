<?php
function cms_internerblogeintrag_details_laden($id, $gruppe, $gruppenid) {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERART, $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID, $CMS_GRUPPEN, $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERTITEL, $CMS_URL;
  $code = "";

  $_SESSION['INTERNERBLOGGRUPPE'] = $gruppe;
  $_SESSION['INTERNERBLOGGRUPPENID'] = $gruppenid;

  $dbs = cms_verbinden('s');
  $GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);
  $gk = cms_textzudb($gruppe);

	$zugriff = false;
	$fehler = false;

  if ($id != '-') {if ($GRUPPENRECHTE['blogeintraege']) {$zugriff = true;}}
  else {if (cms_internblogvorschlag($GRUPPENRECHTE)) {$zugriff = true;}}

  $bez = '';
  $datum = time();
  $aktiv = 1;
  $genehmigt = 0;
  $oeffentlichkeit = 3;
  $text = '';
  $notifikationen = 1;
  $autor = cms_generiere_anzeigename($CMS_BENUTZERVORNAME,$CMS_BENUTZERNACHNAME,$CMS_BENUTZERTITEL);
  $zus = "";

  // Falls ein bestehender Blogeintrag geladen werden soll
  if (cms_check_ganzzahl($id)) {
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, notifikationen, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS zusammenfassung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM $gk"."blogeintraegeintern WHERE id = ?");

    $sql->bind_param("i", $id);
    // Schuljahr finden
    if ($sql->execute()) {
      $sql->bind_result($bez, $datum, $genehmigt, $aktiv, $text, $notifikationen, $zus, $autor);
      if (!$sql->fetch()) {$fehler = true;}
    }
    else {$fehler = true;}
    $sql->close();
  }

	if ($fehler) {$zugriff = false;}
	$angemeldet = cms_angemeldet();


	if ($angemeldet && $zugriff) {
    $genehmigung = false;
    if (($CMS_EINSTELLUNGEN['Genehmigungen '.$gruppe.' Blogeinträge'] == 0) || (cms_r("schulhof.gruppen.$gruppe.artikel.blogeinträge.genehmigen"))) {$genehmigung = true; $genehmigt = 1;}

    if (!$genehmigung) {
      $code .= cms_meldung ('info', "<h4>Genehmigung erforderlich</h4><p>Bis die Genehmigung erteilt wird, handelt es sich um einen vorläufigen Blogeintrag.</p>");
    }
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Art des Blogeintrags</h3>";
		$code .= "<table class=\"cms_formular\">";
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

		$code .= "<tr><th>Datum:</th><td>".cms_datum_eingabe('cms_blogeintrag_datum', $btag, $bmonat, $bjahr)."</td></tr>";
    $code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_blogeintrag_bezeichnung\" id=\"cms_blogeintrag_bezeichnung\" value=\"$bez\"/></td></tr>";
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
    $code .= cms_gruppeneditor('cms_schulhof_blogeintrag_text', $text);

    $code .= "<h3>Zugehörige Downloads</h3>";
    $code .= cms_downloadelemente($dbs, 'blogintern', $id, $gruppe, $gruppenid);

    $code .= "<h3>Zugehörige Beschlüsse</h3>";
    $code .= cms_beschlusselemente($dbs, $id, $gruppe, $gruppenid);

    if ($GRUPPENRECHTE['dateiupload']) {
      $inhalt = "<h3>Dateien der Gruppe</h3>";
      $inhalt .= cms_dateisystem_generieren ('schulhof/gruppen/'.$gk.'/'.$gruppenid, 'schulhof/gruppen/'.$gk.'/'.$gruppenid, 'cms_dateien_gruppe', 's', 'schulhof', $gruppenid, $GRUPPENRECHTE);
      $code .= cms_toggleeinblenden_generieren('cms_gruppendateien', "Dateien der Gruppe anzeigen", "Dateien der Gruppe ausblenden", $inhalt, 0);
    }

		$code .= "</div></div>";



		$code .= "<div class=\"cms_clear\"></div>";


		$code .= "<div class=\"cms_spalte_i\">";

    $ziel = implode('/', array_slice($CMS_URL,0,5));
    if ($id == "-") {
		  $code .= "<p><span class=\"cms_button\" onclick=\"cms_blogeintraegeintern_neu_speichern('$ziel');\">Speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_blogeintraegeintern_bearbeiten_speichern('$ziel');\">Änderungen speichern</span> <a class=\"cms_button_nein\" href=\"$ziel\">Abbrechen</a></p>";
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
