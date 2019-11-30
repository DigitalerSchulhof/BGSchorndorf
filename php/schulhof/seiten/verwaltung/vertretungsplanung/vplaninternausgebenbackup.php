<?php
function cms_vertretungsplan_komplettansicht_heute($dbs, $art, $nachladen = true) {
  // Schultag berechnen
  $jetzt = mktime(0,0,0,date('m'), date('d'), date('Y'));
  $start = $jetzt;
  $sql = "SELECT MIN(tbeginn) FROM unterricht WHERE tbeginn > ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $sql->close();

  $beginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $ende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  $code = cms_vertretungsplan_komplettansicht($dbs, $art, $beginn, $ende, '1', $nachladen);
  return $code;
}

function cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, $art, $nachladen = true) {
  // Schultag berechnen
  $jetzt = mktime(0,0,0,date('m'), date('d'), date('Y'));
  $start = $jetzt;
  $sql = "SELECT MIN(tbeginn) FROM unterricht WHERE tbeginn > ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }

  // Schultag danach berechnen
  $jetzt = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start));
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $sql->close();

  $beginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $ende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  $code = cms_vertretungsplan_komplettansicht($dbs, $art, $beginn, $ende, '2', $nachladen);
  return $code;
}

function cms_vertretungsplan_komplettansicht($dbs, $art, $beginn, $ende, $id = '', $nachladen) {
  global $CMS_SCHLUESSEL, $CMS_IMLN;
  $code = "";
  // Schulstunden in diesem Zeitraum laden
  $SCHULSTUNDEN = array();
  $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden JOIN zeitraeume ON schulstunden.zeitraum = zeitraeume.id WHERE beginn <= ? AND ende >= ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $beginn, $ende);
  if ($sql->execute()) {
    $sql->bind_result($stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
    while ($sql->fetch()) {
      $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)] = $stdbez;
    }
  }
  $sql->close();

  if ($art == 'l') {
    // Vertretungen ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND vplanart != 'e') AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}

        if ($vplanart == 'v') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)]." am ".date("d.m.Y", $pbeginn);} else {$pstd = date("d.m.Y H:i", $pbeginn)." Uhr";}
          $code .= "<tr><td>$tlehrer</td><td>$tstd</td><td>$tkurs</td><td>$traumbez</td><td>Verlegung</td><td>$vplanbem</td><td>$plehrer</td><td>$pstd</td><td>$pkurs</td></tr>";
        }
        else if ($vplanart == 'a') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
          $code .= "<tr><td>$tlehrer</td><td>$tstd</td><td>$tkurs</td><td>$traumbez</td><td>Änderung</td><td>$vplanbem</td><td>$plehrer</td><td>$pstd</td><td>$pkurs</td></tr>";
        }
        else if ($vplanart == 's') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
          $code .= "<tr><td>$tlehrer</td><td>$tstd</td><td>$tkurs</td><td>$traumbez</td><td>Sondereinsatz</td><td>$vplanbem</td><td></td><td></td><td></td></tr>";
        }
      }
    }
    $sql->close();

    // Entfall ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND (pbeginn >= ? AND pende <= ?) AND ((tende <= ? OR tbeginn >= ?) OR (vplanart = 'e'))) AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praumbez, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$pstd = date("H:i", $pbeginn)." Uhr";}
        if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
        $code .= "<tr><td>$plehrer</td><td>$pstd</td><td>$pkurs</td><td>$praumbez</td><td>Entfall</td><td>$vplanbem</td><td></td><td></td><td></td></tr>";
      }
    }
    $sql->close();
  }
  else if ($art == 's') {
    // Vertretungen ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ?) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";

    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        if (strlen($kbez) == 0) {$kbez = $sbez;}

        if ($vplanart == 'v') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)]." am ".date("d.m.Y", $pbeginn);} else {$pstd = date("d.m.Y H:i", $pbeginn)." Uhr";}
          $code .= "<tr><td>$kbez</td><td>$tstd</td><td>$tkurs</td><td>$tlehrer</td><td>$traumbez</td><td>Verlegung</td><td>$vplanbem</td><td>$pstd</td><td>$pkurs</td><td>$plehrer</td></tr>";
        }
        else if ($vplanart == 'a') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
          $code .= "<tr><td>$kbez</td><td>$tstd</td><td>$tkurs</td><td>$tlehrer</td><td>$traumbez</td><td>Änderung</td><td>$vplanbem</td><td>$pstd</td><td>$pkurs</td><td>$plehrer</td></tr>";
        }
        else if ($vplanart == 's') {
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$tstd = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$tstd = date("H:i", $tbeginn)." Uhr";}
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
          $code .= "<tr><td>$kbez</td><td>$tstd</td><td>$tkurs</td><td>$tlehrer</td><td>$traumbez</td><td>Sondereinsatz</td><td>$vplanbem</td><td></td><td></td><td></td></tr>";
        }
      }
    }
    $sql->close();

    // Entfall ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit,  AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND (pbeginn >= ? AND pende <= ?) AND ((tende <= ? OR tbeginn >= ?) OR (vplanart = 'e'))) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praumbez, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        if (strlen($kbez) == 0) {$kbez = $sbez;}
        if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$pstd = date("H:i", $pbeginn)." Uhr";}
        if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$pstd = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {if ($pbeginn !== null) {$pstd = date("H:i", $pbeginn)." Uhr";} else {$pstd = "";}}
        $code .= "<tr><td>$kbez</td><td>$pstd</td><td>$pkurs</td><td>$plehrer</td><td>$praumbez</td><td>Entfall</td><td>$vplanbem</td><td></td><td></td><td></td></tr>";
      }
    }
    $sql->close();
  }

  if (strlen($code) > 0) {
    if ($art == 'l') {
      $code = "<table class=\"cms_liste\"><tr><th colspan=\"6\">Neu</th><th colspan=\"3\">Alt</th></tr><tr><th>Lehrer</th><th>Stunde</th><th>Kurs</th><th>Raum</th><th>Art</th><th>Bemerkung</th><th>Lehrer</th><th>Stunde</th><th>Kurs</th></tr>".$code."</table>";
    }
    else if ($art == 's') {
      $code = "<table class=\"cms_liste\"><tr><th colspan=\"7\">Neu</th><th colspan=\"3\">Alt</th></tr><tr><th>Klasse</th><th>Stunde</th><th>Kurs</th><th>Lehrer</th><th>Raum</th><th>Art</th><th>Bemerkung</th><th>Stunde</th><th>Kurs</th><th>Lehrer</th></tr>".$code."</table>";
    }
  }
  else {
    $code = "<p class=\"cms_notiz\">Keine Vertretungen.</p>";
  }

  if ($art == 'l') {
    // Vertretungstext
    $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = 's' AND zeit = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $beginn);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $text);
      if ($sql->fetch()) {
        if (strlen($text) > 0) {$code = cms_meldung('info', '<h4>Schülerinformationen zum Tag</h4><p>'.cms_textaustextfeld_anzeigen($text).'</p>').$code;}
      }
    }
  }

  // Vertretungstext
  $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = ? AND zeit = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("si", $art, $beginn);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $text);
    if ($sql->fetch()) {
      $infoart = "Informationen";
      if ($art == 'l') {$infoart = "Lehrerinformationen";}
      if (strlen($text) > 0) {$code = cms_meldung('info', '<h4>'.$infoart.' zum Tag</h4><p>'.cms_textaustextfeld_anzeigen($text).'</p>').$code;}
    }
  }

  if (($art == 'l') && ($nachladen)) {
    if ($CMS_IMLN) {
      if ($id == '1') {$code .= cms_generiere_nachladen('cms_vplan_gruende_1', 'cms_vplan_gruende_1(\''.$beginn.'\');');}
      else if ($id == '2') {$code .= cms_generiere_nachladen('cms_vplan_gruende_2', 'cms_vplan_gruende_2(\''.$beginn.'\');');}
    }
    else {
      $code .= cms_meldung_geschuetzer_inhalt ();
    }
  }

  $sql->close();

  return "<h2>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('m', $beginn))." ".date('Y', $beginn)."</h2>".$code;
}




function cms_vertretungsplan_persoenlich_heute($dbs) {
  // Schultag berechnen
  $jetzt = mktime(0,0,0,date('m'), date('d'), date('Y'));
  $start = $jetzt;
  $sql = "SELECT MIN(tbeginn) FROM unterricht WHERE tbeginn > ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $sql->close();

  $beginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $ende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  $code = cms_vertretungsplan_persoenlich($dbs, $beginn, $ende);
  return $code;
}

function cms_vertretungsplan_persoenlich_naechsterschultag($dbs) {
  // Schultag berechnen
  $jetzt = mktime(0,0,0,date('m'), date('d'), date('Y'));
  $start = $jetzt;
  $sql = "SELECT MIN(tbeginn) FROM unterricht WHERE tbeginn > ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }

  // Schultag danach berechnen
  $jetzt = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start));
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $sql->close();

  $beginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $ende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  $code = cms_vertretungsplan_persoenlich($dbs, $beginn, $ende);
  return $code;
}


function cms_vertretungsplan_persoenlich($dbs, $beginn, $ende) {
  global $CMS_SCHLUESSEL, $CMS_IMLN, $CMS_BENUTZERART, $CMS_BENUTZERID;
  //if (($CMS_BENUTZERART != 's') && ($CMS_BENUTZERART != 'l')) {return "";}
  if ($CMS_BENUTZERART != 'l') {return "";}
  $code = "<h3>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('m', $beginn))."</h3>";

  if ($CMS_BENUTZERART == 'l') {
    // Vertretungstext
    $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = 's' AND zeit = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $beginn);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $text);
      if ($sql->fetch()) {
        if (strlen($text) > 0) {$code .= cms_meldung('info', '<h4>Schülerinformationen zum Tag</h4><p>'.cms_textaustextfeld_anzeigen($text).'</p>');}
      }
    }
  }

  // Vertretungstext
  $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = ? AND zeit = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("si", $CMS_BENUTZERART, $beginn);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $text);
    if ($sql->fetch()) {
      $infoart = "Informationen";
      if ($CMS_BENUTZERART == 'l') {$infoart = "Lehrerinformationen";}
      if (strlen($text) > 0) {$code .= cms_meldung('info', '<h4>'.$infoart.' zum Tag</h4><p>'.cms_textaustextfeld_anzeigen($text).'</p>');}
    }
  }

  // Schulstunden in diesem Zeitraum laden
  $SCHULSTUNDEN = array();
  $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden JOIN zeitraeume ON schulstunden.zeitraum = zeitraeume.id WHERE beginn <= ? AND ende >= ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $beginn, $ende);
  if ($sql->execute()) {
    $sql->bind_result($stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
    while ($sql->fetch()) {
      $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)] = $stdbez;
    }
  }
  $sql->close();

  $vstunden = "";

  if ($CMS_BENUTZERART == 'l') {
    // Vertretungen ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND (tlehrer = ? OR plehrer = ?) AND vplanart != 'e') AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginn, $ende, $CMS_BENUTZERID, $CMS_BENUTZERID);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $praumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        $vstunden .= cms_vertretungsplan_vertretungsstunde($vplanart, $vplanbem, $SCHULSTUNDEN, $beginn, $pbeginn, $tbeginn, $pkurs, $plehrer, $praumbez, $tkurs, $tlehrer, $traumbez);
      }
    }
    $sql->close();

    // Entfall ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND (tlehrer = ? OR plehrer = ?) AND (pbeginn >= ? AND pende <= ?) AND ((tende <= ? OR tbeginn >= ?) OR (vplanart = 'e'))) AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiiiii", $CMS_BENUTZERID, $CMS_BENUTZERID, $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praumbez, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        $vstunden .= cms_vertretungsplan_vertretungsstunde('e', $vplanbem, $SCHULSTUNDEN, $beginn, $pbeginn, $tbeginn, $pkurs, $plehrer, $praumbez, $tkurs, $tlehrer, $traumbez);
      }
    }
    $sql->close();
  }
  else if ($CMS_BENUTZERART == 's') {
    // Vertretungen ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND vplanart != 'e' AND tbeginn >= ? AND tende <= ? AND (tkurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?) OR pkurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?))) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";

    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginn, $ende, $CMS_BENUTZERID, $CMS_BENUTZERID);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $praumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        if (strlen($kbez) == 0) {$kbez = $sbez;}
        $vstunden .= cms_vertretungsplan_vertretungsstunde($vplanart, $vplanbem, $SCHULSTUNDEN, $beginn, $pbeginn, $tbeginn, $pkurs, $plehrer, $praumbez, $tkurs, $tlehrer, $traumbez);
      }
    }
    $sql->close();

    // Entfall ausgeben
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit,  AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
    $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
    $sql .= "WHERE vplananzeigen = '1' AND (tkurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?) OR pkurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?)) AND (pbeginn >= ? AND pende <= ?) AND ((tende <= ? OR tbeginn >= ?) OR (vplanart = 'e'))) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiiiii", $CMS_BENUTZERID, $CMS_BENUTZERID, $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($pkursbez, $pkurskbez, $pbeginn, $pende, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praumbez, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        if (strlen($tlehrerk) > 0) {$tlehrer = $tlehrerk;}
        else {$tlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        if (strlen($plehrerk) > 0) {$plehrer = $plehrerk;}
        else {$plehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
        if (strlen($tkurskbez) > 0) {$tkurs = $tkurskbez;}
        else {$tkurs = $tkursbez;}
        if (strlen($pkurskbez) > 0) {$pkurs = $pkurskbez;}
        else {$pkurs = $pkursbez;}
        if (strlen($kbez) == 0) {$kbez = $sbez;}
        $vstunden .= cms_vertretungsplan_vertretungsstunde('e', $vplanbem, $SCHULSTUNDEN, $beginn, $pbeginn, $tbeginn, $pkurs, $plehrer, $praumbez, $tkurs, $tlehrer, $traumbez);
      }
    }
    $sql->close();
  }

  if (strlen($vstunden) > 0) {$code .= "<ul class=\"cms_uebersicht\">$vstunden</ul>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Vertretungen.</p>";}

  return $code;
}


function cms_vertretungsplan_vertretungsstunde ($vplanart, $vplanbem, $SCHULSTUNDEN, $heute, $pbeginn, $tbeginn, $pkursbez, $plehrerkurz, $praumbez, $tkursbez, $tlehrerkurz, $traumbez) {
  $code = "";
  $code .= "<li><span class=\"cms_uebersicht_vertretungsplanung\">";
  if ($vplanart == 'e') {$titel = "Entfall";}
  else if ($vplanart == 's') {$titel = "Zusatzstunde";}
  else if ($vplanart == 'v') {$titel = "Verlegung";}
  else {$titel = "Änderung";}
  $code .= "<span class=\"cms_vertretung_info\"><h4>$titel</h4><p class=\"cms_notiz\">$vplanbem</p></span>";
  $code .= "<p>";
  if ($vplanart != 's') {
    $klasse = "cms_vertretung_vorher";
    if ($vplanart == 'e') {$klasse = "cms_vertretung_entfall";}
    $code .= '<span class="cms_vertretung_inhalt">';
    $datumv = "";
    if (!cms_ist_heute($heute, $pbeginn)) {$datumv = date('d.m.Y', $pbeginn)."<br>";}
    $schulstundev = date('H:i', $pbeginn);
    if (isset($SCHULSTUNDEN[$schulstundev])) {$schulstundenv = $SCHULSTUNDEN[$schulstundev];}
    $zeitv = '<span class="cms_vertretung_stundennr">'.$schulstundenv.'</span>'.$datumv;
    $code .= "<span class=\"$klasse\">".$zeitv.$pkursbez."<br>".$plehrerkurz."<br>".$praumbez."</span>";
    $code .= "</span>";
  }
  if ($vplanart != 'e') {
    $code .= '<span class="cms_vertretung_inhalt">';
    $datumn = "";
    if (!cms_ist_heute($heute, $tbeginn)) {$datumn = date('d.m.Y', $tbeginn)."<br>";}
    $schulstunden = date('H:i', $tbeginn);
    if (isset($SCHULSTUNDEN[$schulstunden])) {$schulstundenn = $SCHULSTUNDEN[$schulstunden];}
    $zeitn = '<span class="cms_vertretung_stundennr">'.$schulstundenn.'</span>'.$datumn;
    $code .= "<span class=\"cms_vertretung_nachher\">".$zeitn.$tkursbez."<br>".$tlehrerkurz."<br>".$traumbez."</span>";
    $code .= "</span>";
  }
  $code .= "</p></span></li>";
  return $code;
}


?>
