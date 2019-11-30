<?php
function cms_fehler($modus, $code) {
  $CMS_URL[0] = $modus;
  $CMS_URL[1] = "Fehler";
  $CMS_URL[2] = $code;
  include_once("php/".strtolower($modus)."/seiten/fehler/".$code.".php");
}

function cms_seitenpfad_id_erzeugen($dbs, $id, $pfad = array()) {
  $dbs = cms_verbinden('s');
  $sqlsuche = $dbs->prepare("SELECT id, bezeichnung, zuordnung FROM seiten WHERE id = ?");
  $sqlsuche->bind_param("i", $id);
  if ($sqlsuche->execute()) {
    $sqlsuche->bind_result($sid, $sbezeichnung, $szuordnung);
    while($sqlsuche->fetch()) {
      $seite['id'] = $sid;
      $seite['bezeichnung'] = $sbezeichnung;
      array_unshift($pfad, $seite);
      if ($szuordnung."" != '-') {
        $sqlsuche->bind_param("i", $szuordnung);
        $sqlsuche->execute();
      }
    }
  }
  $sqlsuche->close();
  cms_trennen($dbs);
  return $pfad;
}

function cms_seitenpfadlink_erzeugen($pfad) {
  $link = '';
  foreach ($pfad as $seite) {
    $s = str_replace(' ', '_', $seite['bezeichnung']);
    $link .= $s."/";
  }
  $link = substr($link, 0, -1);
  return $link;
}

function cms_seitenpfadlink_zusammensetzen($zusatz) {
  if (!is_array($zusatz)) {$zusatz = explode('/', $zusatz);}
  $link = '';
  foreach ($zusatz as $seite) {
    $link .= $seite."/";
  }
  $link = substr($link, 0, -1);
  return $link;
}

function cms_seitenerweiterung_anfuegen($pfad) {
  global $CMS_URL;
  $neueurl = "";
  if (strlen($pfad) > 0) {
    if (count($CMS_URL) < 3) {
      $CMS_URL = array();
      $CMS_URL[0] = 'Website';
      $CMS_URL[1] = 'Seiten';
      $CMS_URL[2] = 'Aktuell';
    }
    $neueurl = array_merge(array_slice($CMS_URL,0,3), explode('/', $pfad));
  }
  return $neueurl;
}

function cms_pfad_aufloesen ($dbs, $pfad) {
  $fehler = false;
  $zuordnung = '-';
  $zuordnungstest = "zuordnung IS NULL";

  if (!is_array($pfad)) {$pfad = explode('/', $pfad);}

  for ($i = 0; $i < count($pfad); $i++) {
    $bezeichnung = str_replace('_', ' ', $pfad[$i]);

    $sql = $dbs->prepare("SELECT id FROM seiten WHERE $zuordnungstest AND bezeichnung = ?");
    $sql->bind_param("s", $bezeichnung);
    if ($sql->execute()) {
      $sql->bind_result($zuordnung);
      if ($sql->fetch()) {
        $zuordnungstest = "zuordnung = '$zuordnung'";
      }
  		else {$fehler = true;}
    }
    else {$fehler = true;}
    $sql->close();
  }
  if ($zuordnung === '-') {$fehler = true;}

  if ($fehler) {return '-';}
  else {return $zuordnung;}
}

function cms_seitendetails_erzeugen($dbs, $pfad) {
  $seite = array();
  $seitenid = cms_pfad_aufloesen($dbs, $pfad);
  if ($seitenid !== '-') {
    $sql = "SELECT * FROM seiten WHERE id = $seitenid";
    if ($anfrage = $dbs->query($sql)) { // Safe weil interne ID
      if ($daten = $anfrage->fetch_assoc()) {
        $seite = $daten;
      }
      $anfrage->free();
    }
  }
  return $seite;
}

function cms_startseitendetails_erzeugen($dbs) {
  $seite = array();
  $sql = "SELECT * FROM seiten WHERE art = 's'";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $seite = $daten;
    }
    $anfrage->free();
  }
  return $seite;
}

function cms_seite_ausgeben($dbs) {
  global $CMS_ANGEMELDET, $CMS_RECHTE, $CMS_GERAET, $CMS_SEITENDETAILS, $CMS_URL;
  $seite = $CMS_SEITENDETAILS;

	if (isset($seite['id'])) {
    $anzeige404 = false;
    $anzeige403 = false;
    $code = "";

    if (($CMS_URL[1] == "Bearbeiten") || ($CMS_URL[1] == "Seiten")) {
      if (($seite['status'] != 'i') || ($CMS_URL[1] == "Bearbeiten") && ($CMS_ANGEMELDET)) {
        if (($CMS_URL[1] == "Bearbeiten") && ($CMS_ANGEMELDET)) {
          include_once('php/schulhof/seiten/website/editor/editor.php');
        }

        if (($CMS_URL[1] == "Bearbeiten") && (!$CMS_ANGEMELDET)) {
          cms_fehler('Website', '403');
        }
        else {
          if (($seite['sidebar'] == '1') && (($CMS_GERAET == 'T') || ($CMS_GERAET == 'P'))) {
            $code .= "<div class=\"cms_spalte_4 cms_sidebar_inhalt\"><div class=\"cms_spalte_i\">";
              $code .= cms_navigation_ausgeben('s');
            $code .= "</div></div>";
            $code .= "<div class=\"cms_spalte_34\">";
          }

          $code .= "<div class=\"cms_spalte_i cms_hauptteil_inhalt\">";
            $code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";

            if ($seite['art'] == 's') {
              // Falls gerade ein Anmeldezeitraum ist, Anmeldungslink ausgeben
              if ($seite['status'] == 's') {
                $CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
                $jetzt = time();
                if (($CMS_VORANMELDUNG['Anmeldung aktiv'] == '1') && ($jetzt > $CMS_VORANMELDUNG['Anmeldung von']) && ($jetzt < $CMS_VORANMELDUNG['Anmeldung bis'])) {
                  $code .= cms_meldung('info', '<h4>Online Voranmeldung</h4><p>Die unverbindliche Schulvoranmeldung ist ab sofort möglich.</p><p><a class="cms_button" href="Website/Voranmeldung">zur Online-Voranmeldung</a></p>');
                }
              }


              $sql = "SELECT id FROM spalten WHERE seite = ".$seite['id']." ORDER BY zeile ASC, position ASC";
              if ($anfrage = $dbs->query($sql)) { // Safe weil interne ID
                while ($spalte = $anfrage->fetch_assoc()) {
                  $code .= cms_spalte_ausgeben($dbs, $spalte['id']);
                }
                $anfrage->free();
              }
              if (($CMS_URL[1] == 'Bearbeiten') && ($CMS_ANGEMELDET) && (($CMS_RECHTE['Website']['Inhalte anlegen']) || ($CMS_RECHTE['Website']['Inhalte bearbeiten']))) {
                $_SESSION['ELEMENTSEITE'] = $seite['id'];
              }
            }
            else if ($seite['art'] == 'm') {
              $code .= "<h1>".$seite['bezeichnung']."</h1>";
              $mcode = "";
              $sql = "SELECT id, bezeichnung, beschreibung, art FROM seiten WHERE zuordnung = ".$seite['id']." ORDER BY position";
              $pfad = cms_seitenpfadlink_erzeugen(cms_seitenpfad_id_erzeugen($dbs, $seite['id']));
              if ($anfrage = $dbs->query($sql)) { // Safe weil interne ID
                while ($s = $anfrage->fetch_assoc()) {
                  if ($s['art'] == 'g') {$jahr = date('Y'); $link = "Website/Galerien/$jahr/".cms_monatsnamekomplett(date('n'));}
                  else if ($s['art'] == 't') {$jahr = date('Y'); $link = "Website/Termine/$jahr/".cms_monatsnamekomplett(date('n'));}
                  else if ($s['art'] == 'b') {$jahr = date('Y'); $link = "Website/Blog/$jahr/".cms_monatsnamekomplett(date('n'));}
                  else {$link = "Website/$CMS_URL[1]/$CMS_URL[2]/".$pfad."/".str_replace('_', ' ', $s['bezeichnung']);}
                  $mcode .= "<li><a class=\"cms_website_menuepunkte\" href=\"$link\">";
                  $mcode .= "<h3>".$s['bezeichnung']."</h3>";
                  if (strlen($s['beschreibung'])>0) {$mcode .= "<p>".$s['beschreibung']."</p>";}
                  $mcode .= "</a></li>";
                }
                $anfrage->free();
              }
              if (($CMS_URL[1] == 'Bearbeiten') && ($CMS_ANGEMELDET) && (($CMS_RECHTE['Website']['Seiten anlegen']))) {
                $mcode .= "<li><span class=\"cms_website_menuepunkte_ja\" onclick=\"cms_schulhof_website_seite_neu_vorbereiten('".$seite['id']."');\">";
                $mcode .= "+ Neue Seite";
                $mcode .= "</span></li>";
              }
              if (strlen($mcode) > 0) {$code .= "<ul class=\"cms_uebersicht\">".$mcode."</ul>";}
            }
          $code .= "</div>";

          if ($seite['sidebar'] == '1') {$code .= "</div>";}

          $code .= "<div class=\"cms_clear\"></div>";
          echo $code;
        }
    	}
      else {
    		cms_fehler('Website', '404');
    	}
    }
  	else {
  		cms_fehler('Website', '404');
  	}
  }
  else {
    cms_fehler('Website', '404');
  }
}

function cms_spalte_ausgeben($dbs, $spalte) {
  global $CMS_ANGEMELDET, $CMS_RECHTE, $CMS_URL, $CMS_ELEMENTE;
  $code = "";
  $elementcode = array();

  // Suche Elemente
  foreach ($CMS_ELEMENTE as $element) {
    $sql = "SELECT * FROM $element WHERE spalte = $spalte";
    if ($anfrage = $dbs->query($sql)) { // TODO: Irgendwie safe machne
      while ($e = $anfrage->fetch_assoc()) {
        if ($element == 'editoren') {$elementcode[$e['position']] = cms_editoren_ausgeben($e);}
        if ($element == 'downloads') {$elementcode[$e['position']] = cms_downloads_ausgeben($e);}
        if ($element == 'boxenaussen') {$elementcode[$e['position']] = cms_boxenaussen_ausgeben($dbs, $e);}
        if ($element == 'eventuebersichten') {$elementcode[$e['position']] = cms_eventuebersichten_ausgeben($dbs, $e);}
        if ($element == 'kontaktformulare') {$elementcode[$e['position']] = cms_kontaktformulare_ausgeben($dbs, $e);}
        if ($element == 'wnewsletter') {$elementcode[$e['position']] = cms_newsletter_ausgeben($dbs, $e);}
      }
      $anfrage->free();
    }
  }

  $position = 1;
  if (($CMS_URL[1] == 'Bearbeiten') && ($CMS_ANGEMELDET) && ($CMS_RECHTE['Website']['Inhalte anlegen'])) {
    $code .= cms_neues_element($spalte, $position, $CMS_URL[2]);
  }

  for ($i = 1; $i <= count($elementcode); $i++) {
    $code .= $elementcode[$i];
    $position++;
    if (($CMS_URL[1] == 'Bearbeiten') && ($CMS_ANGEMELDET) && ($CMS_RECHTE['Website']['Inhalte anlegen'])) {
      $code .= cms_neues_element($spalte, $position, $CMS_URL[2]);
    }
  }

  if (($CMS_URL[1] == 'Bearbeiten') && ($CMS_ANGEMELDET) && (($CMS_RECHTE['Website']['Inhalte anlegen']) || ($CMS_RECHTE['Website']['Inhalte bearbeiten']))) {
    $_SESSION['ELEMENTMAXPOS'] = $position-1;
    $code .= "<p><input type=\"hidden\" id=\"cms_website_neu_maxpos\" name=\"cms_website_neu_maxpos\" value=\"".$position."\"></p>";
  }
  return $code;
}

function cms_neues_element($spalte, $position, $version) {
  global $CMS_URL;
  $CMS_ZUSATZ = array_slice($CMS_URL,3);
  $code = "<div class=\"cms_website_neu\" onclick=\"cms_einblenden('cms_website_neu_menue_$spalte"."_$position')\"></div>";
  $code .= "<div class=\"cms_website_neu_menue\" id=\"cms_website_neu_menue_$spalte"."_$position\" style=\"display: none;\">";
    $code .= "<p class=\"cms_website_neu_menue_box\">";
    $parameter = "'-', '$spalte', '$position', '$version', '".cms_seitenpfadlink_zusammensetzen($CMS_ZUSATZ)."'";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_editor\" onclick=\"cms_editoren_anzeigen($parameter)\">+ Neuer Editor</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_download\" onclick=\"cms_downloads_anzeigen($parameter)\">+ Neuer Download</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_boxen\" onclick=\"cms_boxenaussen_anzeigen($parameter)\">+ Neue Boxen</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_eventuebersicht\" onclick=\"cms_eventuebersichten_anzeigen($parameter)\">+ Neue Eventübersicht</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_kontaktformular\" onclick=\"cms_kontaktformulare_anzeigen($parameter)\">+ Neues Kontaktformular</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_ja cms_button_website_newsletter\" onclick=\"cms_wnewsletter_anzeigen($parameter)\">+ Neues Newsletteranmeldeformular</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_website_schliessen\" onclick=\"cms_ausblenden('cms_website_neu_menue_$spalte"."_$position')\">Menü schließen</span> ";
    $code .= "</p>";
    $code .= "<div class=\"cms_website_neu_element\" id=\"cms_website_neu_element_$spalte"."_$position\"></div>";
  $code .= "</div>";
  return $code;
}

function cms_element_bearbeiten($e, $art, $version, $innen = false) {
  global $CMS_URL;
  $CMS_ZUSATZ = array_slice($CMS_URL,3);
  $neueversion = !cms_ist_aktuell($art, $e, $innen);
  $zusatz = "";
  $aktiv = true;
  if (is_array($innen)) {foreach ($innen as $i) {if ($i['aktiv'] != 1) {$aktiv = false;}}}
  if ($e['aktiv'] == 0) {$aktiv = false;}
  if (!$aktiv) {$zusatz .= " cms_element_inaktiv";}
  $code = "<div class=\"cms_website_bearbeiten_menue$zusatz\" id=\"cms_website_bearbeiten_menue_".$e['spalte']."_".$e['position']."\" style=\"display: none;\">";
    $code .= "<p class=\"cms_website_bearbeiten_menue_box\">";
    $zusatz = cms_seitenpfadlink_zusammensetzen($CMS_ZUSATZ);
    $parameter = "'".$e['id']."', '".$e['spalte']."', '".$e['position']."', '$version', '".$zusatz."'";
    $code .= "<span class=\"cms_iconbutton cms_button_website_bearbeiten\" onclick=\"cms_".$art."_anzeigen($parameter)\">Bearbeiten</span> ";
    if (($neueversion) && ($version == 'Neu')) {
      $code .= "<span class=\"cms_iconbutton cms_button_website_freigeben\" onclick=\"cms_element_freigeben('$art', '".$e['id']."', '$version', '$zusatz')\">Freigeben</span> ";
      $code .= "<span class=\"cms_iconbutton cms_button_wichtig cms_button_website_ablehnen\" onclick=\"cms_element_ablehnen('$art', '".$e['id']."', '$version', '$zusatz')\">Ablehnen</span> ";
    }
    if (!$aktiv) {$code .= "<span class=\"cms_iconbutton cms_button_website_aktivieren\" onclick=\"cms_element_aktivieren('$art', '".$e['id']."', '$version', '$zusatz')\">Aktivieren</span> ";}
    $code .= "<span class=\"cms_iconbutton cms_button_nein cms_button_website_loeschen\" onclick=\"cms_element_loeschen_anzeigen('$art', '".$e['id']."', '$version', '$zusatz')\">Löschen</span> ";
    $code .= "<span class=\"cms_iconbutton cms_button_website_schliessen\" onclick=\"cms_ausblenden('cms_website_bearbeiten_menue_".$e['spalte']."_".$e['position']."')\">Menü schließen</span> ";
    $code .= "</p>";

    $code .= "<p class=\"cms_elementicons\">";
    if ($e['aktiv'] == '0') {$code .= "<span class=\"cms_element_icon\"><span class=\"cms_hinweis\">inaktiv</span><img src=\"res/icons/klein/inaktiv.png\"></span> ";}
    else {$code .= "<span class=\"cms_element_icon\"><span class=\"cms_hinweis\">aktiv</span><img src=\"res/icons/klein/aktiv.png\"></span> ";}
    if ($neueversion) {$code .= "<span class=\"cms_element_icon\"><span class=\"cms_hinweis\">neue Daten verfügbar</span><img src=\"res/icons/klein/neu.png\"></span> ";}
    $code .= "</p>";

    $code .= "<div class=\"cms_website_bearbeiten_element\" id=\"cms_website_bearbeiten_element_".$e['spalte']."_".$e['position']."\"></div>";
  $code .= "</div>";

  $zusatz = "cms_website_bearbeiten";
  if ($e['aktiv'] == 0) {$zusatz .= " cms_element_inaktiv";}
  if ($neueversion) {$zusatz .= " cms_element_neuedaten_anzeige";}
  $code .= "<div class=\"$zusatz\" onclick=\"cms_einblenden('cms_website_bearbeiten_menue_".$e['spalte']."_".$e['position']."')\">";
  return $code;
}

function cms_ist_aktuell($art, $e, $innen = false) {
  if ($art == 'editoren') {return $e['aktuell'] == $e['neu'];}
  else if ($art == 'downloads') {return (($e['pfadaktuell'] == $e['pfadneu']) && ($e['titelaktuell'] == $e['titelneu']) && ($e['beschreibungaktuell'] == $e['beschreibungneu']) && ($e['dateinameaktuell'] == $e['dateinameneu']) && ($e['dateigroesseaktuell'] == $e['dateigroesseneu']));}
  else if ($art == 'boxenaussen') {
    $zwischen = true;
    foreach ($innen as $box) {
      $zwischen = $zwischen && ($box['titelaktuell'] == $box['titelneu']) && ($box['inhaltaktuell'] == $box['inhaltneu']) && ($box['styleaktuell'] == $box['styleneu']);
    }
    return ($zwischen && ($e['ausrichtungaktuell'] == $e['ausrichtungneu']) && ($e['breiteaktuell'] == $e['breiteneu']));
  }
  else if ($art == 'eventuebersichten') {return (($e['termineaktuell'] == $e['termineneu']) && ($e['termineanzahlaktuell'] == $e['termineanzahlneu']) && ($e['blogaktuell'] == $e['blogneu']) && ($e['bloganzahlaktuell'] == $e['bloganzahlneu']) && ($e['galerieaktuell'] == $e['galerieneu']) && ($e['galerieanzahlaktuell'] == $e['galerieanzahlneu']));}
  else {return false;}
}



function cms_editoren_ausgeben($e) {
  global $CMS_URL;
  // Inaktiv für den Benutzer
  if (($CMS_URL[1] == 'Seiten') && ($e['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($e, 'editoren', $CMS_URL[2]);
    }
    if ($CMS_URL[2] == 'Aktuell') {
      $code .= cms_ausgabe_editor($e['aktuell']);
    }
    else if ($CMS_URL[2] == 'Alt') {
      $code .= cms_ausgabe_editor($e['alt']);
    }
    else if ($CMS_URL[2] == 'Neu') {
      $code .= cms_ausgabe_editor($e['neu']);
    }
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}

function cms_downloads_ausgeben($e) {
  global $CMS_URL;
  // Inaktiv für den Benutzer
  if (($CMS_URL[1] == 'Seiten') && ($e['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    $zusatz = strtolower($CMS_URL[2]);
    $zusatzklasse = "";
    $pfad = $e['pfad'.$zusatz];
    $titel = $e['titel'.$zusatz];
    $beschreibung = $e['beschreibung'.$zusatz];
    $dateiname = $e['dateiname'.$zusatz];
    $dateigroesse = $e['dateigroesse'.$zusatz];
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($e, 'downloads', $CMS_URL[2]);
      $event = "";
    }
    else {
      $event = " onclick=\"cms_download('$pfad')\"";
    }
    $aktiv = true;
    if (!is_file($pfad)) {$zusatzklasse = " cms_download_inaktiv"; $event = ""; $aktiv = false;}
    $dname = explode('/', $pfad);
    $dname = $dname[count($dname)-1];
    $endung = explode('.', $dname);
    $endung = $endung[count($endung)-1];
    $icon = cms_dateisystem_icon($endung);
    $code .= "<div class=\"cms_download_anzeige$zusatzklasse\" style=\"background-image: url('res/dateiicons/gross/".$icon."');\"$event>";
      $code .= "<h4>$titel</h4>";
      if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
      $info = "";
      if ($aktiv) {
        if ($dateiname == 1) {$info .= ' - '.$dname;}
        if ($dateigroesse == 1) {$info .= ' - '.cms_groesse_umrechnen(filesize($pfad));}
      }
      else {$info = "Die Datei existiert nicht mehr.";}
      if (strlen($info) > 0) {
        $info = substr($info, 3);
        $code .= "<p class=\"cms_notiz\">".$info."</p>";
      }
    $code .= "</div>";
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}

function cms_boxenaussen_ausgeben($dbs, $e) {
  global $CMS_URL;
  // Inaktiv für den Benutzer
  $boxxen = array();
  $sql = "SELECT * FROM boxen WHERE boxaussen = ".$e['id']." ORDER BY position ASC";
  if ($anfrage = $dbs->query($sql)) { // TODO: Irgendwie safe machen
    while ($daten = $anfrage->fetch_assoc()) {
      array_push($boxxen, $daten);
    }
    $anfrage->free();
  }

  if (($CMS_URL[1] == 'Seiten') && ($e['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    $zusatz = strtolower($CMS_URL[2]);
    $zusatzklasse = "";
    $ausrichtung = $e['ausrichtung'.$zusatz];
    $breite = $e['breite'.$zusatz];
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($e, 'boxenaussen', $CMS_URL[2], $boxxen);
    }

    $code .= "<div class=\"cms_boxen_$ausrichtung\">";
    $boxcode = "";
    foreach ($boxxen as $boxen) {
      $boxcode .= cms_box_ausgeben($boxen, $ausrichtung, $breite, $zusatz);
    }
    if ((strlen($boxcode) == 0) && ($CMS_URL[1] == 'bearbeiten')) {$boxcode = '<p class=\"cms_notiz\">Keine Boxen angelegt</p>';}
    $code .= $boxcode;
    $code .= "<div class=\"cms_clear\"></div>";
    $code .= "</div>";

    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}

function cms_box_ausgeben($boxen, $ausrichtung, $breite, $zusatz) {
  global $CMS_URL;
  $code = "";
  if (($CMS_URL[1] == 'Seiten') && ($boxen['aktiv'] == '0')) {
    return "";
  }
  else {
    if ($ausrichtung == 'u') {$style = "";} else {$style = " style=\"width: $breite"."px;\"";}
    if ($boxen['aktiv'] != '1') {$zklasse = ' cms_element_inaktiv';} else {$zklasse = "";}
    $code .= "<div class=\"cms_box_$ausrichtung cms_box_".$boxen['style'.$zusatz]."$zklasse\" $style>";
      if ($ausrichtung == 'n') {$style = "";} else {$style = " style=\"width: $breite"."px;\"";}
      $code .= "<div class=\"cms_box_titel\" $style>";
      $code .= "<h4>".$boxen['titel'.$zusatz]."</h4>";
      $code .= "</div>";
      $code .= "<div class=\"cms_box_inhalt\">";
        $code .= cms_ausgabe_editor($boxen['inhalt'.$zusatz]);
      $code .= "</div>";
      $code .= "<div class=\"cms_clear\"></div>";
    $code .= "</div>";
  }
  return $code;
}

function cms_eventuebersichten_ausgeben($dbs, $e) {
  global $CMS_SCHLUESSEL, $CMS_URL;
  // Inaktiv für den Benutzer
  if (($CMS_URL[1] == 'Seiten') && ($e['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    $zusatz = strtolower($CMS_URL[2]);
    $zusatzklasse = "";
    $termine = $e['termine'.$zusatz];
    $termineanzahl = $e['termineanzahl'.$zusatz];
    $blog = $e['blog'.$zusatz];
    $bloganzahl = $e['bloganzahl'.$zusatz];
    $galerie = $e['galerie'.$zusatz];
    $galerieanzahl = $e['galerieanzahl'.$zusatz];
    $aktiv = $e['aktiv'];

    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($e, 'eventuebersichten', $CMS_URL[2]);
    }

    $aussenklasse = " cms_eventuebersicht_aussen_";
    if ($termine == 1) {$aussenklasse .= 't';}
    if ($blog == 1) {$aussenklasse .= 'b';}
    if ($galerie == 1) {$aussenklasse .= 'g';}

    $code .= "<div class=\"cms_eventuebersicht$aussenklasse\">";
      $jetzt = time();

      $felder = 0;

      $blogcode = "";
      if ($blog == 1) {
        $sql = "SELECT id, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM blogeintraege WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4 AND datum < $jetzt ORDER BY datum DESC LIMIT ".$bloganzahl;
        if ($anfrage = $dbs->query($sql)) { // Safe weil Einstellung ist numerisch
          include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
          while ($daten = $anfrage->fetch_assoc()) {
            $blogcode .= cms_blogeintrag_link_ausgeben($dbs, $daten, 'artikel');
          }
          $anfrage->free();
        }
      }

      $termincode = "";
      if ($termine == 1) {
        $sqltermine = "SELECT id, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4 AND ende > $jetzt ORDER BY beginn ASC, ende ASC LIMIT 0,".$termineanzahl;
        $sqlferien = "SELECT id, art, 1 AS genehmigt, bezeichnung, '' AS ort, beginn, ende, mehrtaegigt, 0 AS uhrzeitbt, 0 AS uhrzeitet, 0 AS ortt, 4 AS oeffentlichkeit, '' AS text FROM ferien WHERE ende > $jetzt LIMIT ".$termineanzahl;
        $sql = "SELECT * FROM (($sqltermine) UNION ($sqlferien)) AS x ORDER BY beginn ASC, ende ASC LIMIT ".$termineanzahl;

        if ($anfrage = $dbs->query($sql)) { // Safe weil Einstellung ist numerisch
          include_once('php/schulhof/seiten/termine/termineausgeben.php');
          while ($daten = $anfrage->fetch_assoc()) {
            $termincode .= cms_termin_link_ausgeben($dbs, $daten);
          }
          $anfrage->free();
        }
      }

      $galeriecode = "";
      if ($galerie == 1) {
        $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE aktiv = 1 AND genehmigt = 1 ORDER BY datum ASC LIMIT 0,".$galerieanzahl;
        if ($anfrage = $dbs->query($sql)) { // Safe weil Einstellung ist numerisch
          include_once('php/schulhof/seiten/galerien/galerienausgeben.php');
          while ($daten = $anfrage->fetch_assoc()) {
            $galeriecode .= cms_galerie_link_ausgeben($dbs, $daten, "artikel");
          }
          $anfrage->free();
        }
      }

      if (strlen($blogcode) > 0) {
        $code .= "<div class=\"cms_eventuebersicht_box_a cms_eventuebersicht_box_blog\"><div class=\"cms_eventuebersicht_box_i\"><h2>Aktuelles</h2><ul class=\"cms_bloguebersicht_artikel\">".$blogcode."</ul><p><a class=\"cms_button\" href=\"Website/Blog/".date('Y')."/".cms_monatsnamekomplett(date('m'))."\">Ältere Einträge</a></p></div></div>";
      }
      if (strlen($termincode) > 0) {
        $code .= "<div class=\"cms_eventuebersicht_box_a cms_eventuebersicht_box_termine\"><div class=\"cms_eventuebersicht_box_i\"><h2>Anstehende Termine</h2><ul class=\"cms_terminuebersicht\">".$termincode."</ul><p><a class=\"cms_button\" href=\"Website/Termine/".date('Y')."/".cms_monatsnamekomplett(date('m'))."\">Mehr Termine</a></p></div></div>";
      }
      if (strlen($galeriecode) > 0) {
        $code .= "<div class=\"cms_eventuebersicht_box_a cms_eventuebersicht_box_galerien\"><div class=\"cms_eventuebersicht_box_i\"><h2>Galerien</h2><ul class=\"cms_galerieuebersicht_artikel\">".$galeriecode."</ul><p><a class=\"cms_button\" href=\"Website/Galerie/".date('Y')."/".cms_monatsnamekomplett(date('m'))."\">Mehr Galerien</a></p></div></div>";
      }
      $code .= "<div class=\"cms_clear\"></div>";

      if (($felder == 0) && ($CMS_URL[1] == 'Bearbeiten')) {$code .= '<p class="cms_notiz">Aktuell gibt es keine Inhalte für dieses Element.</p>';}

    $code .= "</div>";
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}

function cms_kontaktformulare_ausgeben($dbs, $k) {
  global $CMS_SCHLUESSEL, $CMS_URL;
  // Inaktiv für den Benutzer
  if (($CMS_URL[1] == 'Seiten') && ($k['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    $zusatz = strtolower($CMS_URL[2]);
    $zusatzklasse = "";
    $betreff = $k['betreff'.$zusatz];
    $kopie = $k['kopie'.$zusatz] == "2";
    $anhang = $k['anhang'.$zusatz];
    $aktiv = $k['aktiv'];

    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($k, 'kontaktformulare', $CMS_URL[2]);
    }
    $sql = "SELECT COUNT(*) FROM kontaktformulareempfaenger WHERE kontaktformular = ".$k["id"];
    $sql = $dbs->prepare($sql);
    $sql->bind_result($empfaengeranz);
    $sql->execute();
    $sql->fetch();
    $sql->close();

    $sql = "SELECT id, name$zusatz as name, beschreibung$zusatz as beschreibung, mail$zusatz as mail FROM kontaktformulareempfaenger WHERE kontaktformular = ".$k['id'];
    $sql = $dbs->query($sql); // TODO: Irgendwie safe machen

    if(!$sql)
      return "<p class=\"cms_notiz\">Für das Formular sind keine Empfänger hinterlegt.</p>";

    $aussenklasse = " cms_kontaktformular_aussen_";

    $code .= "<div class=\"cms_kontaktformular cms_kontaktfomular$aussenklasse\">";
      $jetzt = time();


      $felder = 0;

      $code .= "<div class=\"cms_kontaktformular_box_a\"><div class=\"cms_kontaktformular_box_i\">";
        $CMS_EINWILLIGUNG_A = false;
        if (isset($_SESSION["DSGVO_EINWILLIGUNG_A"])) {$CMS_EINWILLIGUNG_A = $_SESSION["DSGVO_EINWILLIGUNG_A"];}
        if (!$CMS_EINWILLIGUNG_A) {$code .= cms_meldung_einwilligungA();}
        else {
        $code .= "<table class=\"cms_formular\" id=\"cms_kontaktformular_tabelle_".$k["id"]."\">";
          $code .= "<tr style=\"display:none\"><th><input type=\"hidden\" class=\"cms_kontaktformular_id\" value=\"".$k["id"]."\"></th></tr>";
          $code .= "<tr><th>Empfänger: </th><td>";
          if(!$empfaengeranz) {
            $code .= "<p class=\"cms_notiz\">Es sind keine Empfänger hinterlegt.<br>Bitte den Administrator kontaktieren.</p>";
          } else {
            if($empfaengeranz == 1) {
              $sqld = $sql->fetch_assoc();
              $code .= "<input type=\"hidden\" class=\"cms_kontaktformular_empfaenger\" value=\"{$sqld['id']}\">";
              $code .= "<input class=\"cms_mittel\" disabled value=\"".$sqld["name"].($sqld["beschreibung"]?" - ".$sqld["beschreibung"]:"")."\">";
            } else {
              $code .= "<select class=\"cms_kontaktformular_empfaenger\"><option selected display=\"none\" hidden value=\"-1\">Bitte wählen</option>";
              while($sqld = $sql->fetch_assoc())
                $code .= "<option value=\"".$sqld["id"]."\">".$sqld["name"].($sqld["beschreibung"]?" - ".$sqld["beschreibung"]:"")."</option>";
              $code .= "</select>";
            }
          }
          $code .= "</td></tr>";

          $code .= "<tr><th>Name: </th><td><input type=\"text\" class=\"cms_kontaktformular_absender\" autocomplete=\"name\"></td></tr>";
          $code .= "<tr><th>eMailadresse: </th><td><input type=\"text\" class=\"cms_kontaktformular_mail\" autocomplete=\"email\"></td></tr>";
          $code .= "<tr><th>Betreff: </th><td><input type=\"text\" class=\"cms_kontaktformular_betreff\"></td></tr>";
          $code .= "<tr><th>Nachricht: </th><td><textarea rows=5 class=\"cms_kontaktformular_nachricht\"></textarea></td></tr>";
          if($anhang)
            $code .= "<tr><th>Anhänge hinzufügen: </th><td><input type=\"file\" class=\"cms_kontaktformular_anhang\" multiple><p class=\"cms_notiz\">Insgesamt max. 8MiB</p></td></tr>";
          if($kopie)
            $code .= "<tr><th>Kopie an Sie: </th><td>".cms_select_generieren("", "cms_kontaktformular_kopie", array(1 => "Ja", 0 => "Nein"), 1, true)."</td></tr>";
          $code .= "<tr><th>Sicherheitsabfrage zur Spamverhinderung: </th><td>".cms_captcha_generieren('', $uid)." Bitte übertragen Sie die Buchstaben und Zahlen aus dem Bild in der korrekten Reihenfolge in das nachstehende Feld.</tr>";
          $code .= "<tr></tr>";
          $code .= "<tr><th></th><td><input type=\"text\" class=\"cms_spamverhinderung\" id=\"cms_spamverhinderung_$uid\"></td></tr>";
          $code .= "<tr><th></th><td><span class=\"cms_button_ja\" onclick=\"cms_kontaktformular_absenden(this)\">Absenden</span></td></tr>";
        $code .= "</table>";
        }

      $code .= "</div></div>";

      $code .= "<div class=\"cms_clear\"></div>";

      if (($felder == 0) && ($CMS_URL[1] == 'Bearbeiten')) {$code .= '<p class="cms_notiz">Aktuell gibt es keine Inhalte für dieses Element.</p>';}

    $code .= "</div>";
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}

function cms_newsletter_ausgeben($dbs, $k) {
  global $CMS_SCHLUESSEL, $CMS_URL, $CMS_GRUPPEN;
  // Inaktiv für den Benutzer
  if (($CMS_URL[1] == 'Seiten') && ($k['aktiv'] == '0')) {
    return "";
  }
  else {
    $code = "";
    $zusatz = strtolower($CMS_URL[2]);
    $zusatzklasse = "";
    $bezeichnung = $k['bezeichnung'.$zusatz];
    $beschreibung = $k['beschreibung'.$zusatz];
    $typ = $k["typ".$zusatz];
    $aktiv = $k['aktiv'];

    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= cms_element_bearbeiten($k, 'wnewsletter', $CMS_URL[2]);
    }

    $aussenklasse = " cms_newsletter_aussen_";

    $code .= "<div class=\"cms_newsletter cms_newsletter$aussenklasse\">";
      $jetzt = time();

      $code .= "<div class=\"cms_newsletter_box_a\"><div class=\"cms_newsletter_box_i\"><h2>$bezeichnung</h2>";
      $CMS_EINWILLIGUNG_A = false;
      if (isset($_SESSION["DSGVO_EINWILLIGUNG_A"])) {$CMS_EINWILLIGUNG_A = $_SESSION["DSGVO_EINWILLIGUNG_A"];}
      if (!$CMS_EINWILLIGUNG_A) {$code .= cms_meldung_einwilligungA();}
      else {
        if($beschreibung)
          $code .= "<p>$beschreibung</p>";
        $code .= "<table class=\"cms_formular\" id=\"cms_newsletter_tabelle_".$k["id"]."\">";
          $code .= "<tr style=\"display:none\"><th><input type=\"hidden\" class=\"cms_newsletter_id\" value=\"".$typ."\"></th></tr>";
          $code .= "<tr><th>Name: </th><td><input type=\"text\" class=\"cms_newsletter_name\" autocomplete=\"name\"></td></tr>";
          $code .= "<tr><th>eMailadresse: </th><td><input type=\"text\" class=\"cms_newsletter_mail\" autocomplete=\"email\"></td></tr>";
          $code .= "<tr></tr>";

          $zuordnungen = "";
          foreach ($CMS_GRUPPEN as $g) {
            $gk = cms_textzudb($g);
            $sql = "SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."newsletter JOIN $gk ON gruppe = id WHERE newsletter = $typ) AS x ORDER BY bezeichnung ASC";
            if ($anfrage2 = $dbs->query($sql)) {
              while ($z = $anfrage2->fetch_assoc()) {
                $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$z['bezeichnung']."</span><img src=\"res/gruppen/klein/".$z['icon']."\"></span> ";
              }
              $anfrage2->free();
            }
          }

          if(strlen($zuordnungen) > 0)
            $code .= "<tr><td colspan=\"2\">$zuordnungen</td></tr>";

          $code .= "<tr><th>Sicherheitsabfrage zur Spamverhinderung: </th><td>".cms_captcha_generieren('', $uid)." Bitte übertragen Sie die Buchstaben und Zahlen aus dem Bild in der korrekten Reihenfolge in das nachstehende Feld.</tr>";
          $code .= "<tr></tr>";
          $code .= "<tr><th></th><td><input type=\"text\" class=\"cms_spamverhinderung\" id=\"cms_spamverhinderung_$uid\"></td></tr>";
          $code .= "<tr><th></th><td><span class=\"cms_button_ja\" onclick=\"cms_wnewsletter_anmelden(this)\">Zum Newsletter anmelden</span></td></tr>";

          $code .= "</table>";
      }
      $code .= "</div></div>";

      $code .= "<div class=\"cms_clear\"></div>";

    $code .= "</div>";
    if ($CMS_URL[1] == 'Bearbeiten') {
      $code .= "</div>";
    }
    return $code;
  }
}



function cms_zeitabhaengig_aus_schulhof() {
  global $CMS_URL, $CMS_SEITENDETAILS, $CMS_SCHLUESSEL, $CMS_GERAET;
  $fehler = false;
  $code = "";

  if ($CMS_URL[1] == 'Termine') {$seitenart = 't';}
  else if ($CMS_URL[1] == 'Galerien') {$seitenart = 'g';}
  else if ($CMS_URL[1] == 'Blog') {$seitenart = 'b';}
  else {$fehler = true;}

  if (isset($CMS_URL[2])) {$jahr = $CMS_URL[2];}
  else {$jahr = date('Y');}


  if (!cms_check_ganzzahl($jahr,0) || ($jahr == '-')) {$jahr = date('Y');}
  $monate = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
  if (isset($CMS_URL[3])) {if (!in_array($CMS_URL[3], $monate)) {$CMS_URL[3] = cms_monatsnamekomplett(date('n'));}}
  else {$CMS_URL[3] = cms_monatsnamekomplett(date('n'));}

  if (!$fehler) {
    $dbs = cms_verbinden('s');
    // Seite laden
    $sql = "SELECT * FROM seiten WHERE art = '$seitenart'";
    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      if ($daten = $anfrage->fetch_assoc()) {
        $seite = $daten;
        $CMS_SEITENDETAILS = $seite;
      }
      else {$fehler = true;}
      $anfrage->free();
    }
    else {$fehler = true;}

    if (isset($seite)) {

      if (($seite['sidebar'] == '1') && (($CMS_GERAET == 'T') || ($CMS_GERAET == 'P')) && (count($CMS_URL) < 6)) {
        $code .= "<div class=\"cms_spalte_4 cms_sidebar_inhalt\"><div class=\"cms_spalte_i\">";
          $code .= cms_zeitnavigation_ausgeben($dbs, 's');
        $code .= "</div></div>";
        $code .= "<div class=\"cms_spalte_34 cms_hauptteil_inhalt\">";
      }

      $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
        if (count($CMS_URL) < 6) {

        $code .= "<h1>".$seite['bezeichnung']."</h1>";
        $code .= "<div class=\"cms_termine_jahrueberischt\">";
          $code .= "<div class=\"cms_termine_jahrueberischt_knoepfe\">";
            $code .= "<span class=\"cms_termine_jahrueberischt_knoepfe_vorher\"><a class=\"cms_button\" href=\"Website/$CMS_URL[1]/".($jahr-1)."/".$CMS_URL[3]."\">«</a></span>";
            $code .= "<span class=\"cms_termine_jahrueberischt_knoepfe_jahr\">$jahr</span>";
              $code .= "<span class=\"cms_termine_jahrueberischt_knoepfe_nachher\"><a class=\"cms_button\" href=\"Website/$CMS_URL[1]/".($jahr+1)."/".$CMS_URL[3]."\">»</a></span>";
          $code .= "</div>";
          $monathoehe = "";
          $monatname = "";

          if ($CMS_URL[1] == 'Termine') {$sqlrumpf = "SELECT COUNT(id) AS anzahl FROM termine WHERE aktiv = 1 AND oeffentlichkeit = 4 AND genehmigt = 1 AND ";}
          else if ($CMS_URL[1] == 'Blog') {$sqlrumpf = "SELECT COUNT(id) AS anzahl FROM blogeintraege WHERE aktiv = 1 AND oeffentlichkeit = 4 AND genehmigt = 1 AND ";}
          else if ($CMS_URL[1] == 'Galerien') {$sqlrumpf = "SELECT COUNT(id) AS anzahl FROM galerien WHERE aktiv = 1 AND oeffentlichkeit = 4 AND genehmigt = 1 AND ";}

          for ($i = 1; $i <= 12; $i++) {
            $hoehe = 0;
            $monatbeginn = mktime(0, 0, 0, $i, 1, $jahr);
            $monatende = mktime(0, 0, 0, $i+1, 1, $jahr)-1;
            if ($CMS_URL[1] == 'Termine') {$sql = $sqlrumpf."((beginn >= $monatbeginn AND beginn <= $monatende) OR (ende >= $monatbeginn AND ende <= $monatende) OR (beginn <= $monatbeginn AND ende >= $monatende))";}
            else {$sql = $sqlrumpf."(datum BETWEEN $monatbeginn AND $monatende)";}
            if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
              if ($daten = $anfrage->fetch_assoc()) {
                $hoehe = 10*$daten['anzahl'];
                if ($hoehe > 100) {$hoehe = 100;}
              }
              $anfrage->free();
            }
            $zusatzklasse = "";
            if ($i == 12) {$zusatzklasse = " cms_letzte";}
            $aktiv = "";
            if (cms_monatsnamekomplett($i) == $CMS_URL[3]) {
              $aktiv .= " cms_jahresuebersicht_aktuell";
              $mb = $monatbeginn;
              $me = $monatende;
            }
            $monathoehe .= "<span class=\"cms_termine_jahrueberischt_monathoeheF$zusatzklasse\"><a class=\"cms_termine_jahrueberischt_monathoehe$aktiv\" style=\"height: $hoehe"."px;\" href=\"Website/$CMS_URL[1]/$jahr/".cms_monatsnamekomplett($i)."\"></a></span>";
            $monatname .= "<span class=\"cms_termine_jahrueberischt_monat$zusatzklasse\">".cms_monatsname($i)."</span>";
          }
          $code .= "<div class=\"cms_termine_jahrueberischt_allemonate_balken\">$monathoehe<div class=\"cms_clear\"></div></div>";
          $code .= "<div class=\"cms_termine_jahrueberischt_allemonate_namen\">$monatname<div class=\"cms_clear\"></div></div>";
        $code .= "</div>";
      }

      if ($CMS_URL[1] == "Termine") {
        include_once('php/schulhof/seiten/termine/termineausgeben.php');
        if (isset($CMS_URL[5])) {
          include_once('php/schulhof/seiten/downloads/downloads.php');
          $code .= cms_termindetailansicht_ausgeben($dbs);
        }
        else {
          $termincode = "";
          $sql = "SELECT id, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, oeffentlichkeit, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4 AND ((beginn >= $mb AND beginn <= $me) OR (ende >= $mb AND ende <= $me) OR (beginn <= $mb AND ende >= $me)) ORDER BY beginn ASC, ende ASC";
          if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
            include_once('php/schulhof/seiten/termine/termineausgeben.php');
            while ($daten = $anfrage->fetch_assoc()) {
              $termincode .= cms_termin_link_ausgeben($dbs, $daten);
            }
            $anfrage->free();
          }

          $code .= "<h2>".$CMS_URL[3]." $jahr</h2>";
          if(strlen($termincode) > 0) {
            $code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";
          }
          else {$code .= "<p class=\"cms_notiz\">Keine Termine</p>";}
        }
      }
      else if ($CMS_URL[1] == "Blog") {
        include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
        if (isset($CMS_URL[5])) {
          include_once('php/schulhof/seiten/downloads/downloads.php');
          $code .= cms_blogeintragdetailansicht_ausgeben($dbs);
        }
        else {
          $blogcode = "";
          $monat = date('n', $mb);
          $jahr = date('Y', $mb);
          $sql = "SELECT id, 'oe' AS art, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM blogeintraege WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4 AND (datum BETWEEN $mb AND $me) ORDER BY datum DESC";
          if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
            include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
            while ($daten = $anfrage->fetch_assoc()) {
              $blogcode .= cms_blogeintrag_link_ausgeben($dbs, $daten, 'artikel');
            }
            $anfrage->free();
          }

          $code .= "<h2>".$CMS_URL[3]." $jahr</h2>";
          if(strlen($blogcode) > 0) {
            $code .= "<ul class=\"cms_bloguebersicht_artikel\">".$blogcode."</ul>";
          }
          else {$code .= "<p class=\"cms_notiz\">Keine Blogeinträge</p>";}
        }
      }
      else if ($CMS_URL[1] == "Galerien") {
        include_once('php/schulhof/seiten/galerien/galerienausgeben.php');
        if (isset($CMS_URL[5])) {
          $code .= cms_galeriedetailansicht_ausgeben($dbs);
        }
        else {
          $galeriecode = "";
          $monat = date('n', $mb);
          $jahr = date('Y', $mb);
          $sql = "SELECT id, genehmigt, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM galerien WHERE aktiv = 1 AND genehmigt = 1 AND oeffentlichkeit = 4 AND (datum BETWEEN $mb AND $me) ORDER BY datum DESC";
          if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
            include_once('php/schulhof/seiten/galerien/galerienausgeben.php');
            while ($daten = $anfrage->fetch_assoc()) {
              $galeriecode .= cms_galerie_link_ausgeben($dbs, $daten, 'artikel');
            }
            $anfrage->free();
          }

          $code .= "<h2>".$CMS_URL[3]." $jahr</h2>";
          if(strlen($galeriecode) > 0) {
            $code .= "<ul class=\"cms_galerieuebersicht_artikel\">".$galeriecode."</ul>";
          }
          else {$code .= "<p class=\"cms_notiz\">Keine Galerien</p>";}
        }
      }

      $code .= "</div>";

      if ($seite['sidebar'] == '1') {$code .= "</div>";}
    }

    $code .= "<div class=\"cms_clear\"></div>";
    cms_trennen($dbs);
  }
  if ($fehler) {return false;}
  else return $code;
}

function cms_seite_aus_schulhof() {
  global $CMS_URL, $CMS_SEITENDETAILS, $CMS_SCHLUESSEL;
  $code = "";
  $seitenart = '';
  $fehler = false;

  if (($CMS_URL[1] == 'Termine') || ($CMS_URL[1] == 'Galerien') || ($CMS_URL[1] == 'Blog')) {
    $code .= cms_zeitabhaengig_aus_schulhof();
    if (!$code) {$fehler = true;}
  }
  else {$fehler = true;}

  if ($fehler) {cms_fehler('Website', '404');}
  else echo $code;
}
?>
