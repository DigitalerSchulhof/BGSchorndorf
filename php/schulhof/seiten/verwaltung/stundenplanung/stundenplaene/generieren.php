<?php
function cms_stundenplan_erzeugen ($dbs, $zeitraum, $art, $id, $aktion = true) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $fehler = false;
  // Zeitraum laden
  $sql = $dbs->prepare("SELECT mo, di, mi, do, fr, sa, so FROM zeitraeume WHERE id = ?");
  $sql->bind_param("i", $zeitraum);
  if ($sql->execute()) {
    $sql->bind_result($mo, $di, $mi, $do, $fr, $sa, $so);
    if ($sql->ferch()) {
      $tage = array();
      $taganzahl = 1;
      if ($mo == 1) {$tage[1] = true; $taganzahl++;} else {$tage[1] = false;}
      if ($di == 1) {$tage[2] = true; $taganzahl++;} else {$tage[2] = false;}
      if ($mi == 1) {$tage[3] = true; $taganzahl++;} else {$tage[3] = false;}
      if ($do == 1) {$tage[4] = true; $taganzahl++;} else {$tage[4] = false;}
      if ($fr == 1) {$tage[5] = true; $taganzahl++;} else {$tage[5] = false;}
      if ($sa == 1) {$tage[6] = true; $taganzahl++;} else {$tage[6] = false;}
      if ($so == 1) {$tage[7] = true; $taganzahl++;} else {$tage[7] = false;}
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Schulstunden in diesem Zeitraum
  $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE zeitraum = ?) AS x ORDER BY bs ASC, bm ASC, es ASC, em ASC, bezeichnung ASC");
  $sql->bind_param("i", $zeitraum);
  if ($sql->execute()) {
    $stunden = array();
    $stundenzahl = 0;
    $sql->bind_result($zid, $zbez, $zbs, $zbm, $zes, $zem);
    while ($daten = $anfrage->fetch_assoc()) {
      $stunden[$stundenzahl]['bez'] = $zbez;
      $stunden[$stundenzahl]['bs'] = $zbs;
      $stunden[$stundenzahl]['bm'] = $zbm;
      $stunden[$stundenzahl]['es'] = $zes;
      $stunden[$stundenzahl]['em'] = $zem;
      $stunden[$stundenzahl]['id'] = $zid;
      $stundenzahl ++;
    }
  } else {$fehler = true;}

  $unterricht = array();

  if ($art == 'l') {
    $beschriftung = "Lehrkraft ";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer JOIN personen ON personen.id = lehrer.id WHERE personen.id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($lvor, $lnach, $ltit, $lkur);
      if ($sql->fetch()) {
        $beschriftung .= cms_generiere_anzeigename($lvor, $lnach, $ltit)." ($lkur)";
      }
    } else {$fehler = true;}
    $sql->close();

    // stunden
    $sqlvor = "(SELECT raum, kurs, tag, stunde FROM stunden WHERE zeitraum = ? AND lehrkraft = ?) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, kurs, tag, stunde FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, raumbez, raum, kurs, tag, stunde FROM $sqlvor JOIN kurse ON stdraum.kurs = kurse.id");
    $sql->bind_param("ii", $zeiraum, $id);
    if ($sql->execute()) {
      $sql->bind_result($rbez, $rid, $rkurs, $kursbez, $rtag, $rstunde);
      while ($sql->fetch()) {
        $zwischen = array();
        $zwischen['raum'] = $rid;
        $zwischen['raumbez'] = $rbez;
        $zwischen['kurs'] = $rkurs;
        $zwischen['kursbez'] = $kursbez;
        if (!isset($unterricht[$rtag][$rstunde])) {$unterricht[$rtag][$rstunde] = array();}
        array_push($unterricht[$rtag][$rstunde], $zwischen);
      }
    } else {$fehler = true;}
    $sql->close();
  }
  else if ($art == 's') {
    $beschriftung = "Schüler ";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen WHERE personen.id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($svor, $snach, $stit);
      if ($sql->fetch()) {
        $beschriftung .= cms_generiere_anzeigename($svor, $snach, $stit);
      }
    } else {$fehler = true;}
    $sql->close();

    // stunden
    $sqlvor = "(SELECT raum, kurs, tag, stunde, lehrkraft FROM stunden WHERE zeitraum = ? AND kurs IN (SELECT kurs FROM kursschueler WHERE schueler = ?)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, kurs, tag, stunde, lehrkraft FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    $sqlvor = "(SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, raumbez, raum, kurs, tag, stunde, lehrkraft FROM $sqlvor JOIN kurse ON stdraum.kurs = kurse.id) AS stdraumk";
    $sql = $dbs->prepare("SELECT kursbez, raumbez, raum, kurs, tag, stunde, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM $sqlvor JOIN lehrer ON stdraumk.lehrkraft = lehrer.id");
    $sql->bind_param("ii", $zeitraum, $id);
    if ($sql->execute()) {
      $sql->bind_result($kursbez, $raumbez, $rid, $rkid, $rtag, $rstunde, $rkuerzel);
      while ($sql->fetch()) {
        $zwischen = array();
        $zwischen['raum'] = $rid;
        $zwischen['raumbez'] = $raumbez;
        $zwischen['kurs'] = $rkid;
        $zwischen['kursbez'] = $kursbez."<br>".$rkuerzel;
        if (!isset($unterricht[$rtag][$rstunde])) {$unterricht[$rtag][$rstunde] = array();}
        array_push($unterricht[$rtag][$rstunde], $zwischen);
      }
    } else {$fehler = true;}
    $sql->close();
  }
  else if ($art == 'r') {
    $beschriftung = "Raumplan ";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($beschriftung);
      $sql->fetch();
    } else {$fehler = true;}
    $sql->close();

    // stunden
    $sqlvor = "(SELECT lehrkraft, kurs, tag, stunde FROM stunden WHERE zeitraum = ? AND raum = ?) AS stunden";
    // stunden mit lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, lehrkraft, kurs, tag, stunde FROM $sqlvor JOIN lehrer ON stunden.lehrkraft = lehrer.id) AS stdlehrer";
    $sql = "SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, lehrerbez, lehrkraft, kurs, tag, stunde FROM $sqlvor JOIN kurse ON stdlehrer.kurs = kurse.id";
    $sql->bind_param("ii", $zeitraum, $id);
    if ($sql->execute()) {
      $sql->bind_result($rkursbez, $rlbez, $rlid, $rkid, $rtag, $rstunde);
      while ($sql->fetch()) {
        $zwischen = array();
        $zwischen['lehrer'] = $rlid;
        $zwischen['lehrerbez'] = $rlbez;
        $zwischen['kurs'] = $rkid;
        $zwischen['kursbez'] = $rkursbez;
        if (!isset($unterricht[$rtag][$rstunde])) {$unterricht[$rtag][$rstunde] = array();}
        array_push($unterricht[$rtag][$rstunde], $zwischen);
      }
    } else {$fehler = true;}

  }
  else if ($art == 'k') {
    $beschriftung = "Klasse ";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenstufen ON klassen.klassenstufe = klassenstufen.id WHERE klassen.id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($kbez, $sbez);
      if ($sql->fetch()) {
        $beschriftung .= $sbez.$kbez;
      }
    } else {$fehler = true;}
    $sql->close();

    // stunden
    $sqlvor = "(SELECT raum, lehrkraft, tag, stunde, kurs FROM stunden WHERE zeitraum = ? AND kurs IN (SELECT kurs FROM kursklassen WHERE klasse = ?)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    // stunden mit raum und lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN lehrer ON stdraum.lehrkraft = lehrer.id) AS stdrl";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kurs, lehrerbez, raumbez, raum, lehrkraft, tag, stunde FROM $sqlvor JOIN kurse ON stdrl.kurs = kurse.id");
    $sql->bind_param("ii", $zeitraum, $id);
    if ($sql->execute()) {
      $sql->bind_result($rkbez, $rlbez, $rrbez, $rrid, $rlid, $rtag, $rstunde);
      while ($sql->fetch()) {
        $zwischen = array();
        $zwischen['lehrer'] = $rlid;
        $zwischen['lehrerbez'] = $rkbez;
        $zwischen['raum'] = $rrid;
        $zwischen['raumbez'] = $rlbez.'<br>'.$rrbez;
        if (!isset($unterricht[$rtag][$rstunde])) {$unterricht[$rtag][$rstunde] = array();}
        array_push($unterricht[$rtag][$rstunde], $zwischen);
      }
    } else {$fehler = true;}
    $sql->close();
  }
  else if ($art == 'stufe') {
    $beschriftung = "Klassenstufe ";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM klassenstufen WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($beschriftung);
      $sql->fetch();
    } else {$fehler = true;}
    $sql->close();

    // stunden
    $sqlvor = "(SELECT raum, lehrkraft, tag, stunde, kurs FROM stunden WHERE zeitraum = ? AND kurs IN (SELECT kurs FROM kursklassen JOIN klassen ON kursklassen.klasse = klassen.id WHERE klassenstufe = ?)) AS stunden";
    // stunden mit raum
    $sqlvor = "(SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN raeume ON stunden.raum = raeume.id) AS stdraum";
    // stunden mit raum und lehrer
    $sqlvor = "(SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS lehrerbez, raumbez, raum, lehrkraft, tag, stunde, kurs FROM $sqlvor JOIN lehrer ON stdraum.lehrkraft = lehrer.id) AS stdrl";
    $sql = $dbs->prepare("SELECT AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kurs, lehrerbez, raumbez, raum, lehrkraft, tag, stunde FROM $sqlvor JOIN kurse ON stdrl.kurs = kurse.id");
    $sql->bind_param("ii", $zeitraum, $id);
    if ($sql->execute()) {
      $sql->bind_result($kbez, $lbez, $rbez, $rid, $lid, $rtag, $rstunde);
      while ($sql->fetch()) {
        $zwischen = array();
        $zwischen['lehrer'] = $lid;
        $zwischen['lehrerbez'] = $kbez;
        $zwischen['raum'] = $rid;
        $zwischen['raumbez'] = $lbez.'<br>'.$rbez;
        if (!isset($unterricht[$rtag][$rstunde])) {$unterricht[$rtag][$rstunde] = array();}
        array_push($unterricht[$rtag][$rstunde], $zwischen);
      }
    } else {$fehler = true;}
    $sql->close();
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
