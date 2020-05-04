<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		    {$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid']))     	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['eintrag'])) 	      {$eintragid = $_POST['eintrag'];} 		            else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($eintragid,0)) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

if ($angemeldet) {
  $code = "";
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  $CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
  $jetzt = time();
  $EINTRAG = array();

  // Kurs laden
  $gefunden = false;
  $sql = $dbs->prepare("SELECT tkurs, tbeginn, tende FROM unterricht WHERE tlehrer = ? AND id = ?");
  $sql->bind_param("ii", $CMS_BENUTZERID, $eintragid);
  if ($sql->execute()) {
    $sql->bind_result($EINTRAG['kid'], $EINTRAG['beginn'], $EINTRAG['ende']);
    if ($sql->fetch()) {
      $gefunden = true;
      $_SESSION['Tagebuch Eintrag'] = $eintragid;
      $_SESSION['Tagebuch Kurs'] = $EINTRAG['kid'];
      $_SESSION['Tagebuch Stundenbeginn'] = $EINTRAG['beginn'];
      $_SESSION['Tagebuch Stundenende'] = $EINTRAG['ende'];
    }
  }
  $sql->close();

  if ($gefunden) {
    // Schüler laden
    $SCHUELER = array();
    $SCHUELEROPT = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id IN (SELECT person FROM kursemitglieder WHERE gruppe = ?) AND art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname, vorname");
    $sql->bind_param("i", $EINTRAG['kid']);
    if ($sql->execute()) {
      $sql->bind_result($id, $vor, $nach, $titel);
      while ($sql->fetch()) {
        $SCHUELER[$id] = array();
        $SCHUELER[$id]['vor'] = $vor;
        $SCHUELER[$id]['nach'] = $nach;
        $SCHUELER[$id]['tit'] = $titel;
        $SCHUELER[$id]['ganz'] = cms_generiere_anzeigename($vor, $nach, $titel);
        $SCHUELEROPT .= "<option value=\"$id\">".cms_generiere_anzeigename($vor, $nach, $titel)."</option>";
      }
    }
    $sql->close();

    // Eintrag laden
    $sql = $dbl->prepare("SELECT id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSELL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSELL'), freigabe, leistungsmessung FROM tagebuch WHERE id = ?");
    $sql->bind_param("i", $eintragid);
    if ($sql->execute()) {
      $sql->bind_result($id, $inhalt, $hausaufgabe, $freigabe, $lm);
      if ($sql->fetch()) {
        $EINTRAG['uid'] = $id;
        $EINTRAG['inhalt'] = $inhalt;
        $EINTRAG['hausaufgabe'] = $hausaufgabe;
        $EINTRAG['freigabe'] = $freigabe;
        $EINTRAG['lm'] = $lm;
        $EINTRAG['fehl'] = array();
        $EINTRAG['lobtadel'] = array();
      }
    }
    $sql->close();

    // Fehlzeiten ergänzen
    $psql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
    $sql = $dbl->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), entschuldigt FROM fehlzeiten WHERE eintrag = ?");
    $sql->bind_param("i", $eintragid);
    if ($sql->execute()) {
      $sql->bind_result($eid, $pers, $evon, $ebis, $ebem, $eent);
      while ($sql->fetch()) {
        $F = array();
        $F['id'] = $eid;
        $F['pers'] = $pers;
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
        array_push($EINTRAG['fehl'], $F);
      }
    }
    $sql->close();

    // Lob und Tadel ergänzen
    $sql = $dbl->prepare("SELECT id, person, art, charakter, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM lobtadel WHERE eintrag = ?");
    $sql->bind_param("i", $eintragid);
    if ($sql->execute()) {
      $sql->bind_result($eid, $pers, $eart, $echar, $ebem);
      while ($sql->fetch()) {
        $LT = array();
        $LT['id'] = $eid;
        $LT['pers'] = $pers;
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
        array_push($EINTRAG['lobtadel'], $LT);
      }
    }
    $sql->close();
    $psql->close();

    // Bearbeiten ausgeben
    $code = "<td colspan=\"6\">";

    // Prüfen, ob der Inhalt bearbeitet werden darf
    $jetzt = time();
    $ifrist = $CMS_EINSTELLUNGEN["Tagebuch Frist Inhalt"];
    $ffrist = $CMS_EINSTELLUNGEN["Tagebuch Frist Abwesenheit"];
    $ltfrist = $CMS_EINSTELLUNGEN["Tagebuch Frist Lob und Tadel"];
    $ierlaubt = false;
    $ferlaubt = false;
    $lterlaubt = false;
    if ($EINTRAG['freigabe'] == 0) {
      if ($ifrist == '-') {$ierlaubt = true;}
      if (($ifrist == 's') && ($jetzt <= $EINTRAG['ende'])) {$ierlaubt = true;}
      if (($ifrist == 't') && ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende']), date('Y', $EINTRAG['ende'])))) {$ierlaubt = true;}
      if (cms_check_ganzzahl($ifrist,1,14)) {
        if ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende'])+$ifrist, date('Y', $EINTRAG['ende']))) {$ierlaubt = true;}
      }

      if ($ffrist == '-') {$ferlaubt = true;}
      if (($ffrist == 's') && ($jetzt <= $EINTRAG['ende'])) {$ferlaubt = true;}
      if (($ffrist == 't') && ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende']), date('Y', $EINTRAG['ende'])))) {$ferlaubt = true;}
      if (cms_check_ganzzahl($ffrist,1,14)) {
        if ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende'])+$ffrist, date('Y', $EINTRAG['ende']))) {$ferlaubt = true;}
      }

      if ($ltfrist == '-') {$lterlaubt = true;}
      if (($ltfrist == 's') && ($jetzt <= $EINTRAG['ende'])) {$lterlaubt = true;}
      if (($ltfrist == 't') && ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende']), date('Y', $EINTRAG['ende'])))) {$lterlaubt = true;}
      if (cms_check_ganzzahl($ltfrist,1,14)) {
        if ($jetzt <= mktime(23,59,59,date('n',$EINTRAG['ende']), date('j',$EINTRAG['ende'])+$ltfrist, date('Y', $EINTRAG['ende']))) {$lterlaubt = true;}
      }
    }



    $code .= "<table class=\"cms_formular\">";
    // Inhalt
    if (!$ierlaubt) {$dis = " disabled=\"disabled\"";} else {$dis = "";}
    $code .= "<tr><th>Unterrichtsgegenstand:</th><td><textarea name=\"cms_tagebuch_eintrag_inhalt\" id=\"cms_tagebuch_eintrag_inhalt\" rows=\"2\"$dis>".$EINTRAG['inhalt']."</textarea></td></tr>";
    $code .= "<tr><th>Hausaufgaben:</th><td><textarea name=\"cms_tagebuch_eintrag_hausi\" id=\"cms_tagebuch_eintrag_hausi\" rows=\"2\"$dis>".$EINTRAG['hausaufgabe']."</textarea></td></tr>";
    $code .= "<tr><th>Leistungsmessung:</th><td>";
    if ($ierlaubt) {$code .= cms_generiere_schieber('cms_tagebuch_eintrag_lm', $EINTRAG['lm']);}
    else {if ($EINTRAG['lm'] == 0) {$code .= "NEIN";} else {$code .= "JA";}}
    $code .= "</td></tr>";

    // Sortieren
    usort($EINTRAG['fehl'], function ($a, $b) {
      if ($a['nach'] == $b['nach'])  {
        if ($a['vor']<$b['vor']) {return -1;}
        else {return 1;}
      }
      else {
        if ($a['nach'] < $b['nach']) {return -1;}
        else {return 1;}
      }
    });
    usort($EINTRAG['lobtadel'], function ($a, $b) {
      if ($a['nach'] == $b['nach'])  {
        if ($a['vor']<$b['vor']) {return -1;}
        else {return 1;}
      }
      else {
        if ($a['nach'] < $b['nach']) {return -1;}
        else {return 1;}
      }
    });

    // Fehlzeiten
    $code .= "<tr><th>Fehlzeiten:</th><td>";
    $code .= "<ul id=\"cms_tagebuch_fehlzeiten\">";
    $fehlids = array();
    if ($ferlaubt) {
      $code .= "<li><select name=\"cms_eintrag_fz_p\" id=\"cms_eintrag_fz_p\" class=\"cms_gross\">$SCHUELEROPT</select>";
      $code .= " <span class=\"cms_button_ja\" onclick=\"cms_eintrag_fzdazu('".$EINTRAG['beginn']."', '".($EINTRAG['ende']+1)."');\">+</span></li>";
      foreach ($EINTRAG['fehl'] AS $F) {
        $code .= "<li id=\"cms_eintrag_fz_".$F['id']."\">";
        $code .= "<input type=\"hidden\" name=\"cms_eintrag_fzp_".$F['id']."\" id=\"cms_eintrag_fzp_".$F['id']."\" value=\"".$F['pers']."\">";
        $code .= cms_uhrzeit_eingabe('cms_eintrag_fz_beginn_'.$F['id'], date('H', $EINTRAG['beginn']), date('i', $EINTRAG['beginn']));
        $code .= " – ".cms_uhrzeit_eingabe('cms_eintrag_fz_ende_'.$F['id'], date('H', $EINTRAG['ende']), date('i', $EINTRAG['ende']));
        $code .= ": ".$F['ganz']."<br>";
        $code .= "<input class=\"cms_gross\" type=\"text\" name=\"cms_eintrag_fz_bem_".$F['id']."\" id=\"cms_eintrag_fz_bem_".$F['id']."\" value=\"".$F['bem']."\"> ";
        $code .= "<span class=\"cms_button_nein\" onclick=\"cms_eintrag_fzweg('".$F['id']."')\">–</span>";
        $code .= "</li>";
        array_push($fehlids, $F['id']);
      }
    }
    else {
      $code .= "<li class=\"cms_notiz\">Es können keine Fehlzeiten mehr erfasst werden.</li>";
      foreach ($EINTRAG['fehl'] AS $F) {
        if ($F['ent'] == 1) {$klasse = "cms_tagebuch_entschuldigt";}
        else {$klasse = "cms_tagebuch_unentschuldig";}
        $code .= "<li class=\"cms_tagebuch_fehlzeit $klasse\">".$F['ganz'];
        if (($F['von'] != $EINTRAG['beginn']) || ($F['bis'] != $EINTRAG['ende'])) {
          $code .= date("H:i", $F['von'])." - ".date("H:i", $F['bis']);
          $code .= " - ".(($F['bis']-$F['von'])/60)."'";
        }
        if (strlen($F['bem']) > 0) {
          $code .= "(".$F['bem'].")";
        }
        $code .= "</li>";
        array_push($fehlids, $F['id']);
      }
    }
    $code .= "</ul>";
    $code .= "<p><input type=\"hidden\" id=\"cms_tagebuch_eintrag_fzids\" name=\"cms_tagebuch_eintrag_fzids\" value=\"".implode(',', $fehlids)."\"><input type=\"hidden\" id=\"cms_tagebuch_eintrag_fzan\" name=\"cms_tagebuch_eintrag_fzan\" value=\"0\"></p>";
    $code .= "</td></tr>";

    // Lob und Tadel
    $code .= "<tr><th>Bemerkung:</th><td>";
    $code .= "<ul id=\"cms_tagebuch_bemerkungen\">";
    $lobtadelids = array();
    if ($lterlaubt) {
      $code .= "<li><select name=\"cms_eintrag_lt_p\" id=\"cms_eintrag_lt_p\" class=\"cms_gross\"><option value=\"-\">ganze Klasse</option>$SCHUELEROPT</select>";
      $code .= " <span class=\"cms_button_ja\" onclick=\"cms_eintrag_ltdazu();\">+</span></li>";
      foreach ($EINTRAG['lobtadel'] AS $LT) {
        $code .= "<li id=\"cms_eintrag_lt_".$LT['id']."\">";
        $code .= "<input type=\"hidden\" name=\"cms_eintrag_ltp_".$LT['id']."\" id=\"cms_eintrag_ltp_".$LT['id']."\" value=\"".$LT['pers']."\">";
        $code .= "<span class=\"cms_button\" id=\"cms_eintrag_ltart_knopf_".$LT['id']."\" onclick=\"cms_ltart_aendern('".$LT['id']."')\">Bemerkung</span> ";
        $code .= "<span class=\"cms_button_nein\" id=\"cms_eintrag_ltchar_knopf_".$LT['id']."\" onclick=\"cms_ltchar_aendern('".$LT['id']."')\">negativ</span>: ";
        $code .= $LT['ganz']."<br>";
        $code .= "<input class=\"cms_gross\" type=\"text\" name=\"cms_eintrag_lt_bem_".$LT['id']."\" id=\"cms_eintrag_lt_bem_".$LT['id']."\" value=\"\"> ";
        $code .= "<span class=\"cms_button_nein\" onclick=\"cms_eintrag_ltweg('".$LT['id']."')\">–</span> ";
        $code .= "<input type=\"hidden\" name=\"cms_eintrag_ltchar_".$LT['id']."\" id=\"cms_eintrag_ltchar_".$LT['id']."\" value=\"-\">";
        $code .= "<input type=\"hidden\" name=\"cms_eintrag_ltart_".$LT['id']."\" id=\"cms_eintrag_ltart_".$LT['id']."\" value=\"B\">";
        $code .= "</li>";
        array_push($lobtadel, $LT['id']);
      }
    }
    else {
      $code .= "<li class=\"cms_notiz\">Es können keine Bemerkungen mehr erfasst werden.</li>";
      foreach ($EINTRAG['lobtadel'] AS $LT) {
        if ($LT['char'] == '+') {$klasse = "cms_tagebuch_positiv";}
        else if ($LT['char'] == '+') {$klasse = "cms_tagebuch_negativ";}
        else {$klasse = "cms_tagebuch_neutral";}
        $code .= "<li class=\"cms_tagebuch_lobtadel $klasse\">".$LT['ganz'];
        $code .= "(<b>".$LT['art']."</b> - ".$LT['bem'].")";
        $code .= "</li> ";
        array_push($lobtadel, $LT['id']);
      }
    }
    $code .= "</ul>";
    $code .= "<p><input type=\"hidden\" id=\"cms_tagebuch_eintrag_ltids\" name=\"cms_tagebuch_eintrag_ltids\" value=\"".implode(',', $lobtadelids)."\"><input type=\"hidden\" id=\"cms_tagebuch_eintrag_ltan\" name=\"cms_tagebuch_eintrag_ltan\" value=\"0\"></p>";
    $code .= "</td></tr>";

    // Änderungen übernehmen
    $code .= "<tr><th></th><td>";
    if ($ierlaubt || $ferlaubt || $lterlaubt) {$code .= "<span class=\"cms_button_ja\" onclick=\"cms_tagebuch_eintrag_speichern('0')\">Speichern</span> <span class=\"cms_button_ja\" onclick=\"cms_tagebuch_eintrag_speichern('1')\">Freigeben</span> ";}
    else {$code .= "<span class=\"cms_button_passiv\">Speichern</span> <span class=\"cms_button_passiv\">Freigeben</span>";}
    $code .= "<span class=\"cms_button_nein\" onclick=\"cms_tagebuch_eintraege_ausblenden()\">Abbrechen</span></td></tr>";

    $code .= "</table>";
    $code .= "<p>";//"<input type=\"hidden\" id=\"cms_tagebuch_eintrag_id\" name=\"cms_tagebuch_eintrag_id\" value=\"".$EINTRAG['uid']."\">";
    $code .= "<input type=\"hidden\" id=\"cms_tagebuch_eintrag_ierlaubt\" name=\"cms_tagebuch_eintrag_ierlaubt\" value=\"$ierlaubt\">";
    $code .= "<input type=\"hidden\" id=\"cms_tagebuch_eintrag_ferlaubt\" name=\"cms_tagebuch_eintrag_ferlaubt\" value=\"$ferlaubt\">";
    $code .= "<input type=\"hidden\" id=\"cms_tagebuch_eintrag_lterlaubt\" name=\"cms_tagebuch_eintrag_lterlaubt\" value=\"$lterlaubt\">";
    $code .= "</p>";
  }
  else {
    $code = "<td colspan=\"6\" class=\"cms_notiz\">Der passende Eintrag wurde nicht gefunden. Entweder sind Sie nicht berechtigt, diesen Eintrag vorzunehmen, oder diese Unterrichtsstunde existert nicht mehr.";
  }
  $code .= "</td>";

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
