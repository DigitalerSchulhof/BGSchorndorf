<?php
function cms_stundenplan_erzeugen ($dbs, $zeitraum, $art, $id, $aktion = true) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $fehler = false;
  // Zeitraum laden
  $sql = "SELECT mo, di, mi, do, fr, sa, so FROM zeitraeume WHERE id = $zeitraum";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      $tage = array();
      $taganzahl = 1;
      if ($daten['mo'] == 1) {$tage[1] = true; $taganzahl++;} else {$tage[1] = false;}
      if ($daten['di'] == 1) {$tage[2] = true; $taganzahl++;} else {$tage[2] = false;}
      if ($daten['mi'] == 1) {$tage[3] = true; $taganzahl++;} else {$tage[3] = false;}
      if ($daten['do'] == 1) {$tage[4] = true; $taganzahl++;} else {$tage[4] = false;}
      if ($daten['fr'] == 1) {$tage[5] = true; $taganzahl++;} else {$tage[5] = false;}
      if ($daten['sa'] == 1) {$tage[6] = true; $taganzahl++;} else {$tage[6] = false;}
      if ($daten['so'] == 1) {$tage[7] = true; $taganzahl++;} else {$tage[7] = false;}
    } else {$fehler = true;}
    $anfrage->free();
  } else {$fehler = true;}

  // Schulstunden in diesem Zeitraum
  $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE zeitraum = $zeitraum)";
  $sql .= " AS x ORDER BY bs ASC, bm ASC, es ASC, em ASC, bezeichnung ASC";
  if ($anfrage = $dbs->query($sql)) {
    $stunden = array();
    $stundenzahl = 0;
    while ($daten = $anfrage->fetch_assoc()) {
      $stunden[$stundenzahl]['bez'] = $daten['bezeichnung'];
      $stunden[$stundenzahl]['bs'] = $daten['bs'];
      $stunden[$stundenzahl]['bm'] = $daten['bm'];
      $stunden[$stundenzahl]['es'] = $daten['es'];
      $stunden[$stundenzahl]['em'] = $daten['em'];
      $stunden[$stundenzahl]['id'] = $daten['id'];
      $stundenzahl ++;
    }
    $anfrage->free();
  } else {$fehler = true;}

  $unterricht = array();

  if ($art == 'l') {
    $beschriftung = "Lehrkraft ";
    $sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer JOIN personen ON personen.id = lehrer.id WHERE personen.id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $beschriftung .= cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel'])." (".$daten['kuerzel'].")";
      }
      $anfrage->free();
    } else {$fehler = true;}

    // stunden
    $sqlvor = "(SELECT raum, kurs, tag, stunde FROM stunden WHERE zeitraum = $zeitraum AND lehrkraft = $id) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, kurs, tag, stunde FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    $sql = "SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, raumbez, raum, kurs, tag, stunde FROM $sqlvor JOIN kurse ON stdraum.kurs = kurse.id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $zwischen = array();
        $zwischen['raum'] = $daten['raum'];
        $zwischen['raumbez'] = $daten['raumbez'];
        $zwischen['kurs'] = $daten['kurs'];
        $zwischen['kursbez'] = $daten['kursbez'];
        if (!isset($unterricht[$daten['tag']][$daten['stunde']])) {$unterricht[$daten['tag']][$daten['stunde']] = array();}
        array_push($unterricht[$daten['tag']][$daten['stunde']], $zwischen);
      }
      $anfrage->free();
    } else {$fehler = true;}
  }
  else if ($art == 's') {
    $beschriftung = "Schüler ";
    $sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE personen.id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $beschriftung .= cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
      }
      $anfrage->free();
    } else {$fehler = true;}

    // stunden
    $sqlvor = "(SELECT raum, kurs, tag, stunde, lehrkraft FROM stunden WHERE zeitraum = $zeitraum AND kurs IN (SELECT kurs FROM kursschueler WHERE schueler = $id)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, kurs, tag, stunde, lehrkraft FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    $sqlvor = "(SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, raumbez, raum, kurs, tag, stunde, lehrkraft FROM $sqlvor JOIN kurse ON stdraum.kurs = kurse.id) AS stdraumk";
    $sql = "SELECT kursbez, raumbez, raum, kurs, tag, stunde, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM $sqlvor JOIN lehrer ON stdraumk.lehrkraft = lehrer.id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $zwischen = array();
        $zwischen['raum'] = $daten['raum'];
        $zwischen['raumbez'] = $daten['raumbez'];
        $zwischen['kurs'] = $daten['kurs'];
        $zwischen['kursbez'] = $daten['kursbez']."<br>".$daten['kuerzel'];
        if (!isset($unterricht[$daten['tag']][$daten['stunde']])) {$unterricht[$daten['tag']][$daten['stunde']] = array();}
        array_push($unterricht[$daten['tag']][$daten['stunde']], $zwischen);
      }
      $anfrage->free();
    } else {$fehler = true;}
  }
  else if ($art == 'r') {
    $beschriftung = "Raumplan ";
    $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $beschriftung .= $daten['bezeichnung'];
      }
      $anfrage->free();
    } else {$fehler = true;}

    // stunden
    $sqlvor = "(SELECT lehrkraft, kurs, tag, stunde FROM stunden WHERE zeitraum = $zeitraum AND raum = $id) AS stunden";
    // stunden mit lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, lehrkraft, kurs, tag, stunde FROM $sqlvor JOIN lehrer ON stunden.lehrkraft = lehrer.id) AS stdlehrer";
    $sql = "SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, lehrerbez, lehrkraft, kurs, tag, stunde FROM $sqlvor JOIN kurse ON stdlehrer.kurs = kurse.id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $zwischen = array();
        $zwischen['lehrer'] = $daten['lehrkraft'];
        $zwischen['lehrerbez'] = $daten['lehrerbez'];
        $zwischen['kurs'] = $daten['kurs'];
        $zwischen['kursbez'] = $daten['kursbez'];
        if (!isset($unterricht[$daten['tag']][$daten['stunde']])) {$unterricht[$daten['tag']][$daten['stunde']] = array();}
        array_push($unterricht[$daten['tag']][$daten['stunde']], $zwischen);
      }
      $anfrage->free();
    } else {$fehler = true;}
  }
  else if ($art == 'k') {
    $beschriftung = "Klasse ";
    $sql = "SELECT AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE klassen.id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $beschriftung .= $daten['stufe'].$daten['bezeichnung'];
      }
      $anfrage->free();
    } else {$fehler = true;}

    // stunden
    $sqlvor = "(SELECT raum, lehrkraft, tag, stunde, kurs FROM stunden WHERE zeitraum = $zeitraum AND kurs IN (SELECT kurs FROM kursklassen WHERE klasse = $id)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    // stunden mit raum und lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN lehrer ON stdraum.lehrkraft = lehrer.id) AS stdrl";
    $sql = "SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kurs, lehrerbez, raumbez, raum, lehrkraft, tag, stunde FROM $sqlvor JOIN kurse ON stdrl.kurs = kurse.id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $zwischen = array();
        $zwischen['lehrer'] = $daten['lehrkraft'];
        $zwischen['lehrerbez'] = $daten['kurs'];
        $zwischen['raum'] = $daten['raum'];
        $zwischen['raumbez'] = $daten['lehrerbez'].'<br>'.$daten['raumbez'];
        if (!isset($unterricht[$daten['tag']][$daten['stunde']])) {$unterricht[$daten['tag']][$daten['stunde']] = array();}
        array_push($unterricht[$daten['tag']][$daten['stunde']], $zwischen);
      }
      $anfrage->free();
    } else {$fehler = true;}
  }
  else if ($art == 'stufe') {
    $beschriftung = "Klassenstufe ";
    $sql = "SELECT AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassenstufen WHERE id = $id";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $beschriftung .= $daten['stufe'];
      }
      $anfrage->free();
    } else {$fehler = true;}

    // stunden
    $sqlvor = "(SELECT raum, lehrkraft, tag, stunde, kurs FROM stunden WHERE zeitraum = $zeitraum AND kurs IN (SELECT kurs FROM kursklassen JOIN klassen ON kursklassen.klasse = klassen.id WHERE klassenstufe = $id)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    // stunden mit raum und lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN lehrer ON stdraum.lehrkraft = lehrer.id) AS stdrl";
    $sql = "SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kurs, lehrerbez, raumbez, raum, lehrkraft, tag, stunde FROM $sqlvor JOIN kurse ON stdrl.kurs = kurse.id";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $zwischen = array();
        $zwischen['lehrer'] = $daten['lehrkraft'];
        $zwischen['lehrerbez'] = $daten['kurs'];
        $zwischen['raum'] = $daten['raum'];
        $zwischen['raumbez'] = $daten['lehrerbez'].'<br>'.$daten['raumbez'];
        if (!isset($unterricht[$daten['tag']][$daten['stunde']])) {$unterricht[$daten['tag']][$daten['stunde']] = array();}
        array_push($unterricht[$daten['tag']][$daten['stunde']], $zwischen);
      }
      $anfrage->free();
    } else {$fehler = true;}
  }
  else {$fehler = true;}

  if ($fehler) {return false;}
  else {
    $tagbreite = 100/$taganzahl;

    $code .= "<table class=\"cms_stundenplan\">";
      $code .= "<tr><th colspan=\"$taganzahl\" class=\"cms_stundenplantitel\">$beschriftung</th></tr>";
      $code .= "<tr>";
      $code .= "<th style=\"width: $tagbreite%;\"></th>";
      for ($t=1; $t<=7; $t++) {
        if ($tage[$t]) {
          $code .= "<th style=\"width: $tagbreite%;\">".cms_tagname($t)."</th>";
        }
      }
      $code .= "</tr>";
      for ($s=0; $s<count($stunden); $s++) {
        $code .= "<tr>";
        if ($aktion) {$code .= "<th>".$stunden[$s]['bez']."</th>";}
        else {$code .= "<th>".cms_zeitzelle ($stunden[$s])."</th>";}
        for ($t=1; $t<=7; $t++) {
          if ($tage[$t]) {
            if ($aktion) {
              $zusatz = "cms_stundenplan_$t"."_$s\" onmouseover=\"cms_stundenplan_stunde_hervorheben('$t', '$s')\" onmouseout=\"cms_stundenplan_stunde_normalisieren('$t', '$s')\" onclick=\"cms_stundenplan_stunde_auswaehlen('$t', '$s')\"";
            }
            else {
              $zusatz = "cms_stundenplan_$t"."_$s\"";
            }

            if (isset($unterricht[$t][$s])) {
              $code .= "<td class=\"cms_stundenplan_stunde $zusatz><div class=\"cms_stundenplan_stunden_teilbox\">";
              foreach ($unterricht[$t][$s] AS $u) {
                $code .= cms_stunde_eintragen($u);
              }
              $code .= "</div></td>";
            }
            else {
              $code .= "<td class=\"cms_stundenplan_stunde $zusatz></td>";
            }
          }
        }
        $code .= "</tr>";
      }
    $code .= "</table>";
    return $code;
  }
}

function cms_zeitzelle ($stunde) {
  $bez = $stunde['bez'];
  $bs = $stunde['bs'];
  $bm = $stunde['bm'];
  $es = $stunde['es'];
  $em = $stunde['em'];
  $code = "<span class=\"cms_stundenplan_stunde_zeit\">";
    $code .= "<span class=\"cms_stundenplan_stunde_bez\">".$bez."</span>";
    $code .= "<span class=\"cms_stundenplan_stunde_beginn\">".$bs.":".$bm."</span>";
    $code .= "<span class=\"cms_stundenplan_stunde_ende\">".$es.":".$em."</span>";
  $code .= "</span>";
  return $code;
}

function cms_stunde_eintragen($unterricht) {
  $code = "";
  $farbe = false;
  $style = "background:#FFFFFF; color:#000000;";
  if (isset($unterricht['kursbez'])) {
    $code .= "<br>".$unterricht['kursbez'];
    if (!$farbe) {$style = cms_stunde_farbe($unterricht['kursbez']); $farbe = true;}
  }
  if (isset($unterricht['lehrerbez'])) {
    $code .= "<br>".$unterricht['lehrerbez'];
    if (!$farbe) {$style = cms_stunde_farbe($unterricht['lehrerbez']); $farbe = true;}
  }
  if (isset($unterricht['raumbez'])) {
    $code .= "<br>".$unterricht['raumbez'];
    if (!$farbe) {$style = cms_stunde_farbe($unterricht['raumbez']); $farbe = true;}
  }
  return "<span class=\"cms_stundenplan_stunde_teil\" style=\"$style\">".substr($code, 4)."</span>";
}
?>
