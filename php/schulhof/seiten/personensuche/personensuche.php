<?php
function cms_personensuche_generieren($dbs, $id, $gruppe, $gruppenid, $art, $gewaehlt, $gewaehlt2 = "", $namek = "") {
  global $CMS_GRUPPENMITGLIEDER, $CMS_SCHLUESSEL;
  $aktiv = 0;
  $namek = cms_textzudb($gruppe);
  $artg = cms_vornegross($art);
  $eltern = $CMS_GRUPPENMITGLIEDER[$gruppe][$artg]['Eltern'];
  $schueler = $CMS_GRUPPENMITGLIEDER[$gruppe][$artg]['Schüler'];
  $lehrer = $CMS_GRUPPENMITGLIEDER[$gruppe][$artg]['Lehrer'];
  $verwaltung = $CMS_GRUPPENMITGLIEDER[$gruppe][$artg]['Verwaltung'];
  $extern = $CMS_GRUPPENMITGLIEDER[$gruppe][$artg]['Extern'];

  $code = "";

  $event = "cms_personensuche('$id', '$art', '$gruppe');";

  if ($art == "mitglieder") {
    $code .= "<h4>Mitglieder</h4>";
    $code .= "<table class=\"cms_formular\">";
    $code .= "<thead><tr><th rowspan=\"2\"></th><th rowspan=\"2\">Person</th><th colspan=\"4\">Dateien</th><th colspan=\"2\">Vorschlagen</th><th colspan=\"2\">Chat</th><th></th></tr>";
    $code .= "<tr><th><img src=\"res/icons/klein/upload.png\"></th><th><img src=\"res/icons/klein/download.png\"></th><th><img src=\"res/icons/klein/loeschen.png\"></th><th><img src=\"res/icons/klein/umbenennen.png\"></th><th><img src=\"res/icons/klein/termine.png\"></th><th><img src=\"res/icons/klein/blog.png\"></th><th><img src=\"res/icons/klein/chat.png\"></th><th>schreiben erlaubt ab</th><th></th></tr></thead>";
    $code .= "<tbody id=\"$id"."_F\">";
    $vorsitzcode = "";
    $vorsitzarray = explode('|', substr($gewaehlt2,1));
    if (strlen($gewaehlt) > 0) {
      $sqlwhere = "(".substr(str_replace('|', ',', $gewaehlt),1).")";
      $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x JOIN $namek"."mitglieder ON x.id = $namek"."mitglieder.person WHERE gruppe = $gruppenid ORDER BY nachname ASC, vorname ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $code .= "<tr id=\"$id"."_personensuche_mitglieder_".$daten['id']."\">";
          if ($daten['art'] == 'l') {$icon = 'lehrer.png';}
          else if ($daten['art'] == 'e') {$icon = 'elter.png';}
          else if ($daten['art'] == 'v') {$icon = 'verwaltung.png';}
          else if ($daten['art'] == 'x') {$icon = 'extern.png';}
          else {$icon = 'schueler.png';}
          $chattenabT = date('d', $daten['chattenab']);
          $chattenabM = date('m', $daten['chattenab']);
          $chattenabJ = date('Y', $daten['chattenab']);
          $chattenabs = date('H', $daten['chattenab']);
          $chattenabm = date('i', $daten['chattenab']);
          $pname = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
          $code .= "<td><span class=\"cms_tabellenicon\"><img src=\"res/icons/klein/$icon\"></span></td>";
          $code .= "<td>".$pname."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_upload_'.$daten['id'], $daten['dateiupload'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_download_'.$daten['id'], $daten['dateidownload'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_loeschen_'.$daten['id'],  $daten['dateiloeschen'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_umbenennen_'.$daten['id'],  $daten['dateiumbenennen'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_termine_'.$daten['id'],  $daten['termine'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_blogeintraege_'.$daten['id'],  $daten['blogeintraege'])."</td>";
          $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_chatten_'.$daten['id'],  $daten['chatten'])."</td>";
          $code .= "<td>".cms_datum_eingabe($id.'_personensuche_mitglieder_chattenab_'.$daten['id'], $chattenabT, $chattenabM, $chattenabJ)." – ";
          $code .= cms_uhrzeit_eingabe($id.'_personensuche_mitglieder_chattenab_'.$daten['id'],$chattenabs, $chattenabm)."</td>";
          $code .= "<td><span class=\"cms_button_nein\" onclick=\"cms_personensuche_entfernen_mitglieder('$id', '".$daten['id']."', '".$gruppe."')\"><span class=\"cms_hinweis\">Person entfernen</span>–</span></td>";
          $code .= "</tr>";
          $vorsitzwert = 0;
          if (in_array($daten['id'], $vorsitzarray)) {$vorsitzwert = 1;}
          $vorsitzcode .= cms_togglebutton_generieren($id."_personensuche_vorsitz_".$daten['id'], $pname, $vorsitzwert, "cms_personensuche_vorsitz_aktualisieren('$id')")." ";
        }
        $anfrage->free();
      }
    }
    $code .= "</tbody>";
    $code .= "</table>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_einblenden('$id"."_suchfeld', 'table');$event\">+ Person hinzufügen</span>";
    if ($namek == 'kurse') {
      $code .= " <span class=\"cms_button\" onclick=\"cms_gruppe_personenausklassen()\">Personen aus Klassen übernehmen</span>";
    }
    $code .= "</p>";
  }
  else if ($art == "aufsicht") {
    $code .= "<h4>Aufsicht</h4>";
    $code .= "<p id=\"$id"."_F\">";
    if (strlen($gewaehlt) > 0) {
      $sqlwhere = "(".substr(str_replace('|', ',', $gewaehlt),1).")";
      $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x JOIN $namek"."aufsicht ON x.id = $namek"."aufsicht.person WHERE gruppe = $gruppenid ORDER BY nachname ASC, vorname ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $code .= cms_togglebutton_generieren($id."_personensuche_aufsicht_".$daten['id'], cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']), 1, "cms_personensuche_entfernen_aufsicht('$id', '".$daten['id']."', '$gruppe')")." ";
        }
        $anfrage->free();
      }
    }
    $code .= "</p>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_einblenden('$id"."_suchfeld', 'table');$event\">+ Aufsicht hinzufügen</span></p>";
  }
  $code .= "<p><input type=\"hidden\" value=\"$gewaehlt\" name=\"$id"."_personensuche_gewaehlt\" id=\"$id"."_personensuche_gewaehlt\"></p>";

  $code .= "<table class=\"cms_formular\" id=\"$id"."_suchfeld\" style=\"display: none;\">";
    $code .= "<tr>";
      $code .= "<th>Nachname</th><th colspan=\"2\">Vorname <span class=\"cms_button_nein cms_button_schliessen\" onclick=\"cms_ausblenden('$id"."_suchfeld')\">&times;</span></th>";
    $code .= "</tr><tr>";
      $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_nachname\" name=\"$id"."_personensuche_nachname\" onkeyup=\"$event\"></td>";
      $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_vorname\" name=\"$id"."_personensuche_vorname\" onkeyup=\"$event\"></td>";
    $code .= "</tr>";
      $code .= "<tr><td colspan=\"2\">";
        if ($schueler) {$code .= cms_togglebutton_generieren($id."_personensuche_s", "Schüler", 0, $event);}
        if ($lehrer) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_l", "Lehrer", 0, $event);}
        if ($verwaltung) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_v", "Verwaltungsangestellte", 0, $event);}
        if ($eltern) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_e", "Eltern", 0, $event);}
        if ($extern) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_x", "Externe", 0, $event);}
      $code .= "</td></tr>";
      $code .= "<tr><td colspan=\"4\" id=\"$id"."_suchergebnis\" style=\"text-align: center;\">-- keine Personen gefunden --</td></tr>";
  $code .= "</table>";


  if ($art == "mitglieder") {
    $code .= "<h4>Vorsitz</h4>";
    $code .= "<p id=\"$id"."_vorsitz_F\">$vorsitzcode</p>";
    $code .= "<p><input type=\"hidden\" value=\"$gewaehlt2\" name=\"$id"."_personensuche_gewaehlt2\" id=\"$id"."_personensuche_gewaehlt2\"></p>";
  }

  return $code;
}


function cms_personensuche_mail_generieren($dbs, $id, $pool, $gewaehlt) {
  global $CMS_SCHLUESSEL;

  $code = "";

  if (count($pool) > 0) {
    $event = "cms_personensuche_mail('$id');";
    $code .= "<p id=\"$id"."_F\">";
    if (strlen($gewaehlt) > 0) {
      $sqlwhere = "(".substr(str_replace('|', ',', $gewaehlt),1).")";
      $sqlpool = "(".implode(',', $pool).")";
      $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere AND id IN $sqlpool) AS x ORDER BY nachname ASC, vorname ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $anzeige = "<img src=\"\">";
          if ($daten['art'] == 'l') {$icon = 'lehrer'; $hinweis = 'Lehrer';}
          else if ($daten['art'] == 's') {$icon = 'schueler'; $hinweis = 'Schüler';}
          else if ($daten['art'] == 'e') {$icon = 'eltern'; $hinweis = 'Eltern';}
          else if ($daten['art'] == 'v') {$icon = 'verwaltung'; $hinweis = 'Verwaltungsangestellte';}
          else if ($daten['art'] == 'x') {$icon = 'extern'; $hinweis = 'Externe';}
          else {$icon = ''; $hinweis = '';}
          $anzeige = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$hinweis</span><img src=\"res/icons/klein/$icon.png\"></span>";
          $anzeige .= cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
          $code .= cms_togglebutton_generieren($id."_personensuche_mail_".$daten['id'], $anzeige, 1, "cms_personensuche_entfernen_mail('$id', '".$daten['id']."')")." ";
        }
        $anfrage->free();
      }
    }
    $code .= "</p>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_einblenden('$id"."_suchfeld', 'table');\">+</span></p>";


    $code .= "<p><input type=\"hidden\" value=\"$gewaehlt\" name=\"$id"."_personensuche_gewaehlt\" id=\"$id"."_personensuche_gewaehlt\"></p>";

    $code .= "<table class=\"cms_formular\" id=\"$id"."_suchfeld\" style=\"display: none;\">";
      $code .= "<tr>";
        $code .= "<th>Nachname</th><th colspan=\"2\">Vorname <span class=\"cms_button_nein cms_button_schliessen\" onclick=\"cms_ausblenden('$id"."_suchfeld')\">&times;</span></th>";
      $code .= "</tr><tr>";
        $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_nachname\" name=\"$id"."_personensuche_nachname\" onkeyup=\"$event\"></td>";
        $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_vorname\" name=\"$id"."_personensuche_vorname\" onkeyup=\"$event\"></td>";
      $code .= "</tr>";
        $code .= "<tr><td colspan=\"2\">";
          $code .= cms_togglebutton_generieren($id."_personensuche_s", "Schüler", 0, $event);
          $code .= " ".cms_togglebutton_generieren($id."_personensuche_l", "Lehrer", 0, $event);
          $code .= " ".cms_togglebutton_generieren($id."_personensuche_v", "Verwaltungsangestellte", 0, $event);
          $code .= " ".cms_togglebutton_generieren($id."_personensuche_e", "Eltern", 0, $event);
          $code .= " ".cms_togglebutton_generieren($id."_personensuche_x", "Externe", 0, $event);
        $code .= "</td></tr>";
        $code .= "<tr><td colspan=\"4\" id=\"$id"."_suchergebnis\" style=\"text-align: center;\">-- keine Personen gefunden --</td></tr>";
    $code .= "</table>";
  }
  else {
    $code .= cms_meldung('info', '<h4>Keine Empfänger wählbar</h4><p>Es gibt niemandem, dem geschrieben werden kann.</p>');
  }
  return $code;
}


function cms_personensuche_schuljahr_generieren($dbs, $id, $erlaubt, $gewaehlt) {
  global $CMS_SCHLUESSEL;

  $code = "";

  if (strlen($erlaubt) > 0) {
    $sqlart = "";
    if (preg_match('/e/', $erlaubt)) {$sqlart .= "'e',";}
    if (preg_match('/s/', $erlaubt)) {$sqlart .= "'s',";}
    if (preg_match('/l/', $erlaubt)) {$sqlart .= "'l',";}
    if (preg_match('/v/', $erlaubt)) {$sqlart .= "'v',";}
    if (preg_match('/x/', $erlaubt)) {$sqlart .= "'x',";}
    $sqlart = "(".substr($sqlart, 0, -1).")";

    $event = "cms_personensuche_schuljahr('$id');";
    $code .= "<p id=\"$id"."_F\">";
    if (strlen($gewaehlt) > 0) {
      $sqlwhere = "(".substr(str_replace('|', ',', $gewaehlt),1).")";
      $sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x WHERE art IN $sqlart ORDER BY nachname ASC, vorname ASC";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $anzeige = "<img src=\"\">";
          if ($daten['art'] == 'l') {$icon = 'lehrer'; $hinweis = 'Lehrer';}
          else if ($daten['art'] == 's') {$icon = 'schueler'; $hinweis = 'Schüler';}
          else if ($daten['art'] == 'e') {$icon = 'eltern'; $hinweis = 'Eltern';}
          else if ($daten['art'] == 'v') {$icon = 'verwaltung'; $hinweis = 'Verwaltungsangestellte';}
          else if ($daten['art'] == 'x') {$icon = 'extern'; $hinweis = 'Externe';}
          else {$icon = ''; $hinweis = '';}
          $anzeige = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$hinweis</span><img src=\"res/icons/klein/$icon.png\"></span>";
          $anzeige .= cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
          $code .= cms_togglebutton_generieren($id."_personensuche_schuljahr_".$daten['id'], $anzeige, 1, "cms_personensuche_entfernen_schuljahr('$id', '".$daten['id']."')")." ";
        }
        $anfrage->free();
      }
    }
    $code .= "</p>";
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_einblenden('$id"."_suchfeld', 'table');\">+</span></p>";


    $code .= "<p><input type=\"hidden\" value=\"$gewaehlt\" name=\"$id"."_personensuche_gewaehlt\" id=\"$id"."_personensuche_gewaehlt\"></p>";

    $code .= "<table class=\"cms_formular\" id=\"$id"."_suchfeld\" style=\"display: none;\">";
      $code .= "<tr>";
        $code .= "<th>Nachname</th><th colspan=\"2\">Vorname <span class=\"cms_button_nein cms_button_schliessen\" onclick=\"cms_ausblenden('$id"."_suchfeld')\">&times;</span></th>";
      $code .= "</tr><tr>";
        $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_nachname\" name=\"$id"."_personensuche_nachname\" onkeyup=\"$event\"></td>";
        $code .= "<td><input type=\"text\" id=\"$id"."_personensuche_vorname\" name=\"$id"."_personensuche_vorname\" onkeyup=\"$event\"></td>";
      $code .= "</tr>";
        $code .= "<tr><td colspan=\"2\">";
          if (preg_match('/s/', $erlaubt)) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_s", "Schüler", 0, $event);}
          if (preg_match('/l/', $erlaubt)) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_l", "Lehrer", 0, $event);}
          if (preg_match('/v/', $erlaubt)) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_v", "Verwaltungsangestellte", 0, $event);}
          if (preg_match('/e/', $erlaubt)) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_e", "Eltern", 0, $event);}
          if (preg_match('/x/', $erlaubt)) {$code .= " ".cms_togglebutton_generieren($id."_personensuche_x", "Externe", 0, $event);}
          $code .= "<input type=\"hidden\" id=\"$id"."_personensuche_erlaubt\" name=\"$id"."_personensuche_erlaubt\" value=\"$erlaubt\">";
        $code .= "</td></tr>";
        $code .= "<tr><td colspan=\"4\" id=\"$id"."_suchergebnis\" style=\"text-align: center;\">-- keine Personen gefunden --</td></tr>";
    $code .= "</table>";
  }
  else {
    $code .= cms_meldung('info', '<h4>Keine Empfänger wählbar</h4><p>Es gibt niemandem, dem geschrieben werden kann.</p>');
  }
  return $code;
}
?>
