<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stundenplanung durchführen'];

$code = "";
if ($zugriff) {

  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['STUNDENPLANUNGSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['STUNDENPLANUNGSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (isset($_SESSION['STUNDENPLANUNGZEITRAUM'])) {
    $ZEITRAUM = $_SESSION['STUNDENPLANUNGZEITRAUM'];
    if ($ZEITRAUM !== '-') {
      $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM zeitraeume WHERE id = ? AND schuljahr = ?");
      $sql->bind_param("ii", $ZEITRAUM, $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
      $sql->close();
    }
  }

  if (!isset($_SESSION['STUNDENPLANUNGSTUFEN']) || !isset($_SESSION['STUNDENPLANUNGKLASSEN']) || !isset($_SESSION['STUNDENPLANUNGKURSE']) || !isset($_SESSION['STUNDENPLANUNGLEHRER']) || !isset($_SESSION['STUNDENPLANUNGVOLLBILD']) || !isset($_SESSION['STUNDENPLANUNGRAUM']) || !isset($_SESSION['STUNDENPLANUNGMODUS']) || !isset($_SESSION['STUNDENPLANUNGRYTHMUS'])) {
    $sjfehler = true;
    echo 1;
  }
  else {
    $stufegewaehlt = $_SESSION['STUNDENPLANUNGSTUFEN'];
    $klassegewaehlt = $_SESSION['STUNDENPLANUNGKLASSEN'];
    $kursgewaehlt = $_SESSION['STUNDENPLANUNGKURSE'];
    $lehrergewaehlt = $_SESSION['STUNDENPLANUNGLEHRER'];
    $raumgewaehlt = $_SESSION['STUNDENPLANUNGRAUM'];
    $rythmusgewaehlt = $_SESSION['STUNDENPLANUNGRYTHMUS'];
    $vollbildgewaehlt = $_SESSION['STUNDENPLANUNGVOLLBILD'];
    $modusgewaehlt = $_SESSION['STUNDENPLANUNGMODUS'];
  }


  if (!$sjfehler) {
    $code .= "<h1>Stundenplanung</h1>";
    $zeitraumfehler = false;

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_stundenplanung_vorbereiten($id, '-')\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeitraumwahlcode = "";
    // Alle Zeiträume laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM zeitraeume WHERE schuljahr = ? ORDER BY beginn DESC");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($id, $zrbez);
      while ($sql->fetch()) {
        if ($ZEITRAUM == '-') {$ZEITRAUM = $id;}
        $klasse = "cms_button";
        if ($id == $ZEITRAUM) {$klasse .= "_ja";}
        $zeitraumwahlcode .= "<span class=\"$klasse\" onclick=\"cms_stundenplanung_vorbereiten($SCHULJAHR, $id)\">$zrbez</span> ";
      }
    }
    $sql->close();
    if (strlen($zeitraumwahlcode) > 0) {$code .= "<p>".$zeitraumwahlcode."</p>";} else {$zeitraumfehler = true;}

    if ($zeitraumfehler) {
      $code .= cms_meldung('info', '<h4>Keine Zeiträume</h4><p>Die Stundenplanung findet in Zeiträumen statt. Noch sind in diesem Schuljahr keine Angelegt.</p><p><span class="cms_button" onclick="cms_stundenplanzeitraeume_vorbereiten('.$SCHULJAHR.')">zu den Zeiträumen</span></p>');
    }
    else {
      $code .= "</div>";

      // Zeitraum laden
      $sql = $dbs->prepare("SELECT beginn, ende, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE id = ?");
      $sql->bind_param("i", $ZEITRAUM);
      if ($sql->execute()) {
        $sql->bind_result($beginn, $ende, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen);
        $sql->fetch();
      }
      $sql->close();

      if (($rythmusgewaehlt > $rythmen) || ($rythmen <= 1)) {$rythmusgewaehlt = '0';}

      $SCHULTAGE = array();
      if ($mo == '1') {array_push($SCHULTAGE, "1");}
      if ($di == '1') {array_push($SCHULTAGE, "2");}
      if ($mi == '1') {array_push($SCHULTAGE, "3");}
      if ($do == '1') {array_push($SCHULTAGE, "4");}
      if ($fr == '1') {array_push($SCHULTAGE, "5");}
      if ($sa == '1') {array_push($SCHULTAGE, "6");}
      if ($so == '1') {array_push($SCHULTAGE, "7");}

      $SCHULSTUNDEN = array();
      $SCHULSTUNDENIDS = array();
      $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
      $sql->bind_param("i", $ZEITRAUM);
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

      $zusatz = "";
      if ($vollbildgewaehlt) {$zusatz = " class=\"cms_vollbild\"";}

      $code .= "<div id=\"cms_stundenplanung_vollbild\"$zusatz>";
      $code .= "<div class=\"cms_vollbild_innen\">";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<p class=\"cms_rechtsbuendig\">";
      if ($modusgewaehlt != 'L') {$modusgewaehlt = 'P';}
      if ($modusgewaehlt == 'L') {$code .= "<span class=\"cms_button\" onclick=\"cms_stundenplanung_modus('P')\">Platzieren</span> <span class=\"cms_button_nein\" onclick=\"cms_stundenplanung_modus('P')\">Löschen</span> ";}
      else {$code .= "<span class=\"cms_button_ja\" onclick=\"cms_stundenplanung_modus('L')\">Platzieren</span> <span class=\"cms_button\" onclick=\"cms_stundenplanung_modus('L')\">Löschen</span> ";}
      if ($vollbildgewaehlt != '0') {$vollbildgewaehlt = '1';}
      if ($vollbildgewaehlt == '0') {$code .= "<span class=\"cms_button\" onclick=\"cms_stundenplanung_vollbild('1')\">Vollbild</span>";}
      else {$code .= "<span class=\"cms_button_nein\" onclick=\"cms_stundenplanung_vollbild('0')\">&times;</span>";}
      $code .= "</p>";
      $code .= "</div>";

      // Stufen des Schuljahres laden
      $STUFEN = array();
      $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM stufen WHERE schuljahr = ? ORDER BY reihenfolge");
      $sql->bind_param("i", $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($STUFEN, $einzeln);
        }
      }
      $sql->close();
      if ((count($STUFEN) > 0) && ($stufegewaehlt === 'x')) {$stufegewaehlt = $STUFEN[0]['id'];}
      if (count($STUFEN) == 0) {$stufegewaehlt = '-';}

      // Klassen der Stufe
      $KLASSEN = array();
      if ($stufegewaehlt === '-') {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM klassen WHERE schuljahr = ? AND stufe IS NULL) AS x ORDER BY bez");
        $sql->bind_param("i", $SCHULJAHR);
      }
      else {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM klassen WHERE schuljahr = ? AND stufe = ?) AS x ORDER BY bez");
        $sql->bind_param("ii", $SCHULJAHR, $stufegewaehlt);
      }
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($KLASSEN, $einzeln);
        }
      }
      $sql->close();
      if ((count($KLASSEN) > 0) && ($klassegewaehlt === 'x')) {$klassegewaehlt = $KLASSEN[0]['id'];}
      if (count($KLASSEN) == 0) {$klassegewaehlt = '-';}

      // Kurse der Stufe
      $KURSE = array();
      if (($stufegewaehlt === '-') && ($klassegewaehlt === '-')) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS bez FROM kurse LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE schuljahr = ? AND stufe IS NULL AND klasse IS NULL) AS x ORDER BY bez");
        $sql->bind_param("i", $SCHULJAHR);
      }
      else if (($stufegewaehlt === '-') && ($klassegewaehlt !== '-')) {
        $sql = $dbs->prepare("SELECT DISTINCT * FROM (SELECT id, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS bez FROM kurse JOIN kurseklassen ON kurse.id = kurseklassen.kurs  WHERE schuljahr = ? AND stufe IS NULL AND klasse = ?) AS x ORDER BY bez");
        $sql->bind_param("ii", $SCHULJAHR, $klassegewaehlt);
      }
      else if (($stufegewaehlt !== '-') && ($klassegewaehlt === '-')) {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS bez FROM kurse LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE schuljahr = ? AND stufe = ? AND klasse IS NULL) AS x ORDER BY bez");
        $sql->bind_param("ii", $SCHULJAHR, $stufegewaehlt);
      }
      else {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS bez FROM kurse JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE schuljahr = ? AND stufe = ? AND klasse = ?) AS x ORDER BY bez");
        $sql->bind_param("iii", $SCHULJAHR, $stufegewaehlt, $klassegewaehlt);
      }
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($KURSE, $einzeln);
        }
      }
      $sql->close();
      if ((count($KURSE) > 0) && ($kursgewaehlt === 'x')) {$kursgewaehlt = $KURSE[0]['id'];}
      if (count($KURSE) == 0) {$kursgewaehlt = '-';}

      // Lehrer des Kurses
      $LEHRER = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY kuerzel, nachname, vorname, titel");
      if ($sql->execute()) {
        $sql->bind_result($eid, $evor, $enach, $etitel, $ekurz);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $name = $ekurz;
          if (strlen($name) == 0) {$name = cms_generiere_anzeigename($evor, $enach, $etitel);}
          $einzeln['name'] = $name;
          array_push($LEHRER, $einzeln);
        }
      }
      $sql->close();
      if ((count($LEHRER) > 0) && ($lehrergewaehlt === 'x')) {$lehrergewaehlt = $LEHRER[0]['id'];}
      if (count($LEHRER) == 0) {$lehrergewaehlt = '-';}

      // Räume laden
      $RAEUME = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM raeume WHERE verfuegbar = '1') AS x ORDER BY bez");
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($RAEUME, $einzeln);
        }
      }
      $sql->close();
      if ((count($RAEUME) > 0) && ($raumgewaehlt === 'x')) {$raumgewaehlt = $RAEUME[0]['id'];}
      if (count($RAEUME) == 0) {$raumgewaehlt = '-';}



      $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
      $code .= "<h2>Rythmen</h2>";
      $einzelcode = "";
      if ($rythmen > 1) {
        for ($r = 1; $r <= $rythmen; $r++) {
          $wert = 0;
          if ($r."" == $rythmusgewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_rythmen_'.$r, chr(64+$r), $wert, "cms_stundenplanung_rythmuswaehlen(".$r.")")." ";
        }
      }
      $wert = 0;
      if ($rythmusgewaehlt == '0') {$wert = 1;}
      $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_rythmen_ue', 'immer', $wert, "cms_stundenplanung_rythmuswaehlen('0')");
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_rythmus\" name=\"cms_stundenplanung_rythmus\" value=\"$rythmusgewaehlt\"></p>";

      $code .= "<h2>Stufen</h2>";
      $einzelcode = "";
      foreach ($STUFEN as $e) {
        $wert = 0;
        if ($e['id']."" == $stufegewaehlt."") {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_stufen_'.$e['id'], $e['bez'], $wert, "cms_stundenplanung_stufewaehlen(".$e['id'].")")." ";
      }
      $wert = 0;
      if ($stufegewaehlt === '-') {$wert = 1;}
      $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_stufen_ue', 'stufenübergreifend', $wert, "cms_stundenplanung_stufewaehlen('-')");
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_stufe\" name=\"cms_stundenplanung_stufe\" value=\"$stufegewaehlt\"></p>";

      $code .= "<h2>Klassen</h2>";
      $einzelcode = "";

      foreach ($KLASSEN as $e) {
        $wert = 0;
        if ($e['id']."" == $klassegewaehlt."") {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_klassen_'.$e['id'], $e['bez'], $wert, "cms_stundenplanung_klassewaehlen(".$e['id'].")")." ";
      }
      $wert = 0;
      if ($klassegewaehlt === '-') {$wert = 1;}
      $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_klassen_ue', 'klassenübergreifend', $wert, "cms_stundenplanung_klassewaehlen('-')");
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_klasse\" name=\"cms_stundenplanung_klasse\" value=\"$klassegewaehlt\"></p>";
      $code .= "</div></div>";

      $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
      $code .= "<h2>Kurse</h2>";
      $einzelcode = "";
      foreach ($KURSE as $e) {
        $wert = 0;
        if ($e['id']."" == $kursgewaehlt."") {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_kurse_'.$e['id'], $e['bez'], $wert, "cms_stundenplanung_kurswaehlen(".$e['id'].")")." ";
      }
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_kurs\" name=\"cms_stundenplanung_kurs\" value=\"$kursgewaehlt\"></p>";

      $code .= "<h2>Lehrer</h2>";
      $einzelcode = "";
      foreach ($LEHRER as $e) {
        $wert = 0;
        if ($e['id']."" == $lehrergewaehlt."") {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_lehrer_'.$e['id'], $e['name'], $wert, "cms_stundenplanung_lehrerwaehlen(".$e['id'].")")." ";
      }
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_kurs\" name=\"cms_stundenplanung_kurs\" value=\"$kursgewaehlt\"></p>";
      $code .= "</div></div>";

      $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
      $code .= "<h2>Räume</h2>";
      $einzelcode = "";
      foreach ($RAEUME as $e) {
        $wert = 0;
        if ($e['id']."" == $raumgewaehlt."") {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_raume_'.$e['id'], $e['bez'], $wert, "cms_stundenplanung_raumwaehlen(".$e['id'].")")." ";
      }
      $code .= "<p>$einzelcode<input type=\"hidden\" id=\"cms_stundenplanung_raum\" name=\"cms_stundenplanung_raum\" value=\"$raumgewaehlt\"></p>";
      $code .= "</div></div>";
      $code .= "<div class=\"cms_clear\"></div>";
      $code .= "<div class=\"cms_spalte_i\">";
      echo $code;
      $code = "";

      if (count($SCHULSTUNDEN) > 0) {
        $KLASSENUNTERRICHT = array();
        $LEHRERUNTERRICHT = array();
        $RAUMUNTERRICHT = array();
        foreach($SCHULTAGE as $t) {
          $KLASSENUNTERRICHT[$t] = array();
          $LEHRERUNTERRICHT[$t] = array();
          $RAUMUNTERRICHT[$t] = array();
          foreach ($SCHULSTUNDENIDS as $s) {
            $KLASSENUNTERRICHT[$t][$SCHULSTUNDEN[$s]['id']] = array();
            $LEHRERUNTERRICHT[$t][$SCHULSTUNDEN[$s]['id']] = array();
            $RAUMUNTERRICHT[$t][$SCHULSTUNDEN[$s]['id']] = array();
          }
        }
        // UNTERRICHT IM RAUM LADEN
        if ($raumgewaehlt != '-') {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN faecher ON kurse.fach = faecher.id WHERE raum = ? AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param('iii', $raumgewaehlt, $ZEITRAUM, $rythmusgewaehlt);
          if ($sql->execute()) {
            $sql->bind_result($eid, $esstd, $etag, $eryth, $ekurs, $elehrer, $eraum, $ekbez, $elbez, $erbez, $efarbe);
            while ($sql->fetch()) {
              $stunde = array();
              $stunde['id'] = $eid;
              $stunde['rythmus'] = $eryth;
              $stunde['kursid'] = $ekurs;
              $stunde['kursbez'] = $ekbez;
              $stunde['lehrerid'] = $elehrer;
              $stunde['lehrerbez'] = $elbez;
              $stunde['raumid'] = $eraum;
              $stunde['raumbez'] = $erbez;
              $stunde['farbe'] = $efarbe;
              array_push($RAUMUNTERRICHT[$etag][$esstd], $stunde);
            }
          }
          $sql->close();
        }
        if ($lehrergewaehlt != '-') {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN faecher ON kurse.fach = faecher.id WHERE lehrer = ? AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param('iii', $lehrergewaehlt, $ZEITRAUM, $rythmusgewaehlt);
          if ($sql->execute()) {
            $sql->bind_result($eid, $esstd, $etag, $eryth, $ekurs, $elehrer, $eraum, $ekbez, $elbez, $erbez, $efarbe);
            while ($sql->fetch()) {
              $stunde = array();
              $stunde['id'] = $eid;
              $stunde['rythmus'] = $eryth;
              $stunde['kursid'] = $ekurs;
              $stunde['kursbez'] = $ekbez;
              $stunde['lehrerid'] = $elehrer;
              $stunde['lehrerbez'] = $elbez;
              $stunde['raumid'] = $eraum;
              $stunde['raumbez'] = $erbez;
              $stunde['farbe'] = $efarbe;
              array_push($RAUMUNTERRICHT[$etag][$esstd], $stunde);
            }
          }
          $sql->close();
        }
        if (($stufegewaehlt === '-') && ($klassegewaehlt === '-')) {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, regelunterricht.kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs JOIN faecher ON kurse.fach = faecher.id WHERE stufe IS NULL AND klasse IS NULL AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param("ii", $ZEITRAUM, $rythmusgewaehlt);
        }
        else if (($stufegewaehlt === '-') && ($klassegewaehlt !== '-')) {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, regelunterricht.kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN kurseklassen ON kurse.id = kurseklassen.kurs JOIN faecher ON kurse.fach = faecher.id WHERE stufe IS NULL AND klasse = ? AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param("iii", $klassegewaehlt, $ZEITRAUM, $rythmusgewaehlt);
        }
        else if (($stufegewaehlt !== '-') && ($klassegewaehlt === '-')) {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, regelunterricht.kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs JOIN faecher ON kurse.fach = faecher.id WHERE stufe = ? AND klasse IS NULL AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param("iii", $stufegewaehlt, $ZEITRAUM, $rythmusgewaehlt);
        }
        else {
          $sql = $dbs->prepare("SELECT * FROM (SELECT regelunterricht.id AS id, schulstunde, tag, rythmus, regelunterricht.kurs, lehrer, raum, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kbez, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lbez, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, farbe FROM regelunterricht JOIN schulstunden ON regelunterricht.schulstunde = schulstunden.id JOIN raeume ON regelunterricht.raum = raeume.id JOIN lehrer ON regelunterricht.lehrer = lehrer.id JOIN kurse ON regelunterricht.kurs = kurse.id JOIN kurseklassen ON kurse.id = kurseklassen.kurs JOIN faecher ON kurse.fach = faecher.id WHERE stufe = ? AND klasse = ? AND zeitraum = ? AND (rythmus = 0 OR rythmus = ?)) AS x ORDER BY kbez, lbez, rbez");
          $sql->bind_param("iiii", $stufegewaehlt, $klassegewaehlt, $ZEITRAUM, $rythmusgewaehlt);
        }
        if ($sql->execute()) {
          $sql->bind_result($eid, $esstd, $etag, $eryth, $ekurs, $elehrer, $eraum, $ekbez, $elbez, $erbez, $efarbe);
          while ($sql->fetch()) {
            $stunde = array();
            $stunde['id'] = $eid;
            $stunde['rythmus'] = $eryth;
            $stunde['kursid'] = $ekurs;
            $stunde['kursbez'] = $ekbez;
            $stunde['lehrerid'] = $elehrer;
            $stunde['lehrerbez'] = $elbez;
            $stunde['raumid'] = $eraum;
            $stunde['raumbez'] = $erbez;
            $stunde['farbe'] = $efarbe;
            array_push($KLASSENUNTERRICHT[$etag][$esstd], $stunde);
          }
        }
        $sql->close();

        $minpp = 1.25;
        $yakt = 40;
        $zende = $SCHULSTUNDEN[0]['beginn'];
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

        $spaltenbreite = 100/(count($SCHULTAGE)*3+1);

        $code = "<div class=\"cms_stundenplan_box\" style=\"height: $sphoehe"."px\">";
          foreach ($SCHULSTUNDENIDS as $s) {
            $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginns']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginnm'])."</span></span>";
              $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;line-height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">".$SCHULSTUNDEN[$s]['bez']."</span>";
            $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".$SCHULSTUNDEN[$s]['endey']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['endes']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['endem'])."</span></span>";
          }
          $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: $spaltenbreite%;\"><h3>Klasse</h3></span>";
          $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".((count($SCHULTAGE)+1)*$spaltenbreite)."%\"><h3>Lehrer</h3></span>";
          $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".((count($SCHULTAGE)*2+1)*$spaltenbreite)."%\"><h3>Raum</h3></span>";
          $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
          $code .= "</div>";
          // Klasse / Stufe
          foreach($SCHULTAGE AS $t) {
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".cms_tagname($t)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $events = "onclick=\"cms_stundeplatzieren('$t', '".$SCHULSTUNDEN[$s]['id']."')\" onmouseover=\"cms_stundemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\" onmouseout=\"cms_stundedemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\"";
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_k_".$t."_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\" $events>";
              foreach ($KLASSENUNTERRICHT[$t][$s] AS $std) {
                $code .= cms_generiere_unterrichtsstunde($std);
              }
              $code .= "</span>";
            }
            $code .= "</div>";
          }
          // Lehrer
          foreach($SCHULTAGE AS $t) {
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".cms_tagname($t)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $events = "onclick=\"cms_stundeplatzieren('$t', '".$SCHULSTUNDEN[$s]['id']."')\" onmouseover=\"cms_stundemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\" onmouseout=\"cms_stundedemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\"";
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_l_".$t."_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\" $events>";
              foreach ($LEHRERUNTERRICHT[$t][$s] AS $std) {
                $code .= cms_generiere_unterrichtsstunde($std);
              }
              $code .= "</span>";
            }
            $code .= "</div>";
          }
          // Raum
          foreach($SCHULTAGE AS $t) {
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".cms_tagname($t)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $events = "onclick=\"cms_stundeplatzieren('$t', '".$SCHULSTUNDEN[$s]['id']."')\" onmouseover=\"cms_stundemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\" onmouseout=\"cms_stundedemarkieren('$t', '".$SCHULSTUNDEN[$s]['id']."');\"";
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_r_".$t."_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\" $events>";
              foreach ($RAUMUNTERRICHT[$t][$s] AS $std) {
                $code .= cms_generiere_unterrichtsstunde($std);
              }
              $code .= "</span>";
            }
            $code .= "</div>";
          }

        $code .= "</div>";
      }
      else {
        $code .= cms_meldung('info', '<h4>Keine Schulstunden</h4><p>Stunden können nur in vorgegebenen Zeitslots angelegt werden. Noch sind in diesem Zeitraum keine Schulstunden.</p><p><span class="cms_button" onclick="cms_stundenplanzeitraeume_vorbereiten('.$SCHULJAHR.')">zu den Zeiträumen</span></p>');
      }

      $code .= "</div>";
      $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_clear\"></div>";


    }


  }
  else {$code .= "<h1>Stundenplanung</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Stundenplanung</h1>".cms_meldung_berechtigung();
}

function cms_generiere_unterrichtsstunde($stunde) {
  $code = "<span class=\"cms_stundenplanung_stunde cms_farbbeispiel_".$std['farbe']."\" onclick=\"cms_stundenplanung_loeschen(".$std['id'].")\"><span class=\"cms_stundenplanung_stundeinfo\">".$std['kursbez']."<br>".$std['lehrerbez']."<br>".$std['raumbez']."";
  if ($std['rythmus'].'' != '0') {
    $code .= "<br>".chr(64+$std['rythmus']);
  }
  $code .= "</span></span>";
  return $code;
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
