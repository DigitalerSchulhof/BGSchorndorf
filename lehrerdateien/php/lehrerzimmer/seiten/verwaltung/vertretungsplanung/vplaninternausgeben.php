<?php
function cms_vertretungsplan_komplettansicht_heute($dbs, $dbl, $art) {
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

  $code = cms_vertretungsplan_komplettansicht($dbs, $dbl, $art, $beginn, $ende);
  return $code;
}

function cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, $dbl, $art) {
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

  $code = cms_vertretungsplan_komplettansicht($dbs, $dbl, $art, $beginn, $ende);
  return $code;
}

function cms_vertretungsplan_komplettansicht($dbs, $dbl, $art, $beginn, $ende) {
  global $CMS_SCHLUESSEL, $CMS_IMLN;
  $code = "";
  // Schulstunden in diesem Zeitraum laden
  $rythmen = 0;
  $SCHULSTUNDEN = array();
  $sql = "SELECT AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem, rythmen FROM schulstunden JOIN zeitraeume ON schulstunden.zeitraum = zeitraeume.id WHERE beginn <= ? AND ende >= ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $beginn, $ende);
  if ($sql->execute()) {
    $sql->bind_result($stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem, $rythmen);
    while ($sql->fetch()) {
      $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)] = $stdbez;
    }
  }
  $sql->close();

  // $rythmus laden
  $RYTHMUS = "";
  if ($rythmen > 0) {
    $wochenbeginn = mktime(0,0,0, date('m', $beginn), date('d', $beginn) + 1-date('N', $beginn), date('Y', $beginn));
    $wochenende = mktime(0,0,0, date('m', $beginn), date('d', $beginn) + 8-date('N', $beginn), date('Y', $beginn))-1;
    $sql = $dbs->prepare("SELECT rythmus FROM rythmisierung WHERE beginn >= ? AND beginn < ?");
    $sql->bind_param("ii", $wochenbeginn, $wochenende);
    if ($sql->execute()) {
      $sql->bind_result($ryth);
      if ($sql->fetch()) {
        $RYTHMUS = " – ".chr(64+$ryth)." Woche";
      }
    }
    $sql->close();
  }

  $PLAN = array();
  if (substr($art,0,1) == 'l') {
    // Ausgeplante Kollegen laden
    $LIDS = array();
    $sql = "SELECT lehrer FROM ausplanunglehrer WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
    $sql = $dbl->prepare($sql);
    $sql->bind_param("iiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($eid);
      while ($sql->fetch()) {
        array_push($LIDS, $eid);
      }
    }
    $sql->close();

    // Vertretungen ausgeben
    if ($art == 'l') {
      $sql = "SELECT * FROM (SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
      $sql .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND vplanart != 'e') AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("ii", $beginn, $ende);
    }
    else if ($art == 'lv') {
      $sql1 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql1 .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
      $sql1 .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND vplanart != 'e' AND unterricht.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)";
      $sql2 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, uk.tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, uk.tbeginn AS tbeginn, uk.tende, uk.tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, uk.traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, uk.vplanart, AES_DECRYPT(uk.vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql2 .= " FROM unterrichtkonflikt AS uk LEFT JOIN unterricht ON uk.altid = unterricht.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON uk.tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON uk.traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON uk.tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id JOIN personen AS tpersonen ON uk.tlehrer = tpersonen.id ";
      $sql2 .= "WHERE uk.vplananzeigen = '1' AND uk.tbeginn >= ? AND uk.tende <= ? AND uk.vplanart != 'e'";
      $sql = $dbs->prepare("SELECT * FROM (($sql1) UNION ($sql2)) AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC");
      $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    }
    if ($sql->execute()) {
      $sql->bind_result($pkurs, $pkursbez, $pkurskbez, $pbeginn, $pende, $plehrer, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praum, $praumbez, $tkurs, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrer, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traum, $traumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        $anstatt = "";
        $vplanart = "";
        // Stundentext
        if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$textstunde = date("H:i", $tbeginn)." Uhr";}
        // Kurstext
        if (strlen($tkurskbez) > 0) {$textkurs = $tkurskbez;} else {$textkurs = $tkursbez;}
        // Lehrertext
        if (strlen($tlehrerk) > 0) {$textlehrer = $tlehrerk;}
        else {$textlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        // Raumtext
        $textraum = $traumbez;

        if ($pbeginn !== null) {
          if ($tbeginn != $pbeginn) {
            $vplanart .= " / Verlegung";
            $anstatt .= " - ";
            if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$anstatt .= $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$anstatt .= date("H:i", $pbeginn)." Uhr";}
            if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {$anstatt .= " am ".date("d.m.Y", $pbeginn);}
          }
          if ($tkurs != $pkurs) {
            $vplanart .= " / Fach geändert";
            if (strlen($pkurskbez) > 0) {$anstatt .= " - ".$pkurskbez;} else {$anstatt .= " - ".$pkursbez;}
          }
          if ($tlehrer != $plehrer) {
            $vplanart .= " / Vertretung";
            if (strlen($plehrerk) > 0) {$anstatt .= " - ".$plehrerk;}
            else {$anstatt .= " - ".cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
          }
          if ($traum != $praum) {
            $vplanart .= " / Raumänderung";
            $anstatt .= " - ".$praumbez;
          }
        }
        else {
          $vplanart .= " / Zusatzstunde";
        }

        if (strlen($anstatt) > 0) {$anstatt = "anstatt ".substr($anstatt, 3);}
        if (strlen($vplanart) > 0) {$vplanart = substr($vplanart, 3);}

        $P = array();
        $P[0] = $textlehrer;
        $P[1] = $textstunde;
        $P[2] = $textkurs;
        $P[3] = $textraum;
        $P[4] = $vplanart;
        $P[5] = $vplanbem;
        $P[6] = $anstatt;
        $P[7] = 'a';
        array_push($PLAN, $P);
      }
    }
    $sql->close();

    // Entfall ausgeben
    if ($art == 'l') {
      $sql = "SELECT * FROM (SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
      $sql .= "WHERE vplananzeigen = '1' AND ((vplanart = 'e' AND pbeginn >= ? AND pende <= ? AND pbeginn IS NOT NULL) OR ((tende <= ? OR tbeginn >= ? OR tlehrer != plehrer) AND pbeginn >= ? AND pende <= ? AND vplanart != 'e'))) AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende);
    }
    if ($art == 'lv') {
      $sql1 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql1 .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id ";
      $sql1 .= "WHERE vplananzeigen = '1' AND ((vplanart = 'e' AND pbeginn >= ? AND pende <= ? AND pbeginn IS NOT NULL) OR ((tende <= ? OR tbeginn >= ? OR tlehrer != plehrer) AND pbeginn >= ? AND pende <= ? AND vplanart != 'e')) AND unterricht.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)";
      $sql2 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, uk.tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, uk.tbeginn AS tbeginn, uk.tende, uk.tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, uk.traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, uk.vplanart, AES_DECRYPT(uk.vplanbemerkung, '$CMS_SCHLUESSEL')";
      $sql2 .= " FROM unterrichtkonflikt AS uk LEFT JOIN unterricht ON uk.altid = unterricht.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON uk.tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON uk.traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON uk.tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON uk.tlehrer = tpersonen.id ";
      $sql2 .= "WHERE uk.vplananzeigen = '1' AND ((uk.vplanart = 'e' AND pbeginn >= ? AND pende <= ? AND pbeginn IS NOT NULL) OR ((uk.tende <= ? OR uk.tbeginn >= ? OR uk.tlehrer != plehrer) AND pbeginn >= ? AND pende <= ? AND uk.vplanart != 'e'))";
      $sql = $dbs->prepare("SELECT * FROM (($sql1) UNION ($sql2)) AS x ORDER BY tlehrerk ASC, tnach ASC, tvor ASC, ttit ASC, tbeginn ASC");
      $sql->bind_param("iiiiiiiiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende, $beginn, $ende, $beginn, $ende, $beginn, $ende);
    }
    if ($sql->execute()) {
      $sql->bind_result($pkurs, $pkursbez, $pkurskbez, $pbeginn, $pende, $plehrer, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praum, $praumbez, $tkurs, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrer, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traum, $traumbez, $vplanart, $vplanbem);
      while ($sql->fetch()) {
        $anstatt = "";
        if ($vplanart == 'e') {
          $vplanart = "Entfall";
          // Stundentext
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$textstunde = date("H:i", $tbeginn)." Uhr";}
          // Kurstext
          if (strlen($tkurskbez) > 0) {$textkurs = $tkurskbez;} else {$textkurs = $tkursbez;}
          // Lehrertext
          if (strlen($tlehrerk) > 0) {$textlehrer = $tlehrerk;}
          else {$textlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
          $lehrerid = $tlehrer;
          // Raumtext
          $textraum = $traumbez;

          if ($pbeginn !== null) {
            if ($tbeginn != $pbeginn) {
              $vplanart .= " / Verlegt";
              $anstatt .= " - ";
              if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$anstatt .= $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$anstatt .= date("H:i", $tbeginn)." Uhr";}
              if (date("d.m.Y", $pbeginn) != date("d.m.Y", $tbeginn)) {$anstatt .= " am ".date("d.m.Y", $tbeginn);}
              if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$textstunde = date("H:i", $pbeginn)." Uhr";}

              // Kurstext
              if (strlen($pkurskbez) > 0) {$textkurs = $pkurskbez;} else {$textkurs = $pkursbez;}
              // Lehrertext
              if (strlen($plehrerk) > 0) {$textlehrer = $plehrerk;}
              else {$textlehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
              $lehrerid = $plehrer;
              // Raumtext
              $textraum = $praumbez;

              // if ($tkurs != $pkurs) {
              //   if (strlen($tkurskbez) > 0) {$anstatt .= " - ".$tkurskbez;} else {$anstatt .= " - ".$tkursbez;}
              // }
              // if ($tlehrer != $plehrer) {
              //   if (strlen($tlehrerk) > 0) {$anstatt .= " - ".$tlehrerk;}
              //   else {$anstatt .= " - ".cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
              // }
              // if ($traum != $praum) {
              //   $anstatt .= " - ".$traumbez;
              // }
              // if (strlen($anstatt) > 0) {$anstatt = "nun ".substr($anstatt, 3);}
            }
            else {
              // if ($tkurs != $pkurs) {
              //   if (strlen($pkurskbez) > 0) {$anstatt .= " - ".$pkurskbez;} else {$anstatt .= " - ".$pkursbez;}
              // }
              // if ($tlehrer != $plehrer) {
              //   if (strlen($plehrerk) > 0) {$anstatt .= " - ".$plehrerk;}
              //   else {$anstatt .= " - ".cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
              // }
              // if ($traum != $praum) {
              //   $anstatt .= " - ".$praumbez;
              // }
              // if (strlen($anstatt) > 0) {$anstatt = "anstatt ".substr($anstatt, 3);}
            }
          }
        }
        else {
          $vplanart = "Entfall";
          // Stundentext
          if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$textstunde = date("H:i", $pbeginn)." Uhr";}
          // Kurstext
          if (strlen($tkurskbez) > 0) {$textkurs = $pkurskbez;} else {$textkurs = $pkursbez;}
          // Lehrertext
          if (strlen($plehrerk) > 0) {$textlehrer = $plehrerk;}
          else {$textlehrer = cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
          $lehrerid = $plehrer;
          // Raumtext
          $textraum = $praumbez;
          //
          // if ($tbeginn != $pbeginn) {
          //   $vplanart .= " / Verlegt";
          //   $anstatt .= " - ";
          //   if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$anstatt .= $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$anstatt .= date("H:i", $tbeginn)." Uhr";}
          //   if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {$anstatt .= " am ".date("d.m.Y", $tbeginn);}
          // }
          // if ($tkurs != $pkurs) {
          //   if (strlen($tkurskbez) > 0) {$anstatt .= " - ".$tkurskbez;} else {$anstatt .= " - ".$tkursbez;}
          // }
          // if ($tlehrer != $plehrer) {
          //   if (strlen($tlehrerk) > 0) {$anstatt .= " - ".$tlehrerk;}
          //   else {$anstatt .= " - ".cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
          // }
          // if ($traum != $praum) {
          //   $anstatt .= " - ".$traumbez;
          // }
          // if (strlen($anstatt) > 0) {$anstatt = "nun ".substr($anstatt, 3);}
        }

        //if (!in_array($lehrerid, $LIDS)) {
          $PE = array();
          $PE[0] = $textlehrer;
          $PE[1] = $textstunde;
          $PE[2] = $textkurs;
          $PE[3] = $textraum;
          $PE[4] = $vplanart;
          $PE[5] = $vplanbem;
          $PE[6] = $anstatt;
          $PE[7] = 'e';
          array_push($PLAN, $PE);
        //}
      }
    }
    $sql->close();
  }
  else if (substr($art,0,1) == 's') {
    // Vertretungen ausgeben
    if ($art == 's') {
      $sql = "SELECT * FROM (SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND vplanart != 'e') AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("ii", $beginn, $ende);
    }
    else if ($art == 'sv') {
      $sql1 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql1 .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql1 .= "WHERE vplananzeigen = '1' AND tbeginn >= ? AND tende <= ? AND vplanart != 'e' AND unterricht.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)";
      $sql2 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, uk.tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, uk.tbeginn AS tbeginn, uk.tende, uk.tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, uk.traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, uk.vplanart, AES_DECRYPT(uk.vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql2 .= " FROM unterrichtkonflikt AS uk LEFT JOIN unterricht ON uk.altid = unterricht.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON uk.tkurs = tkurse.id LEFT JOIN raeume AS traeume ON uk.traum = traeume.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON uk.tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON uk.tlehrer = tpersonen.id LEFT JOIN kurseklassen ON uk.tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql2 .= "WHERE uk.vplananzeigen = '1' AND uk.tbeginn >= ? AND uk.tende <= ? AND uk.vplanart != 'e'";
      $sql = $dbs->prepare("SELECT * FROM (($sql1) UNION ($sql2)) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC");
      $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    }
    if ($sql->execute()) {
      $sql->bind_result($pkurs, $pkursbez, $pkurskbez, $pbeginn, $pende, $plehrer, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praum, $praumbez, $tkurs, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrer, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traum, $traumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        $anstatt = "";
        $vplanart = "";
        // Stundentext
        if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$textstunde = date("H:i", $tbeginn)." Uhr";}
        // Kurstext
        if (strlen($tkurskbez) > 0) {$textkurs = $tkurskbez;} else {$textkurs = $tkursbez;}
        // Lehrertext
        if (strlen($tlehrerk) > 0) {$textlehrer = $tlehrerk;}
        else {$textlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        // Raumtext
        $textraum = $traumbez;

        if ($pbeginn !== null) {
          if ($tbeginn != $pbeginn) {
            $vplanart .= " / Verlegung";
            $anstatt .= " - ";
            if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$anstatt .= $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$anstatt .= date("H:i", $pbeginn)." Uhr";}
            if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {$anstatt .= " am ".date("d.m.Y", $pbeginn);}
          }
          if ($tkurs != $pkurs) {
            $vplanart .= " / Fach geändert";
            if (strlen($pkurskbez) > 0) {$anstatt .= " - ".$pkurskbez;} else {$anstatt .= " - ".$pkursbez;}
          }
          if ($tlehrer != $plehrer) {
            $vplanart .= " / Vertretung";
            if (strlen($plehrerk) > 0) {$anstatt .= " - ".$plehrerk;}
            else {$anstatt .= " - ".cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
          }
          if ($traum != $praum) {
            $vplanart .= " / Raumänderung";
            $anstatt .= " - ".$praumbez;
          }
        }
        else {
          $vplanart .= " / Zusatzstunde";
        }

        if (strlen($kbez) == 0) {$kbez = $sbez;}
        if (strlen($anstatt) > 0) {$anstatt = "anstatt ".substr($anstatt, 3);}
        if (strlen($vplanart) > 0) {$vplanart = substr($vplanart, 3);}

        $P = array();
        $P[0] = $kbez;
        $P[1] = $textstunde;
        $P[2] = $textkurs;
        $P[3] = $textlehrer;
        $P[4] = $textraum;
        $P[5] = $vplanart;
        $P[6] = $vplanbem;
        $P[7] = $anstatt;
        $P[8] = 'a';
        array_push($PLAN, $P);
      }
    }
    $sql->close();

    // Entfall ausgeben
    if ($art == 's') {
      $sql = "SELECT * FROM (SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql .= "WHERE vplananzeigen = '1' AND vplanart = 'e' AND tbeginn >= ? AND tende <= ?) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("ii", $beginn, $ende);
    }
    else if ($art == 'sv') {
      $sql1 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql1 .= " FROM unterricht LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON tlehrer = tpersonen.id LEFT JOIN kurseklassen ON tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql1 .= "WHERE vplananzeigen = '1' AND vplanart = 'e' AND tbeginn >= ? AND tende <= ? AND unterricht.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)";
      $sql2 = "SELECT pkurs, AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL') AS pkursbez, AES_DECRYPT(pkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS pkurskurzbez, pbeginn, pende, plehrer, AES_DECRYPT(plehrert.kuerzel, '$CMS_SCHLUESSEL') AS plehrerk, AES_DECRYPT(ppersonen.vorname, '$CMS_SCHLUESSEL') AS pvor, AES_DECRYPT(ppersonen.nachname, '$CMS_SCHLUESSEL') AS pnach, AES_DECRYPT(ppersonen.titel, '$CMS_SCHLUESSEL') AS ptit, praum, AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL') AS praumbez, uk.tkurs, AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL') AS tkursbez, AES_DECRYPT(tkurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS tkurskurzbez, uk.tbeginn AS tbeginn, uk.tende, uk.tlehrer, AES_DECRYPT(tlehrert.kuerzel, '$CMS_SCHLUESSEL') AS tlehrerk, AES_DECRYPT(tpersonen.vorname, '$CMS_SCHLUESSEL') AS tvor, AES_DECRYPT(tpersonen.nachname, '$CMS_SCHLUESSEL') AS tnach, AES_DECRYPT(tpersonen.titel, '$CMS_SCHLUESSEL') AS ttit, uk.traum, AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL') AS traumbez, uk.vplanart, AES_DECRYPT(uk.vplanbemerkung, '$CMS_SCHLUESSEL'), reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez";
      $sql2 .= " FROM unterrichtkonflikt AS uk LEFT JOIN unterricht ON uk.altid = unterricht.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN kurse AS tkurse ON uk.tkurs = tkurse.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN raeume AS traeume ON uk.traum = traeume.id LEFT JOIN lehrer AS plehrert ON plehrer = plehrert.id LEFT JOIN lehrer AS tlehrert ON uk.tlehrer = tlehrert.id LEFT JOIN personen AS ppersonen ON plehrer = ppersonen.id LEFT JOIN personen AS tpersonen ON uk.tlehrer = tpersonen.id LEFT JOIN kurseklassen ON uk.tkurs = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id LEFT JOIN stufen ON tkurse.stufe = stufen.id ";
      $sql2 .= "WHERE uk.vplananzeigen = '1' AND uk.vplanart = 'e' AND uk.tbeginn >= ? AND uk.tende <= ?";
      $sql = $dbs->prepare("SELECT * FROM (($sql1) UNION ($sql2)) AS x ORDER BY reihenfolge ASC, klassenbez ASC, tbeginn ASC");
      $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    }

    if ($sql->execute()) {
      $sql->bind_result($pkurs, $pkursbez, $pkurskbez, $pbeginn, $pende, $plehrer, $plehrerk, $plehrervor, $plehrernach, $plehrertit, $praum, $praumbez, $tkurs, $tkursbez, $tkurskbez, $tbeginn, $tende, $tlehrer, $tlehrerk, $tlehrervor, $tlehrernach, $tlehrertit, $traum, $traumbez, $vplanart, $vplanbem, $reihenfolge, $kbez, $sbez);
      while ($sql->fetch()) {
        $anstatt = "";
        $vplanart = "Entfall";
        // Stundentext
        if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$textstunde = $SCHULSTUNDEN[date("H:i", $tbeginn)];} else {$textstunde = date("H:i", $tbeginn)." Uhr";}
        // Kurstext
        if (strlen($tkurskbez) > 0) {$textkurs = $tkurskbez;} else {$textkurs = $tkursbez;}
        // Lehrertext
        if (strlen($tlehrerk) > 0) {$textlehrer = $tlehrerk;}
        else {$textlehrer = cms_generiere_anzeigename($tlehrervor, $tlehrernach, $tlehrertit);}
        // Raumtext
        $textraum = $traumbez;

        if ($pbeginn !== null) {
          // if ($tbeginn != $pbeginn) {
          //   $vplanart .= " / Verlegt";
          //   $anstatt .= " - ";
          //   if (isset($SCHULSTUNDEN[date("H:i", $pbeginn)])) {$anstatt .= $SCHULSTUNDEN[date("H:i", $pbeginn)];} else {$anstatt .= date("H:i", $pbeginn)." Uhr";}
          //   if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {$anstatt .= " am ".date("d.m.Y", $pbeginn);}
          // }
          // if ($tkurs != $pkurs) {
          //   if (strlen($pkurskbez) > 0) {$anstatt .= " - ".$pkurskbez;} else {$anstatt .= " - ".$pkursbez;}
          // }
          // if ($tlehrer != $plehrer) {
          //   if (strlen($plehrerk) > 0) {$anstatt .= " - ".$plehrerk;}
          //   else {$anstatt .= " - ".cms_generiere_anzeigename($plehrervor, $plehrernach, $plehrertit);}
          // }
          // if ($traum != $praum) {
          //   $anstatt .= " - ".$praumbez;
          // }
        }
        if (strlen($kbez) == 0) {$kbez = $sbez;}
        if (strlen($anstatt) > 0) {$anstatt = "anstatt ".substr($anstatt, 3);}
        $PE = array();
        $PE[0] = $kbez;
        $PE[1] = $textstunde;
        $PE[2] = $textkurs;
        $PE[3] = $textlehrer;
        $PE[4] = $textraum;
        $PE[5] = $vplanart;
        $PE[6] = $vplanbem;
        $PE[7] = $anstatt;
        $PE[8] = 'e';
        array_push($PLAN, $PE);
      }
    }
    $sql->close();
  }

  usort($PLAN, function($a, $b) {
    if ($a[0] == $b[0])  {
      return ($a[1]<$b[1])?-1:1;
    }
    return ($a[0]<$b[0])?-1:1;
  });

  $akt = null;
  $markierung = 1;
  if (substr($art,0,1) == 'l') {$felder = 7;} else {$felder = 8;}
  foreach ($PLAN AS $P) {
    if ($P[0] != $akt) {$markierung = ($markierung + 1) % 2; $akt = $P[0];}

    $code .= "<tr class=\"cms_markierte_liste_$markierung";
    if ($P[$felder] == 'e') {$code .= " cms_vplanliste_entfall\">";}
    else {$code .= " cms_vplanliste_neu\">";}
    for ($f = 0; $f < $felder; $f++) {
      $code .= "<td>".$P[$f]."</td>";
    }
    $code .= "</tr>";
  }

  if (strlen($code) > 0) {
    $code = "<table class=\"cms_liste\">".$code."</table>";
  }
  else {
    $code = "<p class=\"cms_notiz\">Keine Vertretungen</p>";
  }

  $vtext = "";

  if (($art != 'lv') && ($art != 'sv')) {
    // Vertretungstext
    $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = 's' AND zeit = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $beginn);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $text);
      if ($sql->fetch()) {
        if (strlen($text) > 0) {
          if (substr($text,0,2) != "<p") {$vtext .= '<p>'.$text.'</p>';}
          else {$vtext .= $text;}
        }
      }
    }
    $sql->close();

    // Vertretungstext
    if ($art == 'l') {
      $sql = "SELECT COUNT(*), AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE art = 'l' AND zeit = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i", $beginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $text);
        if ($sql->fetch()) {
          if (strlen($text) > 0) {
            if (substr($text,0,2) != "<p") {$vtext .= '<p>'.$text.'</p>';}
            else {$vtext .= $text;}
          }
        }
      }
      $sql->close();
    }

    if (strlen($vtext) == 0) {$vtext .= '<p>-</p>';}
    $code = cms_meldung('info', '<h4>Tagesinformationen</h4>'.$vtext).$code;

    if ($art == 'l') {
      $code .= cms_vplan_gruende_ausgeben($dbs, $dbl, $beginn);
    }
    $code = "<h2>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('m', $beginn))." ".date('Y', $beginn).$RYTHMUS."</h2>".$code;
  }

  return $code;
}



function cms_vplan_gruende_ausgeben($dbs, $dbl, $zeit) {
  global $CMS_SCHLUESSELL, $CMS_SCHLUESSEL;
  $hb = mktime(0,0,0,date('m', $zeit), date('d', $zeit), date('Y', $zeit));;
  $he = mktime(0,0,0,date('m', $hb), date('d', $hb)+1, date('Y', $hb))-1;
  $AUSPLANUNGENL = array();
  $LIDS = array();
  $sql = "SELECT id, lehrer, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanunglehrer WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENL[$eid]['aid'] = $aid;
      $AUSPLANUNGENL[$eid]['von'] = $von;
      $AUSPLANUNGENL[$eid]['bis'] = $bis;
      $AUSPLANUNGENL[$eid]['grund'] = $grund;
      $AUSPLANUNGENL[$eid]['zusatz'] = $zusatz;
      array_push($LIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENR = array();
  $RIDS = array();
  $sql = "SELECT id, raum, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungraeume WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENR[$eid]['aid'] = $aid;
      $AUSPLANUNGENR[$eid]['von'] = $von;
      $AUSPLANUNGENR[$eid]['bis'] = $bis;
      $AUSPLANUNGENR[$eid]['grund'] = $grund;
      $AUSPLANUNGENR[$eid]['zusatz'] = $zusatz;
      array_push($RIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENK = array();
  $KIDS = array();
  $sql = "SELECT id, klasse, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungklassen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENK[$eid]['aid'] = $aid;
      $AUSPLANUNGENK[$eid]['von'] = $von;
      $AUSPLANUNGENK[$eid]['bis'] = $bis;
      $AUSPLANUNGENK[$eid]['grund'] = $grund;
      $AUSPLANUNGENK[$eid]['zusatz'] = $zusatz;
      array_push($KIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENS = array();
  $SIDS = array();
  $sql = "SELECT id, stufe, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungstufen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENS[$eid]['aid'] = $aid;
      $AUSPLANUNGENS[$eid]['von'] = $von;
      $AUSPLANUNGENS[$eid]['bis'] = $bis;
      $AUSPLANUNGENS[$eid]['grund'] = $grund;
      $AUSPLANUNGENS[$eid]['zusatz'] = $zusatz;
      array_push($SIDS, $eid);
    }
  }
  $sql->close();

  // Aktuellen Zeitraum laden
  $ZEITRAUM = null;
  $sql = "SELECT id FROM zeitraeume WHERE beginn <= ? AND ende >= ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM);
    $sql->fetch();
  }
  $sql->close();

  // Schulstundeninformationen laden
  $SCHULSTUNDENBEGINN = array();
  $SCHULSTUNDENENDE = array();
  if ($ZEITRAUM !== null) {
    $sql = "SELECT beginns, beginnm, endes, endem, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schulstunden WHERE zeitraum = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($stdbs, $stdbm, $stdes, $stdem, $stdbez);
      while ($sql->fetch()) {
        $SCHULSTUNDENBEGINN[cms_fuehrendenull($stdbs).":".cms_fuehrendenull($stdbm)] = $stdbez;
        $SCHULSTUNDENENDE[cms_fuehrendenull($stdes).":".cms_fuehrendenull($stdem)] = $stdbez;
      }
    }
    $sql->close();
  }

  $LEHRER = array();
  // Lehrerinformationen laden
  if (count($LIDS) > 0) {
    $vorgabe = implode(',', $LIDS);
    $sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.id IN ($vorgabe)) AS x ORDER BY kuerzel, vorname, nachname, titel";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $vorname, $nachname, $titel, $kuerzel);
      while ($sql->fetch()) {
        if (strlen($kuerzel) > 0) {$bez = $kuerzel;}
        else {$bez = cms_generiere_anzeigename($vorname, $nachname, $titel);}
        $neu = $bez." (".$AUSPLANUNGENL[$eid]['grund'];
        if (strlen($AUSPLANUNGENL[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENL[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENL[$eid]['von'];
        $bis = $AUSPLANUNGENL[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($LEHRER, $neu);
      }
    }
    $sql->close();
  }

  $RAEUME = array();
  // Rauminformationen laden
  if (count($RIDS) > 0) {
    $vorgabe = implode(',', $RIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENR[$eid]['grund'];
        if (strlen($AUSPLANUNGENR[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENR[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENR[$eid]['von'];
        $bis = $AUSPLANUNGENR[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($RAEUME, $neu);
      }
    }
    $sql->close();
  }

  $STUFEN = array();
  // Rauminformationen laden
  if (count($SIDS) > 0) {
    $vorgabe = implode(',', $SIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM stufen WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENS[$eid]['grund'];
        if (strlen($AUSPLANUNGENS[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENS[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENS[$eid]['von'];
        $bis = $AUSPLANUNGENS[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($STUFEN, $neu);
      }
    }
    $sql->close();
  }

  $KLASSEN = array();
  // Klasseninformationen laden
  if (count($KIDS) > 0) {
    $vorgabe = implode(',', $KIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM klassen WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENK[$eid]['grund'];
        if (strlen($AUSPLANUNGENK[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENK[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENK[$eid]['von'];
        $bis = $AUSPLANUNGENK[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($KLASSEN, $neu);
      }
    }
    $sql->close();
  }

  $auscode = "";
  if (count($LEHRER) > 0) {$auscode .= "<p><b>Lehrer:</b> ".implode(", ", $LEHRER)."</p>";}
  if (count($RAEUME) > 0) {$auscode .= "<p><b>Räume:</b> ".implode(", ", $RAEUME)."</p>";}
  if (count($KLASSEN) > 0) {$auscode .= "<p><b>Klassen:</b> ".implode(", ", $KLASSEN)."</p>";}
  if (count($STUFEN) > 0) {$auscode .= "<p><b>Stufen:</b> ".implode(", ", $STUFEN)."</p>";}

  if (strlen($auscode) > 0) {
    return cms_meldung('vplan', $auscode);
  }
  else {return "";}
}
?>
