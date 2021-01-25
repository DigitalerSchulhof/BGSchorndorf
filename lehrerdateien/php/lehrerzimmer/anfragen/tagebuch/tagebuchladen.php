<?php
function cms_tagebucheintrag_tag($dbs, $dbl, $klasse, $t, $m, $j) {
  global $CMS_SCHLUESSEL, $CMS_SCHLUESSELL;
  // Kurse der Klasse bestimmen
  $kurse = [];
  $kurseids = [];
  $sql = $dbs->prepare("SELECT kurs, AES_DECRYPT(bezeichnung, 'bez') FROM kurseklassen JOIN kurse ON kurs = kurse.id WHERE klasse = ?");
  $sql->bind_param("i", $klasse);
  if ($sql->execute()) {
    $sql->bind_result($kid, $kbez);
    while ($sql->fetch()) {
      $k['id'] = $kid;
      $k['bez'] = $kbez;
      array_push($kurseids, $kid);
      array_push($kurse, $k);
    }
  }
  $sql->close();
  $tagb = mktime(0,0,0,$m,$t,$j);
  $tagm = mktime(12,0,0,$m,$t,$j);
  $tage = mktime(0,0,0,$m,$t+1,$j)-1;
  // Schulstunden laden
  $schulstunden = [];
  $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE (? BETWEEN beginn AND ende)) ORDER BY beginns, beginnm");
  $sql->bind_param("i", $tagm);
  if ($sql->execute()) {
    $sql->bind_result($bez, $beginns, $beginnm);
    while ($sql->fetch()) {
      $schulstunden[date("H:i", mktime($beginns, $beginnm, 0, $m,$t,$j))] = $bez;
    }
  }
  $sql->close();

  // Unterricht an diesem Tag laden
  $unterricht = [];
  $uids = [];
  $personen = [];
  if (count($kurseids) > 0) {
    $kurseids = "(".implode(",", $kurseids).")";
    $sql = $dbs->prepare("SELECT unterricht.id, tkurs, tbeginn, tende, tlehrer, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') FROM unterricht JOIN kurse ON tkurs = kurse.id WHERE (tbeginn BETWEEN ? AND ?) AND (tende BETWEEN ? AND ?) AND tkurs IN $kurseids ORDER BY tbeginn ASC");
    $sql->bind_param("iiii", $tagb, $tage, $tagb, $tage);
    if ($sql->execute()) {
      $sql->bind_result($uid, $tkurs, $tbeginn, $tende, $tlehrer, $tkursbez, $tkurskbez);
      while ($sql->fetch()) {
        $u['id'] = $uid;
        $u['kurs'] = $tkurs;
        $u['beginn'] = $tbeginn;
        $u['ende'] = $tende;
        $u['lehrer'] = $tlehrer;
        $u['kursbez'] = $tkursbez;
        $u['kurskbez'] = $tkurskbez;
        array_push($personen, $tlehrer);
        array_push($unterricht, $u);
        array_push($uids, $uid);
      }
    }
    $sql->close();

    // Fehlzeiten von Personen dieser Klasse laden
    // Personen der Klasse laden
    $personenpool = [];
    $sql = $dbs->prepare("SELECT person FROM kursemitglieder WHERE gruppe IN $kurseids");
    if ($sql->execute()) {
      $sql->bind_result($person);
      while ($sql->fetch()) {
        array_push($personenpool, $person);
      }
    }
    $sql->close();

    $fehlzeiten = [];
    if (count($personenpool) > 0) {
      $personenpool = "(".implode(",", $personenpool).")";
      $sql = $dbl->prepare("SELECT person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), entschuldigt, urheber FROM fehlzeiten WHERE (von BETWEEN ? AND ?) AND (bis BETWEEN ? AND ?) AND person IN $personenpool");
      $sql->bind_param("iiii", $tagb, $tage, $tagb, $tage);
      if ($sql->execute()) {
        $sql->bind_result($person, $von, $bis, $bem, $ent, $urheber);
        while ($sql->fetch()) {
          $f = [];
          $f['person'] = $person;
          $f['von'] = $von;
          $f['bis'] = $bis;
          $f['bemerkung'] = $bem;
          $f['ent'] = $ent;
          $f['urheber'] = $urheber;
          array_push($fehlzeiten, $f);
          array_push($personen, $person);
          if ($urheber !== null) {
            array_push($personen, $urheber);
          }
        }
      }
      $sql->close();
    }
  }

  $tagebuch = [];
  if (count($uids) > 0) {
    $uids = "(".implode(",", $uids).")";
    // Lade Inhalt der Unterrichtsstunden
    $sql = $dbl->prepare("SELECT id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSELL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSEL'), freigabe, leistungsmessung, urheber, eintragsdatum FROM tagebuch WHERE id IN $uids");
    if ($sql->execute()) {
      $sql->bind_result($id, $inhalt, $hausi, $frei, $leistung, $urheber, $datum);
      while ($sql->fetch()) {
        $tagebuch[$id]['inhalt'] = $inhalt;
        $tagebuch[$id]['hausi'] = $hausi;
        $tagebuch[$id]['frei'] = $frei;
        $tagebuch[$id]['leistung'] = $leistung;
        $tagebuch[$id]['ur'] = $urheber;
        $tagebuch[$id]['datum'] = $datum;
        $tagebuch[$id]['lobtadel'] = [];
        if ($urheber !== null) {
          array_push($personen, $urheber);
        }
      }
    }
    $sql->close();

    // Lade Lob und Tadel
    $sql = $dbl->prepare("SELECT id, eintrag, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), urheber, eintragszeit FROM lobtadel WHERE eintrag IN $uids");
    if ($sql->execute()) {
      $sql->bind_result($id, $eintrag, $person, $art, $bemerkung, $urheber, $eintragszeit);
      while ($sql->fetch()) {
        $lt = [];
        $lt['id'] = $id;
        $lt['person'] = $person;
        $lt['art'] = $art;
        $lt['bem'] = $bemerkung;
        $lt['urheber'] = $urheber;
        $lt['eintragszeit'] = $eintragszeit;
        array_push($tagebuch[$eintrag]['lobtadel'], $lt);
        array_push($personen, $person, $urheber);
      }
    }
    $sql->close();
  }

  // Personen laden
  $P = [];
  if (count($personen) > 0) {
    $personen = "(".implode(',', $personen).")";
    $sql = $dbs->prepare("SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM personen LEFT JOIN lehrer ON personen.id = lehrer.id WHERE personen.id IN $personen");
    if ($sql->execute()) {
      $sql->bind_result($pid, $vor, $nach, $tit, $kurz);
      while ($sql->fetch()) {
        $P[$pid]['name'] = cms_generiere_anzeigename($vor, $nach, $tit);
        $P[$pid]['kurz'] = $kurz;
      }
    }
    $sql->close();
  }

  // Tagebucheinträge zusammenbauen
  return cms_tagebucheintraege($unterricht, $tagebuch, $P, $schulstunden, $fehlzeiten);
}

function cms_tagebucheintrag_kurs($dbs, $dbl, $kurs, $t, $m, $j, $anzahl) {
  global $CMS_SCHLUESSEL, $CMS_SCHLUESSELL;

  $jetzt = mktime(23,59,59,$m,$t,$j);
  $schulstunden = [];
  $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE (? BETWEEN beginn AND ende)) ORDER BY beginns, beginnm");
  $sql->bind_param("i", $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($bez, $beginns, $beginnm);
    while ($sql->fetch()) {
      $schulstunden[date("H:i", mktime($beginns, $beginnm, 0, $m,$t,$j))] = $bez;
    }
  }
  $sql->close();

  // Unterricht an diesem Tag laden
  $unterricht = [];
  $uids = [];
  $personen = [];
  $sql = $dbs->prepare("SELECT unterricht.id, tkurs, tbeginn, tende, tlehrer, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') FROM unterricht JOIN kurse ON tkurs = kurse.id WHERE tende < ? AND tkurs = ? ORDER BY tbeginn DESC LIMIT ?");
  $sql->bind_param("iii", $jetzt, $kurs, $anzahl);
  if ($sql->execute()) {
    $sql->bind_result($uid, $tkurs, $tbeginn, $tende, $tlehrer, $tkursbez, $tkurskbez);
    while ($sql->fetch()) {
      $u['id'] = $uid;
      $u['kurs'] = $tkurs;
      $u['beginn'] = $tbeginn;
      $u['ende'] = $tende;
      $u['lehrer'] = $tlehrer;
      $u['kursbez'] = $tkursbez;
      $u['kurskbez'] = $tkurskbez;
      array_push($personen, $tlehrer);
      array_push($unterricht, $u);
      array_push($uids, $uid);
    }
  }
  $sql->close();

  $tagebuch = [];
  if (count($uids) > 0) {
    $uids = "(".implode(",", $uids).")";
    // Lade Inhalt der Unterrichtsstunden
    $sql = $dbl->prepare("SELECT id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSELL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSEL'), freigabe, leistungsmessung, urheber, eintragsdatum FROM tagebuch WHERE id IN $uids");
    if ($sql->execute()) {
      $sql->bind_result($id, $inhalt, $hausi, $frei, $leistung, $urheber, $datum);
      $tagebuch[$id]['inhalt'] = $inhalt;
      $tagebuch[$id]['hausi'] = $hausi;
      $tagebuch[$id]['frei'] = $frei;
      $tagebuch[$id]['leistung'] = $leistung;
      $tagebuch[$id]['ur'] = $urheber;
      $tagebuch[$id]['datum'] = $datum;
      $tagebuch[$id]['lobtadel'] = [];
      $tagebuch[$id]['fehlzeiten'] = [];
      if ($urheber !== null) {
        array_push($personen, $urheber);
      }
    }
    $sql->close();
  }

  $personenpool = [];
  $sql = $dbs->prepare("SELECT person FROM kursemitglieder WHERE gruppe IN $kurseids");
  if ($sql->execute()) {
    $sql->bind_result($person);
    while ($sql->fetch()) {
      array_push($personenpool, $person);
    }
  }
  $sql->close();

  foreach ($unterricht as $u) {
    // Lade Lob und Tadel
    $sql = $dbl->prepare("SELECT id, eintrag, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), urheber, eintragszeit FROM lobtadel WHERE eintrag = ?");
    $sql->bind_param("i", $u['id']);
    if ($sql->execute()) {
      $sql->bind_result($id, $eintrag, $person, $art, $bemerkung, $urheber, $eintragszeit);
      while ($sql->fetch()) {
        $lt = [];
        $lt['id'] = $id;
        $lt['person'] = $person;
        $lt['art'] = $art;
        $lt['bem'] = $bemerkung;
        $lt['urheber'] = $urheber;
        $lt['eintragszeit'] = $eintragszeit;
        if (isset($tagebuch[$u['id']])) {
          array_push($tagebuch[$u['id']]['lobtadel'], $lt);
          array_push($personen, $person, $urheber);
        }
      }
    }
    $sql->close();

    // Fehlzeiten
    if (count($personenpool) > 0) {
      $pool = "(".implode(",", $personenpool).")";
      $b = mktime(0,0,0,date("m", $u['beginn']),date("d", $u['beginn']),date("Y", $u['beginn']));
      $e = mktime(0,0,0,date("m", $u['beginn']),date("d", $u['beginn'])+1,date("Y", $u['beginn']))-1;
      $sql = $dbl->prepare("SELECT person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), entschuldigt, urheber FROM fehlzeiten WHERE (von BETWEEN ? AND ?) AND (bis BETWEEN ? AND ?) AND person IN $pool");
      $sql->bind_param("iiiii", $b, $e, $b, $e, $kurs);
      if ($sql->execute()) {
        $sql->bind_result($person, $von, $bis, $bem, $ent, $urheber);
        while ($sql->fetch()) {
          $f = [];
          $f['person'] = $person;
          $f['von'] = $von;
          $f['bis'] = $bis;
          $f['bemerkung'] = $bem;
          $f['ent'] = $ent;
          $f['urheber'] = $urheber;
          array_push($u['fehlzeiten'], $f);
          array_push($personen, $person);
          if ($urheber !== null) {
            array_push($personen, $urheber);
          }
        }
      }
      $sql->close();
    }
  }

  // Personen laden
  $P = [];
  if (count($personen) > 0) {
    $personen = "(".implode(',', $personen).")";
    $sql = $dbs->prepare("SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM personen LEFT JOIN lehrer ON personen.id = lehrer.id WHERE personen.id IN $personen");
    if ($sql->execute()) {
      $sql->bind_result($pid, $vor, $nach, $tit, $kurz);
      while ($sql->fetch()) {
        $P[$pid]['name'] = cms_generiere_anzeigename($vor, $nach, $tit);
        $P[$pid]['kurz'] = $kurz;
      }
    }
    $sql->close();
  }

  // Tagebucheinträge zusammenbauen
  return cms_tagebucheintraege($unterricht, $tagebuch, $P, $schulstunden, $fehlzeiten);
}

function cms_tagebucheintraege($unterricht, $tagebuch, $P, $schulstunden, $fehlzeiten = null) {
  $ausgabe = "";
  foreach ($unterricht as $u) {
    $ausgabe .= "<tr>";
      $std = date("H:i", $u['beginn']);
      if (isset($schulstunden[$std])) {$std = $schulstunden[$std];}
      if (strlen($u['kurskbez']) > 0) {$f = $u['kurskbez'];} else {$f = $u['kursbez'];}
      if (isset($P[$u['lehrer']])) {
        if (strlen($P[$u['lehrer']]['kurz']) > 0) {$l = $P[$u['lehrer']]['kurz'];} else {$l = $P[$u['lehrer']]['name'];}
      } else {$l = "";}
      if ($fehlzeiten === null) {
        $std = "<span class=\"cms_notiz\">".date("d.m.Y", $u['beginn'])."</span><br>".$std;
      }
      $ausgabe .= "<th>$std</th><th>$f</th><th>$l</th>";
      if (isset($tagebuch[$u['id']])) {
        $i = $tagebuch[$u['id']]['inhalt'];
        $h = $tagebuch[$u['id']]['hausi'];
        $lm = $tagebuch[$u['id']]['leistung'];
      } else {$i = ""; $h = ""; $lm = '0';}
      if ($lm == "1") {$cssklasse = " class=\"cms_tagebuch_leistungsmessung\"";} else {$cssklasse = "";}
      $ausgabe .= "<td$cssklasse>$i</td><td>$h</td>";


      $bem = "";
      if (($fehlzeiten === null) && (isset($tagebuch[$u['id']]))) {
        foreach ($tagebuch[$u['id']]['fehlzeiten'] as $fz) {
          $bem .= cms_fehlzeit($fz, $P);
        }

        // Lob & Tadel ausgeben
        foreach ($tagebuch[$u['id']]['lobtadel'] as $lt) {
          $bem .= cms_lobtadel($lt, $P);
        }
        $ausgabe .= "<td>$bem</td>";
      }
    $ausgabe .= "</tr>";
  }

  if (strlen($ausgabe) > 0) {
    $ausgabe = "<table class=\"cms_liste\">$ausgabe</table>";
  } else {
    $ausgabe  = "<p class=\"cms_notiz\">Kein Unterricht</p>";
  }

  $bem = "";
  if ($fehlzeiten !== null) {
    foreach ($fehlzeiten as $fz) {
      $bem .= cms_fehlzeit($fz, $P);
    }

    // Lob & Tadel ausgeben
    foreach ($unterricht as $u) {
      if (isset($tagebuch[$u['id']])) {
        foreach ($tagebuch[$u['id']]['lobtadel'] as $lt) {
          $bem .= cms_lobtadel($lt, $P);
        }
      }
    }
  }

  if (strlen($bem > 0)) {
    $ausgabe .= "<div class=\"cms_tagebuch_bemerkungen\">$bem</div>";
  }

  return $ausgabe;
}


function cms_lobtadel($lt, $P) {
  if ($lt['person'] === NULL && $lt['art'] == 'l') {
    $lobtadel .= "<b>ganze Klasse</b>";
  } else if (isset($P[$lt['person']])) {
    $lobtadel .= "<b>".$P[$lt['person']]['name']."</b>";
  } else {
    $lobtadel .= "<i>entfernt</i>";
  }
  if (strlen($lt['bem']) > 0) {
    $lobtadel .= " – ".$lt['bem'];
  }
  $lobtadel .= "<br><span class=\"cms_tagebuch_klein\">";
  if (isset($P[$lt['urheber']])) {
    if (strlen($P[$lt['urheber']]['kurz']) > 0) {
      $lobtadel .= $P[$lt['urheber']]['kurz']." – ";
    } else {
      $lobtadel .= $P[$lt['urheber']]['name']." – ";
    }
  }
  if ($lt['art'] == 'm') {
    $klasse = "cms_tagebuch_negativ";
    $lobtadel .= "(M)";
  } else if ($lt['art'] == 'v') {
    $klasse = "cms_tagebuch_negativ";
    $lobtadel .= "(V)";
  } else if ($lt['art'] == "l") {
    $klasse = "cms_tagebuch_positiv";
    $lobtadel .= "(L)";
  }
  $lobtadel .= "</span>";
  return "<span class=\"cms_lobtadel $klasse\">".$lobtadel."</span>";
}

function cms_fehlzeit($fz, $P) {
  $fehl = "";
  if (isset($P[$fz['person']])) {
    $fehl .= "<b>".$P[$fz['person']]['name']."</b>";
  } else {
    $fehl .= "<i>entfernt</i>";
  }
  if (strlen($fz['bemerkung']) > 0) {
    $fehl .= " – ".$fz['bemerkung'];
  }
  if ($fz['ent'] == 1) {
    $klasse = "cms_tagebuch_entschuldigt";
    $fehl .= " – (e)";
  } else if ($fz['ent'] == -1) {
    $klasse = "cms_tagebuch_unentschuldigt";
    $fehl .= "  – (ue)";
  } else {
    $klasse = "cms_tagebuch_unbearbeitet";
  }

  $urheber = "";
  if (isset($P[$fz['urheber']])) {
    if (strlen($P[$fz['urheber']]['kurz']) > 0) {
      $urheber .= $P[$fz['urheber']]['kurz'];
    } else {
      $urheber .= $P[$fz['urheber']]['name'];
    }
  }
  if (strlen($urheber) > 0) {
    $urheber = " – ".$urheber;
  }
  $urheber = "<br><span class=\"cms_tagebuch_klein\">".date("H:i", $fz['von'])." bis ".date("H:i", $fz['bis']+1).$urheber."</span>";
  return "<span class=\"cms_fehlzeit $klasse\">".$fehl.$urheber."</span>";
}
?>
