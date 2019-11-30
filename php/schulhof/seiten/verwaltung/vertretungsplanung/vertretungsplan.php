<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>
<?php

$zugriff = $CMS_RECHTE['Planung']['Vertretungen planen'];

if ($zugriff) {
  $jetzt = time();

  $fehler = false;
  $lehrer = "-";
  $raum = "-";
  $klasse = "-";
  $schulajhr = "-";
  $vtextschueler = "";
  $vtextlehrer = "";
  $vtextid = "-";
  $atag = date('d', $jetzt);
  $amonat = date('m', $jetzt);
  $ajahr = date('Y', $jetzt);
  $astunde = date('H', $jetzt);
  $aminute = date('i', $jetzt);

  $dbs = cms_verbinden();
  // Lehrer suchen
  $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id)";
  $sql .= " AS x ORDER BY nachname ASC, vorname ASC, kuerzel ASC";
  $lehreroptionen = "";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      if ($lehrer == '-') {$lehrer = $daten['id'];}
      $lehreroptionen .= "<option id=\"cms_vertretungsplan_ausgang_lehrer_".$daten['id']."\" value=\"".$daten['id']."\">";
        $lehreroptionen .= cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel'])." (".$daten['kuerzel'].")";
      $lehreroptionen .= "</option>";
    }
    $anfrage->free();
  } else {$fehler = true;}

  // Räume suchen
  $sql = "SELECT * FROM (SELECT id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume) AS x ORDER BY bezeichnung ASC";
  $raumoptionen = "";
  if ($anfrage = $dbs->query($sql)) { //  Safe weil keine Einabe
    while ($daten = $anfrage->fetch_assoc()) {
      if ($raum == '-') {$raum = $daten['id'];}
      $raumoptionen .= "<option id=\"cms_vertretungsplan_ausgang_raum_".$daten['id']."\" value=\"".$daten['id']."\">";
        $raumoptionen .= $daten['bezeichnung'];
      $raumoptionen .= "</option>";
    }
    $anfrage->free();
  } else {$fehler = true;}


  // Schuljahr suchen, in dem sich der Tag befindet
  $sql = "SELECT id FROM schuljahre WHERE $jetzt BETWEEN beginn AND ende";
  $schuljahr = "";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $schuljahr = $daten['id'];
    } else {$fehler = true;}
    $anfrage->free();
  } else {$fehler = true;}

  // Heute
  $tagzeit = mktime(0, 0, 0, $amonat, $atag, $ajahr);
  $sql = "SELECT id, AES_DECRYPT(textschueler, '$CMS_SCHLUESSEL') AS s,  AES_DECRYPT(textlehrer, '$CMS_SCHLUESSEL') AS l FROM vertretungstexte WHERE beginn = $tagzeit";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $vtextschueler = $daten['s'];
      $vtextlehrer = $daten['l'];
      $vtextid = $daten['id'];
    }
    $anfrage->free();
  }

  if (!$fehler) {
    // Klassen suchen
    $sql = "SELECT * FROM (SELECT reihenfolge, klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE schuljahr = $schuljahr) AS x ORDER BY reihenfolge ASC, stufe ASC, bezeichnung ASC";
    $klassenoptionen = "";
    if ($anfrage = $dbs->query($sql)) { // Safe weil interne ID
      while ($daten = $anfrage->fetch_assoc()) {
        if ($klasse == '-') {$klasse = $daten['id'];}
        $klassenoptionen .= "<option id=\"cms_stundenplanung_klasse_".$daten['id']."\" value=\"".$daten['id']."\">";
          $klassenoptionen .= $daten['stufe']." ".$daten['bezeichnung'];
        $klassenoptionen .= "</option>";
      }
      $anfrage->free();
    } else {$fehler = true;}
  }

  if (!$fehler) {
    include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/grundfunktionen.php');
    include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/tagladen.php');

    $code = "</div>";
    $code .= "<div class=\"cms_vollbild\" id=\"cms_stundenplanfenster\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<h1>Vertretungsplanung</h1>";
    $code .= "<span id=\"cms_stundenplanfenster_oeffnen\" class=\"cms_iconbutton cms_button_vollbild_oeffnen\" onclick=\"cms_vollbild_oeffnen('cms_stundenplanfenster')\">Vollbild</span>";
    $code .= "<span id=\"cms_stundenplanfenster_schliessen\" class=\"cms_iconbutton cms_button_vollbild_schliessen\" onclick=\"cms_vollbild_schliessen('cms_stundenplanfenster')\" style=\"display: none;\">In der Seite</span>";
    $code .= "</div>";

    // Welche Tage sollen angezeigt werden
    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<h3>Stunden suchen</h3>";
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr>";
        $code .= "<th>Datum:</th>";
        $code .= "<td>".cms_datum_eingabe('cms_vertretungsplan_ausgang_tag', $atag, $amonat, $ajahr, 'cms_vertretungsplan_tag_laden()')."</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Suche nach:</th>";
        $code .= "<td><select id=\"cms_vertretungsplan_ausgang_suche\" onchange=\"cms_vertretungsplanung_suchauswahl()\">";
          $code .= "<option value=\"l\">Lehrkraft</option>";
          $code .= "<option value=\"r\">Raum</option>";
          $code .= "<option value=\"k\">Klasse</option>";
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr id=\"cms_vertretungsplan_ausgang_lehrer_F\" style=\"display: table-row;\">";
        $code .= "<th>Lehrkraft:</th>";
        $code .= "<td><select id=\"cms_vertretungsplan_ausgang_lehrer\" onchange=\"cms_vertretungsplan_tag_laden()\">";
          $code .= $lehreroptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr id=\"cms_vertretungsplan_ausgang_raum_F\" style=\"display: none;\">";
        $code .= "<th>Raum:</th>";
        $code .= "<td><select id=\"cms_vertretungsplan_ausgang_raum\" onchange=\"cms_vertretungsplan_tag_laden()\">";
          $code .= $raumoptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr id=\"cms_vertretungsplan_ausgang_klasse_F\" style=\"display: none;\">";
        $code .= "<th>Klasse:</th>";
        $code .= "<td><select id=\"cms_vertretungsplan_ausgang_klasse\" onchange=\"cms_vertretungsplan_tag_laden()\">";
          $code .= $klassenoptionen;
        $code .= "</select></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Ausplanen:</th>";
        $code .= "<td class=\"cms_notiz\">Mit Anträgen kommt auch diese Funktion in den Schulhof.</td>";
      $code .= "</tr>";
    $code .= "</table>";

    $code .= "<div id=\"cms_vertretungsplan_ausgang_stunde\">";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\" >";
    $code .= "<h3>Stunde nachher</h3>";
    $code .= "<p>";
      $code .= "<span class=\"cms_toggle_aktiv\" id=\"cms_vertretungsplan_entfall_knopf\" style=\"display: none;\" onclick=\"cms_vertretungsplan_stunde_entfall()\">Entfall</span> ";
      $code .= "<span class=\"cms_toggle\" id=\"cms_vertretungsplan_aenderung_knopf\" style=\"display: none;\" onclick=\"cms_vertretungsplan_stunde_aendern()\">Änderung</span> ";
      $code .= "<span class=\"cms_toggle\" id=\"cms_vertretungsplan_zusatzstunde_knopf\" onclick=\"cms_vertretungsplan_stunde_zusatzstunde()\">Zusatzstunde</span>";
      $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_ziel_entfall\" name=\"cms_vertretungsplan_ziel_entfall\" value=\"0\">";
      $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_ziel_aenderung\" name=\"cms_vertretungsplan_ziel_aenderung\" value=\"0\">";
      $code .= "<input type=\"hidden\" id=\"cms_vertretungsplan_ziel_zusatzstunde\" name=\"cms_vertretungsplan_ziel_zusatzstunde\" value=\"0\">";
    $code .= "</p>";
    $code .= "<table class=\"cms_formular\" id=\"cms_vertretungsplan_zieldetails\" style=\"display: none;\">";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_datum_F\">";
      $code .= "<th>Datum:</th>";
      $code .= "<td>".cms_datum_eingabe('cms_vertretungsplan_ziel_tag', $atag, $amonat, $ajahr, 'cms_vertretungsplan_zielschulstunden_laden();cms_vertretungsplan_verfuegbar_laden()')."</td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_zeit_F\">";
      $code .= "<th>Zeit:</th>";
      $code .= "<td><p>".cms_uhrzeit_eingabe('cms_vertretungsplan_ziel_beginn', $astunde, $aminute, 'cms_vertretungsplan_verfuegbar_laden();cms_vertretungsplan_stunde_raus();')." – ".cms_uhrzeit_eingabe('cms_vertretungsplan_ziel_ende', $astunde, $aminute+$CMS_STUNDENDAUER, 'cms_vertretungsplan_verfuegbar_laden();cms_vertretungsplan_stunde_raus();')."</p><p class=\"cms_notiz\" id=\"cms_vertretungsplan_ziel_zeit_meldung\"></p><input type=\"hidden\" name=\"cms_vertretungsplan_ziel_stunde\" id=\"cms_vertretungsplan_ziel_stunde\" value=\"-\"></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_stunden_F\">";
      $code .= "<th>Stunden:</th>";
      $code .= "<td id=\"cms_vertretungsplan_ziel_stunden_FI\"></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_lehrer_F\">";
      $code .= "<th>Lehrkraft:</th>";
      $code .= "<td><select id=\"cms_vertretungsplan_ziel_lehrer\" onchange=\"cms_vertretungsplan_zieltage_laden()\">";
        $code .= str_replace('id="cms_vertretungsplan_ausgang_lehrer_', 'id="cms_vertretungsplan_ziel_lehrer_', $lehreroptionen);
      $code .= "</select></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_raum_F\">";
      $code .= "<th>Raum:</th>";
      $code .= "<td><select id=\"cms_vertretungsplan_ziel_raum\" onchange=\"cms_vertretungsplan_zieltage_laden()\">";
        $code .= str_replace('id="cms_vertretungsplan_ausgang_raum_', 'id="cms_vertretungsplan_ziel_raum_', $raumoptionen);
      $code .= "</select></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_klasse_F\">";
      $code .= "<th>Klasse:</th>";
      $code .= "<td><select id=\"cms_vertretungsplan_ziel_klasse\" onchange=\"cms_vertretungsplan_kurse_laden()\">";
        $code .= str_replace('id="cms_vertretungsplan_ausgang_klasse_', 'id="cms_vertretungsplan_klasse_raum_', $klassenoptionen);
      $code .= "</select></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_kurs_F\">";
      $code .= "<th>Kurs:</th>";
      $code .= "<td id=\"cms_vertretungsplan_ziel_kurs_Fi\"></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_vtext_F\">";
      $code .= "<th>Vertretungstext:</th>";
      $code .= "<td><input type=\"text\" id=\"cms_vertretungsplan_ziel_vtext\" name=\"cms_vertretungsplan_ziel_vtext\"></td>";
    $code .= "</tr>";
    $code .= "<tr id=\"cms_vertretungsplan_ziel_aktion_F\">";
      $code .= "<th></th>";
      $code .= "<td><span class=\"cms_button_ja\" onclick=\"cms_vertretungsplan_vertretung_speichern()\">Speichern</span> <span class=\"cms_button_nein\" onclick=\"cms_vertretungsplan_felder_reset()\">Abbrechen</span></td>";
    $code .= "</tr>";
    $code .= "</table>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<div id=\"cms_vertretungsplan_verfuegbar\" style=\"display: none;\">";
      $code .= "<h3>Verfügbare Kollegen</h3>";
      $code .= "<p class=\"cms_notiz\">Keine</p>";
      $code .= "<h3>Verfügbare Räume</h3>";
      $code .= "<p class=\"cms_notiz\">Keine</p>";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_clear\"></div>";


    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<div id=\"cms_vertretungsplan_ausgang_tagesverlauf\">";
    if ($lehrer == '-') {$code .= cms_meldung('info', '<h4>Keine Lehrkraft</h4><p>Es wurden noch keine Lehrkraft angelegt.</p>');}
    else {
      $code .= cms_vertretungsplan_tagesverlauf_laden($dbs, $schuljahr, $lehrer, 'l', $atag, $amonat, $ajahr);
    }
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_6\">";
    $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<div id=\"cms_vertretungsplan_ziel_tagesverlauf\">";
      $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_clear\"></div>";


    $code .= "<div class=\"cms_spalte_2\">";
    $code .= "<div class=\"cms_spalte_i\">";
      // Alter Tag
    $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_2\">";
    $code .= "<div class=\"cms_spalte_i\">";
      // Neuer Tag
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_clear\"></div>";


    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<div id=\"cms_vertretungsplan_ausgang_tag_vtext\">";
    $code .= "<h3>Vertretungstexte</h3>";
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr>";
        $code .= "<th>Schüler:</th>";
        $code .= "<td><textarea name=\"cms_vertretungsplan_ausgang_text_schueler\" id=\"cms_vertretungsplan_ausgang_text_schueler\">$vtextschueler</textarea></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Lehrer:</th>";
        $code .= "<td><textarea name=\"cms_vertretungsplan_ausgang_text_lehrer\" id=\"cms_vertretungsplan_ausgang_text_lehrer\">$vtextlehrer</textarea></td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th></th>";
        $code .= "<td><span class=\"cms_button_ja\" onclick=\"cms_vertretungsplan_vertretungstext_speichern('ausgang');\">Speichern</span> <span class=\"cms_button_nein\" onclick=\"cms_vertretungsplan_vertretungstext_loeschen_vorebereiten('$vtextid');\">Löschen</span></td>";
      $code .= "</tr>";
    $code .= "</table>";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_3\">";
    $code .= "<div class=\"cms_spalte_i\">";
    $code .= "<div id=\"cms_vertretungsplan_ziel_tag_vtext\" style=\"display: none;\">";
    $code .= "</div>";
    $code .= "</div>";
    $code .= "</div>";

    $code .= "<div class=\"cms_clear\"></div>";



    $code .= "</div>";
    echo $code;
  }
  else if ($fehler) {
    echo "<h1>Vertretungsplanung</h1>";
    echo cms_meldung_fehler();
    echo "</div>";
  }
  else {
    echo "<h1>Vertretungsplanung</h1>";
    echo cms_meldung_bastler();
    echo "</div>";
  }
  cms_trennen($dbs);
}
else {
  echo "<h1>Vertretungsplanung</h1>";
  echo cms_meldung_berechtigung();
  echo "</div>";
}
?>


<div class="cms_clear"></div>
