<?php
function cms_vertretungsplan_komplettansicht_heute($art) {
  global $CMS_RECHTE;

  if (!$CMS_RECHTE['verwaltung'] && !$CMS_RECHTE['lehrer']) {
    return cms_meldung_berechtigung();
  }

  $jetzt = time();
  $beginn = mktime(0,0,0, date('n', $jetzt), date('j', $jetzt), date('Y', $jetzt));
  $ende = mktime(23,59,59, date('n', $jetzt), date('j', $jetzt), date('Y', $jetzt));

  $dbs = cms_verbinden('s');
  $code = cms_vertretungsplan_komplettansicht_tag($dbs, $art, $beginn, $ende);
  cms_trennen($dbs);

  $code = "<h3>Heute</h3><p><b>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('n', $beginn))." ".date('Y', $beginn)."</b></p>".$code;
  return $code;
}

function cms_vertretungsplan_komplettansicht_naechsterschultag($art) {
  global $CMS_RECHTE;

  if (!$CMS_RECHTE['verwaltung'] && !$CMS_RECHTE['lehrer']) {
    return cms_meldung_berechtigung();
  }

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

  $code = cms_vertretungsplan_komplettansicht_tag($dbs, $art, $beginn, $ende);
  cms_trennen($dbs);

  $code = "<h3>Nächster Schultag</h3><p><b>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('n', $beginn))." ".date('Y', $beginn)."</b></p>".$code;

  return $code;
}

function cms_vertretungsplan_komplettansicht_tag($dbs, $art, $beginn, $ende) {
  global $CMS_RECHTE, $CMS_SCHLUESSEL;

  if (!$CMS_RECHTE['verwaltung'] && !$CMS_RECHTE['lehrer']) {
    return cms_meldung_berechtigung();
  }

  $vtext = "";
  $sql = "SELECT AES_DECRYPT(textschueler, '$CMS_SCHLUESSEL') AS vts, AES_DECRYPT(textlehrer, '$CMS_SCHLUESSEL') AS vtl FROM vertretungstexte WHERE beginn = $beginn";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      if ($art == 'l') {$vtext = $daten['vtl'];}
      else if ($art == 's') {$vtext = $daten['vts'];}
    }
    $anfrage->free();
  }
  // Kein passender Zeitraum gefunden - leere Ausgabe
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

  $tabelle = "";

  $tag = date('d', $beginn);
  $monat = date('m', $beginn);
  $jahr = date('Y', $beginn);

  if ($art == 'l') {
    // Suche Vertretungsstunden
    $sql1 = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, tbeginn, tende, beginn AS abeginn, ende AS aende, tstunde, kurs, tlehrkraft, traum, '1' AS heute FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (tbeginn BETWEEN $beginn AND $ende)";
    $sql2 = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, beginn AS tbeginn, ende AS tende, beginn AS abeginn, ende AS aende, stunde AS tstunde, kurs, lehrkraft AS tlehrkraft, raum AS traum, '0' AS heute FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (beginn BETWEEN $beginn AND $ende) AND NOT (tbeginn BETWEEN $beginn AND $ende)";
    $sql = "SELECT heute, AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS stundenbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, kurs, entfall, zusatzstunde, vtext, tbeginn, tende, abeginn, aende FROM (($sql1) UNION ($sql2)) AS y JOIN lehrer ON y.tlehrkraft = lehrer.id JOIN raeume ON y.traum = raeume.id JOIN schulstunden ON y.tstunde = schulstunden.id JOIN kurse ON y.kurs = kurse.id";
    $sql = "SELECT * FROM ($sql) AS x ORDER BY kuerzel, tbeginn";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $tabelle .= cms_vertretungsplan_komplettansicht_vertretungsstunde_lehrer($dbs, $daten, $tag, $monat, $jahr);
      }
      $anfrage->free();
    }
    if (strlen($tabelle) > 0) {
      $code = "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th></td><th>Zeit</th><th>Kurs</th><th>Klassen</th><th>Raum</th><th></th><th></th></tr>";
      $code .= $tabelle;
      $code .= "</table>";
    }
  }
  else if ($art == 's') {
    // Suche Vertretungsstunden
    $sql1 = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, tbeginn, tende, beginn AS abeginn, ende AS aende, tstunde, kurs, tlehrkraft, traum, '1' AS heute FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (tbeginn BETWEEN $beginn AND $ende)";
    $sql2 = "SELECT entfall, zusatzstunde, AES_DECRYPT(vertretungstext, '$CMS_SCHLUESSEL') AS vtext, beginn AS tbeginn, ende AS tende, beginn AS abeginn, ende AS aende, stunde AS tstunde, kurs, lehrkraft AS tlehrkraft, raum AS traum, '0' AS heute FROM tagebuch_$schuljahr WHERE vertretungsplan = 1 AND (beginn BETWEEN $beginn AND $ende) AND NOT (tbeginn BETWEEN $beginn AND $ende)";
    $sql = "SELECT DISTINCT heute, AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL') AS stundenbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, y.kurs AS kurs, entfall, zusatzstunde, vtext, tbeginn, tende, abeginn, aende, reihenfolge FROM (($sql1) UNION ($sql2)) AS y JOIN lehrer ON y.tlehrkraft = lehrer.id JOIN raeume ON y.traum = raeume.id JOIN schulstunden ON y.tstunde = schulstunden.id JOIN kurse ON y.kurs = kurse.id JOIN kursklassen ON y.kurs = kursklassen.kurs JOIN klassen ON kursklassen.klasse = klassen.id JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id";
    $sql = "SELECT * FROM ($sql) AS x ORDER BY reihenfolge, klasse, tbeginn";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $tabelle .= cms_vertretungsplan_komplettansicht_vertretungsstunde_schueler($dbs, $daten, $tag, $monat, $jahr);
      }
      $anfrage->free();
    }
    if (strlen($tabelle) > 0) {
      $code = "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th></td><th>Zeit</th><th>Kurs</th><th>Lehrer</th><th>Raum</th><th></th><th></th></tr>";
      $code .= $tabelle;
      $code .= "</table>";
    }
  }

  if (strlen($tabelle) == 0) {$code = "<p><i>Keine Vertretungen.</i></p>";}

  return $vtext.$code;
}

function cms_vertretungsplan_komplettansicht_vertretungsstunde_lehrer ($dbs, $daten, $tag, $monat, $jahr) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $klassen = "";
  $sql = "SELECT klasse FROM kursklassen WHERE kurs = ".$daten['kurs'];
  $sql = "SELECT AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge FROM ($sql) AS x JOIN klassen ON klassen.id = x.klasse JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id";
  $sql = "SELECT * FROM ($sql) AS y ORDER BY reihenfolge, klasse";
  if ($anfrage = $dbs->query($sql)) {
    while ($k = $anfrage->fetch_assoc()) {
      $klassen .= " ".$k['stufe'].$k['klasse'];
    }
    $anfrage->free();
  }

  $code .= "<tr>";
  $code .= "<td>".$daten['kuerzel']."</td>";
  $code .= "<td>".$daten['stundenbez']."</td><td>".date('H:i', $daten['tbeginn'])."<br>".date('H:i', $daten['tende'])."</td>";
  $code .= "<td>".$daten['kursbez']."</td>";
  $code .= "<td>$klassen</td>";
  $code .= "<td>".$daten['raumbez']."</td>";
  $code .= "<td>";
    if ($daten['entfall'] == '1') {$code .= "<b>Entfall</b>";}
    else if ($daten['zusatzstunde'] == '1') {$code .= "<b>Neu</b>";}
    else {
      $code .= "<b>Änderung</b><br>";
      if ($daten['heute'] == '1') {
        if (($daten['abeginn'] == $daten['tbeginn']) && ($daten['aende'] == $daten['tende'])) {$code .= "findet statt";}
        else {$code .= "anstatt ".date('H:i', $daten['abeginn'])." - ".date('H:i', $daten['aende']);}
      }
      else {$code .= "entfällt - verschoben";}
    }
  $code .= "</td>";
  $code .= "<td>".$daten['vtext']."</td>";
  $code .= "</tr>";
  return $code;
}

function cms_vertretungsplan_komplettansicht_vertretungsstunde_schueler ($dbs, $daten, $tag, $monat, $jahr) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $code .= "<tr>";
  $code .= "<td>".$daten['stufe'].$daten['klasse']."</td>";
  $code .= "<td>".$daten['stundenbez']."</td><td>".date('H:i', $daten['tbeginn'])."<br>".date('H:i', $daten['tende'])."</td>";
  $code .= "<td>".$daten['kursbez']."</td>";
  $code .= "<td>".$daten['kuerzel']."</td>";
  $code .= "<td>".$daten['raumbez']."</td>";
  $code .= "<td>";
    if ($daten['entfall'] == '1') {$code .= "<b>Entfall</b>";}
    else if ($daten['zusatzstunde'] == '1') {$code .= "<b>Neu</b>";}
    else {
      $code .= "<b>Änderung</b><br>";
      if ($daten['heute'] == '1') {
        if (($daten['abeginn'] == $daten['tbeginn']) && ($daten['aende'] == $daten['tende'])) {$code .= "findet statt";}
        else {$code .= "anstatt ".date('H:i', $daten['abeginn'])." - ".date('H:i', $daten['aende']);}
      }
      else {$code .= "entfällt - verschoben";}
    }
  $code .= "</td>";
  $code .= "<td>".$daten['vtext']."</td>";
  $code .= "</tr>";
  return $code;
}
?>
