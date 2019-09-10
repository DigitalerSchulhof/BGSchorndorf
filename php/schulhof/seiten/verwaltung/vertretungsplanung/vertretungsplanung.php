<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Vertretungsplanung</h1>
<?php
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

$code = "";
if ($zugriff) {
  if (!$CMS_IMLN) {
    $code .= cms_meldung_firewall();
  }
  else {
    $varfehler = false;
    if (!isset($_SESSION['VERTRETUNGSPLANUNGTAG']) || !isset($_SESSION['VERTRETUNGSPLANUNGMONAT']) || !isset($_SESSION['VERTRETUNGSPLANUNGJAHR']) || !isset($_SESSION['VERTRETUNGSPLANUNGSTUFEN']) || !isset($_SESSION['VERTRETUNGSPLANUNGKLASSEN']) || !isset($_SESSION['VERTRETUNGSPLANUNGKURSE']) || !isset($_SESSION['VERTRETUNGSPLANUNGLEHRER']) || !isset($_SESSION['VERTRETUNGSPLANUNGVOLLBILD']) || !isset($_SESSION['VERTRETUNGSPLANUNGRAUM']) || !isset($_SESSION['VERTRETUNGSPLANUNGSTUNDE']) || !isset($_SESSION['VERTRETUNGSPLANUNGOPTION'])) {
      $varfehler = true;
    }
    else {
      $stufegewaehlt = $_SESSION['VERTRETUNGSPLANUNGSTUFEN'];
      $klassegewaehlt = $_SESSION['VERTRETUNGSPLANUNGKLASSEN'];
      $kursgewaehlt = $_SESSION['VERTRETUNGSPLANUNGKURSE'];
      $lehrergewaehlt = $_SESSION['VERTRETUNGSPLANUNGLEHRER'];
      $raumgewaehlt = $_SESSION['VERTRETUNGSPLANUNGRAUM'];
      $tag = $_SESSION['VERTRETUNGSPLANUNGTAG'];
      $monat = $_SESSION['VERTRETUNGSPLANUNGMONAT'];
      $jahr = $_SESSION['VERTRETUNGSPLANUNGJAHR'];
      $vollbildgewaehlt = $_SESSION['VERTRETUNGSPLANUNGVOLLBILD'];
      $stundegewaehlt = $_SESSION['VERTRETUNGSPLANUNGSTUNDE'];
      $optiongewaehlt = $_SESSION['VERTRETUNGSPLANUNGOPTION'];
    }


    if (!$varfehler) {

      $code .= "<table class=\"cms_formular\">";
      $code .= "<tr><th>Tag:</th><td>".cms_datum_eingabe('cms_vplan_datum', $tag, $monat, $jahr, 'cms_vertretungsplanung_tagaendern()')."</td></tr>";
      $code .= "<tr><th>Vertretungstext Schüler:</th><td><textarea name=\"cms_vplan_vtext_schueler\" id=\"cms_vplan_vtext_schueler\"></textarea></td></tr>";
      $code .= "<tr><th>Vertretungstext Lehrer:</th><td><textarea name=\"cms_vplan_vtext_lehrer\" id=\"cms_vplan_vtext_lehrer\"></textarea></td></tr>";
      $code .= "</table>";
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_vtexte_speichern()\">Vertretungstexte speichern</span></p>";


      $sjfehler = false;
      $jetzt = mktime(0,0,0,$monat, $tag, $jahr);
      $heuteende = mktime(0,0,0,$monat, $tag+1, $jahr)-1;
      // Schuljahr und Zeitraum laden
      $sql = $dbs->prepare("SELECT COUNT(*), id, schuljahr FROM zeitraeume WHERE ? BETWEEN beginn AND ende");
      $sql->bind_param("i", $jetzt);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $ZEITRAUM, $SCHULJAHR);
        if ($sql->fetch()) {
          if ($anzahl != 1) {$sjfehler = true;}
        } else {$sjfehler = true;}
      } else {$sjfehler = true;}
      $sql->close();

      if (!$sjfehler) {
        $zusatz = "";
        if ($vollbildgewaehlt) {$zusatz = " class=\"cms_vollbild\"";}

        $code .= "<div id=\"cms_stundenplanung_vollbild\"$zusatz>";
        $code .= "<div class=\"cms_vollbild_innen\">";
        $code .= "<div class=\"cms_spalte_i\">";
        $code .= "<p class=\"cms_rechtsbuendig\">";
        if ($vollbildgewaehlt != '0') {$vollbildgewaehlt = '1';}
        if ($vollbildgewaehlt == '0') {$code .= "<span class=\"cms_button\" onclick=\"cms_vplan_vollbild('1')\">Vollbild</span>";}
        else {$code .= "<span class=\"cms_button_nein\" onclick=\"cms_vplan_vollbild('0')\">&times;</span>";}
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

        // Lehrer
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

        $SCHULSTUNDEN = array();
        $SCHULSTUNDENIDS = array();
        $SCHULSTUNDENBEGINN = array();
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
            $SCHULSTUNDENBEGINN[cms_fuehrendenull($sbeginns).":".cms_fuehrendenull($sbeginnm)] = $sid;
          }
        }
        $sql->close();

        $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
        $code .= "<h2>Stufen</h2>";
        $einzelcode = "";
        foreach ($STUFEN as $e) {
          $wert = 0;
          if ($e['id']."" == $stufegewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_stufen_'.$e['id'], $e['bez'], $wert, "cms_vplan_stufewaehlen(".$e['id'].")")." ";
        }
        $wert = 0;
        if ($stufegewaehlt === '-') {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_stufen_ue', 'stufenübergreifend', $wert, "cms_vplan_stufewaehlen('-')");
        $code .= "<p>$einzelcode</p>";

        $code .= "<h2>Klassen</h2>";
        $einzelcode = "";

        foreach ($KLASSEN as $e) {
          $wert = 0;
          if ($e['id']."" == $klassegewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_klassen_'.$e['id'], $e['bez'], $wert, "cms_vplan_klassewaehlen(".$e['id'].")")." ";
        }
        $wert = 0;
        if ($klassegewaehlt === '-') {$wert = 1;}
        $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_klassen_ue', 'klassenübergreifend', $wert, "cms_vplan_klassewaehlen('-')");
        $code .= "<p>$einzelcode</p>";
        $code .= "</div></div>";

        $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
        $code .= "<h2>Kurse</h2>";
        $einzelcode = "";
        foreach ($KURSE as $e) {
          $wert = 0;
          if ($e['id']."" == $kursgewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_kurse_'.$e['id'], $e['bez'], $wert, "cms_vplan_kurswaehlen(".$e['id'].")")." ";
        }
        $code .= "<p>$einzelcode</p>";

        $code .= "<h2>Lehrer</h2>";
        $einzelcode = "";
        foreach ($LEHRER as $e) {
          $wert = 0;
          if ($e['id']."" == $lehrergewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_lehrer_'.$e['id'], $e['name'], $wert, "cms_vplan_lehrerwaehlen(".$e['id'].")")." ";
        }
        $code .= "<p>$einzelcode</p>";
        $code .= "</div></div>";

        $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
        $code .= "<h2>Räume</h2>";
        $einzelcode = "";
        foreach ($RAEUME as $e) {
          $wert = 0;
          if ($e['id']."" == $raumgewaehlt."") {$wert = 1;}
          $einzelcode .= cms_togglebutton_generieren('cms_stundenplanung_raume_'.$e['id'], $e['bez'], $wert, "cms_vplan_raumwaehlen(".$e['id'].")")." ";
        }
        $code .= "<p>$einzelcode</p>";
        $code .= "</div></div>";
        $code .= "<div class=\"cms_clear\"></div>";
        echo $code;
        $code = "<div class=\"cms_spalte_i\">".cms_generiere_nachladen('cms_vplan_konflikte', 'cms_vplan_konflikte();')."</div>";
        $code .= "<div class=\"cms_spalte_25\"><div class=\"cms_spalte_i\">";
        echo $code;
        $code = "";

        if (count($SCHULSTUNDEN) > 0) {
          $KLASSENUNTERRICHT = array();
          $LEHRERUNTERRICHT = array();
          $RAUMUNTERRICHT = array();
          foreach ($SCHULSTUNDENIDS as $s) {
            $KLASSENUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
            $LEHRERUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
            $RAUMUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
          }

          // UNTERRICHT IM RAUM LADEN
          if ($raumgewaehlt."" != '-') {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE traum = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
            $sql->bind_param('iii', $raumgewaehlt, $jetzt, $heuteende);
            if ($sql->execute()) {
              $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
              while ($sql->fetch()) {
                $stunde = array();
                $stunde['uid'] = $uid;
                $stunde['kursid'] = $kid;
                if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
                $stunde['kursbez'] = $kursbez;
                $stunde['lehrerid'] = $ulehrer;
                if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
                $stunde['lehrerbez'] = $lehrerbez;
                $stunde['raumid'] = $uraum;
                $stunde['raumbez'] = $uraumbez;
                $stunde['vplanan'] = $vpan;
                $stunde['vplanart'] = $vpa;
                $stunde['vplanbem'] = $vpbem;
                $stunde['farbe'] = $ufarbe;
                array_push($RAUMUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
              }
            }
            $sql->close();
          }
          // UNTERRICHT DES LEHRERS LADEN
          if ($lehrergewaehlt."" != '-') {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE tlehrer = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");

            $sql->bind_param('iii', $lehrergewaehlt, $jetzt, $heuteende);
            if ($sql->execute()) {
              $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
              while ($sql->fetch()) {
                $stunde = array();
                $stunde['uid'] = $uid;
                $stunde['kursid'] = $kid;
                if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
                $stunde['kursbez'] = $kursbez;
                $stunde['lehrerid'] = $ulehrer;
                if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
                $stunde['lehrerbez'] = $lehrerbez;
                $stunde['raumid'] = $uraum;
                $stunde['raumbez'] = $uraumbez;
                $stunde['vplanan'] = $vpan;
                $stunde['vplanart'] = $vpa;
                $stunde['vplanbem'] = $vpbem;
                $stunde['farbe'] = $ufarbe;
                array_push($LEHRERUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
              }
            }
            $sql->close();
          }
          if (($stufegewaehlt === '-') && ($klassegewaehlt === '-')) {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, unterricht.kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = unterricht.kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE stufe IS NULL AND klasse IS NULL AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
            $sql->bind_param("ii", $jetzt, $heuteende);
          }
          else if (($stufegewaehlt === '-') && ($klassegewaehlt !== '-')) {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, unterricht.kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = unterricht.kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE stufe IS NULL AND klasse = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
            $sql->bind_param("iii", $klassegewaehlt, $jetzt, $heuteende);
          }
          else if (($stufegewaehlt !== '-') && ($klassegewaehlt === '-')) {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, unterricht.kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = unterricht.kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE stufe = ? AND klasse IS NULL AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
            $sql->bind_param("iii", $stufegewaehlt, $jetzt, $heuteende);
          }
          else {
            $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, unterricht.kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = unterricht.kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE stufe = ? AND klasse = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
            $sql->bind_param("iiii", $stufegewaehlt, $klassegewaehlt, $jetzt, $heuteende);
          }
          if ($sql->execute()) {
          $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
          while ($sql->fetch()) {
            $stunde = array();
            $stunde['uid'] = $uid;
            $stunde['kursid'] = $kid;
            if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
            $stunde['kursbez'] = $kursbez;
            $stunde['lehrerid'] = $ulehrer;
            if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
            $stunde['lehrerbez'] = $lehrerbez;
            $stunde['raumid'] = $uraum;
            $stunde['raumbez'] = $uraumbez;
            $stunde['vplanan'] = $vpan;
            $stunde['vplanart'] = $vpa;
            $stunde['vplanbem'] = $vpbem;
            $stunde['farbe'] = $ufarbe;
            array_push($KLASSENUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
          }
          }
          $sql->close();

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

          $spaltenbreite = 25;

          $code = "";

          $_SESSION['VERTRETUNGSPLANUNGKURSE'] = $kursgewaehlt;
          $_SESSION['VERTRETUNGSPLANUNGLEHRER'] = $lehrergewaehlt;
          $_SESSION['VERTRETUNGSPLANUNGRAUM'] = $raumgewaehlt;
          $_SESSION['VERTRETUNGSPLANUNGZEITRAUM'] = $ZEITRAUM;

          $code .= "<div class=\"cms_stundenplan_box\" style=\"height: $sphoehe"."px\">";
            foreach ($SCHULSTUNDENIDS as $s) {
              $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginns']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginnm'])."</span></span>";
                $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;line-height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">".$SCHULSTUNDEN[$s]['bez']."</span>";
              $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".$SCHULSTUNDEN[$s]['endey']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['endes']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['endem'])."</span></span>";
            }
            $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: $spaltenbreite%;\"><h3>Klasse</h3></span>";
            $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(2*$spaltenbreite)."%\"><h3>Lehrkraft</h3></span>";
            $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(3*$spaltenbreite)."%\"><h3>Raum</h3></span>";
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "</div>";
            // Klasse / Stufe
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_k_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
              foreach ($KLASSENUNTERRICHT[$s] AS $std) {
                $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
              }
              $code .= "</span>";
            }
            $code .= "</div>";
            // Lehrer
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_l_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
              foreach ($LEHRERUNTERRICHT[$s] AS $std) {
                $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
              }
              $code .= "</span>";
            }
            $code .= "</div>";
            // Raum
            $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
            $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
            foreach ($SCHULSTUNDENIDS as $s) {
              $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_r_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
              foreach ($RAUMUNTERRICHT[$s] AS $std) {
                $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
              }
              $code .= "</span>";
            }
            $code .= "</div>";

          $code .= "</div>";
        }
        else {
          $code .= cms_meldung('info', '<h4>Keine Schulstunden</h4><p>Stunden können nur in vorgegebenen Zeitslots angelegt werden. Noch sind in diesem Zeitraum keine Schulstunden.</p><p><span class="cms_button" onclick="cms_stundenplanzeitraeume_vorbereiten('.$SCHULJAHR.')">zu den Zeiträumen</span></p>');
        }

        $code .= "</div></div>";
        $code .= "<div class=\"cms_spalte_15\"><div class=\"cms_spalte_i\">";
          $code .= "<h3>Planungsoptionen</h3>";
          $code .= "<p>";
          if ($stundegewaehlt != 'x') {
              if ($optiongewaehlt == 'e') {$wert = 1;} else {$wert = 0;}
              $code .= cms_togglebutton_generieren ('cms_vplan_stunde_entfall', 'Entfall', $wert, "cms_vplanoption_waehlen('e')")." ";
              if ($optiongewaehlt == 'v') {$wert = 1;} else {$wert = 0;}
              $code .= cms_togglebutton_generieren ('cms_vplan_stunde_verlegung', 'Verlegung', $wert, "cms_vplanoption_waehlen('v')")." ";
              if ($optiongewaehlt == 'a') {$wert = 1;} else {$wert = 0;}
              $code .= cms_togglebutton_generieren ('cms_vplan_stunde_aenderung', 'Änderung', $wert, "cms_vplanoption_waehlen('a')")." ";
          }
          if ($optiongewaehlt == 's') {$wert = 1;} else {$wert = 0;}
          $code .= cms_togglebutton_generieren ('cms_vplan_stunde_sondereinsatz', 'Sondereinsatz', $wert, "cms_vplanoption_waehlen('s')")."</p>";


          if (($optiongewaehlt == 'a') || ($optiongewaehlt == 'v') || ($optiongewaehlt == 'e') || ($optiongewaehlt == 's')) {
            $code .= "<table class=\"cms_formular\">";
              if ($optiongewaehlt != 'e') {
                if (($optiongewaehlt != 'v') && ($optiongewaehlt != 's')) {$style = "display: none;";} else {$style = "";}
                $code .= "<tr style=\"$style\"><th>Datum:</th><td>".cms_datum_eingabe('cms_vplan_neu_datum', $tag, $monat, $jahr, 'cms_vplan_zielstunden();')."</td></tr>";
                $code .= "<tr><th>Lehrer:</th><td><select name=\"cms_vplan_neu_l\" id=\"cms_vplan_neu_l\" onchange=\"cms_vplan_zielstunden();\">";
                foreach ($LEHRER as $l) {$code .= "<option value=\"".$l['id']."\">".$l['name']."</option>";}
                $code .= "</select></td></tr>";
                $code .= "<tr><th>Raum:</th><td><select name=\"cms_vplan_neu_r\" id=\"cms_vplan_neu_r\" onchange=\"cms_vplan_zielstunden();\">";
                foreach ($RAEUME as $r) {$code .= "<option value=\"".$r['id']."\">".$r['bez']."</option>";}
                $code .= "</select></td></tr>";
                $code .= "<tr><th>Stunde:</th><td><select name=\"cms_vplan_neu_s\" id=\"cms_vplan_neu_s\">";
                foreach ($SCHULSTUNDEN as $s) {$code .= "<option value=\"".$s['id']."\">".$s['bez']."</option>";}
                $code .= "</select></td></tr>";

                $lehrerneugewaehlt = $LEHRER[0]['id'];
                $raumneugewaehlt = $RAEUME[0]['id'];
              }
              $code .= "<tr><th>Bemerkung:</th><td><input type=\"text\" name=\"cms_vplan_neu_bem\" id=\"cms_vplan_neu_bem\"></td></tr>";
              $code .= "<tr><th>Anzeigen:</th><td>".cms_schieber_generieren('vplan_neu_anz', 1)."</td></tr>";
            $code .= "</table>";
            if ($optiongewaehlt == 'e') {$code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_entfall();\">Stunde entfällt</span></p>";}
            if ($optiongewaehlt == 'v') {$code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_verlegung();\">Stunde verlegen</span></p>";}
            if ($optiongewaehlt == 'a') {$code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_aenderung();\">Stunde ändern</span></p>";}
            if ($optiongewaehlt == 's') {$code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_sondereinsatz();\">Stunde erstellen</span></p>";}
          }
          $code .= "</p>";
        $code .= "</div></div>";
        echo $code;
        $code = "";
        $code .= "<div class=\"cms_spalte_25\"><div class=\"cms_spalte_i\" id=\"cms_vplan_zielstunden\">";
        if (($optiongewaehlt == 'v') || ($optiongewaehlt == 'a') || ($optiongewaehlt == 's')) {
          if (count($SCHULSTUNDEN) > 0) {
            $LEHRERNEUUNTERRICHT = array();
            $RAUMNEUUNTERRICHT = array();
            foreach ($SCHULSTUNDENIDS as $s) {
              $LEHRERNEUUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
              $RAUMNEUUNTERRICHT[$SCHULSTUNDEN[$s]['id']] = array();
            }

            // UNTERRICHT IM RAUM LADEN
            if ($raumneugewaehlt."" != '-') {
              $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE traum = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");
              $sql->bind_param('iii', $raumneugewaehlt, $jetzt, $heuteende);
              if ($sql->execute()) {
                $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
                while ($sql->fetch()) {
                  $stunde = array();
                  $stunde['uid'] = $uid;
                  $stunde['kursid'] = $kid;
                  if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
                  $stunde['kursbez'] = $kursbez;
                  $stunde['lehrerid'] = $ulehrer;
                  if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
                  $stunde['lehrerbez'] = $lehrerbez;
                  $stunde['raumid'] = $uraum;
                  $stunde['raumbez'] = $uraumbez;
                  $stunde['vplanan'] = $vpan;
                  $stunde['vplanart'] = $vpa;
                  $stunde['vplanbem'] = $vpbem;
                  $stunde['farbe'] = $ufarbe;
                  array_push($RAUMNEUUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
                }
              }
              $sql->close();
            }
            // UNTERRICHT DES LEHRERS LADEN
            if ($lehrerneugewaehlt."" != '-') {
              $sql = $dbs->prepare("SELECT * FROM (SELECT unterricht.id, kurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurzbez, tbeginn, tlehrer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL') AS lehrerkurz, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL'), farbe FROM unterricht JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN kurse ON kurse.id = kurs JOIN personen ON personen.id = tlehrer JOIN lehrer ON lehrer.id = tlehrer LEFT JOIN faecher ON kurse.fach = faecher.id WHERE tlehrer = ? AND (tbeginn BETWEEN ? AND ?)) as x ORDER BY tbeginn, kurzbez, lehrerkurz");

              $sql->bind_param('iii', $lehrerneugewaehlt, $jetzt, $heuteende);
              if ($sql->execute()) {
                $sql->bind_result($uid, $kid, $kbez, $kkbez, $stdbeginn, $ulehrer, $ulvor, $ulnach, $ultitel, $ulkurz, $uraum, $uraumbez, $vpan, $vpa, $vpbem, $ufarbe);
                while ($sql->fetch()) {
                  $stunde = array();
                  $stunde['uid'] = $uid;
                  $stunde['kursid'] = $kid;
                  if (strlen($kkbez) > 0) {$kursbez = $kkbez;} else {$kursbez = $kbez;}
                  $stunde['kursbez'] = $kursbez;
                  $stunde['lehrerid'] = $ulehrer;
                  if (strlen($ulkurz) > 0) {$lehrerbez = $ulkurz;} else {$lehrerbez = cms_generiere_anzeigename($ulvor, $ulnach, $ultitel);}
                  $stunde['lehrerbez'] = $lehrerbez;
                  $stunde['raumid'] = $uraum;
                  $stunde['raumbez'] = $uraumbez;
                  $stunde['vplanan'] = $vpan;
                  $stunde['vplanart'] = $vpa;
                  $stunde['vplanbem'] = $vpbem;
                  $stunde['farbe'] = $ufarbe;
                  array_push($LEHRERNEUUNTERRICHT[$SCHULSTUNDENBEGINN[date('H:i', $stdbeginn)]], $stunde);
                }
              }
              $sql->close();
            }

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

            $spaltenbreite = 25;

            $code .= "<div class=\"cms_stundenplan_box\" style=\"height: $sphoehe"."px\">";
              foreach ($SCHULSTUNDENIDS as $s) {
                $code .= "<span class=\"cms_stundenplan_zeitliniebeginn\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginns']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['beginnm'])."</span></span>";
                  $code .= "<span class=\"cms_stundenplan_zeitliniebez\" style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;line-height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">".$SCHULSTUNDEN[$s]['bez']."</span>";
                $code .= "<span class=\"cms_stundenplan_zeitlinieende\" style=\"top: ".$SCHULSTUNDEN[$s]['endey']."px;\"><span class=\"cms_stundenplan_zeitlinietext\">".cms_fuehrendenull($SCHULSTUNDEN[$s]['endes']).":".cms_fuehrendenull($SCHULSTUNDEN[$s]['endem'])."</span></span>";
              }
              $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: $spaltenbreite%;\"><h3>Klasse</h3></span>";
              $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(2*$spaltenbreite)."%\"><h3>Lehrkraft</h3></span>";
              $code .= "<span class=\"cms_stundenplan_ueberschrift\" style=\"top: 0px; left: ".(3*$spaltenbreite)."%\"><h3>Raum</h3></span>";
              $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
              $code .= "</div>";
              // Klasse / Stufe
              $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
              $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
              foreach ($SCHULSTUNDENIDS as $s) {
                $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_k_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
                foreach ($KLASSENUNTERRICHT[$s] AS $std) {
                  $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
                }
                $code .= "</span>";
              }
              $code .= "</div>";
              // Lehrer
              $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
              $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
              foreach ($SCHULSTUNDENIDS as $s) {
                $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_l_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
                foreach ($LEHRERNEUUNTERRICHT[$s] AS $std) {
                  $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
                }
                $code .= "</span>";
              }
              $code .= "</div>";
              // Raum
              $code .= "<div class=\"cms_stundenplan_spalte\" style=\"width: $spaltenbreite%;\">";
              $code .= "<span class=\"cms_stundenplan_spaltentitel\">".date("d.m.Y", $jetzt)."</span>";
              foreach ($SCHULSTUNDENIDS as $s) {
                $code .= "<span class=\"cms_stundenplanung_stundenfeld\" id=\"cms_stunde_r_".$SCHULSTUNDEN[$s]['id']."\"style=\"top: ".$SCHULSTUNDEN[$s]['beginny']."px;height: ".($SCHULSTUNDEN[$s]['endey']-$SCHULSTUNDEN[$s]['beginny'])."px;\">";
                foreach ($RAUMNEUUNTERRICHT[$s] AS $std) {
                  $code .= cms_generiere_vplanstunde($std, $stundegewaehlt);
                }
                $code .= "</span>";
              }
              $code .= "</div>";

            $code .= "</div>";
          }
        }

        $code .= "</div></div>";
        $code .= "<div class=\"cms_clear\"></div>";
        $code .= "</div>";
        $code .= "</div>";
        $code .= "<div class=\"cms_clear\"></div>";
      }
      else {
        $code .= cms_meldung('info', '<h4>Kein Planungszeitraum</h4><p>Das eingegebene Datum gehört zu keinem Planungszeitraum!</p>');
      }
    }
    else {
      $code .= cms_meldung_bastler();
    }
  }

}
else {
  $code .= cms_meldung_berechtigung();
}

// ORT:  j - jetzt z - zukunft -- ART: k - klasse l - lehrer r - raum
function cms_generiere_vplanstunde($std, $ugewaehlt) {
  if ($ugewaehlt == $std['uid']) {$zusatz = ' cms_stundenplan_stunde_gewaehlt';} else {$zusatz = "";}
  $event = " onclick=\"cms_vplanstunde_waehlen(".$std['uid'].")\"";
  if (($std['farbe'] <= 4) || (($std['farbe'] >= 12) && ($std['farbe'] <= 23))) {$style = "color:#ffffff;";} else {$style="";}
  $code = "<span class=\"cms_stundenplanung_stunde cms_farbbeispiel_".$std['farbe']."$zusatz\" style=\"$style\"$event>";
    $code .= $std['kursbez']."<br>".$std['lehrerbez']."<br>".$std['raumbez'];
  $code .= "</span>";
  return $code;
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
