<?php
function cms_vertretungsplan_tagesverlauf_laden($dbs, $schuljahr, $id, $art, $t, $m, $j, $aktionen = true) {
  $beginn = mktime(0,0,0,$m,$t,$j);
  $ende = mktime(23,59,59,$m,$t,$j);

  $code = "";
  $stunden = cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $id, $art, $beginn, $ende);
  $code .= cms_vertretungsplan_tagesverlauf_ausgeben($stunden, $aktionen);

  return $code;
}

function cms_vertretungsplan_stundenamtag_erzeugen($dbs, $schuljahr, $id, $art, $beginn, $ende) {
  global $CMS_SCHLUESSEL;
  $stunden = array();
  // Suche alle relevanten Stunden
  if ($art == 'l') {
    $sql = "SELECT id, tbeginn AS beginn, tende AS ende, tstunde AS stunde, kurs, tlehrkraft AS lehrer, traum AS raum, entfall FROM tagebuch_".$schuljahr." WHERE tlehrkraft = $id AND tbeginn BETWEEN $beginn AND $ende";
    $sql = "SELECT x.id AS id, beginn, ende, stunde, kurs, lehrer, raum, entfall, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM ($sql) AS x JOIN raeume ON x.raum = raeume.id JOIN kurse ON x.kurs = kurse.id JOIN lehrer ON x.lehrer = lehrer.id ORDER BY beginn ASC";
    if ($anfrage = $dbs->query($sql)) { // TODO: Eingaben der Funktion prüfen
      while ($daten = $anfrage->fetch_assoc()) {
        $daten['ende']--;
        $stunden = cms_vertretungsplan_stunde_einsortieren($stunden, $daten);
      }
      $anfrage->free();
    }
  }
  else if ($art == 'r') {
    $sql = "SELECT id, tbeginn AS beginn, tende AS ende, tstunde AS stunde, kurs, tlehrkraft AS lehrer, traum AS raum, entfall FROM tagebuch_".$schuljahr." WHERE traum = $id AND tbeginn BETWEEN $beginn AND $ende";
    $sql = "SELECT x.id AS id, beginn, ende, stunde, kurs, lehrer, raum, entfall, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM ($sql) AS x JOIN raeume ON x.raum = raeume.id JOIN kurse ON x.kurs = kurse.id JOIN lehrer ON x.lehrer = lehrer.id ORDER BY beginn ASC";
    if ($anfrage = $dbs->query($sql)) { // TODO: Eingaben der Funktion prüfen
      while ($daten = $anfrage->fetch_assoc()) {
        $daten['ende']--;
        $stunden = cms_vertretungsplan_stunde_einsortieren($stunden, $daten);
      }
      $anfrage->free();
    }
  }
  else if ($art == 'k') {
    $sql = "SELECT id, tbeginn AS beginn, tende AS ende, tstunde AS stunde, kurs, tlehrkraft AS lehrer, traum AS raum, entfall FROM tagebuch_".$schuljahr." WHERE kurs IN (SELECT kurs FROM kursklassen WHERE klasse = $id) AND tbeginn BETWEEN $beginn AND $ende";
    $sql = "SELECT x.id AS id, beginn, ende, stunde, kurs, lehrer, raum, entfall, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM ($sql) AS x JOIN raeume ON x.raum = raeume.id JOIN kurse ON x.kurs = kurse.id JOIN lehrer ON x.lehrer = lehrer.id ORDER BY beginn ASC";
    if ($anfrage = $dbs->query($sql)) { // TODO: Eingaben der Funktion prüfen
      while ($daten = $anfrage->fetch_assoc()) {
        $daten['ende']--;
        $stunden = cms_vertretungsplan_stunde_einsortieren($stunden, $daten);
      }
      $anfrage->free();
    }
  }
  return $stunden;
}

function cms_vertretungsplan_tagesverlauf_ausgeben($stunden, $titel = array(), $ueberlappend = false, $aktionen = true) {
  $code = "";
  // Stunden ausgeben
  if (count($stunden) == 0) {
    $code .= cms_meldung('info', '<h4>Kein Unterricht</h4><p>Für die Suchkriterien wurde kein Unterricht gefunden.</p>');
  }
  else {
    $minmax = cms_vertretungsplan_tag_minmax($stunden);
    $dauer = ($minmax['max']-$minmax['min'])/60;
    $hoehe = $dauer + 20;

    $code .= "<div class=\"cms_vertretungsplan_tagesansicht cms_vertretungsplanung_tagansicht\" style=\"height: ".$hoehe."px;\">";
      // Zeitlinien bestimmen
      $std = mktime(date('H', $minmax['min']), 0, 0, date('n', $minmax['min']), date('j', $minmax['min']), date('Y', $minmax['min']));
      // Falls generiertes Datum zu klein, addiere stunde 60 Sekunden * 60 Minuten
      if ($std < $minmax['min']) {$std += 60*60;}
      while ($std <= $minmax['max']) {
        $code .= "<div class=\"cms_kalender_zeitlinie\" style=\"top: ".(($std-$minmax['min'])/60)."px;\"><span class=\"cms_kalender_zeitlinie_beschriftung\">".date("H:i", $std)."</span></div>";
        $std += 60*60;
      }
      // Stunden eintragen
      $spalten = count($stunden);
      $spaltennr = 0;
      $spaltenbreite = 100/$spalten-1;
      $code .= "<div class=\"cms_terminbereich\">";
      foreach ($stunden AS $spalte) {
        $code .= "<div class=\"cms_terminspalte\" style=\"width: $spaltenbreite%; height: ".$hoehe."px\">";
        if (isset($titel[$spaltennr])) {$code .= "<span class=\"cms_vertretungsplan_spaltentitel\">".$titel[$spaltennr]."</span>";}
        $spaltennr++;
        foreach ($spalte AS $std) {
          if (!$aktionen) {
            $event = "";
            $ks = "cms_vertretungsplan_stunde";
          }
          else {
            $event = "cms_vertretungsplan_stunde_waehlen('".$std['id']."')";
            $ks = "cms_vertretungsplan_stunde cms_vertretungsplan_stunde_aktion";
          }
          if ($std['entfall'] == 1) {$ks .= " cms_vertretungsplan_stunde_entfall";}
          $style = "top: ".(($std['beginn']-$minmax['min'])/60)."px; height: ".max((($std['ende']-$std['beginn']-1)/60),5)."px; ".cms_stunde_farbe($std['kursbez'])."";
          $code .= "<div class=\"$ks\" style=\"$style\" onclick=\"$event\">";
            $code .= "<span class=\"cms_vertretungsplan_stunde_inhalt\"><span>".$std['kursbez']."</span><span>".$std['kuerzel']."</span><span>".$std['raumbez']."</span></span>";
          $code .= "</div>";
        }
        $code .= "</div>";
        $code .= "<div class=\"cms_terminspaltentrenner\" style=\"height: ".$hoehe."px;\"></div>";
      }
      $code .= "<div class=\"cms_clear\"></div>";
      if (is_array($ueberlappend)) {
        $code .= "<div class=\"cms_vertretungsplan_ueberlappend\" style=\"top: ".(($ueberlappend['beginn']-$minmax['min'])/60)."px; height: ".max((($ueberlappend['ende']-$ueberlappend['beginn']-1)/60),5)."px;\">";
        $code .= "</div>";
      }
      $code .= "</div>";
    $code .= "</div>";
  }

  return $code;
}
?>
