<?php
function cms_vertretungsplan_komplettansicht_heute($dbs, $art, $nachladen = 'a') {
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

function cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, $art, $nachladen = 'a') {
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
    $code = "<p class=\"cms_notiz\">Keine Vertretungen.</p>";
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
          if (substr($text,0,2) != "<p") {$vtext .= '<p>'.cms_ausgabe_editor($text).'</p>';}
          else {$vtext .= cms_ausgabe_editor($text);}
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
            if (substr($text,0,2) != "<p") {$vtext .= '<p>'.cms_ausgabe_editor($text).'</p>';}
            else {$vtext .= cms_ausgabe_editor($text);}
          }
        }
      }
      $sql->close();
    }

    if (strlen($vtext) == 0) {$vtext .= '<p>-</p>';}
    $code = cms_meldung('info', '<h4>Tagesinformationen</h4>'.$vtext).$code;

    if ($art == 'l') {
      if ($CMS_IMLN) {
        if (($nachladen == 'a') || ($nachladen == 'k')) {
          $code .= "<p><input type=\"hidden\" name=\"cms_lvplan_zeit$id\" id=\"cms_lvplan_zeit$id\" value=\"$beginn\"></p>";
          $code .= cms_generiere_nachladen('cms_vplan_gruende_'.$id, 'cms_vplan_gruende(\''.$id.'\', \''.$beginn.'\', \''.$nachladen.'\');');
        }
      }
      else {
        $code .= cms_meldung_geschuetzer_inhalt ();
      }
    }
    $code = "<h2>".cms_tagnamekomplett(date('N', $beginn)).", den ".date('d', $beginn).". ".cms_monatsnamekomplett(date('m', $beginn))." ".date('Y', $beginn).$RYTHMUS."</h2>".$code;
  }

  return $code;
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


function cms_vertretungsplan_persoenlich($dbs) {
  global $CMS_BENUTZERART, $CMS_BENUTZERID;
  if (($CMS_BENUTZERART != 's') && ($CMS_BENUTZERART != 'l')) {return "";}
  // Diesen Schultag berechnen
  $jetzt = mktime(0,0,0,date('m'), date('d'), date('Y'));
  $start = $jetzt;
  $sql = "SELECT MIN(tbeginn) FROM unterricht WHERE tbeginn > ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $hbeginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $hende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  // Schultag danach berechnen
  $sql->bind_param("i", $hende);
  if ($sql->execute()) {
    $sql->bind_result($start);
    $sql->fetch();
  }
  $sql->close();

  $mbeginn = mktime(0,0,0,date('m', $start), date('d', $start), date('Y', $start));
  $mende = mktime(0,0,0,date('m', $start), date('d', $start)+1, date('Y', $start))-1;

  $code = "";

  $code .= "<ul class=\"cms_reitermenue\">";
    $code .= "<li><span id=\"cms_reiter_meintag_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('meintag', 0,1)\">".cms_tagname(date('N', $hbeginn))." ".date("d.m", $hbeginn)."</span></li> ";
    $code .= "<li><span id=\"cms_reiter_meintag_1\" class=\"cms_reiter\" onclick=\"cms_reiter('meintag', 1,1)\">".cms_tagname(date('N', $mbeginn))." ".date("d.m", $mbeginn)."</span></li> ";
  $code .= "</ul>";

  $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_meintag_0\" style=\"display: block;\">";
    $code .= "<div class=\"cms_reitermenue_i\">";
    $code .= cms_vertretungsplan_tagesansicht($dbs, $hbeginn, $hende);
    $code .= "<p><a class=\"cms_button\" href=\"javascript:cms_stundenplan_vorbereiten('m', '$CMS_BENUTZERID', '-')\">Stundenplan</a></p>";
    $code .= "</div>";
  $code .= "</div>";
  $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_meintag_1\">";
    $code .= "<div class=\"cms_reitermenue_i\">";
    $code .= cms_vertretungsplan_tagesansicht($dbs, $mbeginn, $mende);
    $code .= "<p><a class=\"cms_button\" href=\"javascript:cms_stundenplan_vorbereiten('m', '$CMS_BENUTZERID', '-')\">Stundenplan</a></p>";
    $code .= "</div>";
  $code .= "</div>";

  $code .= "<div><div class=\"cms_spalte_3\"><span class=\"cms_stundenplan_stunde\">Regelstunde</span></div><div class=\"cms_spalte_3\"><span class=\"cms_stundenplan_stunde_geaendert\">Geändert</span></div><div class=\"cms_spalte_3\"><span class=\"cms_stundenplan_stunde_ausfall\">Entfall</span></div><div class=\"clear\"></div></div>";

  return $code;
}

function cms_vertretungsplan_tagesansicht($dbs, $beginn, $ende) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERART, $CMS_BENUTZERID;
  $vtext = "";
  // Vertretungstext
  $sql = "SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL'), zeit FROM vplantext WHERE art = 's' AND zeit = ? ORDER BY zeit ASC";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $beginn);
  if ($sql->execute()) {
    $sql->bind_result($inhalt, $zeit);
    if ($sql->fetch()) {
      if (strlen($inhalt) > 0) {
        if (substr($inhalt,0,2) != "<p") {$vtext .= '<p>'.cms_ausgabe_editor($inhalt).'</p>';}
        else {$vtext .= cms_ausgabe_editor($inhalt);}
      }
    }
  }
  $sql->close();

  // Vertretungstext
  if ($CMS_BENUTZERART == 'l') {
    $sql = "SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL'), zeit FROM vplantext WHERE art = 'l' AND zeit = ? ORDER BY zeit ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $beginn);
    if ($sql->execute()) {
      $sql->bind_result($inhalt, $zeit);
      if ($sql->fetch()) {
        if (strlen($inhalt) > 0) {
          if (substr($inhalt,0,2) != "<p") {$vtext .= '<p>'.$inhalt.'</p>';}
          else {$vtext .= $inhalt;}
        }
      }
    }
    $sql->close();
  }

  if (strlen($vtext) == 0) {$vtext = "<p>-</p>";}

  $code = cms_meldung('info', "<h4>Tagesinformationen</h4>".$vtext);

  // Zeitraum laden
  $ZEITRAUM = null;
  $SCHULSTUNDEN = array();
  $PLAN = array();
  $sql = $dbs->prepare("SELECT id FROM zeitraeume WHERE ? BETWEEN beginn AND ende");
  $sql->bind_param("i", $beginn);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM);
    $sql->fetch();
  }
  $sql->close();

  if ($ZEITRAUM !== null) {
    // Schulstunden für den Zeitraum laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
    $sql->bind_param("i", $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
      while ($sql->fetch()) {
        $S = array();
        $S['id'] = $stdid;
        $S['bez'] = $stdbez;
        $S['beginn'] = cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm);
        $S['ende'] = cms_fuehrendenull($stdendes).":".cms_fuehrendenull($stdendem);
        array_push($SCHULSTUNDEN, $S);
      }
    }
    $sql->close();

    // PLAN VORBEREITEN
    $datum = date("d.m.Y", $beginn);
    $PLAN[$datum] = array();
    $PLAN[$datum]['zeitraum'] = $ZEITRAUM;
    $PLAN[$datum]['datum'] = $datum;
    $tagesinfo = cms_erstelle_tagesinfo($SCHULSTUNDEN);
    $PLAN[$datum]['tagbeginn'] = $tagesinfo['beginn'];
    $PLAN[$datum]['tagende'] = $tagesinfo['ende'];
    $PLAN[$datum]['tagdauer'] = $tagesinfo['dauer'];
    $PLAN[$datum]['wochentag'] = cms_tagname(date('N', $beginn))." <span class=\"cms_notiz\">".date('d.m.', $beginn)."</span>";
    $PLAN[$datum]['schulstunden'] = $tagesinfo['schulstunden'];

    // Unterricht laden
    // UNTERRICHT und UNTERRICHTSKONFLIKTLÖSUNGEN laden
    $sql = "SELECT unterricht.id, tkurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), ";
    $sql .= "AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplanart ";
    $sql .= "FROM unterricht LEFT JOIN kurse ON unterricht.tkurs = kurse.id LEFT JOIN raeume ON unterricht.traum = raeume.id ";
    $sql .= "LEFT JOIN lehrer ON unterricht.tlehrer = lehrer.id LEFT JOIN personen ON unterricht.tlehrer = personen.id ";
    $sql .= "WHERE tbeginn >= ? AND tende <= ?";
    if ($CMS_BENUTZERART == 'l') {$sql .= " AND tlehrer = ?";}
    if ($CMS_BENUTZERART == 's') {$sql .= " AND tkurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?)";}
    //echo "SELECT * FROM ($sql) AS x GROUP BY id ORDER BY tbeginn, kurskurzbez, kursbez<br>".$beginn."<br>".$ende."<br>".$CMS_BENUTZERID;
    $sql = $dbs->prepare("SELECT * FROM ($sql) AS x GROUP BY id ORDER BY tbeginn, kurskurzbez, kursbez");
    $sql->bind_param("iii", $beginn, $ende, $CMS_BENUTZERID);
    if ($sql->execute()) {
      $sql->bind_result($uid, $kursid, $kursbez, $kurskurz, $ubeginn, $uende, $lehrerid, $lehrerkurz, $lehrervor, $lehrernach, $lehrertit, $raumid, $raumbez, $vplanart);
      while ($sql->fetch()) {
        $tag = date("d.m.Y", $ubeginn);
        if (isset($PLAN[$tag])) {
          $zeit = date("H:i", $ubeginn);
          if (isset($PLAN[$tag]['schulstunden'][$zeit]['stunden'])) {
            $u = array();
            $u['uid'] = $uid;
            $u['beginn'] = $ubeginn;
            $u['kursid'] = $kursid;
            if (strlen($kurskurz) > 0) {$u['kurs'] = $kurskurz;}
            else {$u['kurs'] = $kursbez;}
            $u['lehrerid'] = $lehrerid;
            if (strlen($lehrerkurz) > 0) {$u['lehrer'] = $lehrerkurz;}
            else {$u['lehrer'] = cms_generiere_anzeigename($lehrervor, $lehrernach, $lehrertit);}
            $u['raumid'] = $raumid;
            $u['raum'] = $raumbez;
            $u['vplanart'] = $vplanart;
            array_push($PLAN[$tag]['schulstunden'][$zeit]['stunden'], $u);
          }
        }
      }
    }
    $sql->close();
    $code .= cms_vplan_ausgeben($PLAN);
  }
  return $code;
}


function cms_erstelle_tagesinfo($stds) {
  $tagesinfo = array();
  $tagesinfo['schulstunden'] = array();
  $tagesinfo['beginn'] = "00:00";
  $tagesinfo['ende'] = "00:00";
  $tagesinfo['dauer'] = "0";
  if (is_array($stds)) {
    $tb = explode(":", $stds[0]['beginn']);
    $te = explode(":", $stds[count($stds)-1]['ende']);
    $tagesinfo['beginn'] = $stds[0]['beginn'];
    $tagesinfo['ende'] = $stds[count($stds)-1]['ende'];
    $tagesinfo['dauer'] = ($te[0]*60+$te[1])-($tb[0]*60+$tb[1]);
    foreach ($stds AS $s) {
      $tagesinfo['schulstunden'][$s['beginn']] = array();
      $tagesinfo['schulstunden'][$s['beginn']]['beginn'] = $s['beginn'];
      $tagesinfo['schulstunden'][$s['beginn']]['ende'] = $s['ende'];
      $b = explode(":", $s['beginn']);
      $e = explode(":", $s['ende']);
      $tagesinfo['schulstunden'][$s['beginn']]['dauer'] = ($e[0]*60+$e[1])-($b[0]*60+$b[1]);
      $tagesinfo['schulstunden'][$s['beginn']]['bez'] = $s['bez'];
      $tagesinfo['schulstunden'][$s['beginn']]['beginny'] = ($b[0]*60+$b[1])-($tb[0]*60+$tb[1]);
      $tagesinfo['schulstunden'][$s['beginn']]['endey'] = ($e[0]*60+$e[1])-($tb[0]*60+$tb[1]);
      $tagesinfo['schulstunden'][$s['beginn']]['stunden'] = array();
    }
  }
  return $tagesinfo;
}


function cms_vplan_ausgeben($PLAN) {
  $code = "";
  $minpp = 1;
  $yakt = 20;
  $wochentag = null;
  $wochenwechsel = false;

  $aktzeitraum = null;
  foreach ($PLAN as $t) {
    // Neuer Zeitraum beginnt, erstelle neuen Plan
    if ($aktzeitraum != $t['zeitraum']) {
      // Schließe alten Zeitplan ab
      if ($aktzeitraum !== null) {
        $code .= "</div>";
      }
      // Beginne neuen Zeitplan
      $aktzeitraum = $t['zeitraum'];
      $planhoehe = $t['tagdauer']*$minpp+$yakt;
      $planspalten = 1;
      foreach ($PLAN as $s) {if ($s['zeitraum'] == $aktzeitraum) {$planspalten ++;}}
      $planspaltenbreite = 100/$planspalten;

      $code .= "<div class=\"cms_stundenplan_box\" style=\"height: $planhoehe"."px\">";
      // Zeitlinien ausgeben
      foreach ($t['schulstunden'] as $s) {
        $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".($s['beginny']*$minpp+$yakt)."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".$s['beginn']."</span></span>";
        $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".($s['beginny']*$minpp+$yakt)."px;line-height: ".($s['dauer']*$minpp)."px;\">".$s['bez']."</span>";
        $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".($s['endey']*$minpp+$yakt)."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".$s['ende']."</span></span>";
      }
      // Spalten erstellen -- Platzhalter für die Stunden
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $planspaltenbreite%;\">";
      $code .= "</div>";
    }
    $tagdatum = explode(".",$t['datum']);
    $tagzeit = mktime(0,0,0,$tagdatum[1], $tagdatum[0], $tagdatum[2]);
    $neuerwochentag = date('N', $tagzeit);
    if ($neuerwochentag < $wochentag) {$wochenwechsel = true;}
    $wochentag = $neuerwochentag;
    // Tag ausgeben
    if ($wochenwechsel) {
      $code .= "<div class=\"cms_stundenplan_spalte cms_stundenplan_spalte_trenner\" style=\"width: $planspaltenbreite%;\">";
      $wochenwechsel = false;
    }
    else {
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $planspaltenbreite%;\">";
    }
      // Schulstunden dieses Tages ausgeben
      $code .= "<span class=\"cms_stundenplan_spaltentitel\">".$t['wochentag']."</span>";
      foreach ($t['schulstunden'] as $s) {
        $code .= "<span class=\"cms_stundenplan_stundenfeld\" style=\"top: ".($s['beginny']*$minpp+$yakt)."px;height: ".($s['dauer']*$minpp)."px;\">";
        foreach ($s['stunden'] AS $std) {
          $code .= cms_vplan_unterricht_ausgeben($std);
        }
        $code .= "</span>";
      }
    $code .= "</div>";
  }
  // Schließe letzten Zeitplan ab
  if ($aktzeitraum !== null) {
    $code .= "</div>";
  }
  if (strlen($code) == 0) {$code .= '<p class="cms_notiz">Für dieses Datum ist kein Planungszeitraum verfügbar.</p>';}
  return $code;
}

function cms_vplan_unterricht_ausgeben($std) {
  $klasse = "";
  if ($std['vplanart'] == 'e') {$klasse = "_ausfall";}
  else if ($std['vplanart'] != '-') {$klasse = "_geaendert";}
  $code = "<span class=\"cms_stundenplan_stunde$klasse\">";
    $code .= $std['kurs']."<br>".$std['lehrer']."<br>".$std['raum'];
  $code .= "</span>";
  return $code;
}

?>
