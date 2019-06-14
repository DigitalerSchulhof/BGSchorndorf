<?php
function cms_buchungen_ausgeben($buchungsart, $buchungsstandort, $tag, $monat, $jahr, $anonymisiert = false) {
  global $CMS_URLGANZ, $CMS_RECHTE;
  if ($buchungsart == 'l') {
    $blocktabelle = 'leihenblockieren';
    $buchtabelle = 'leihenbuchen';
  }
  else if ($buchungsart == 'r') {
    $blocktabelle = 'raeumeblockieren';
    $buchtabelle = 'raeumebuchen';
  }
  else {return "";}

  $code = "";
  $code .= "<h3>Buchung</h3>";

  // BUCHUNGSMASKE
  if ($CMS_RECHTE['Planung']['Buchungen vornehmen']) {
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_einblenden('cms_neue_buchung')\">+ Buchung hinzufügen</span></p>";

    $code .= "<div id=\"cms_neue_buchung\" style=\"display: none;\">";
      $code .= "<table class=\"cms_formular\">";
        $code .= "<tr><th>Grund:</th><td><input name=\"cms_buchung_grund\" id=\"cms_buchung_grund\"></td></tr>";
        $code .= "<tr><th>Datum:</th><td>".cms_datum_eingabe('cms_buchung_datum')."</td></tr>";
        $code .= "<tr><th>Beginn:</th><td>".cms_uhrzeit_eingabe('cms_buchung_beginn')."</td></tr>";
        $code .= "<tr><th>Ende:</th><td>".cms_uhrzeit_eingabe('cms_buchung_ende')."</td></tr>";
      $code .= "</table>";

      $code .= "<p><span class=\"cms_button\" onclick=\"cms_buchung_neu_speichern('$buchungsart', '$buchungsstandort', '$CMS_URLGANZ')\">Buchung speichern</span> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('cms_neue_buchung')\">Abbrechen</span></p>";
    $code .= "</div>";
  }

  $code .= "<div class=\"cms_termine_jahrueberischt_knoepfe\"><span class=\"cms_termine_jahrueberischt_knoepfe_vorher\"><span class=\"cms_button\" onclick=\"cms_buchunganzeigen('$buchungsart', '$buchungsstandort', '-', '$CMS_URLGANZ')\">«</span></span><span class=\"cms_termine_jahrueberischt_knoepfe_jahr\">".cms_datum_eingabe('cms_buchungsplan_datum', $tag, $monat, $jahr, 'cms_buchunganzeigen(\''.$buchungsart.'\', '.$buchungsstandort.', 0, \''.$CMS_URLGANZ.'\')')."</span><span class=\"cms_termine_jahrueberischt_knoepfe_nachher\"><span class=\"cms_button\" onclick=\"cms_buchunganzeigen('$buchungsart', '$buchungsstandort', '+', '$CMS_URLGANZ')\">»</span></span></div>";

  $code .= cms_buchungsplan_laden($buchungsart, $buchungsstandort, $tag, $monat, $jahr, $CMS_URLGANZ, $anonymisiert);

  if ($CMS_RECHTE['Organisation']['Buchungen löschen']) {
    $code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_buchung_alleloeschen_vorbereiten('$CMS_URLGANZ')\">Alle vergangenen Buchungen aller Räume und Leihgeräte löschen</span></p>";
  }

  return $code;
}


function cms_buchungsplan_laden($buchungsart, $buchungsstandort, $tag, $monat, $jahr, $url, $anonymisiert = false;) {
  global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN, $CMS_BENUTZERID, $CMS_RECHTE;
  $jetzt = mktime(0,0,0,$monat, $tag, $jahr);
  if ($buchungsart == 'l') {
    $blocktabelle = 'leihenblockieren';
    $buchtabelle = 'leihenbuchen';
  }
  else if ($buchungsart == 'r') {
    $blocktabelle = 'raeumeblockieren';
    $buchtabelle = 'raeumebuchen';
  }
  else {return "";}

  $code = "";
  $dbs = cms_verbinden('s');
  // BUCHUNGSPLAN
  $buchungszeitraumb = mktime(0,0,0, $monat, $tag, $jahr);
  $buchungszeitraume = $buchungszeitraumb + 7*60*60*24 - 1;

  // FERIEN laden
  $ferien = array(0 => false, 1 => false, 2 => false, 3 => false, 4 => false, 5 => false, 6 => false);
  $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM ferien WHERE ? BETWEEN beginn AND ende ORDER BY bezeichnung ASC");
  for ($i=0; $i<7; $i++) {
    $buchungstag = $buchungszeitraumb + $i*60*60*24;
    $sql->bind_param("i", intval($buchungstag));
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl > 0) {$ferien[$i] = true;}}
    }
  }
  $sql->close();

  // Blockierungen laden
  for ($i = 1; $i <= 7; $i++) {$blockierungen[$i] = array();}
  $sql = $dbs->prepare("SELECT * FROM (SELECT wochentag, AES_DECRYPT(grund, '$CMS_SCHLUESSEL') AS grund, AES_DECRYPT(beginns, '$CMS_SCHLUESSEL') AS beginns, AES_DECRYPT(beginnm, '$CMS_SCHLUESSEL') AS beginnm, AES_DECRYPT(endes, '$CMS_SCHLUESSEL') AS endes, AES_DECRYPT(endem, '$CMS_SCHLUESSEL') AS endem, ferien FROM $blocktabelle WHERE standort = ?) AS x ORDER BY wochentag ASC, beginns ASC");
  $sql->bind_param("i", $buchungsstandort);
  if ($sql->execute()) {
    $sql->bind_result($wochentag, $grund, $beginns, $beginnm, $endes, $endem, $bferien);
    while($sql->fetch()) {
      $block['grund'] = $grund;
      $block['beginn'] = $beginns*60+$beginnm;
      $block['ende'] = $endes*60+$endem;
      $block['beginns'] = $beginns;
      $block['endes'] = $endes;
      $block['beginnm'] = $beginnm;
      $block['endem'] = $endem;
      $block['ferien'] = $bferien;
      array_push($blockierungen[$wochentag], $block);
    }
  }
  $sql->close();

  // Buchungen laden
  for ($i = 1; $i <= 7; $i++) {$buchungen[$i] = array();}
  $sql = $dbs->prepare("SELECT $buchtabelle.id AS id, person, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(grund, '$CMS_SCHLUESSEL') AS grund, beginn, ende FROM $buchtabelle JOIN personen ON $buchtabelle.person = personen.id WHERE standort = ? AND beginn >= ? AND ende <= ?");
  $sql->bind_param("iii", $buchungsstandort, $buchungszeitraumb, $buchungszeitraume);
  if ($sql->execute()) {
    $sql->bind_result($bid, $bperson, $bvorname, $bnachname, $btitel, $bgrund, $bbeginn, $bende);
    while($sql->fetch()) {
      $buch['id'] = $bid;
      $buch['person'] = $bperson;
      $buch['vorname'] = $bvorname;
      $buch['nachname'] = $bnachname;
      $buch['titel'] = $btitel;
      $buch['grund'] = $bgrund;
      $buch['beginns'] = date('H', $bbeginn);
      $buch['endes'] = date('H', $bende);
      $buch['beginnm'] = date('i', $bbeginn);
      $buch['endem'] = date('i', $bende)+1;
      $buch['beginn'] = $buch['beginns']*60+$buch['beginnm'];
      $buch['ende'] = $buch['endes']*60+$buch['endem']+1;
      if ($buch['endem'] == 60) {$buch['endem'] = '00'; $buch['endes'] = $buch['endes']+1;}
      $wochentag = date('N', $bbeginn);
      array_push($buchungen[$wochentag], $buch);
    }
  }
  $sql->close();

  $beginnS = $CMS_EINSTELLUNGEN['Stundenplan Buchungsbeginn Stunde'];
  $beginnM = $CMS_EINSTELLUNGEN['Stundenplan Buchungsbeginn Minute'];
  $endeS = $CMS_EINSTELLUNGEN['Stundenplan Buchungsende Stunde'];
  $endeM = $CMS_EINSTELLUNGEN['Stundenplan Buchungsende Minute'];
  $beginnabschnitt = $beginnS*60+$beginnM;
  $minuten = ($endeS-$beginnS)*60;
  if ($minuten > 0) {$minuten = $minuten - 60;}
  $minuten = $minuten + (60-$beginnM) + $endeM;
  $linien = "";

  $code .= "<div id=\"cms_buchungsplan\">";
    $code .= "<div class=\"cms_buchungsspalte_uhrzeiten\" style=\"height: $minuten"."px\">";
      $code .= "<span class=\"cms_buchungsuhrzeit\" style=\"top: 0px\">$beginnS:$beginnM</span>";
      $linien .= "<span class=\"cms_buchungsuhrzeitlinien\" style=\"top: 0px\"></span>";
      $position = 60-$beginnM;
      $stunde = $beginnS;
      while ($position < $minuten) {
        $stunde ++;
        $code .= "<span class=\"cms_buchungsuhrzeit\" style=\"top: $position"."px\">".cms_fuehrendenull($stunde).":00</span>";
        $linien .= "<span class=\"cms_buchungsuhrzeitlinien\" style=\"top: $position"."px\"></span>";
        $position += 60;
      }
      $code .= "<span class=\"cms_buchungsuhrzeit\" style=\"top: ".$minuten."px\">$endeS:$endeM</span>";
      $linien .= "<span class=\"cms_buchungsuhrzeitlinien\" style=\"top: $minuten"."px\"></span>";


      $code .= "";
    $code .= "</div>";
    for ($i=0; $i<7; $i++) {
      $tagzeit = $jetzt + $i*(60*60*24);
      $wochentag = date('N', $tagzeit);
      if ($ferien[$i]) {$spaltenklasse = " cms_buchungsspalte_ferien";} else {$spaltenklasse = "";}
      $code .= "<div class=\"cms_buchungsspalte$spaltenklasse\" style=\"height: $minuten"."px\">";
      $code .= "<span class=\"cms_buchungsspaltetitel\">".cms_tagnamekomplett($wochentag)."<br>".date('d.m.Y', $tagzeit)."</span>";

      foreach ($blockierungen[$wochentag] as $b) {
        if (!$ferien[$i] || ($b['ferien'] == '1')) {
          $hoehe = ($b['ende'] - $b['beginn']);
          $ypos = $b['beginn'] - $beginnabschnitt;
          $code .= "<div class=\"cms_buchung_blockierung\" style=\"top: $ypos"."px;height: $hoehe"."px;\">";
            $code .= "<span class=\"cms_buchung_zeit\">".$b['beginns'].":".$b['beginnm']." - ".$b['endes'].":".$b['endem']."</span>";
            if (!$anonymisiert) {
              $code .= "<span class=\"cms_buchung_grund\">".$b['grund']."</span>";
            }
          $code .= "</div>";
        }
      }

      foreach ($buchungen[$wochentag] as $b) {
        $hoehe = ($b['ende'] - $b['beginn']);
        $ypos = $b['beginn'] - $beginnabschnitt;
        if ($b['person'] == $CMS_BENUTZERID) {$klasse = "selbst";} else {$klasse = "fremd";}
        $code .= "<div class=\"cms_buchung_$klasse\" style=\"top: $ypos"."px;height: $hoehe"."px;\">";
          $code .= "<span class=\"cms_buchung_zeit\">".$b['beginns'].":".$b['beginnm']." - ".$b['endes'].":".$b['endem']."</span>";
          if (!$anonymisiert) {
            $code .= "<span class=\"cms_buchung_grund\">".$b['grund']."</span>";
            $code .= "<span class=\"cms_buchung_von\">".cms_generiere_anzeigename($b['vorname'], $b['nachname'], $b['titel'])."</span>";
          }
          if (($b['person'] == $CMS_BENUTZERID) || ($CMS_RECHTE['Organisation']['Buchungen löschen'])) {
            $code .= "<span class=\"cms_buchung_aktion\"><span class=\"cms_button_nein\" onclick=\"cms_buchung_loeschen_vorbereiten(".$b['id'].", '$buchungsart', $buchungsstandort, '$url')\">Löschen</span></span>";
          }
        $code .= "</div>";
      }
      $code .= "</div>";
    }
    $code .= $linien;
    $code .= "<div class=\"cms_clear\"></div>";
  $code .= "</div>";
  cms_trennen($dbs);

  return $code;
}
?>
