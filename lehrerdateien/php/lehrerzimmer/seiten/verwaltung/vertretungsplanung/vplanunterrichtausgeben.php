<?php
function cms_finde_zeitraum($tag, $ZEITRAEUME) {
  foreach ($ZEITRAEUME as $Z) {
    if (($Z['beginn'] <= $tag) && ($Z['ende'] >= $tag)) {
      return $Z;
    }
  }
  return null;
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

function cms_finde_ueberschneidung($uid, $kid, $UEBER) {
  if ($kid === null) {return false;}
  else {return in_array($kid, $UEBER);}
}

function cms_finde_ueberschneidung_detail($kid, $UEL, $UER, $UEK) {
  $grund = "";
  if (($kid !== null) && ($kid != '-')) {
    if (in_array($kid, $UEL)) {$grund .= "l";}
    if (in_array($kid, $UER)) {$grund .= "r";}
    if (in_array($kid, $UEK)) {$grund .= "k";}
  }
  return $grund;
}

function cms_finde_konflikt($uid, $kid, $KONFLIKTE) {
  if ($kid === null) {$kid = '-';}
  return in_array($uid."|".$kid, $KONFLIKTE);
}

function cms_finde_konflikt_detail($lehrer, $raum, $klasse, $stufe, $b, $e, $AUL, $AUR, $AUK, $AUS) {
  $grund = "";
  foreach ($AUL AS $a) {
    if ($a['zid'] == $lehrer) {
      if ((($b >= $a['von']) && ($b <= $a['bis'])) || (($e >= $a['von']) && ($e <= $a['bis']))) {$grund .= 'l';}
    }
  }
  foreach ($AUR AS $a) {
    if ($a['zid'] == $raum) {
      if ((($b >= $a['von']) && ($b <= $a['bis'])) || (($e >= $a['von']) && ($e <= $a['bis']))) {$grund .= 'r';}
    }
  }
  foreach ($AUK AS $a) {
    if ($a['zid'] == $klasse) {
      if ((($b >= $a['von']) && ($b <= $a['bis'])) || (($e >= $a['von']) && ($e <= $a['bis']))) {$grund .= 'k';}
    }
  }
  foreach ($AUS AS $a) {
    if ($a['zid'] == $stufe) {
      if ((($b >= $a['von']) && ($b <= $a['bis'])) || (($e >= $a['von']) && ($e <= $a['bis']))) {$grund .= 'k';}
    }
  }
  return $grund;
}

function cms_vplan_ausgeben($PLAN, $planart, $ausgeplant, $wochenplankopplung = true, $regelplan = false) {
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
        $stduhrb = explode(':', $s['beginn']);
        $stduhre = explode(':', $s['ende']);
        $stdzeitb = mktime($stduhrb[0],$stduhrb[1],0,$tagdatum[1], $tagdatum[0], $tagdatum[2]);
        $stdzeite = mktime($stduhre[0],$stduhre[1]-1,59,$tagdatum[1], $tagdatum[0], $tagdatum[2]);
        $stundenklasse = "cms_schulstunde_".str_replace(':','-',str_replace('.','-',$t['datum']."-".$s['beginn']));
        $block = false;
        foreach ($ausgeplant as $a) {
          if (($a['von'] <= $stdzeitb) && ($a['bis'] >= $stdzeite)) {$block = true;}
        }
        if ($block) {$stundenklasse .= ' cms_stundenfeld_blockiert';}
        $markierungsevent = "cms_vplan_stunde_markieren_setzen('$stundenklasse');";
        $code .= "<span class=\"cms_stundenplan_stundenfeld $stundenklasse\" style=\"top: ".($s['beginny']*$minpp+$yakt)."px;height: ".($s['dauer']*$minpp)."px;\"";
        if ((count($s['stunden']) == 0) || ($regelplan)) {
          $code .= " ondrop=\"cms_vertretungsplan_stunde_verschieben_ziel(event, '".$t['datum']." ".$s['beginn']."', '', '', '', '', '')\"";
          if (count($s['stunden']) == 0) {
            $code .= " onmouseup=\"cms_stundendetails_laden('-', '-', '".$t['datum']."', '".$s['beginn']."'); $markierungsevent\"";
          }
          else {
            $code .= " onmouseup=\"$markierungsevent\"";
          }
          $code .= " ondragover=\"cms_vertretungsplan_stunde_verschieben_erlauben(event)\"";
        }
        else {
          $code .= " onmouseup=\"$markierungsevent\"";
        }
        $code .= ">";
        foreach ($s['stunden'] AS $std) {
          $code .= cms_vplan_unterricht_ausgeben($std, $planart, $wochenplankopplung, $regelplan);
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

function cms_vplan_unterricht_ausgeben($std, $planart, $wochenplankopplung = true, $regelplan = false) {
  $klasse = "";
  if ($std['vplanart'] == 'e') {
    if (($std['kid'] != '-') && ($std['kid'] !== null) && ($std['uid'] !== null)) {$klasse = "_entfall";}
    else {$klasse = "_entfallgeaendert";}
  }
  else if ($std['konflikt']) {$klasse = "_konflikt";}
  else if ($std['ueberschneidung']) {$klasse = "_ueberschneidung";}
  else if ($std['geaendert'] && (($std['kid'] == '-') || ($std['kid'] === null))) {$klasse = "_geaendert";}
  else if ($std['kid'] !== null) {
    if (!$regelplan) {$klasse = "_geloest";}
  }
  $bezeichnung = "Kurs <b>".$std['kurs']."</b> bei <b>".$std['lehrer']."</b> in <b>".$std['raum']."</b> am ";
  $code = "<span class=\"cms_stundenplan_stunde$klasse\"";
  if ($std['klasseid'] !== null) {$kauswahl = 'k'.$std['klasseid'];}
  else if ($std['stufeid'] !== null) {$kauswahl = 's'.$std['stufeid'];}
  else {$kauswahl = '';}
  if (strlen($std['uid']) == 0) {$uid = "-";} else {$uid = $std['uid'];}
  if (strlen($std['kid']) == 0) {$kid = "-";} else {$kid = $std['kid'];}
  if (!$regelplan) {
    $code .= " draggable=\"true\"";
    $code .= " onmouseup=\"";
    if ($wochenplankopplung) {
      $code .= "cms_vplan_wochenplan_neuladen('a', '".$std['lehrerid']."', '".$std['raumid']."', '$kauswahl', '".date('d.m.Y', $std['beginn'])."'); ";
    }
    $code .= " cms_stundendetails_laden('$uid', '$kid');\"";
    $code .= " ondragstart=\"cms_vertretungsplan_stunde_verschieben_start(event, '".date("d.m.Y H:i", $std['beginn'])."', '".$std['kursid']."', '".$std['lehrerid']."', '".$std['raumid']."', '$bezeichnung', '".$std['uid']."', '".$std['kid']."')\"";
    $code .= " ondrop=\"cms_vertretungsplan_stunde_verschieben_ziel(event, '".date("d.m.Y H:i", $std['beginn'])."', '".$std['kursid']."', '".$std['lehrerid']."', '".$std['raumid']."', '$bezeichnung', '".$std['uid']."', '".$std['kid']."')\"";
    $code .= " ondragover=\"cms_vertretungsplan_stunde_verschieben_erlauben(event)\"";
  }
  else {
    $code .= " onmouseup=\"";
    if ($wochenplankopplung) {
      $code .= "cms_vplan_wochenplan_neuladen('a', '".$std['lehrerid']."', '".$std['raumid']."', '$kauswahl', '".date('d.m.Y', $std['beginn'])."'); ";
    }
    $code .= " cms_stundendetails_laden('$uid', '$kid');\"";
  }
  $code .= ">";
    $code .= $std['kurs'];
    if (($planart == 'k') || ($planart == 's') || ($planart == 'r')) {$code .= "<br>".$std['lehrer'];}
    if (($planart == 'k') || ($planart == 's') || ($planart == 'l')) {$code .= "<br>".$std['raum'];}
  $code .= "</span>";
  return $code;
}
?>
