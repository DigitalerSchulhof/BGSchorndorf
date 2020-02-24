<?php
function cms_generiere_sessionid () {
    $pool = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $sessionid = "";
    srand ((double)microtime()*1000000);
    for($i = 0; $i < 20; $i++) {
        $sessionid .= substr($pool,(rand()%(strlen ($pool))), 1);
    }
    return $sessionid;
}

function cms_generiere_passwort () {
    $pool = "abcdefhkmnpqrstuvwxyz2345678ABCDEFGHKLMNPQRSTUVWXYZ!_-+";
    $passwort = "";
    srand ((double)microtime()*1000000);
    for($i = 0; $i < 10; $i++) {
        $passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
    }
    return $passwort;
}

function cms_generiere_kleinste_id ($tabelle, $netz = "l", $benutzer = '-', $verspaetung = 0) {
  global $CMS_BENUTZERID, $CMS_DBL_HOST, $CMS_DBL_USER, $CMS_DBL_PASS, $CMS_DBL_DB;
  $fehler = false;
  $id = '-';

  if ($benutzer == '-') {
    if (isset($CMS_BENUTZERID)) {$benutzer = $CMS_BENUTZERID;}
    else {$fehler = true;}
  }

  if (!$fehler) {

    if ($netz == "l") {$db = cms_verbinden('l');}
    else if ($netz == "s") {$db = cms_verbinden('s');}
    $jetzt = time() + $verspaetung;

    // Neue ID bestimmten und eintragen
    $sql = "INSERT INTO $tabelle (id, idvon, idzeit) SELECT id, idvon, idzeit FROM (SELECT IFNULL(id*0,0)+$benutzer AS idvon, IFNULL(id*0,0)+$jetzt AS idzeit, IFNULL(MIN(id)+1,1) AS id FROM $tabelle WHERE id+1 NOT IN (SELECT id FROM $tabelle)) AS vorherigeid";
    $anfrage = $db->query($sql);

    // ID zurückgewinnen
    $sql = "SELECT id FROM $tabelle WHERE idvon = $benutzer AND idzeit = $jetzt";
    if ($anfrage = $db->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $id = $daten['id'];
      }
      else {$fehler = true;}
      $anfrage->free();
    }
    else {$fehler = true;}

    // Persönliche Daten löschen
    if (!$fehler) {
      $sql = "UPDATE $tabelle SET idvon = NULL, idzeit = NULL WHERE id = $id";
      $anfrage = $db->query($sql);
    }
    cms_trennen($db);
  }

  return $id;
}

function cms_generiere_anzeigename($vorname, $nachname, $titel) {
  $anzeigename = $vorname." ".$nachname;
  if (strlen($titel)>0) {
    $anzeigename = $titel." ".$anzeigename;
  }
  return $anzeigename;
}

function cms_uhrzeit_eingabe ($id, $stunde = '-', $minute = '-', $onchange = '') {
  $jetzt = time();
  if ($stunde == '-') {$stunde = date ('H', $jetzt);}
  if ($minute == '-') {$minute = date ('i', $jetzt);}

  $ausgabe  = "<input class=\"cms_input_h\" type=\"text\" name=\"".$id."_h\" id=\"".$id."_h\" onchange=\"cms_uhrzeitcheck('".$id."'); $onchange\" value=\"$stunde\"/> : ";
  $ausgabe .= "<input class=\"cms_input_m\" type=\"text\" name=\"".$id."_m\" id=\"".$id."_m\" onchange=\"cms_uhrzeitcheck('".$id."'); $onchange\" value=\"$minute\"/>";
  return $ausgabe;
}

function cms_datum_eingabe ($id, $tag = '-', $monat = '-', $jahr = '-', $onchange = '') {
  $jetzt = time();
  if ($tag == '-') {$tag = date ('d', $jetzt);}
  if ($monat == '-') {$monat = date ('m', $jetzt);}
  if ($jahr == '-') {$jahr = date ('Y', $jetzt);}
  $jetzt = mktime(0, 0, 0, $monat, $tag, $jahr);
  $tagbez = cms_tagname(date('N', $jetzt));

  $ausgabe  = "<span class=\"cms_input_Tbez\" id=\"".$id."_Tbez\">$tagbez</span> ";
  $ausgabe .= "<input class=\"cms_input_T\" type=\"text\" name=\"".$id."_T\" id=\"".$id."_T\" onchange=\"cms_datumcheck('".$id."'); $onchange\" value=\"$tag\"/> . ";
  $ausgabe .= "<input class=\"cms_input_M\" type=\"text\" name=\"".$id."_M\" id=\"".$id."_M\" onchange=\"cms_datumcheck('".$id."'); $onchange\" value=\"$monat\"/> . ";
  $ausgabe .= "<input class=\"cms_input_J\" type=\"text\" name=\"".$id."_J\" id=\"".$id."_J\" onchange=\"cms_datumcheck('".$id."'); $onchange\" value=\"$jahr\"/>";
  return $ausgabe;
}

function cms_monatsname($m) {
  if ($m == 1) {return 'JAN';}
  else if ($m == 2) {return 'FEB';}
  else if ($m == 3) {return 'MÄR';}
  else if ($m == 4) {return 'APR';}
  else if ($m == 5) {return 'MAI';}
  else if ($m == 6) {return 'JUN';}
  else if ($m == 7) {return 'JUL';}
  else if ($m == 8) {return 'AUG';}
  else if ($m == 9) {return 'SEP';}
  else if ($m == 10) {return 'OKT';}
  else if ($m == 11) {return 'NOV';}
  else if ($m == 12) {return 'DEZ';}
  else {return false;}
}

function cms_monatsnamekomplett($m) {
  if ($m == 1) {return 'Januar';}
  else if ($m == 2) {return 'Februar';}
  else if ($m == 3) {return 'März';}
  else if ($m == 4) {return 'April';}
  else if ($m == 5) {return 'Mai';}
  else if ($m == 6) {return 'Juni';}
  else if ($m == 7) {return 'Juli';}
  else if ($m == 8) {return 'August';}
  else if ($m == 9) {return 'September';}
  else if ($m == 10) {return 'Oktober';}
  else if ($m == 11) {return 'November';}
  else if ($m == 12) {return 'Dezember';}
  else {return false;}
}

function cms_tagname($t) {
  if ($t == 0) {return 'SO';}
  else if ($t == 1) {return 'MO';}
  else if ($t == 2) {return 'DI';}
  else if ($t == 3) {return 'MI';}
  else if ($t == 4) {return 'DO';}
  else if ($t == 5) {return 'FR';}
  else if ($t == 6) {return 'SA';}
  else if ($t == 7) {return 'SO';}
  else {return false;}
}

function cms_tagnamekomplett($t) {
  if ($t == 0) {return 'Sonntag';}
  else if ($t == 1) {return 'Montag';}
  else if ($t == 2) {return 'Dienstag';}
  else if ($t == 3) {return 'Mittwoch';}
  else if ($t == 4) {return 'Donnerstag';}
  else if ($t == 5) {return 'Freitag';}
  else if ($t == 6) {return 'Samstag';}
  else if ($t == 7) {return 'Sonntag';}
  else {return false;}
}



function cms_generiere_beschluss($id, $titel, $beschreibung, $status, $datum, $blogdatum, $langfristig, $pro, $contra, $enthaltung, $gruppe, $gruppeid, $blogid, $gruppebezeichnung, $kurz = true, $edit = false, $rechte = false) {
  $tag = date('d', $datum);
  $monat = date('m', $datum);
  $jahr = date('Y', $datum);

  $zusatzklasse = "";
  if (!$kurz) {$zusatzklasse = "cms_blog_keinhover";}

  // Falls der Blog nicht mehr existiert, gibt es keine kleine Ansicht
  if ($blogdatum != $datum) {$kurz = false;}

  $aktion = "";
  if ($kurz) {
    $aktion = "onclick=\"cms_lehrerzimmer_gruppe_blogeintrag_anzeigen_vorbereiten ('$gruppe', ".$blogid.", $gruppeid, '$gruppebezeichnung')\"";
  }

  $code = "<li><span class=\"cms_beschlusseintrag $zusatzklasse\" $aktion>";
  $code .= "<h3>".$titel."</h3>";
  if ($kurz) {
    $beschreibung = substr($beschreibung, 0, 20)."...";
  }
  $code .= "<p class=\"cms_inhaltvorschau\">$beschreibung</p>";
  $code .= "<p class=\"cms_inhaltvorschau\">Stimmen: <span class=\"cms_beschluss_angenommen\">$pro</span> : <span class=\"cms_beschluss_abgelehnt\">$contra</span> : <span class=\"cms_beschluss_vertagt\">$enthaltung</span></p>";
  $code .= "<p class=\"cms_beschlussicons\">";
  $code .= "<span class=\"cms_beschluss_icon\"><span class=\"cms_hinweis\">$status</span><img src=\"res/icons/klein/beschluss_$status.png\"></span>";
  if ($langfristig == 1) {
    $code .= "<span class=\"cms_beschluss_icon\"><span class=\"cms_hinweis\">langfristig</span><img src=\"res/icons/klein/langfristig.png\"></span>";
  }
  $code .= "</p>";
  $code .= "<p class=\"cms_ersteller\">Beschluss vom $tag. ".cms_monatsnamekomplett($monat)." $jahr</p>";
  $code .= "</span>";
  if ($edit && $rechte) {
    $code .= "<p>";
    $gruppenbezeichnunglink = str_replace(' ', '_', $gruppebezeichnung);
    // Bearbeiten, wenn der Blog noch existiert
    if ($blogdatum == $datum) {
      $blogaktion = "cms_lehrerzimmer_gruppe_blogeintrag_bearbeiten_vorbereiten ('$gruppe', '$gruppeid', '$gruppenbezeichnunglink', ".$blogid.")";
      $code .= "<span class=\"cms_aktion_klein\" onclick=\"$blogaktion\"><span class=\"cms_hinweis\">Beschluss bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
    }
    $beschlussaktion = "cms_lehrerzimmer_gruppe_beschluss_loeschen_vorbereiten('$gruppe', '$gruppeid', '$gruppenbezeichnunglink', '$id', '$titel')";
    $code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"$beschlussaktion\"><span class=\"cms_hinweis\">Beschluss löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
    $code .= "</p>";
  }
  $code .= "</li>";
  return $code;
}

function cms_schieber_generieren($id, $wert, $zusatzaktion = '') {
  $code = "";
  $vorsilbe = "in";
  if ($wert == 1) {$vorsilbe = "";}
  $code .= "<span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_$id\" onclick=\"cms_schieber('$id'); $zusatzaktion\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_$id\" id=\"cms_$id\" value=\"$wert\">";
  return $code;
}

function cms_fuehrendenull($zahl) {
  if (($zahl > -1) && ($zahl < 10)) {return '0'.$zahl;}
  else {return $zahl;}
}



function cms_stundefinden($beginn, $ZEITRAEUME) {
  $uhrzeit = date("H:i", $beginn);
  foreach ($ZEITRAEUME AS $Z) {
    if (($beginn >= $Z['beginn']) && ($beginn <= $Z['ende'])) {
      if (isset($Z['std'][$uhrzeit])) {return $Z['std'][$uhrzeit];}
      else {return $uhrzeit;}
    }
  }
  return $uhrzeit;
}
?>
