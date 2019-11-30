<?php
function cms_vertretungsplan_heute() {
  global $CMS_BENUTZERART;
  if (($CMS_BENUTZERART != 'l') && ($CMS_BENUTZERART != 's')) {return "";}

  $jetzt = time();
  $beginn = mktime(0,0,0, date('n', $jetzt), date('j', $jetzt), date('Y', $jetzt));
  $ende = mktime(23,59,59, date('n', $jetzt), date('j', $jetzt), date('Y', $jetzt));

  $dbs = cms_verbinden('s');
  $code = cms_vertretungsplan_tag($dbs, $beginn, $ende);
  cms_trennen($dbs);

  if (strlen($code) > 0) {$code = "<h3>Heute</h3>".$code;}
  return $code;
}

function cms_vertretungsplan_naechsterschultag() {
  global $CMS_BENUTZERART;
  if (($CMS_BENUTZERART != 'l') && ($CMS_BENUTZERART != 's')) {return "";}

  // Suche den nächsten Schultag
  $jetzt = time();
  $beginn = mktime(0,0,0, date('n', $jetzt), date('j', $jetzt), date('Y', $jetzt));
  $wochentag = date('N', $beginn);

  $zeitraum = '-';
  $dbs = cms_verbinden('s');
  // Suche Schultage dieses Zeitraums
  $sql = "SELECT * FROM zeitraeume WHERE $beginn BETWEEN beginn AND ende";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      $zeitraum = $daten;
    }
    $anfrage->free();
  }
  // Kein passender Zeitraum gefunden - leere Ausgabe
  if ($zeitraum == '-') {return "";}

  $naechsterwochentag = '-';
  $tageabstand = 0;
  // Prüfen, ob danach noch ein gültiger Wochentag kommt
  for ($i = $wochentag+1; $i<=7; $i++) {
    if ($naechsterwochentag == '-') {
      $tageabstand ++;
      if ($zeitraum[strtolower(cms_tagname($i))] == 1) {$naechsterwochentag = $i;}
    }
  }
  // Falls der nächste Wochentag vor dem aktuellen liegt
  if ($naechsterwochentag == '-') {
    for ($i = 1; $i<=$wochentag; $i++) {
      if ($naechsterwochentag == '-') {
        $tageabstand ++;
        if ($zeitraum[strtolower(cms_tagname($i))] == 1) {$naechsterwochentag = $i;}
      }
    }
  }

  // Beginn des nächsten Tages
  $beginn = $beginn + ($tageabstand * 60 * 60 * 24);

  $zeitraumok = false;
  // Prüfen, ob dieser Tag im gleichen Zeitraum liegt
  $sql = "SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE id = ".$zeitraum['id']." AND $beginn BETWEEN beginn AND ende";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      if ($daten['anzahl'] == 1) {$zeitraumok = true;}
    }
    $anfrage->free();
  }

  if (!$zeitraumok) {
    // Falls der Zeitraum nicht okay ist, lade nächsten Zeitraum
    $nzeitraum = '-';
    $sql = "SELECT id, schuljahr, MIN(beginn) AS beginn, ende, mo, di, mi, do, fr, sa, so FROM zeitraeume WHERE beginn > ".$zeitraum['ende']." ";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $nzeitraum = $daten;
      }
      $anfrage->free();
    }
    // Kein passender Zeitraum gefunden - leere Ausgabe
    if ($nzeitraum == '-') {return "";}
    // An sonsten neuen Zeitraum übernehmen
    else {$zeitraum = $nzeitraum;}

    $beginn = mktime(0,0,0,date('n', $zeitraum['beginn']), date('j', $zeitraum['beginn']), date('Y', $zeitraum['beginn']));
    $wochentag = date('N', $beginn);

    $naechsterwochentag = '-';
    $tageabstand = 0;
    // Prüfen, ob danach noch ein gültiger Wochentag kommt
    for ($i = $wochentag; $i<=7; $i++) {
      if ($naechsterwochentag == '-') {
        $tageabstand ++;
        if ($zeitraum[strtolower(cms_tagname($i))] == 1) {$naechsterwochentag = $i;}
      }
    }
    // Falls der nächste Wochentag vor dem aktuellen liegt
    if ($naechsterwochentag == '-') {
      for ($i = 1; $i<=$wochentag-1; $i++) {
        if ($naechsterwochentag == '-') {
          $tageabstand ++;
          if ($zeitraum[strtolower(cms_tagname($i))] == 1) {$naechsterwochentag = $i;}
        }
      }
    }
    // Beginn des nächsten Tages
    $beginn = $beginn + ($tageabstand * 60 * 60 * 24);
  }
  $ende = $beginn + (60 * 60 * 24) - 1 ;

  $code = cms_vertretungsplan_tag($dbs, $beginn, $ende);
  cms_trennen($dbs);

  if (strlen($code) > 0) {$code = "<h3>Nächster Schultag</h3><p><b>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('n', $beginn))." ".date('Y', $beginn)."</b></p>".$code;}

  return $code;
}

function cms_vertretungsplan_tag($dbs, $beginn, $ende) {
  global $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_SCHLUESSEL;
  if (($CMS_BENUTZERART != 'l') && ($CMS_BENUTZERART != 's')) {return "";}

  $vtext = "";
  $sql = "SELECT AES_DECRYPT(textschueler, '$CMS_SCHLUESSEL') AS vts, AES_DECRYPT(textlehrer, '$CMS_SCHLUESSEL') AS vtl FROM vertretungstexte WHERE beginn = $beginn";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      if ($CMS_BENUTZERART == 'l') {$vtext = $daten['vtl'];}
      else if ($CMS_BENUTZERART == 's') {$vtext = $daten['vts'];}
    }
    $anfrage->free();
  }
  if (strlen($vtext) > 0) {$vtext = "<p>".cms_textaustextfeld_anzeigen($vtext)."</p>";}

  // Suche das Schuljahr des gegebenen Tages
  $schuljahr = '-';
  $sql = "SELECT id FROM schuljahre WHERE $beginn BETWEEN beginn AND ende";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      $schuljahr = $daten['id'];
    }
    $anfrage->free();
  }
  // Kein passender Zeitraum gefunden - leere Ausgabe
  if ($schuljahr == '-') {return $vtext;}

  $code = "";

  $tag = date('d', $beginn);
  $monat = date('m', $beginn);
  $jahr = date('Y', $beginn);

  if ($CMS_BENUTZERART == 'l') {
    // Suche Vertretungsstunden
    $sql = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, beginn, ende, stunde, tbeginn, tende, tstunde, kurs, lehrkraft, tlehrkraft, raum, traum FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (lehrkraft = $CMS_BENUTZERID OR tlehrkraft = $CMS_BENUTZERID) AND ((beginn BETWEEN $beginn AND $ende) OR (tbeginn BETWEEN $beginn AND $ende))";
    $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS astundenbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS akuerzel, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS araumbez, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, entfall, zusatzstunde, vtext, beginn, ende, stunde, tbeginn, tende, tstunde, tlehrkraft, traum FROM ($sql) AS x JOIN kurse ON x.kurs = kurse.id LEFT JOIN lehrer ON x.lehrkraft = lehrer.id LEFT JOIN raeume ON x.raum = raeume.id LEFT JOIN schulstunden ON x.stunde = schulstunden.id";
    $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS nstundenbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS nraumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS nkuerzel, astundenbez, akuerzel, araumbez, kursbez, entfall, zusatzstunde, vtext, beginn, ende, tbeginn, tende, tstunde FROM ($sql) AS y LEFT JOIN lehrer ON y.tlehrkraft = lehrer.id LEFT JOIN raeume ON y.traum = raeume.id LEFT JOIN schulstunden ON y.tstunde = schulstunden.id ORDER BY beginn";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $code .= cms_vertretungsplan_vertretungsstunde($daten, $tag, $monat, $jahr);
      }
      $anfrage->free();
    }
  }
  else if ($CMS_BENUTZERART == 's') {
    // Suche Vertretungsstunden
    $sql = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, beginn, ende, stunde, tbeginn, tende, tstunde, kurs, lehrkraft, tlehrkraft, raum, traum FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (kurs IN (SELECT kurs FROM kursschueler WHERE schueler = $CMS_BENUTZERID)) AND ((beginn BETWEEN $beginn AND $ende) OR (tbeginn BETWEEN $beginn AND $ende))";
    $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS astundenbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS akuerzel, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS araumbez, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, entfall, zusatzstunde, vtext, beginn, ende, stunde, tbeginn, tende, tstunde, tlehrkraft, traum FROM ($sql) AS x JOIN kurse ON x.kurs = kurse.id LEFT JOIN lehrer ON x.lehrkraft = lehrer.id LEFT JOIN raeume ON x.raum = raeume.id LEFT JOIN schulstunden ON x.stunde = schulstunden.id";
    $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS nstundenbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS nraumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS nkuerzel, astundenbez, akuerzel, araumbez, kursbez, entfall, zusatzstunde, vtext, beginn, ende, tbeginn, tende, tstunde FROM ($sql) AS y LEFT JOIN lehrer ON y.tlehrkraft = lehrer.id LEFT JOIN raeume ON y.traum = raeume.id LEFT JOIN schulstunden ON y.tstunde = schulstunden.id ORDER BY beginn";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $code .= cms_vertretungsplan_vertretungsstunde($daten, $tag, $monat, $jahr);
      }
      $anfrage->free();
    }
  }


  if (strlen($code) > 0) {
    $code = "<ul class=\"cms_uebersicht\">".$code."</ul>";
  }

  return $vtext.$code;
}

function cms_vertretungsplan_vertretungsstunde ($daten, $tag, $monat, $jahr) {
  $code = "";
  $code .= "<li><span class=\"cms_uebersicht_vertretungsplanung\">";
  if ($daten['entfall'] == 1) {$titel = "Entfall";}
  else if ($daten['zusatzstunde'] == 1) {$titel = "Neu";}
  else {$titel = "Änderung";}
  $code .= "<span class=\"cms_vertretung_info\"><h4>$titel</h4><p class=\"cms_notiz\">".$daten['vtext']."</p></span>";
  $code .= "<p>";
  if ($daten['zusatzstunde'] != 1) {
    $zeitvorher = '<span class="cms_vertretung_inhalt">';
    if (cms_ist_heute($daten['beginn'], $tag, $monat, $jahr)) {$zeitvorher .= date('H:i', $daten['beginn'])." - ".date('H:i', $daten['ende']);}
    else {$zeitvorher .= cms_tagname(date('N', $daten['beginn']))." ".date('d.m.Y', $daten['beginn'])."<br>".date('H:i', $daten['beginn'])." - ".date('H:i', $daten['ende']);}
    if (!is_null($daten['astundenbez'])) {$zeitvorher = '<span class="cms_vertretung_stundennr">'.$daten['astundenbez'].'</span>'.$zeitvorher;}
    $code .= "<span class=\"cms_vertretung_vorher\">$zeitvorher<br>".$daten['kursbez']."<br>".$daten['akuerzel']."<br>".$daten['araumbez']."</span></span>";
  }
  if ($daten['entfall'] != 1) {
    $zeitnachher = '<span class="cms_vertretung_inhalt">';
    if (cms_ist_heute($daten['tbeginn'], $tag, $monat, $jahr)) {$zeitnachher .= date('H:i', $daten['tbeginn'])." - ".date('H:i', $daten['tende']);}
    else {$zeitnachher .= cms_tagname(date('N', $daten['tbeginn']))." ".date('d.m.Y', $daten['tbeginn'])."<br>".date('H:i', $daten['tbeginn'])." - ".date('H:i', $daten['tende']);}
    if (!is_null($daten['nstundenbez'])) {$zeitnachher = '<span class="cms_vertretung_stundennr">'.$daten['nstundenbez'].'</span>'.$zeitnachher;}
    $code .= "<span class=\"cms_vertretung_nachher\">$zeitnachher<br>".$daten['kursbez']."<br>".$daten['nkuerzel']."<br>".$daten['nraumbez']."</span></span>";
  }
  $code .= "</p></span></li>";
  return $code;
}
?>
