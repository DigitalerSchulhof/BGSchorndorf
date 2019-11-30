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

function cms_generiere_kleinste_id ($tabelle, $netz = "s", $benutzer = '-') {
  $fehler = false;
  $id = '-';

  if ($benutzer == '-') {
    if (isset($_SESSION['BENUTZERID'])) {$benutzer = $_SESSION['BENUTZERID'];}
    else {$fehler = true;}
  }

  if (!$fehler) {

    if ($netz == "l") {$db = cms_verbinden('l');}
    else if ($netz == "s") {$db = cms_verbinden('s');}
    else if ($netz == "p") {$db = cms_verbinden('p');}
    $jetzt = time();
    // Neue ID bestimmten und eintragen
    $sql = "SET FOREIGN_KEY_CHECKS = 0";
    $anfrage = $db->query($sql);  // Safe weil keine Eingabe

    $sql = $db->prepare("INSERT INTO $tabelle (id, idvon, idzeit) SELECT id, idvon, idzeit FROM (SELECT IFNULL(id*0,0)+? AS idvon, IFNULL(id*0,0)+? AS idzeit, IFNULL(MIN(id)+1,1) AS id FROM $tabelle WHERE id+1 NOT IN (SELECT id FROM $tabelle)) AS vorherigeid");
  	$sql->bind_param("ii", $benutzer, $jetzt);
  	$sql->execute();
  	$sql->close();

		$sql = "SET FOREIGN_KEY_CHECKS = 1";
    $anfrage = $db->query($sql);  // Safe weil keine Eingabe

    // ID zurückgewinnen
    $id = null;
    $sql = $db->prepare("SELECT id FROM $tabelle WHERE idvon = ? AND idzeit = ?");
  	$sql->bind_param("ii", $benutzer, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($id);
      $sql->fetch();
    }
    else {$fehler = true;}
    $sql->close();

    // Persönliche Daten löschen
    if ($id !== null) {
      $sql = $db->prepare("UPDATE $tabelle SET idvon = NULL, idzeit = NULL WHERE id = ?");
    	$sql->bind_param("i", $id);
    	$sql->execute();
    	$sql->close();
    }
    cms_trennen($db);
  }
  return $id;
}

function cms_generiere_anzeigename($vorname, $nachname, $titel) {
  if (!is_null($vorname)) {
    $anzeigename = $vorname." ".$nachname;
    if (strlen($titel)>0) {
      $anzeigename = $titel." ".$anzeigename;
    }
  }
  else {
    $anzeigename = false;
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

function cms_monatnamezuzahl($m) {
  if ($m == 'Januar') {return 1;}
  else if ($m == 'Februar') {return 2;}
  else if ($m == 'März') {return 3;}
  else if ($m == 'April') {return 4;}
  else if ($m == 'Mai') {return 5;}
  else if ($m == 'Juni') {return 6;}
  else if ($m == 'Juli') {return 7;}
  else if ($m == 'August') {return 8;}
  else if ($m == 'September') {return 9;}
  else if ($m == 'Oktober') {return 10;}
  else if ($m == 'November') {return 11;}
  else if ($m == 'Dezember') {return 12;}
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

function cms_fuehrendenull($zahl) {
  if (($zahl > -1) && ($zahl < 10)) {return '0'.$zahl;}
  else {return $zahl;}
}

function cms_status_generieren($status) {
  $s['icon'] = 'gruen.png';
  $s['text'] = 'fehlerfrei';
  if ($status == 0) {
    $s['icon'] = 'gruen.png';
    $s['text'] = 'fehlerfrei';
  }
  else if ($status == 1) {
    $s['icon'] = 'blau.png';
    $s['text'] = 'Meldung eingegangen';
  }
  else if ($status == 2) {
    $s['icon'] = 'gelb.png';
    $s['text'] = 'eingeschränkt nutzbar';
  }
  else if ($status == 3) {
    $s['icon'] = 'rot.png';
    $s['text'] = 'defekt - interne Problemlösung';
  }
  else if ($status == 4) {
    $s['icon'] = 'schwarz.png';
    $s['text'] = 'defekt - externe Problemlösung';
  }
  else if ($status == 5) {
    $s['icon'] = 'weiss.png';
    $s['text'] = 'von extern für gelöst erklärt';
  }
  return $s;
}

function cms_kategorieicons_generieren($id, $art, $icon = 'standard.png') {
  global $CMS_SCHLUESSEL, $CMS_GRUPPEN;
  $dbs = cms_verbinden('s');
  // Finde alle verwendeten Icons
  $verwendet = array();

  $sql = "";
  foreach ($CMS_GRUPPEN as $tabelle)	{
    $tabelle = cms_textzudb($tabelle);
    $sql .= " UNION (SELECT DISTINCT AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $tabelle)";
  }
  if (strlen($sql) > 0) {
    $sql = substr($sql, 7);
    $sql = "SELECT DISTINCT icon FROM ($sql) AS x";
    if ($anfrage = $dbs->query($sql)) { // TODO: Irgendwie safe machen
      while ($daten = $anfrage->fetch_assoc()) {
        array_push ($verwendet, $daten['icon']);
      }
      $anfrage->free();
    }
  }

  cms_trennen($dbs);

  $code = "";
  $code .= "<tr><th>Icon:</th><td>";
  $pfad = "res/ereignisse/$art";
  $anzahl = 0;
  if (is_dir($pfad)) {
    $verzeichnis = scandir($pfad, 0);
    foreach ($verzeichnis as $datei) {
      if (is_file("$pfad/$datei")) {
        $dateiteile = explode('.', $datei);
        $dateiname = $dateiteile[0];
        if (($dateiteile[1] == 'png') && ($dateiname != 'ort')) {
          $aktiv = "";
          if ($datei == $icon) {$aktiv = '_aktiv';}
          $klasse = "";
          if (in_array($datei, $verwendet)) {$klasse = 'cms_icon_verwendet';}
          $code .= "<span class=\"cms_kategorie_icon$aktiv $klasse\" id=\"".$id."_icon_$anzahl\" onclick=\"cms_kategorie_icon_waehlen('$id', $anzahl)\"><img src=\"$pfad/$datei\"><input type=\"hidden\" name=\"$id"."_icon_$anzahl"."_name\" id=\"".$id."_icon_$anzahl"."_name\" value=\"$datei\"></span> ";
          $anzahl ++;
        }
      }
    }
  }
  $code .= "<input type=\"hidden\" name=\"".$id."_icon\" id=\"".$id."_icon\" value=\"$icon\">";
  $code .= "<input type=\"hidden\" name=\"".$id."_anzahl\" id=\"".$id."_icon_anzahl\" value=\"".($anzahl-1)."\">";
  $code .= "</td></tr>";
  return $code;
}

function cms_geraeteverwalten_knopf($dbs) {
  $zusatz = "";
  $sqlwhere = "";
  $anzahldefekt = 0;
  $anzahlneu = 0;
  $anzahl = "";
  $sql = "SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM leihengeraete WHERE statusnr > 0) UNION (SELECT COUNT(*) AS anzahl FROM raeumegeraete WHERE statusnr > 0)) AS x";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $anzahldefekt = $daten['anzahl'];
    }
    $anfrage->free();
  }
  $sql = "SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM leihengeraete WHERE statusnr = 1 OR statusnr = 5) UNION (SELECT COUNT(*) AS anzahl FROM raeumegeraete WHERE statusnr = 1 OR statusnr = 5)) AS x";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $anzahlneu = $daten['anzahl'];
    }
    $anfrage->free();
  }
  $zusatz = '';
  if ($anzahlneu > 0) {
    $anzahl = " <span class=\"cms_meldezahl cms_meldezahl_wichtig\"><b>$anzahlneu</b> / $anzahldefekt</span>";
  }
  else if ($anzahldefekt > 0) {
    $anzahl = " <span class=\"cms_meldezahl\">$anzahldefekt</span>";
  }
  return "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Geräte_verwalten\">Geräte verwalten".$anzahl."</a>";
}

function cms_terminegenehmigen_knopf($dbs) {
  global $CMS_RECHTE, $CMS_GRUPPEN;
  $zusatz = "";
  $sql = "";
  $code = "";
  if ($CMS_RECHTE['Organisation']['Termine genehmigen']) {$sql .= " UNION (SELECT COUNT(*) AS anzahl FROM termine WHERE genehmigt = 0)";}
  if ($CMS_RECHTE['Organisation']['Gruppentermine genehmigen']) {
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT COUNT(*) AS anzahl FROM $gk"."termineintern WHERE genehmigt = 0)";
    }
  }
  $sql = substr($sql, 7);
  $sql = "SELECT SUM(anzahl) AS anzahl FROM ($sql) AS x";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $zusatz = "";
      $anzahl = "";
      if ($daten['anzahl'] > 0) {
        $zusatz = "cms_meldezahl_wichtig";
        $anzahl = "<span class=\"cms_meldezahl $zusatz\">".$daten['anzahl']."</span>";
      }
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Termine_genehmigen\">Termine genehmigen".$anzahl."</a>";
    }
    $anfrage->free();
  }
  return $code;
}

function cms_blogeintraegegenehmigen_knopf($dbs) {
  global $CMS_RECHTE, $CMS_GRUPPEN;
  $code = "";
  $zusatz = "";
  $sql = "";
  if ($CMS_RECHTE['Organisation']['Blogeinträge genehmigen']) {$sql .= " UNION (SELECT COUNT(*) AS anzahl FROM blogeintraege WHERE genehmigt = 0)";}
  if ($CMS_RECHTE['Organisation']['Gruppenblogeinträge genehmigen']) {
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT COUNT(*) AS anzahl FROM $gk"."blogeintraegeintern WHERE genehmigt = 0)";
    }
  }
  $sql = substr($sql, 7);
  $sql = "SELECT SUM(anzahl) AS anzahl FROM ($sql) AS x";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $zusatz = "";
      $anzahl = "";
      if ($daten['anzahl'] > 0) {
        $zusatz = "cms_meldezahl_wichtig";
        $anzahl = "<span class=\"cms_meldezahl $zusatz\">".$daten['anzahl']."</span>";
      }
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\">Blogeinträge genehmigen".$anzahl."</a>";
    }
    $anfrage->free();
  }
  return $code;
}

function cms_galeriengenehmigen_knopf($dbs) {
  $code = "";
  $zusatz = "";
  $sql = "SELECT COUNT(*) AS anzahl FROM galerien WHERE genehmigt = 0";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $zusatz = "";
      $anzahl = "";
      if ($daten['anzahl'] > 0) {
        $zusatz = "cms_meldezahl_wichtig";
        $anzahl = "<span class=\"cms_meldezahl $zusatz\">".$daten['anzahl']."</span>";
      }
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Galerien_genehmigen\">Galerien genehmigen".$anzahl."</a>";
    }
    $anfrage->free();
  }
  return $code;
}

function cms_identitaetsdiebstaehle_knopf($dbs) {
  $code = "";
  $zusatz = "";
  $sql = "SELECT COUNT(*) AS anzahl FROM identitaetsdiebstahl";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $zusatz = "";
      $anzahl = "";
      if ($daten['anzahl'] > 0) {
        $zusatz = "cms_meldezahl_wichtig";
        $anzahl = "<span class=\"cms_meldezahl $zusatz\">".$daten['anzahl']."</span>";
      }
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Identitätsdiebstähle_behandeln\">Identitätsdiebstähle behandeln".$anzahl."</a>";
    }
    $anfrage->free();
  }
  return $code;
}

function cms_hausmeisterauftraege_knopf($dbs) {
  $zusatz = "";
  $anzahlauftraege = 0;
  $anzahlneu = 0;
  $anzahl = "";
  $sql = "SELECT COUNT(*) AS anzahl FROM hausmeisterauftraege";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $anzahlauftraege = $daten['anzahl'];
    }
    $anfrage->free();
  }
  $sql = "SELECT COUNT(*) AS anzahl FROM hausmeisterauftraege WHERE status != 'e'";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $anzahlneu = $daten['anzahl'];
    }
    $anfrage->free();
  }
  $zusatz = '';
  if ($anzahlneu > 0) {
    $anzahl = " <span class=\"cms_meldezahl cms_meldezahl_wichtig\"><b>$anzahlneu</b> / $anzahlauftraege</span>";
  }
  else if ($anzahlauftraege > 0) {
    $anzahl = " <span class=\"cms_meldezahl\">$anzahlauftraege</span>";
  }
  return "<a class=\"cms_button\" href=\"Schulhof/Hausmeister/Aufträge\">Hausmeisterbuch".$anzahl."</a>";
}

function cms_auffaelliges_knopf($dbs) {
  $code = "";
  $zusatz = "";
  $sql = "SELECT COUNT(*) AS anzahl FROM auffaelliges WHERE status=0";
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    if ($daten = $anfrage->fetch_assoc()) {
      $zusatz = "";
      $anzahl = "";
      if ($daten['anzahl'] > 0) {
        $zusatz = "cms_meldezahl_wichtig";
        $anzahl = "<span class=\"cms_meldezahl $zusatz\">".$daten['anzahl']."</span>";
      }
      $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Auffälliges\">Neues auffälliges Verhalten ".$anzahl."</a>";
    }
    $anfrage->free();
  }
  return $code;
}

function cms_chatmeldungen_knopf($dbs) {
  global $CMS_GRUPPEN;
  $code = "";
  $zusatz = "";
  $sql = "";
  foreach($CMS_GRUPPEN as $i => $g) {
    $gk = cms_textzudb($g);
    $sql .= " SELECT '$g' as gruppe, COUNT(*) AS anzahl FROM $gk"."chatmeldungen UNION";
  }
  $sql = substr($sql, 0, -5);
  $anzahl = 0;
  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      $anzahl += $daten["anzahl"];
    }
    $anfrage->free();
  }
  $zusatz = "";
  if ($anzahl > 0) {
    $zusatz = "cms_meldezahl_wichtig";
    $anzahl = " <span class=\"cms_meldezahl $zusatz\">".$anzahl."</span>";
  } else
    $anzahl = "";

  $code .= "<a class=\"cms_button\" href=\"Schulhof/Aufgaben/Chatmeldungen\">Chatmeldungen".$anzahl."</a>";
  return $code;
}

function cms_sonderrollen_generieren() {
	global $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_GRUPPEN, $CMS_BENUTZERART;
	$code = "";
	$dbs = cms_verbinden('s');
  if ($CMS_BENUTZERART == 'l') {
    $code .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Probleme_melden\">Probleme melden</a></li> ";

  }
	if ($CMS_RECHTE['Technik']['Geräte verwalten']) {
    $code .= "<li>".cms_geraeteverwalten_knopf($dbs)."</li> ";
  }
	if ($CMS_RECHTE['Organisation']['Termine genehmigen'] || $CMS_RECHTE['Organisation']['Gruppentermine genehmigen']) {
    $code .= "<li>".cms_terminegenehmigen_knopf($dbs)."</li> ";
	}
  if ($CMS_RECHTE['Organisation']['Blogeinträge genehmigen'] || $CMS_RECHTE['Organisation']['Gruppenblogeinträge genehmigen']) {
    $code .= "<li>".cms_blogeintraegegenehmigen_knopf($dbs)."</li> ";
	}
	if ($CMS_RECHTE['Organisation']['Galerien genehmigen']) {
    $code .= "<li>".cms_galeriengenehmigen_knopf($dbs)."</li> ";
	}
	if ($CMS_RECHTE['Administration']['Identitätsdiebstähle behandeln']) {
		$code .= "<li>".cms_identitaetsdiebstaehle_knopf($dbs)."</li> ";
	}
  if ($CMS_RECHTE['Technik']['Hausmeisteraufträge sehen'] && $CMS_RECHTE['Technik']['Hausmeisteraufträge markieren']) {
		$code .= "<li>".cms_hausmeisterauftraege_knopf($dbs)."</li> ";
	}
  if ($CMS_RECHTE['Website']['Auffälliges verwalten']) {
		$code .= "<li>".cms_auffaelliges_knopf($dbs)."</li> ";
	}
  if ($CMS_RECHTE['Gruppen']['Chatmeldungen sehen'] || $CMS_RECHTE['Gruppen']['Chatmeldungen verwalten']) {
		$code .= "<li>".cms_chatmeldungen_knopf($dbs)."</li> ";
	}
	cms_trennen($dbs);
	return $code;
}

function cms_schieber_generieren($id, $wert, $zusatzaktion = '') {
  $code = "";
  $vorsilbe = "in";
  if ($wert == 1) {$vorsilbe = "";}
  $code .= "<span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_$id\" onclick=\"cms_schieber('$id'); $zusatzaktion\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_$id\" id=\"cms_$id\" value=\"$wert\">";
  return $code;
}

function cms_positionswahl_generieren($id, $position, $maxpos, $neu = false) {
  if ($neu) {$maxpos++;}
  $code = "<select name=\"$id\" id=\"$id\">";
  for ($i=1; $i<=$maxpos; $i++) {
    if ($i == $position) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option$zusatz value=\"$i\">$i</option>";
  }
  $code .= "</select>";
  return $code;
}
/**
* $index: bei true: selected nach index festlegen, ansonsten nach value
**/
function cms_select_generieren($id, $klasse, $werte, $wert = null, $index = false) {
  $code = "<select name=\"$id\", id=\"$id\" class=\"$klasse\">";
  foreach ($werte as $i => $w) {
    if (($index && $i == $wert) || (!$index && $w == $wert)) {$zusatz = " selected=\"selected\"";} else {$zusatz = "";}
    $code .= "<option$zusatz value=\"".($index?$i:$w)."\">$w</option>";
  }
  $code .= "</select>";
  return $code;
}

function cms_generiere_bilddaten($pfad) {
  $typ = pathinfo($pfad, PATHINFO_EXTENSION);
  $daten = file_get_contents($pfad);
  return 'data:image/'.$typ.';base64,'.base64_encode($daten);
}

function cms_gruppeninfos_generieren ($dbs) { // QUESTION: Wofür ist das? »$gruppen« ist leer?
  global $CMS_SCHLUESSEL, $CMS_GRUPPEN;
  $gruppen = array();
  foreach ($gruppen as $a) {
    $a = cms_textzudb($a);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $a) AS x ORDER BY bezeichnung";
    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      while ($daten = $anfrage->fetch_assoc()) {
        $gruppen[$a][$daten['id']]['bezeichnung'] = $daten['bezeichnung'];
        $gruppen[$a][$daten['id']]['icon'] = $daten['icon'];
        $gruppen[$a][$daten['id']]['id'] = $daten['id'];
      }
      $anfrage->free();
    }
  }
  return $gruppen;
}

function cms_stunde_farbe($kuerzel) {
  $kuerzel .= 'FFF';
  $kuerzel = MD5($kuerzel);
  $f1 = min(floor(ord(substr($kuerzel,0,1))*4.2),255);
  $f2 = min(floor(ord(substr($kuerzel,1,1))*4.2),255);
  $f3 = min(floor(ord(substr($kuerzel,2,1))*4.2),255);
  $durchschnitt = ($f1+$f2+$f3)/3;
  if ($durchschnitt > 170) {$schrift = 'color:#000000;';}
  else {$schrift = 'color:#ffffff;';}
  return 'background:#'.dechex($f1).dechex($f2).dechex($f3).';'.$schrift;
}

function file_get_contents_utf8($datei) {
  $inhalt = file_get_contents($datei);
  $code = mb_convert_encoding($inhalt, 'UTF-8',
          mb_detect_encoding($inhalt, 'UTF-8, ISO-8859-1', true));
  $code = str_replace("\n", "", $code);
  $code = str_replace("\N", "", $code);
  $code = str_replace("\r", "", $code);
  $code = str_replace("\R", "", $code);
  $code = str_replace("\t", "", $code);
  $code = str_replace("&nbsp;", "", $code);
  return $code;
}

function cms_textzudb ($text) {
  $text = mb_strtolower($text);
  $text = str_replace(' ', '', $text);
  $text = str_replace('ä', 'ae', $text);
  $text = str_replace('ö', 'oe', $text);
  $text = str_replace('ü', 'ue', $text);
  $text = str_replace('ß', 'ss', $text);
  return $text;
}

function cms_textzulink ($text) {
  $text = str_replace(' ', '_', $text);
  return $text;
}

function cms_linkzutext ($text) {
  $text = str_replace('_', ' ', $text);
  return $text;
}

function cms_vornegross($text) {
  return strtoupper(substr($text,0,1)).strtolower(substr($text,1,strlen($text)-1));
}

function cms_togglebutton_generieren ($id, $buttontext, $wert, $zusatzaktion = "") {
  if ($wert != 1) {$zusatz = "in";} else {$zusatz = "";}
  return "<span class=\"cms_toggle_$zusatz"."aktiv\" id=\"$id"."_K\" onclick=\"cms_togglebutton('$id');$zusatzaktion\">$buttontext</span><input type=\"hidden\" id=\"$id\" name=\"$id\" value=\"$wert\">";
}

function cms_toggleiconbutton_generieren ($id, $icon, $buttontext, $wert, $zusatzaktion = "") {
  if ($wert != 1) {$zusatz = "in";} else {$zusatz = "";}
  return "<span class=\"cms_iconbutton cms_toggle_$zusatz"."aktiv\" id=\"$id"."_K\" onclick=\"cms_toggleiconbutton('$id');$zusatzaktion\" style=\"background-image: url('$icon')\">$buttontext</span><input type=\"hidden\" id=\"$id\" name=\"$id\" value=\"$wert\">";
}

function cms_toggleeinblenden_generieren ($id, $buttontext1, $buttontext0, $inhalt, $wert) {
  $buttontext = "";
  if ($wert != 1) {$zusatz = "in"; $buttontext = $buttontext1; $style = "none";}
  else {$zusatz = ""; $buttontext = $buttontext0; $style = "block";}
  return "<div class=\"cms_toggleeinblenden\" id=\"$id"."_F\" style=\"display: $style;\">$inhalt</div><p><span class=\"cms_toggle_$zusatz"."aktiv\" id=\"$id"."_K\" onclick=\"cms_toggleeinblenden('$id', '$buttontext1', '$buttontext0');\">$buttontext</span><input type=\"hidden\" id=\"$id\" name=\"$id\" value=\"$wert\"></p>";
}

function cms_toggletext_generieren ($id, $buttontext1, $buttontext0, $wert, $zusatzaktion = "") {
  $buttontext = "";
  if ($wert != 1) {$zusatz = "in"; $buttontext = $buttontext1; $style = "none";}
  else {$zusatz = ""; $buttontext = $buttontext0; $style = "block";}
  return "<span class=\"cms_toggle_$zusatz"."aktiv\" id=\"$id"."_K\" onclick=\"cms_toggletextbutton('$id', '$buttontext1', '$buttontext0');$zusatzaktion\">$buttontext</span><input type=\"hidden\" id=\"$id\" name=\"$id\" value=\"$wert\">";
}

function cms_toggleiconbuttontext_generieren ($id, $icon, $buttontext1, $buttontext0, $wert, $zusatzaktion = "") {
  if ($wert != 1) {$zusatz = "in"; $buttontext = $buttontext0;} else {$zusatz = ""; $buttontext = $buttontext1;}
  return "<span class=\"cms_iconbutton cms_toggle_$zusatz"."aktiv\" id=\"$id"."_K\" onclick=\"cms_toggleiconbuttontext('$id', '$buttontext1', '$buttontext0');$zusatzaktion\" style=\"background-image: url('$icon')\">$buttontext</span><input type=\"hidden\" id=\"$id\" name=\"$id\" value=\"$wert\">";
}

function cms_amtstraeger ($dbs, $id, $amt) {
  global $CMS_GRUPPEN, $CMS_BENUTZERID, $CMS_BENUTZERART;
  $amtstraeger = false;

  if (cms_check_ganzzahl($id, 0) && (($amt == 'mitglieder') || ($amt == 'aufsicht') || ($amt == 'vorsitz'))) {
    // Prüfe Gruppenmitglieder
    $sql = "";
    if ($CMS_BENUTZERART == 'l') {$limit = 1;}
    if ($CMS_BENUTZERART == 'e') {$limit = 3;}
    if ($CMS_BENUTZERART == 'v') {$limit = 2;}
    if ($CMS_BENUTZERART == 's') {$limit = 3;}
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      // Mitglieder aller Gruppen in denen ich Mitglied bin
      if ($amt == 'mitglieder') {
        $sql .= " UNION (SELECT person FROM $gk"."$amt WHERE person = $id AND gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID))";
      }
      // Vorsitz / Aufsicht der Gruppen, die ich sehe
      else {
        $sql .= " UNION (SELECT person FROM $gk"."$amt WHERE person = $id AND (gruppe IN (SELECT id AS gruppe FROM $gk WHERE sichtbar >= $limit) OR gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID)))";
        //
      }
    }
    $sql = substr($sql,7);
    $sql = "SELECT DISTINCT COUNT(*) AS anzahl FROM ($sql) AS x";
    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      if ($daten = $anfrage->fetch_assoc()) {
        if ($daten['anzahl'] > 0) {$amtstraeger = true;}
      }
      $anfrage->free();
    }
  }
  return $amtstraeger;
}

function cms_postfachvorschau($text) {
  $html_reg = '/<+\s*\/*\s*([A-Z][A-Z0-9]*)\b[^>]*\/*\s*>+/i';
  $text = preg_replace($html_reg, " ", $text);
  $zwischen = explode(" ", $text);
  $vorschau = "";
  foreach ($zwischen as $z) {$vorschau .= $z." ";}
  if (count($zwischen) > 15) {$text .= "...";}
  return $vorschau;
}

function cms_listezuabsatz($liste) {
  $absatz = str_replace("<ul>", "<p>", $liste);
  $absatz = str_replace("</ul>", "</p>", $absatz);
  $absatz = str_replace("<li>", "", $absatz);
  $absatz = str_replace("</li>", " ", $absatz);
  return $absatz;
}


function cms_generiere_hinweisicon($icon, $hinweis) {
  return "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$hinweis</span><img src=\"res/icons/klein/$icon.png\"></span>";
}

function cms_generiere_hinweisinformation($text, $hinweis) {
  return "<span class=\"cms_hinweis_aussen\">$text:<span class=\"cms_hinweis\">$hinweis</span>";
}


function cms_generiere_sqlidliste($idliste) {
  return "(".str_replace('|', ',', substr($idliste, 1)).")";
}

function cms_generiere_idlisteanzahl($idliste) {
  if ($idliste == "()") {return false;}
  return count(explode(',', $idliste));
}

function cms_ladeicon() {return "<div class=\"cms_ladeicon\"><div></div><div></div><div></div><div></div></div>";}

/**
* Gültige SQL-Query braucht $tabelle, sonst kommt nur der select Teil
**/
function cms_sql_mit_aes($felder, $tabelle = "", $bedingung = "") {
  global $CMS_SCHLUESSEL;
  $sql = "";
  if(strlen($tabelle))
    $sql .= "SELECT ";

  foreach($felder as $f)
    $sql .= "AES_DECRYPT($f, '$CMS_SCHLUESSEL') as $f, ";

  $sql = substr($sql, 0, -2);
  if(strlen($tabelle)) {
    $sql .= " FROM $tabelle";
    if(strlen($bedingung))
      $sql .= " WHERE $bedingung";
  }
  return $sql;
}

// Alt - Aktuell - Neu
function cms_sql_aan($wert, $aes = false) {
  global $CMS_SCHLUESSEL;
  $r = "";
  if(is_array($wert))
    foreach($wert as $i => $w)
      $r .= cms_sql_aan($w, $aes);
  else {
    if($aes)
      $f = "AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
    else
      $f = "?";
    $r = $wert."alt = $f, ".$wert."aktuell = $f, ".$wert."neu = $f,";
  }
  return $r;
}
//  Aktuell - Neu
function cms_sql_an($wert, $aes = false) {
  global $CMS_SCHLUESSEL;
  $r = "";
  if(is_array($wert))
    foreach($wert as $i => $w)
      $r .= cms_sql_an($w, $aes);
  else {
    if($aes)
      $f = "AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
    else
      $f = "?";
    $r = $wert."aktuell = $f, ".$wert."neu = $f,";
  }
  return $r;
}

function cms_sql_set_fragezeichen($wert, $aes = false) {
  global $CMS_SCHLUESSEL;
  $r = "";
  if(is_array($wert))
    foreach($wert as $i => $w)
      $r .= cms_sql_set_fragezeichen($w, $aes);
  else {
    if($aes)
      $f = "AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
    else
      $f = "?";
    $r = $wert." = $f, ";
  }
  return $r;
}

function cms_generiere_nachladen($id, $script) {
  return "<div id=\"$id\" class=\"cms_gesichert\"><div class=\"cms_meldung_laden\">".cms_ladeicon()."<p>Inhalte werden geladen...<script>$script</script></p></div></div>";
}

function cms_finde_montag($tag, $monat, $jahr) {
  $tagzeit = mktime(0,0,0, $monat, $tag, $jahr);
  $wochentag = date('N', $tagzeit);
  $datummo = mktime(0,0,0, $monat, $tag-$wochentag+1, $jahr);
  $datum = array();
  $datum['T'] = date('d', $datummo);
  $datum['M'] = date('m', $datummo);
  $datum['J'] = date('Y', $datummo);
  return $datum;
}

function cms_ausgabe_editor($text) {
  if (preg_match("/<iframe/", $text)) {
    $CMS_DSGVO_EINWILLIGUNG_B = false;
    if (isset($_SESSION['DSGVO_EINWILLIGUNG_B'])) {$CMS_DSGVO_EINWILLIGUNG_B = $_SESSION['DSGVO_EINWILLIGUNG_B'];}
    if ($CMS_DSGVO_EINWILLIGUNG_B) {return $text;}
    else {return cms_meldung_einwilligungB();}
  }
  else {
    return $text;
  }
}
?>
