<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		    {$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid']))     	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['ausgeschlossen'])) 	{$kursea = $_POST['ausgeschlossen'];} 		  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['gewaehlt'])) 	      {$kurseg = $_POST['gewaehlt'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['freigabe'])) 	      {$freigabe = $_POST['freigabe'];} 		      else {cms_anfrage_beenden(); exit;}

$sonstigeg = preg_match("/\|s$/", $kurseg);
$sonstigea = preg_match("/\|s$/", $kursea);

if ($sonstigeg) {$kurseg = str_replace("|s", "", $kurseg);}
if ($sonstigea) {$kursea = str_replace("|s", "", $kursea);}

if (!cms_check_idfeld($kurseg)) {cms_anfrage_beenden(); exit;}
if (!cms_check_idfeld($kursea)) {cms_anfrage_beenden(); exit;}
$kurseasql = "";
$kursegsql = "";
if (strlen($kursea) > 0) {$kurseasql = str_replace("|", ",", substr($kursea, 1));}
if (strlen($kurseg) > 0) {$kursegsql = str_replace("|", ",", substr($kurseg, 1));}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

if ($angemeldet) {
  $code = "";
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  // Benutzerschuljahr laden
  $SCHULJAHR = null;
  $sql = $dbs->prepare("SELECT schuljahr FROM nutzerkonten JOIN personen ON nutzerkonten.id = personen.id WHERE personen.id = ? AND personen.art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')");
  $sql->bind_param("i", $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($SCHULJAHR);
    $sql->fetch();
  }
  $sql->close();

  if ($SCHULJAHR !== null) {
    $CMS_EINSTELLUNGEN = cms_einstellungen_laden();
    $jetzt = time();

    // Schulstunden laden
    $ZEITRAEUME = array();
    $SCHULSTUNDEN = array();
    $sql = $dbs->prepare("SELECT zeitraeume.id, beginn, ende, AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM zeitraeume JOIN schulstunden WHERE schuljahr = ? ORDER BY beginn, beginns, beginnm");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($zid, $zbeginn, $zende, $stdbez, $stdbs, $stdbm);
      while ($sql->fetch()) {
        $neuerzeitraum = false;
        if (!isset($ZEITRAEUME[count($ZEITRAEUME) - 1])) {$neuerzeitraum = true;}
        else if ($ZEITRAEUME[count($ZEITRAEUME) - 1]['id'] != $zid) {$neuerzeitraum = true;}

        if ($neuerzeitraum) {
          $Z = array();
          $Z['id'] = $zid;
          $Z['beginn'] = $zbeginn;
          $Z['ende'] = $zende;
          $Z['std'] = array();
          array_push($ZEITRAEUME, $Z);
        }
        $stdzeit = cms_fuehrendenull($stdbs).":".cms_fuehrendenull($stdbm);
        $ZEITRAEUME[count($ZEITRAEUME) - 1]['std'][$stdzeit] = $stdbez;
      }
    }
    $sql->close();

    // Zu betrachtende Einträge laden
    $EINTRAEGE = array();
    $SCHLIESSEN = array();
    $KURSE = array();
    $EIDS = array();

    if ((strlen($kurseg) != 0)) {
      if ($sonstigeg) {
        if (strlen($kurseasql) > 0) {
          $sql = $dbs->prepare("SELECT unterricht.id, tbeginn, tende, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), tkurs FROM unterricht JOIN kurse ON tkurs = kurse.id WHERE tkurs IN (SELECT DISTINCT kurse.id FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?) AND tkurs NOT IN ($kurseasql) AND tbeginn < ? ORDER BY tbeginn DESC");
        }
        else {
          $sql = $dbs->prepare("SELECT unterricht.id, tbeginn, tende, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), tkurs FROM unterricht JOIN kurse ON tkurs = kurse.id WHERE tkurs IN (SELECT DISTINCT kurse.id FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?) AND tbeginn < ? ORDER BY tbeginn DESC");
        }
      }
      else if ($sonstigea) {
        $sql = $dbs->prepare("SELECT unterricht.id, tbeginn, tende, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), tkurs FROM unterricht JOIN kurse ON tkurs = kurse.id WHERE tkurs IN (SELECT DISTINCT kurse.id FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?) AND tkurs IN ($kursegsql) AND tbeginn < ? ORDER BY tbeginn DESC");
      }
      $sql->bind_param("iii", $SCHULJAHR, $CMS_BENUTZERID, $jetzt);
      if ($sql->execute()) {
        $sql->bind_result($uid, $ubeginn, $uende, $kbez, $kkbez, $kid);
        while ($sql->fetch()) {
          $EINTRAEGE[$uid]['uid'] = $uid;
          $EINTRAEGE[$uid]['kid'] = $kid;
          if ($kkbez !== null) {$EINTRAEGE[$uid]['bez'] = $kkbez;}
          else {$EINTRAEGE[$uid]['bez'] = $kbez;}
          $EINTRAEGE[$uid]['beginn'] = $ubeginn;
          $EINTRAEGE[$uid]['ende'] = $ubeginn;
          if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == 's') {$zielzeit = $uende;}
          else if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == 't') {$zielzeit = mktime(23,59,59,date('n', $uende),date('j', $uende),date('Y', $uende));}
          else if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == '-') {$zielzeit = $jetzt + 1;}
          else {$zielzeit = mktime(23,59,59,date('n', $uende),date('j', $uende) + $CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'],date('Y', $uende));}
          $EINTRAEGE[$uid]['ziel'] = $zielzeit;
          array_push($EIDS, $uid);
          if ($zielzeit < $jetzt) {array_push($SCHLIESSEN, $uid);}
          if (!in_array($kid, $KURSE)) {array_push($KURSE, $kid);}
        }
      }
      $sql->close();
    }

    // Schliessen ausführen
    if (count($SCHLIESSEN) > 0) {
      $schlusssql = implode(",", $SCHLIESSEN);
      $sql = $dbl->prepare("UPDATE tagebuch SET freigabe = 2 WHERE freigabe = 0 AND id IN ($schlusssql)");
      $sql->execute();
      $sql->close();
    }

    // Schüler laden
    $SCHUELER = array();
    if (count($KURSE) > 0) {
      $kursesql = implode(",", $KURSE);
      $sql = $dbs->prepare("SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id IN (SELECT person FROM kursemitglieder WHERE gruppe IN ($kursesql)) AND art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
      if ($sql->execute()) {
        $sql->bind_result($id, $vor, $nach, $titel);
        while ($sql->fetch()) {
          $SCHUELER[$id] = array();
          $SCHUELER[$id]['vor'] = $vor;
          $SCHUELER[$id]['nach'] = $nach;
          $SCHUELER[$id]['tit'] = $titel;
          $SCHUELER[$id]['ganz'] = cms_generiere_anzeigename($vor, $nach, $titel);
        }
      }
      $sql->close();
    }

    // Einträge laden
    $ANZEIGE = array();
    if (count($EINTRAEGE) > 0) {
      $esql = implode(",", $EIDS);
      if ($freigabe == 1) {$fsql = "=";}
      else {$fsql = ">=";}
      $sql = $dbl->prepare("SELECT id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSELL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSELL'), freigabe, leistungsmessung FROM tagebuch WHERE freigabe $fsql 0 AND id IN ($esql)");
      if ($sql->execute()) {
        $sql->bind_result($id, $inhalt, $hausaufgabe, $freigabe, $lm);
        while ($sql->fetch()) {
          $A = array();
          $A['uid'] = $id;
          $A['inhalt'] = $inhalt;
          $A['hausaufgabe'] = $hausaufgabe;
          $A['freigabe'] = $freigabe;
          $A['lm'] = $lm;
          $A['kid'] = $EINTRAEGE[$id]['kid'];
          $A['kbez'] = $EINTRAEGE[$id]['bez'];
          $A['beginn'] = $EINTRAEGE[$id]['beginn'];
          $A['ende'] = $EINTRAEGE[$id]['ende'];
          $A['std'] = cms_stundefinden($EINTRAEGE[$id]['beginn'], $ZEITRAEUME);
          $A['fehl'] = array();
          $A['lobtadel'] = array();
          array_push($ANZEIGE, $A);
        }
      }
      $sql->close();

      // Fehlzeiten ergänzen
      $psql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id = ? ");
      $sql = $dbl->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), entschuldigt FROM fehlzeiten WHERE eintrag = ?");
      for ($i=0; $i<count($ANZEIGE); $i++) {
        $sql->bind_param("i", $ANZEIGE[$i]['uid']);
        if ($sql->execute()) {
          $sql->bind_result($eid, $pers, $evon, $ebis, $ebem, $eent);
          while ($sql->fetch()) {
            $F = array();
            $F['id'] = $eid;
            if (isset($SCHUELER[$pers])) {
              $F['vor'] = $SCHUELER[$pers]['vor'];
              $F['nach'] = $SCHUELER[$pers]['nach'];
              $F['tit'] = $SCHUELER[$pers]['tit'];
              $F['ganz'] = $SCHUELER[$pers]['ganz'];
            }
            else {
              $psql->bind_param("i", $pers);
              $psql->execute();
              $psql->bind_result($vor, $nach, $tit);
              $psql->fetch();
              $F['vor'] = $vor;
              $F['nach'] = $nach;
              $F['tit'] = $tit;
              $F['ganz'] = cms_generiere_anzeigename($vor, $nach, $tit);
            }
            $F['von'] = $evon;
            $F['bis'] = $ebis;
            $F['bem'] = $ebem;
            $F['ent'] = $eent;
            array_push($ANZEIGE[$i]['fehl'], $F);
          }
        }
      }
      $sql->close();

      // Lob und Tadel ergänzen
      $sql = $dbl->prepare("SELECT id, person, art, charakter, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM lobtadel WHERE eintrag = ?");
      for ($i=0; $i<count($ANZEIGE); $i++) {
        $sql->bind_param("i", $ANZEIGE[$i]['uid']);
        if ($sql->execute()) {
          $sql->bind_result($eid, $pers, $eart, $echar, $ebem);
          while ($sql->fetch()) {
            $LT = array();
            if ($pers != null) {
              if (isset($SCHUELER[$pers])) {
                $LT['vor'] = $SCHUELER[$pers]['vor'];
                $LT['nach'] = $SCHUELER[$pers]['nach'];
                $LT['tit'] = $SCHUELER[$pers]['tit'];
                $LT['ganz'] = $SCHUELER[$pers]['ganz'];
              }
              else {
                $psql->bind_param("i", $pers);
                $psql->execute();
                $psql->bind_result($vor, $nach, $tit);
                $psql->fetch();
                $LT['vor'] = $vor;
                $LT['nach'] = $nach;
                $LT['tit'] = $tit;
                $LT['ganz'] = cms_generiere_anzeigename($vor, $nach, $tit);
              }
            }
            else {
              $LT['vor'] = "ganze Klasse";
              $LT['nach'] = "ganze Klasse";
              $LT['tit'] = "ganze Klasse";
              $LT['ganz'] = "ganze Klasse";
            }
            $LT['art'] = $eart;
            $LT['char'] = $echar;
            $LT['bem'] = $ebem;
            array_push($ANZEIGE[$i]['lobtadel'], $LT);
          }
        }
      }
      $sql->close();
      $psql->close();
    }

    if (count($ANZEIGE) == 0) {
      $code = '<p class="cms_notiz">Es liegen keine Tagebucheinträge für diese Kursauswahl vor.</p>';
    }
    else {
      // Einträge der Zeit nach sortieren
      usort($ANZEIGE, function ($a, $b) {
        if ($a['beginn'] == $b['beginn'])  {
          if ($a['kbez']<$b['kbez']) {return -1;}
          else {return 1;}
        }
        else {
          if ($a['beginn'] < $b['beginn']) {return -1;}
          else {return 1;}
        }
      });

      $code = "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th>Kurs</th><th>Unterrichtsgegenstand</th><th>HA</th><th>Fehlzeiten</th><th>Bemerkungen</th></tr>";
      $letztesdatum = "";
      $uids = array();
      foreach ($ANZEIGE AS $A) {
        if ($letztesdatum != date("d.m.Y", $A['beginn'])) {
          $letztesdatum = date("d.m.Y", $A['beginn']);
          $code .= "<tr><th class=\"cms_zwischenueberschrift\" colspan=\"6\">";
          $code .= cms_tagnamekomplett(date('N', $A['beginn'])).", den ".date('d', $A['beginn']).". ".cms_monatsnamekomplett(date('m', $A['beginn']))." ".date('Y', $A['beginn']);
          $code .= "</th></tr>";
        }
        if ($A['freigabe'] == 0) {$klasse = "cms_tagebuch_offen"; $event = " onclick=\"cms_tagebuch_eintragbearbeiten('".$A['uid']."')\"";}
        if ($A['freigabe'] == 1) {$klasse = "cms_tagebuch_freigabe"; $event = "";}
        if ($A['freigabe'] == 2) {$klasse = "cms_tagebuch_gesperrt"; $event = "";}
        $code .= "<tr class=\"$klasse\"$event>";
          $code .= "<td>".$A['std']."</td>";
          $code .= "<td>".$A['kbez']."</td>";
          if ($A['lm'] == 1) {$code .= "<td class=\"cms_tagebuch_leistungsmessung\">".$A['inhalt']."</td>";}
          else {$code .= "<td>".$A['inhalt']."</td>";}
          $code .= "<td>".$A['hausaufgabe']."</td>";
          usort($A['fehl'], function ($a, $b) {
            if ($a['nach'] == $b['nach'])  {
              if ($a['vor']<$b['vor']) {return -1;}
              else {return 1;}
            }
            else {
              if ($a['nach'] < $b['nach']) {return -1;}
              else {return 1;}
            }
          });

          usort($A['lobtadel'], function ($a, $b) {
            if ($a['nach'] == $b['nach'])  {
              if ($a['vor']<$b['vor']) {return -1;}
              else {return 1;}
            }
            else {
              if ($a['nach'] < $b['nach']) {return -1;}
              else {return 1;}
            }
          });

          $code .= "<td>";
          foreach ($A['fehl'] AS $F) {
            if ($F['ent'] == 1) {$klasse = "cms_tagebuch_entschuldigt";}
            else {$klasse = "cms_tagebuch_unentschuldigt";}
            $code .= "<span class=\"cms_tagebuch_fehlzeit $klasse\">".$F['ganz'];
            if (($F['von'] != $A['beginn']) || ($F['bis'] != $A['ende'])) {
              $code .= date("H:i", $F['von'])." - ".date("H:i", $F['bis']);
              $code .= " - ".(($F['bis']-$F['von'])/60)."'";
            }
            if (strlen($F['bem']) > 0) {
              $code .= "(".$F['bem'].")";
            }
            $code .= "</span> ";
          }
          $code .= "</td>";

          $code .= "<td>";
          foreach ($A['lobtadel'] AS $LT) {
            if ($LT['char'] == '+') {$klasse = "cms_tagebuch_positiv";}
            else if ($LT['char'] == '+') {$klasse = "cms_tagebuch_negativ";}
            else {$klasse = "cms_tagebuch_neutral";}
            $code .= "<span class=\"cms_tagebuch_lobtadel $klasse\">".$LT['ganz'];
            $code .= "(<b>".$LT['art']."</b> - ".$LT['bem'].")";
            $code .= "</span> ";
          }
          $code .= "</td>";
        $code .= "</tr>";
        $code .= "<tr id=\"cms_tagebuch_eintrag_".$A['uid']."\" style=\"display:none;\"><td colspan=\"6\">LALALA</td></tr>";
        array_push($uids, $A['uid']);
      }
      $code .= "</table>";
      $code .= "<p><input type=\"hidden\" name=\"cms_tagebuch_eintraege\" id=\"cms_tagebuch_eintraege\" value=\"".implode(",", $uids)."\"></p>";
    }
  }
  else {
    $code = '<p class="cms_notiz">Sie haben kein Schuljahr gewählt.</p>';
  }

  cms_trennen($dbl);
  cms_trennen($dbs);
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
