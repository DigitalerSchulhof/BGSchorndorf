<?php
function cms_vertretungsplan_komplettansicht_aus_datei($art, $datei) {

  $code = cms_vertretungsplan_komplettansicht_aus_datei_lehrer($datei);
  return $code;
}


function cms_vertretungsplan_komplettansicht_aus_datei_lehrer($datei) {
  $code = "";
  if (is_file($datei)) {
    $vertretungsplan = file_get_contents_utf8($datei);
    $code = "<h2>".cms_vertretungsplan_extern_datum_auslesen($vertretungsplan)."</h2>";

    $infokomplett = cms_vertretungsplan_extern_information_auslesen($vertretungsplan);
    $infocode = $infokomplett['info'];
    $abwesend = $infokomplett['abwesend'];
    if (strlen($infocode) > 0) {
      $code .= $infocode;
    }

    $plancode = cms_vertretungsplan_extern_plan_auslesen($vertretungsplan);
    if (strlen($plancode) > 0) {$code .= "<table class=\"cms_liste\">".$plancode."</table>";}

    if (strlen($abwesend) > 0) {
      $abwesend = cms_meldung('vplan', $abwesend);
      $code .= $abwesend;
    }

  }
  return $code;
}


function cms_vertretungsplan_extern_information_auslesen($vertretungsplan) {
  $seiteninhalt = explode('<div class="mon_title">', $vertretungsplan);
  $informationen = explode('<table class="info" >', $seiteninhalt[1]);
  $abwesend = "";
  $infocode = "";
  if (isset($informationen)) {
    $informationen = explode('</table>', $informationen[1]);
    $informationen = str_replace("class='info'", 'class="info"', $informationen[0]);
    $informationen = explode('</tr>', $informationen);
    for ($i = 0; $i < count($informationen)-1; $i++) {
      $zeile = explode('<tr class="info">', $informationen[$i]);
      if (substr($zeile[1], 0, 30) == '<td class="info" align="left">') {
        $inhalt = explode("</td><td class=\"info\" align=\"left\">", substr($zeile[1], 30));
        $abwesend .= "<p><b>".$inhalt[0]."</b><br>".$inhalt[1]."</p>";
      }
      else if (substr($zeile[1], 0, 29) == '<td class="info" colspan="2">') {
        $inhalt = str_replace('<br><br>', '</p><p>', substr($zeile[1], 29));
        $infocode = '<p>'.$inhalt.'</p>';
        $infocode = cms_meldung('info', $infocode);
      }
    }
  }
  $ausgabe['info'] = $infocode;
  $ausgabe['abwesend'] = $abwesend;
  return $ausgabe;
}


function cms_vertretungsplan_extern_datum_auslesen($vertretungsplan) {
  $seiteninhalt = explode('<div class="mon_title">', $vertretungsplan);
  $datumfeld = explode(' ', $seiteninhalt[1]);
  $datum = explode('.', $datumfeld[0]);
  $zeit = mktime(0,0,0,$datum[1], $datum[0], $datum[2]);
  $tag = date('N', $zeit);
  return cms_tagnamekomplett($tag).", den ".$datum[0].". ".cms_monatsnamekomplett($datum[1])." ".$datum[2];
}


function cms_vertretungsplan_plan_vorbereiten($vertretungsplan) {
  $seiteninhalt = explode('<table class="mon_list" >', $vertretungsplan);
  $seiteninhalt = explode('</table>', $seiteninhalt[1]);
  $plan = str_replace('</span>', '', $seiteninhalt[0]);
  $plan = str_replace('<span style="color: #010101">', '', $plan);
  $plan = str_replace(' class=\'list\'', '', $plan);
  $plan = str_replace(' class=\'list even\'', '', $plan);
  $plan = str_replace(' class=\'list odd\'', '', $plan);
  $plan = str_replace(' class="list"', '', $plan);
  $plan = str_replace(' align="center"', '', $plan);
  $plan = str_replace(' width=\'7\'', '', $plan);
  $plan = str_replace(' width=\'6\'', '', $plan);
  $plan = str_replace(' width=\'5\'', '', $plan);
  $plan = str_replace('<b>', '', $plan);
  $plan = str_replace('</b>', '', $plan);
  $plan = str_replace('</td>', '', $plan);
  $plan = str_replace('</th>', '', $plan);
  $plan = explode('</tr>', $plan);
  return $plan;
}


function cms_vertretungsplan_extern_plan_auslesen($vertretungsplan) {
  $plan = cms_vertretungsplan_plan_vorbereiten($vertretungsplan);
  $plancode = "";
  if (isset($plan[0])) {
    $zeile = explode('<th>', substr($plan[0], 4));
    $plancode .= "<tr>";
    array_shift($zeile);
    foreach ($zeile AS $feld) {
      $plancode .= "<th>".$feld."</th>";
    }
    $plancode .= "</tr>";
  }
  for ($i=1; $i < count($plan); $i++) {
    $zeile = explode('<td>', substr($plan[$i], 4));
    array_shift($zeile);
    if (isset($zeile[0])) {
      $plancode .= "<tr>";
      $style = cms_stunde_farbe($zeile[0].$zeile[0].$zeile[0]);
      foreach ($zeile AS $feld) {
        $plancode .= "<td style=\"$style\">".$feld."</td>";
      }
      $plancode .= "</tr>";
    }
  }
  return $plancode;
}


function cms_vertretungsplan_extern_plan_persoenlich_lehrer($vertretungsplan, $kuerzel) {
  $plan = cms_vertretungsplan_plan_vorbereiten($vertretungsplan);
  $plancode = "";
  $persoenlich = array();
  $anzahl = 0;
  for ($i=1; $i < count($plan); $i++) {
    $zeile = explode('<td>', substr($plan[$i], 4));
    array_shift($zeile);
    if ((isset($zeile[0]) && isset($zeile[5])) && (($zeile[0] == $kuerzel) || ($zeile[5] == $kuerzel))) {
      $persoenlich[$anzahl]['vertreter'] = $zeile[0];
      $persoenlich[$anzahl]['stunde'] = str_replace(' ', '', $zeile[1]);
      $persoenlich[$anzahl]['fach'] = $zeile[2];
      $persoenlich[$anzahl]['klassen'] = $zeile[3];
      $persoenlich[$anzahl]['raum'] = $zeile[4];
      $persoenlich[$anzahl]['lehrer'] = $zeile[5];
      $persoenlich[$anzahl]['art'] = $zeile[6];
      $persoenlich[$anzahl]['vertretungvon'] = $zeile[7];
      $persoenlich[$anzahl]['text'] = $zeile[8];
      $anzahl++;
    }
  }

  foreach ($persoenlich AS $vert) {
    $plancode .= cms_vertretungsplan_persoenlich_stunde_anzeigen($vert);
  }

  if (strlen($plancode) > 0) {
    $plancode = "<ul class=\"cms_vplanuebersicht\">".$plancode."</ul>";
  }

  return $plancode;
}



function cms_vertretungsplan_extern_plan_persoenlich_schueler($vertretungsplan, $klassen) {
  $plan = cms_vertretungsplan_plan_vorbereiten($vertretungsplan);
  $plancode = "";
  $persoenlich = array();
  $anzahl = 0;
  for ($i=1; $i < count($plan); $i++) {
    $zeile = explode('<td>', substr($plan[$i], 4));
    array_shift($zeile);
    foreach ($klassen as $k) {
      $bedingung = (isset($zeile[0])) && ((preg_match("/^".$k['stufe']."/", $zeile[0])) && (preg_match("/".$k['klasse']."/", $zeile[0])));

      if ($bedingung) {
        $persoenlich[$anzahl]['klassen'] = $zeile[0];
        $persoenlich[$anzahl]['stunde'] = str_replace(' ', '', $zeile[1]);
        $persoenlich[$anzahl]['fach'] = $zeile[2];
        $persoenlich[$anzahl]['vertreter'] = $zeile[3];
        $persoenlich[$anzahl]['raum'] = $zeile[4];
        $persoenlich[$anzahl]['lehrer'] = $zeile[5];
        $persoenlich[$anzahl]['art'] = $zeile[6];
        $persoenlich[$anzahl]['vertretungvon'] = $zeile[7];
        $persoenlich[$anzahl]['text'] = $zeile[8];
        $anzahl++;
      }
    }
  }

  foreach ($persoenlich AS $vert) {
    $plancode .= cms_vertretungsplan_persoenlich_stunde_anzeigen($vert);
  }

  if (strlen($plancode) > 0) {
    $plancode = "<ul class=\"cms_vplanuebersicht\">".$plancode."</ul>";
  }

  return $plancode;
}



function cms_vertretungsplan_persoenlich_stunde_anzeigen($vert) {
  $code = "";
  $code .= "<li>";
  $stundealt = $vert['stunde'];
  $datumalt = $vert['vertretungvon'];
  if (strlen($datumalt) > 0) {
    $zwischen = explode('-', $datumalt);
    if (isset($zwischen[1])) {
      $daten = explode(' / ', $zwischen[1]);
      if (isset($daten[1])) {$stundealt = $daten[1];}
      $datumalt = strtoupper($zwischen[0])." ".$daten[0];
    }
  }
  $code .= "<h3>".$vert['art']."</h3>";
  if (strlen($vert['text']) > 0) {$plancode .= "<p>".$vert['text']."</p>";}

  $code .= "<div class=\"cms_vplan_vertretungsstunde\">";
  if ($vert['art'] == 'Entfall') {$zusatz = ' cms_vplan_stundevorentfall';} else {$zusatz = '';}
  $code .= "<span class=\"cms_vplan_stundevorher$zusatz\">";
    $code .= "<span class=\"cms_vertretung_stundennr\">".$stundealt."</span>";
    $code .= "<span class=\"cms_vertretung_inhalt\">";
    if (strlen($datumalt) > 0) {$code .= $datumalt."<br>";}
    $code .= $vert['lehrer']."<br>".$vert['klassen'];
    $code .= "</span>";
  $code .= "</span>";
  if ($vert['art'] != 'Entfall') {
    $code .= "<span class=\"cms_vplan_stundenachher\">";
      $code .= "<span class=\"cms_vertretung_stundennr\">".$vert['stunde']."</span>";
      $code .= "<span class=\"cms_vertretung_inhalt\">";
      $code .= $vert['vertreter']."<br>".$vert['fach']." - ".$vert['klassen']."<br>".$vert['raum'];
      $code .= "</span>";
    $code .= "</span>";
  }
  else {
    $code .= "<span class=\"cms_vplan_stundeentfall\"></span>";
  }
  if ($vert['art'] != 'Entfall') {$code .= "<span class=\"cms_vplan_uebergang\">»</span>";}
  $code .= "</div>";

  $code .= "</li>";
  return $code;
}
?>
