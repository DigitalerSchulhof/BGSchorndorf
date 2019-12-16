<?php
function cms_personensuche_generieren($dbs, $id, $gruppe, $gruppenid, $art, $gewaehlt, $gewaehlt2 = "", $namek = "") {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
  $aktiv = 0;
  $namek = cms_textzudb($gruppe);
  $artg = cms_vornegross($art);
  $eltern = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Eltern'];
  $schueler = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Schüler'];
  $lehrer = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Lehrer'];
  $verwaltung = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Verwaltungsangestellte'];
  $extern = $CMS_EINSTELLUNGEN[$artg.' '.$gruppe.' Externe'];

  $code = "";

  $event = "cms_personensuche('$id', '$art', '$gruppe');";

  if ($art == "mitglieder") {
    $code .= "<h4>Mitglieder</h4>";
    $code .= "<table class=\"cms_formular\">";
    $code .= "<thead><tr><th rowspan=\"2\"></th><th rowspan=\"2\">Person</th><th colspan=\"4\">Dateien</th><th colspan=\"2\">Vorschlagen</th><th colspan=\"2\">Chat</th><th></th></tr>";
    $code .= "<tr><th><img src=\"res/icons/klein/upload.png\"><span class=\"cms_hinweis\">Dateien hochladen</span></th><th><img src=\"res/icons/klein/download.png\"><span class=\"cms_hinweis\">Dateien herunterladen</span></th><th><img src=\"res/icons/klein/loeschen.png\"><span class=\"cms_hinweis\">Dateien löschen</span></th><th><img src=\"res/icons/klein/umbenennen.png\"><span class=\"cms_hinweis\">Dateien umbenennen</span></th><th><img src=\"res/icons/klein/termine.png\"><span class=\"cms_hinweis\">Interne Termine vorschlagen</span></th><th><img src=\"res/icons/klein/blog.png\"><span class=\"cms_hinweis\">Interne Blogeinträge vorschlagen</span></th><th><img src=\"res/icons/klein/chat.png\"><span class=\"cms_hinweis\">Chatten</span></th><th><img src=\"res/icons/klein/loeschen.png\"><span class=\"cms_hinweis\">Chatnachrichten löschen</span></th><th><img src=\"res/icons/klein/stumm.png\"><span class=\"cms_hinweis\">Mitglieder stummschalten</span><span class=\"cms_hinweis\">Mitglieder stummschalten</span></th><th></th></tr></thead>";
    $code .= "<tbody id=\"$id"."_F\">";
    $vorsitzcode = "";
    $vorsitzarray = explode('|', substr($gewaehlt2,1));
    if (strlen($gewaehlt) > 0) {
      $sqlwhere = "(".substr(str_replace('|', ',', $gewaehlt),1).")";
      if (cms_check_idliste($sqlwhere)) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x JOIN $namek"."mitglieder ON x.id = $namek"."mitglieder.person WHERE gruppe = ? ORDER BY nachname ASC, vorname ASC");
        $sql->bind_param("i", $gruppenid);
        if ($sql->execute()) {
          $sql->bind_result($pid, $part, $pvor, $pnach, $ptit, $pgruppe, $pperson, $pdateiupload, $pdateidownload, $pdateiloeschen, $pdateiumbenennen, $ptermine, $pblogeintraege, $pchatten, $pnachrichtloeschen, $pnutzerstumm, $pchatbannb, $pchatbannv);
          while ($sql->fetch()) {
            $code .= "<tr id=\"$id"."_personensuche_mitglieder_$pid\">";
            if ($part == 'l') {$icon = 'lehrer.png';}
            else if ($part == 'e') {$icon = 'elter.png';}
            else if ($part == 'v') {$icon = 'verwaltung.png';}
            else if ($part == 'x') {$icon = 'extern.png';}
            else {$icon = 'schueler.png';}
            $pname = cms_generiere_anzeigename($pvor, $pnach, $ptit);
            $code .= "<td><span class=\"cms_tabellenicon\"><img src=\"res/icons/klein/$icon\"></span></td>";
            $code .= "<td>".$pname."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_upload_'.$pid, $pdateiupload)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_download_'.$pid, $pdateidownload)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_loeschen_'.$pid,  $pdateiloeschen)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_umbenennen_'.$pid,  $pdateiumbenennen)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_termine_'.$pid,  $ptermine)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_blogeintraege_'.$pid,  $pblogeintraege)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_chatten_'.$pid,  $pchatten)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_chat_loeschen_'.$pid,  $pnachrichtloeschen)."</td>";
            $code .= "<td>".cms_schieber_generieren($id.'_personensuche_mitglieder_chat_bannen_'.$pid,  $pnutzerstumm)."</td>";
            $code .= "<td><span class=\"cms_button_nein\" onclick=\"cms_personensuche_entfernen_mitglieder('$id', '$pid', '".$gruppe."')\"><span class=\"cms_hinweis\">Person entfernen</span>–</span></td>";
            $code .= "</tr>";
            $vorsitzwert = 0;
            if (in_array($pid, $vorsitzarray)) {$vorsitzwert = 1;}
            $vorsitzcode .= cms_togglebutton_generieren($id."_personensuche_vorsitz_".$pid, $pname, $vorsitzwert, "cms_personensuche_vorsitz_aktualisieren('$id')")." ";
          }
        }
        $sql->close();
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
      if (cms_check_idliste($sqlwhere)) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x JOIN $namek"."aufsicht ON x.id = $namek"."aufsicht.person WHERE gruppe = $gruppenid ORDER BY nachname ASC, vorname ASC");
        if ($sql->execute()) {
          $sql->bind_result($aid, $aart, $avor, $anach, $atit, $agruppe, $aperson);
          while ($sql->fetch()) {
            $code .= cms_togglebutton_generieren($id."_personensuche_aufsicht_".$aid, cms_generiere_anzeigename($avor, $anach, $atit), 1, "cms_personensuche_entfernen_aufsicht('$id', '$aid', '$gruppe')")." ";
          }
        }
        $sql->close();
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
      if (cms_check_idliste($sqlwhere) && (cms_check_idliste($sqlpool))) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere AND id IN $sqlpool) AS x ORDER BY nachname ASC, vorname ASC");
        if ($sql->execute()) {
          $sql->bind_result($pid, $part, $pvor, $pnach, $ptit);
          while ($sql->fetch()) {
            $anzeige = "<img src=\"\">";
            if ($part == 'l') {$icon = 'lehrer'; $hinweis = 'Lehrer';}
            else if ($part == 's') {$icon = 'schueler'; $hinweis = 'Schüler';}
            else if ($part == 'e') {$icon = 'eltern'; $hinweis = 'Eltern';}
            else if ($part == 'v') {$icon = 'verwaltung'; $hinweis = 'Verwaltungsangestellte';}
            else if ($part == 'x') {$icon = 'extern'; $hinweis = 'Externe';}
            else {$icon = ''; $hinweis = '';}
            $anzeige = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$hinweis</span><img src=\"res/icons/klein/$icon.png\"></span>";
            $anzeige .= cms_generiere_anzeigename($pvor, $pnach, $ptit);
            $code .= cms_togglebutton_generieren($id."_personensuche_mail_$pid", $anzeige, 1, "cms_personensuche_entfernen_mail('$id', '$pid')")." ";
          }
        }
        $sql->close();
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


function cms_personensuche_personhinzu_generieren($dbs, $id, $erlaubt, $gewaehlt) {
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
      $gewaehlt = "";
      if (cms_check_idliste($sqlwhere)) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE id IN $sqlwhere) AS x WHERE art IN $sqlart ORDER BY nachname ASC, vorname ASC");
        if ($sql->execute()) {
          $sql->bind_result($pid, $part, $pvor, $pnach, $ptit);
          while ($sql->fetch()) {
            $anzeige = "<img src=\"\">";
            $gewaehlt .= '|'.$pid;
            if ($part == 'l') {$icon = 'lehrer'; $hinweis = 'Lehrer';}
            else if ($part == 's') {$icon = 'schueler'; $hinweis = 'Schüler';}
            else if ($part == 'e') {$icon = 'eltern'; $hinweis = 'Eltern';}
            else if ($part == 'v') {$icon = 'verwaltung'; $hinweis = 'Verwaltungsangestellte';}
            else if ($part == 'x') {$icon = 'extern'; $hinweis = 'Externe';}
            else {$icon = ''; $hinweis = '';}
            $anzeige = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$hinweis</span><img src=\"res/icons/klein/$icon.png\"></span>";
            $anzeige .= cms_generiere_anzeigename($pvor, $pnach, $ptit);
            $code .= cms_togglebutton_generieren($id."_personensuche_schuljahr_$pid", $anzeige, 1, "cms_personensuche_personhinzu_entfernen('$id', '$pid')")." ";
          }
        }
        $sql->close();
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
