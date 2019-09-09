<?php
function cms_personenregelplan_aus_db($dbs, $person, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum != '-') {
    $sql = "SELECT regelunterricht.schulstunde, schulstunden.beginns, schulstunden.beginnm, schulstunden.endes, schulstunden.endem, regelunterricht.tag, regelunterricht.rythmus, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), faecher.farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer on regelunterricht.lehrer = lehrer.id JOIN personen ON lehrer.id = personen.id JOIN faecher ON kurse.fach = faecher.id WHERE schulstunden.zeitraum = ? AND regelunterricht.kurs IN (SELECT gruppe FROM kursemitglieder WHERE person = ?) ORDER BY regelunterricht.tag, schulstunden.beginns, schulstunden.beginnm, regelunterricht.rythmus";
  }
  $INFO = cms_stunden_generieren($dbs, $sql, $person, $zeitraum);
  return cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO);
}

function cms_raumregelplan_aus_db($dbs, $raum, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum != '-') {
    $sql = "SELECT regelunterricht.schulstunde, schulstunden.beginns, schulstunden.beginnm, schulstunden.endes, schulstunden.endem, regelunterricht.tag, regelunterricht.rythmus, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), faecher.farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer on regelunterricht.lehrer = lehrer.id JOIN personen ON lehrer.id = personen.id JOIN faecher ON kurse.fach = faecher.id WHERE schulstunden.zeitraum = ? AND raum = ? ORDER BY regelunterricht.tag, schulstunden.beginns, schulstunden.beginnm, regelunterricht.rythmus";
  }
  $INFO = cms_stunden_generieren($dbs, $sql, $raum, $zeitraum);
  return cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO);
}

function cms_klassenregelplan_aus_db($dbs, $klasse, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum != '-') {
    $sql = "SELECT regelunterricht.schulstunde, schulstunden.beginns, schulstunden.beginnm, schulstunden.endes, schulstunden.endem, regelunterricht.tag, regelunterricht.rythmus, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), faecher.farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer on regelunterricht.lehrer = lehrer.id JOIN personen ON lehrer.id = personen.id JOIN faecher ON kurse.fach = faecher.id WHERE schulstunden.zeitraum = ? AND regelunterricht.kurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) ORDER BY regelunterricht.tag, schulstunden.beginns, schulstunden.beginnm, regelunterricht.rythmus";
  }
  $INFO = cms_stunden_generieren($dbs, $sql, $klasse, $zeitraum);
  return cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO);
}

function cms_stufenregelplan_aus_db($dbs, $stufe, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum != '-') {
    $sql = "SELECT regelunterricht.schulstunde, schulstunden.beginns, schulstunden.beginnm, schulstunden.endes, schulstunden.endem, regelunterricht.tag, regelunterricht.rythmus, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), faecher.farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer on regelunterricht.lehrer = lehrer.id JOIN personen ON lehrer.id = personen.id JOIN faecher ON kurse.fach = faecher.id WHERE schulstunden.zeitraum = ? AND kurse.stufe = ? ORDER BY regelunterricht.tag, schulstunden.beginns, schulstunden.beginnm, regelunterricht.rythmus";
  }
  $INFO = cms_stunden_generieren($dbs, $sql, $stufe, $zeitraum);
  return cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO);
}

function cms_lehrerregelplan_aus_db($dbs, $person, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum != '-') {
    $sql = "SELECT regelunterricht.schulstunde, schulstunden.beginns, schulstunden.beginnm, schulstunden.endes, schulstunden.endem, regelunterricht.tag, regelunterricht.rythmus, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), faecher.farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer on regelunterricht.lehrer = lehrer.id JOIN personen ON lehrer.id = personen.id JOIN faecher ON kurse.fach = faecher.id WHERE schulstunden.zeitraum = ? AND lehrer = ? ORDER BY regelunterricht.tag, schulstunden.beginns, schulstunden.beginnm, regelunterricht.rythmus";
  }
  $INFO = cms_stunden_generieren($dbs, $sql, $person, $zeitraum);
  return cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO);
}

function cms_stunden_generieren($dbs, $sqlstd, $zielid, $zeitraum) {
  global $CMS_SCHLUESSEL;
  if ($zeitraum == '-') {
    return array();
  }

  $SCHULTAGE = array();
  $beginn = null;
  $ende = null;
  $zrythmen = null;
  $sql = $dbs->prepare("SELECT beginn, ende, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE id = ?");
  $sql->bind_param("i", $zeitraum);
  if ($sql->execute()) {
    $sql->bind_result($zbeginn, $zende, $mo, $di, $mi, $do, $fr, $sa, $so, $zrythmen);
    if ($sql->fetch()) {
      if ($mo == '1') {array_push($SCHULTAGE, "1");}
      if ($di == '1') {array_push($SCHULTAGE, "2");}
      if ($mi == '1') {array_push($SCHULTAGE, "3");}
      if ($do == '1') {array_push($SCHULTAGE, "4");}
      if ($fr == '1') {array_push($SCHULTAGE, "5");}
      if ($sa == '1') {array_push($SCHULTAGE, "6");}
      if ($so == '1') {array_push($SCHULTAGE, "7");}
    }
  }
  $sql->close();

  $SCHULSTUNDEN = array();
  $SCHULSTUNDENIDS = array();
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
  $sql->bind_param("i", $zeitraum);
  if ($sql->execute()) {
    $sql->bind_result($sid, $sbez, $sbeginns, $sbeginnm, $sendes, $sendem);
    while ($sql->fetch()) {
      $schulstunde = array();
      $schulstunde['id'] = $sid;
      $schulstunde['bez'] = $sbez;
      $schulstunde['beginn'] = $sbeginns*60+$sbeginnm;
      $schulstunde['beginns'] = $sbeginns;
      $schulstunde['beginnm'] = $sbeginnm;
      $schulstunde['ende'] = $sendes*60+$sendem;
      $schulstunde['endes'] = $sendes;
      $schulstunde['endem'] = $sendem;
      $SCHULSTUNDEN[$sid] = $schulstunde;
      array_push($SCHULSTUNDENIDS, $sid);
    }
  }
  $sql->close();

  $sql = $dbs->prepare($sqlstd);
  $sql->bind_param("ii", $zeitraum, $zielid);
  $STUNDEN = array();
  $rythmenmax = $zrythmen;
  if ($zrythmen == 1) {$rythmenmax = 0;} else {$rythmenmax = $zrythmen;}
  foreach ($SCHULTAGE AS $t) {
    $STUNDEN[$t] = array();
    foreach($SCHULSTUNDENIDS AS $s) {
      $STUNDEN[$t][$s] = array();
      for ($r=0; $r<=$rythmenmax; $r++) {
        $STUNDEN[$t][$s][$r] = array();
      }
    }
  }
  if ($sql->execute()) {
    $sql->bind_result($std, $stdbeginns, $stdbeginnm, $stdendes, $stdendem, $stdtag, $stdrythmus, $stdkurs, $stdraum, $stdlkurz, $stdlvorname, $stdlnachname, $stdltitel, $stdfarbe);
    while ($sql->fetch()) {
      $s = array();
      $s['beginns'] = $stdbeginns;
      $s['beginnm'] = $stdbeginnm;
      $s['endes'] = $stdendes;
      $s['endem'] = $stdendem;
      $s['kurs'] = $stdkurs;
      $s['raum'] = $stdraum;
      $s['rythmus'] = $stdrythmus;
      if (strlen($stdlkurz) > 0) {$lehrerbez = $stdlkurz;}
      else {$lehrerbez = cms_generiere_anzeigename($stdlvorname, $stdlnachname, $stdltitel);}
      $s['lehrer'] = $lehrerbez;
      $s['farbe'] = $stdfarbe;
      if (!isset($STUNDEN[$stdtag][$std][$stdrythmus])) {$STUNDEN[$stdtag][$std][$stdrythmus] = array();}
      array_push($STUNDEN[$stdtag][$std][$stdrythmus], $s);
    }
  }
  $sql->close();
  $INFO['schulstunden'] = $SCHULSTUNDEN;
  $INFO['schulstundenids'] = $SCHULSTUNDENIDS;
  $INFO['schultage'] = $SCHULTAGE;
  $INFO['stunden'] = $STUNDEN;
  $INFO['zeitraumbeginn'] = $zbeginn;
  $INFO['zeitraumende'] = $zende;
  $INFO['rythmenmax'] = $rythmenmax;
  return $INFO;
}

function cms_stundenplan_ausgeben($dbs, $zeitraum, $INFO) {
  if ($zeitraum == '-') {
    return cms_meldung('info', '<h4>Nicht verfügbar</h4><p>Im Moment ist kein Stundenplan verfügbar.</p>');
  }

  $SCHULSTUNDEN = $INFO['schulstunden'];
  $SCHULSTUNDENIDS = $INFO['schulstundenids'];
  $SCHULTAGE = $INFO['schultage'];
  $STUNDEN = $INFO['stunden'];

  $minpp = 1;
  $yakt = 40;
  $zende = $SCHULSTUNDEN[$SCHULSTUNDENIDS[0]]['beginn'];
  foreach ($SCHULSTUNDENIDS AS $s) {
    $spdauer = $SCHULSTUNDEN[$s]['ende'] - $SCHULSTUNDEN[$s]['beginn'];
    // Abstand zur letzten Stunde berechnen
    $yakt += $SCHULSTUNDEN[$s]['beginn'] - $zende;
    $SCHULSTUNDEN[$s]['beginny'] = $yakt;
    $yakt += floor($spdauer / $minpp);
    $SCHULSTUNDEN[$s]['endey'] = $yakt;
    $zende = $SCHULSTUNDEN[$s]['ende'];
  }
  $sphoehe = $SCHULSTUNDEN[$SCHULSTUNDENIDS[count($SCHULSTUNDENIDS)-1]]['endey'];

  $spaltenbreite = 100/(count($SCHULTAGE)+1);

  $code = "";
  if ($INFO['zeitraumbeginn'] !== null) {
    $code .= "<h3>Regelstundenplan für den Zeitraum vom ".date("d.m.Y", $INFO['zeitraumbeginn'])." bis zum ".date("d.m.Y", $INFO['zeitraumende'])."</h3>";


    $code .= "<div class=\"cms_stundenplan_box\" style=\"height: $sphoehe"."px\">";
      foreach ($SCHULSTUNDENIDS as $s) {
        $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginns']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginnm'])."</span></span>";
          $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;line-height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">".$SCHULSTUNDEN[$s]['bez']."</span>";
        $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".$SCHULSTUNDEN[$s]['endey']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['endes']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['endem'])."</span></span>";
      }
      $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
      $code .= "</div>";
      // Klasse / Stufe
      foreach($SCHULTAGE AS $t) {
        $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
        $code .= "<span class=\"cms_stundenplan_spaltentitel\">".cms_tagname($t)."</span>";
        foreach ($SCHULSTUNDENIDS as $s) {
          $code .= "<span class=\"cms_stundenplan_stundenfeld\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
          for ($r=0; $r<=$INFO['rythmenmax']; $r++) {
            foreach ($STUNDEN[$t][$s][$r] AS $std) {
              $code .= cms_stunde_ausgeben($std);
            }
          }
          $code .= "</span>";
        }
        $code .= "</div>";
      }
    $code .= "</div>";
  }
  return $code;
}

function cms_stunde_ausgeben($std) {
  $style = "";
  if (($std['farbe'] <= 4) || (($std['farbe'] >= 12) && ($std['farbe'] <= 23))) {$style = "color:#ffffff;";}
  $code = "<span class=\"cms_stundenplan_stunde cms_farbbeispiel_".$std['farbe']."\" style=\"$style\">";
    $code .= $std['kurs']."<br>".$std['lehrer']."<br>".$std['raum'];
    if ($std['rythmus'].'' != '0') {
      $code .= "<span class=\"cms_stundenplan_stunde_rythmus\">".chr(64+$std['rythmus'])."</span>";
    }
  $code .= "</span>";
  return $code;
}
?>
