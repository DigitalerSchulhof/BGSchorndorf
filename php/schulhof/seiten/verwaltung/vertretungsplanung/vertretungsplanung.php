<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Vertretungsplanung</h1>
<?php
$code = "";
if (r("schulhof.planung.vertretungsplan.vertretungsplanung")) {
  if (!$CMS_IMLN) {
    $code .= cms_meldung_firewall();
  }
  else {
    if (isset($_SESSION['VPlanKonflikte1T']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte1T'],1,31)) {$tag = $_SESSION['VPlanKonflikte1T'];} else {$tag = date('d');}
    if (isset($_SESSION['VPlanKonflikte1M']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte1M'],1,12)) {$monat = $_SESSION['VPlanKonflikte1M'];} else {$monat = date('m');}
    if (isset($_SESSION['VPlanKonflikte1J']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte1J'],0)) {$jahr = $_SESSION['VPlanKonflikte1J'];} else {$jahr = date('Y');}
    $_SESSION['VPlanKonflikte1T'] = $tag;
    $_SESSION['VPlanKonflikte1M'] = $monat;
    $_SESSION['VPlanKonflikte1J'] = $jahr;
    $_SESSION['AusplanungenT'] = $tag;
    $_SESSION['AusplanungenM'] = $monat;
    $_SESSION['AusplanungenJ'] = $jahr;
    if (isset($_SESSION['VPlanKonflikte2T']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte2T'],1,31)) {$k2tag = $_SESSION['VPlanKonflikte2T'];} else {$k2tag = date('d')+7;}
    if (isset($_SESSION['VPlanKonflikte2M']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte2M'],1,12)) {$k2monat = $_SESSION['VPlanKonflikte2M'];} else {$k2monat = date('m');}
    if (isset($_SESSION['VPlanKonflikte2J']) && cms_check_ganzzahl($_SESSION['VPlanKonflikte2J'],0)) {$k2jahr = $_SESSION['VPlanKonflikte2J'];} else {$k2jahr = date('Y');}
    $_SESSION['VPlanKonflikte2T'] = $k2tag;
    $_SESSION['VPlanKonflikte2M'] = $k2monat;
    $_SESSION['VPlanKonflikte2J'] = $k2jahr;
    if (isset($_SESSION['VPlanWocheLehrerT']) && cms_check_ganzzahl($_SESSION['VPlanWocheLehrerT'],1,31)) {$ltag = $_SESSION['VPlanWocheLehrerT'];} else {$ltag = date('d');}
    if (isset($_SESSION['VPlanWocheLehrerM']) && cms_check_ganzzahl($_SESSION['VPlanWocheLehrerM'],1,12)) {$lmonat = $_SESSION['VPlanWocheLehrerM'];} else {$lmonat = date('m');}
    if (isset($_SESSION['VPlanWocheLehrerJ']) && cms_check_ganzzahl($_SESSION['VPlanWocheLehrerJ'],0)) {$ljahr = $_SESSION['VPlanWocheLehrerJ'];} else {$ljahr = date('Y');}
    $_SESSION['VPlanWocheLehrerT'] = $ltag;
    $_SESSION['VPlanWocheLehrerM'] = $lmonat;
    $_SESSION['VPlanWocheLehrerJ'] = $ljahr;
    if (isset($_SESSION['VPlanWocheRaeumeT']) && cms_check_ganzzahl($_SESSION['VPlanWocheRaeumeT'],1,31)) {$rtag = $_SESSION['VPlanWocheRaeumeT'];} else {$rtag = date('d');}
    if (isset($_SESSION['VPlanWocheRaeumeM']) && cms_check_ganzzahl($_SESSION['VPlanWocheRaeumeM'],1,12)) {$rmonat = $_SESSION['VPlanWocheRaeumeM'];} else {$rmonat = date('m');}
    if (isset($_SESSION['VPlanWocheRaeumeJ']) && cms_check_ganzzahl($_SESSION['VPlanWocheRaeumeJ'],0)) {$rjahr = $_SESSION['VPlanWocheRaeumeJ'];} else {$rjahr = date('Y');}
    $_SESSION['VPlanWocheRaeumeT'] = $rtag;
    $_SESSION['VPlanWocheRaeumeM'] = $rmonat;
    $_SESSION['VPlanWocheRaeumeJ'] = $rjahr;
    if (isset($_SESSION['VPlanWocheKlassenStufenT']) && cms_check_ganzzahl($_SESSION['VPlanWocheKlassenStufenT'],1,31)) {$ktag = $_SESSION['VPlanWocheKlassenStufenT'];} else {$ktag = date('d');}
    if (isset($_SESSION['VPlanWocheKlassenStufenM']) && cms_check_ganzzahl($_SESSION['VPlanWocheKlassenStufenM'],1,12)) {$kmonat = $_SESSION['VPlanWocheKlassenStufenM'];} else {$kmonat = date('m');}
    if (isset($_SESSION['VPlanWocheKlassenStufenJ']) && cms_check_ganzzahl($_SESSION['VPlanWocheKlassenStufenJ'],0)) {$kjahr = $_SESSION['VPlanWocheKlassenStufenJ'];} else {$kjahr = date('Y');}
    $_SESSION['VPlanWocheKlassenStufenT'] = $ktag;
    $_SESSION['VPlanWocheKlassenStufenM'] = $kmonat;
    $_SESSION['VPlanWocheKlassenStufenJ'] = $kjahr;

    $vollbildgewaehlt = 0;
    $hb = mktime(0,0,0, $monat, $tag, $jahr);

    $ldatum = cms_finde_montag($ltag, $lmonat, $ljahr);
    $ltag = $ldatum['T']; $lmonat = $ldatum['M']; $ljahr = $ldatum['J'];
    $rdatum = cms_finde_montag($rtag, $rmonat, $rjahr);
    $rtag = $rdatum['T']; $rmonat = $rdatum['M']; $rjahr = $rdatum['J'];
    $kdatum = cms_finde_montag($ktag, $kmonat, $kjahr);
    $ktag = $kdatum['T']; $kmonat = $kdatum['M']; $kjahr = $kdatum['J'];
    $k2datum = cms_finde_montag($k2tag, $k2monat, $k2jahr);
    $k2tag = $k2datum['T']; $k2monat = $k2datum['M']; $k2jahr = $k2datum['J'];
    $VERTRETUNGSTEXTL = "";
    $VERTRETUNGSTEXTS = "";

    $code .= "</div>";

    $sjfehler = false;
    // Vertretungstext laden
    $sql = $dbs->prepare("SELECT art, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM vplantext WHERE zeit = ?");
    $sql->bind_param("i", $hb);
    if ($sql->execute()) {
      $sql->bind_result($art, $inhalt);
      while ($sql->fetch()) {
        if ($art == 'l') {$VERTRETUNGSTEXTL = $inhalt;}
        if ($art == 's') {$VERTRETUNGSTEXTS = $inhalt;}
      }
    }
    $sql->close();

    // Schuljahr und Zeitraum laden
    $sql = $dbs->prepare("SELECT COUNT(*), id, schuljahr FROM zeitraeume WHERE ? BETWEEN beginn AND ende");
    $sql->bind_param("i", $hb);
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

      $code .= "<div id=\"cms_vplanung_vollbild_F\"$zusatz>";
      $code .= "<div class=\"cms_vollbild_innen\">";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<p class=\"cms_rechtsbuendig\">";
      $code .= cms_toggletext_generieren('cms_vplanung_vollbild', 'Vollbild', '&times;', $vollbildgewaehlt, "cms_vollbild('cms_vplanung_vollbild');");
      $code .= "</p>";
      $code .= "</div>";

      // Klassen des Schuljahres laden
      $KLASSEN = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
      $sql->bind_param("i", $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez, $erei);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($KLASSEN, $einzeln);
        }
      }
      $sql->close();
      if (count($KLASSEN) > 0) {$ksartgewaehlt = 'k'; $kszielgewaehlt = $KLASSEN[0]['id'];}
      else {$ksartgewaehlt = null; $kszielgewaehlt = null;}

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
      if ($ksartgewaehlt === null) {
        if (count($STUFEN) > 0) {$ksartgewaehlt = 's'; $kszielgewaehlt = $STUFEN[0]['id'];}
        else {$ksartgewaehlt = null; $kszielgewaehlt = null;}
      }

      // Lehrer
      $LEHRER = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY kuerzel, nachname, vorname, titel");
      if ($sql->execute()) {
        $sql->bind_result($eid, $evor, $enach, $etitel, $ekurz);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          if (strlen($ekurz) > 0) {$einzeln['bez'] = $ekurz." - ".cms_generiere_anzeigename($evor, $enach, $etitel);}
          else {$einzeln['bez'] = cms_generiere_anzeigename($evor, $enach, $etitel);}
          array_push($LEHRER, $einzeln);
        }
      }
      $sql->close();
      if (count($LEHRER) > 0) {$lehrergewaehlt = $LEHRER[0]['id'];}
      else {$lehrergewaehlt = null;}

      // Räume laden
      $RAEUME = array();
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT raeume.id AS id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzk, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzs FROM raeume LEFT JOIN raeumeklassen ON raeume.id = raeumeklassen.raum LEFT JOIN klassen ON raeumeklassen.klasse = klassen.id LEFT JOIN raeumestufen ON raeume.id = raeumestufen.raum LEFT JOIN stufen ON raeumestufen.stufe = stufen.id)) AS x GROUP BY id ORDER BY bezeichnung ASC");
      if ($sql->execute()) {
        $sql->bind_result($id, $bez, $zusatzk, $zusatzs);
        while ($sql->fetch()) {
          $einzeln = array();
          $rbez = $bez;
          $zusatz = "";
          if ($zusatzk !== null) {$zusatz .= ", ".$zusatzk;}
          if ($zusatzs !== null) {$zusatz .= ", ".$zusatzs;}
          if (strlen($zusatz) > 0) {$rbez .= " » ".substr($zusatz, 2);}
          $einzeln['id'] = $id;
          $einzeln['bez'] = $rbez;
          array_push($RAEUME, $einzeln);
        }
      }
      $sql->close();
      if (count($RAEUME) > 0) {$raumgewaehlt = $RAEUME[0]['id'];}
      else {$raumgewaehlt = null;}

      // KURSE
      $KURSE = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id) AS x ORDER BY reihenfolge, bez");
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez, $ereihe);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($KURSE, $einzeln);
        }
      }
      $sql->close();

      // SCHULSTUNDEN LADEN
      $SCHULSTUNDEN = array();
      $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ?) AS x ORDER BY beginns, beginnm ASC";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("i", $ZEITRAUM);
      if ($sql->execute()) {
        $sql->bind_result($id, $bez, $beginns, $beginnm, $endes, $endem);
        while ($sql->fetch()) {
          $s = array();
          $s['id'] = $id;
          $s['bez'] = $bez;
          array_push($SCHULSTUNDEN, $s);
        }
      }
      $sql->close();

      // WOCHENPLÄNE
      $code .= "<div class=\"cms_spalte_60\" id=\"cms_spalte_wochenplaene\">";
      $code .= "<div class=\"cms_groesseaendern\" id=\"cms_groesseaendern_wochekonf\"></div>";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<h2>Wochenpläne ".cms_togglebutton_generieren('cms_vplan_wochenplaene', 'Koppeln', 1)."</h2>";
      $code .= "</div>";

      $code .= "<div class=\"cms_spalte_3\" id=\"cms_spalte_klassen\">";
      $code .= "<div class=\"cms_groesseaendern\" id=\"cms_groesseaendern_klasselehrer\"></div>";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<table class=\"cms_zeitwahl\">";
      $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_klasse('-')\">«</span></td><td>".cms_datum_eingabe('cms_vplanklasse_datum', $ktag, $kmonat, $kjahr, 'cms_vplan_klasse(\'j\');')."</td><td><span class=\"cms_button\" onclick=\"cms_vplan_klasse('+')\">»</span></td></tr>";
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_klasse\" name=\"cms_vplan_woche_klasse\" onchange=\"cms_vplan_klasse('x')\">";
      $code .= "<optgroup label=\"Klassen\">";
      foreach ($KLASSEN as $e) {
        $code .= "<option value=\"k".$e['id']."\">".$e['bez']."</option>";
      }
      //$code .= "<option value=\"k-\">klassenübergreifend</option></optgroup>";
      $code .= "<optgroup label=\"Stufen\">";
      foreach ($STUFEN as $e) {
        $code .= "<option value=\"s".$e['id']."\">".$e['bez']."</option>";
      }
      //$code .= "<option value=\"s-\">stufenübergreifend</option></optgroup>";
      $code .= "</select></td></tr>";
      $code .= "</table>";
      $code .= cms_generiere_nachladen('cms_vplan_wochenplan_k', '');
      $code .= "</div></div>";

      $code .= "<div class=\"cms_spalte_3\" id=\"cms_spalte_lehrer\">";
      $code .= "<div class=\"cms_groesseaendern\" id=\"cms_groesseaendern_lehrerraum\"></div>";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<table class=\"cms_zeitwahl\">";
      $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_lehrer('-')\">«</span></td><td>".cms_datum_eingabe('cms_vplanlehrer_datum', $ltag, $lmonat, $ljahr, 'cms_vplan_lehrer(\'j\');')."</td><td><span class=\"cms_button\" onclick=\"cms_vplan_lehrer('+')\">»</span></td></tr>";
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_lehrer\" name=\"cms_vplan_woche_lehrer\" onchange=\"cms_vplan_lehrer('x')\">";
      foreach ($LEHRER as $e) {
        $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
      }
      $code .= "</select></td></tr>";
      $code .= "</table>";
      $code .= cms_generiere_nachladen('cms_vplan_wochenplan_l', '');
      $code .= "</div></div>";

      $code .= "<div class=\"cms_spalte_3\" id=\"cms_spalte_raeume\">";
      $code .= "<div class=\"cms_spalte_i\">";
      $code .= "<table class=\"cms_zeitwahl\">";
      $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_raum('-')\">«</span></td><td>".cms_datum_eingabe('cms_vplanraum_datum', $rtag, $rmonat, $rjahr, 'cms_vplan_raum(\'j\');')."</td><td><span class=\"cms_button\" onclick=\"cms_vplan_raum('+')\">»</span></td></tr>";
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_raum\" name=\"cms_vplan_woche_raum\" onchange=\"cms_vplan_raum('x')\">";
      foreach ($RAEUME as $e) {
        $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
      }
      $code .= "</select></td></tr>";
      $code .= "</table>";
      $code .= cms_generiere_nachladen('cms_vplan_wochenplan_r', '');
      $code .= "</div></div>";

      $code .= "<div class=\"cms_clear\"></div>";

      $code .= "<div class=\"cms_spalte_i\">";

      $code .= "</div>";

      $code .= "</div>";

      // TAGESDETAILS
      $code .= "<div class=\"cms_spalte_40\" id=\"cms_spalte_konflikte\">";
      $code .= "<div class=\"cms_spalte_i\">";

      $code .= "<h2>Konflikte</h2>";
      $code .= "<table class=\"cms_zeitwahl\">";
      $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_konflikte('-')\">«</span></td><td>".cms_datum_eingabe('cms_vplankonflikte_datum', $tag, $monat, $jahr, 'cms_vplan_konflikte(\'j\');')."</td><td><span class=\"cms_button\" onclick=\"cms_vplan_konflikte('+')\">»</span></td>";
      $code .= "</table>";



      $code .= "<ul class=\"cms_reitermenue\">";
        $code .= "<li><span id=\"cms_reiter_konflikte_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('konflikte', 0,5)\">Liste</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_1\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 1,5)\">Plan</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_2\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 2,5)\">Stundendetails</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_3\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 3,5)\">Tagesinfo</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_4\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 4,5)\">Vorschau</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_5\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 5,5)\">Ausplanung</span></li> ";
      $code .= "</ul>";

      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_0\" style=\"display: block;\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= cms_generiere_nachladen('cms_vplan_konflikte_liste', '');
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_1\" style=\"display: none;\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= "<table class=\"cms_vplan_konfliktplan_wahl\"><tr><td><select id=\"cms_vplankonflikte_plan_art\" name=\"cms_vplankonflikte_plan_art\" onchange=\"cms_vplan_konflikte_planwahl()\">";
        $code .= "<option value=\"l\">Lehrer</option><option value=\"r\">Räume</option><option value=\"k\">Klassen</option><option value=\"s\">Stufen</option>";
        $code .= "</select></td>";
        $code .= "<td><select id=\"cms_vplankonflikte_plan_ziel\" name=\"cms_vplankonflikte_plan_ziel\" onchange=\"cms_vplan_konflikte_plan()\">";
        foreach ($LEHRER as $e) {
          $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr><td>".cms_togglebutton_generieren('cms_vplan_konfliktplan_regelplan', 'Regelstundenplan', 0, 'cms_vplan_konflikte_plan()')."</td><td>";
        $code .= "<table class=\"cms_zeitwahl\">";
        $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_zweitkonflikte('-')\">«</span></td>";
        // Nächsten Montag bestimmen
        $code .= "<td>".cms_datum_eingabe('cms_vplankonflikte_zweitdatum', cms_fuehrendenull($k2tag), $k2monat, $k2jahr, 'cms_vplan_zweitkonflikte(\'j\');')."</td>";
        $code .= "<td><span class=\"cms_button\" onclick=\"cms_vplan_zweitkonflikte('+')\">»</span></td></tr>";
        $code .= "</table>";
        $code .= "</td></tr>";
        $code .= "</table>";
        $code .= cms_generiere_nachladen('cms_vplan_konflikte_plan', '');
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_2\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= "<div id=\"cms_vplan_stundendetails\">";
        $code .= "<p class=\"cms_notiz\">Es wurde keine Stunde ausgewählt.</p>";
        $code .= "</div>";
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_3\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= cms_generiere_nachladen('cms_vplan_vertretungstext', '');
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_4\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= "<div id=\"cms_vplan_vplanvorschau\"></div>";
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_5\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= "<table class=\"cms_formular\">";
        $code .= "<tr><th>von:</th><td>".cms_datum_eingabe('cms_ausplanung_datum_von', $tag, $monat, $jahr, 'cms_vplan_schulstunden_laden_von();')."</td><td id=\"cms_ausplanung_schulstunden_von\"><select id=\"cms_ausplanung_std_von\" bis=\"cms_ausplanung_std_von\">";
        for ($s=0; $s<count($SCHULSTUNDEN); $s++) {
          $code .= "<option value=\"".$SCHULSTUNDEN[$s]['id']."\">".$SCHULSTUNDEN[$s]['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr><th>bis:</th><td>".cms_datum_eingabe('cms_ausplanung_datum_bis', $tag, $monat, $jahr, 'cms_vplan_schulstunden_laden_bis();')."</td><td id=\"cms_ausplanung_schulstunden_bis\"><select id=\"cms_ausplanung_std_bis\" name=\"cms_ausplanung_std_bis\">";
        for ($s=0; $s<count($SCHULSTUNDEN)-1; $s++) {
          $code .= "<option value=\"".$SCHULSTUNDEN[$s]['id']."\">".$SCHULSTUNDEN[$s]['bez']."</option>";
        }
        $code .= "<option value=\"".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['id']."\" selected=\"selected\">".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['bez']."</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr><th>Art:</th><td colspan=\"2\"><select id=\"cms_ausplanen_art\" name=\"cms_ausplanen_art\" onmouseup=\"cms_ausplanung_art_aendern()\" onchange=\"cms_ausplanung_art_aendern()\">";
          $code .= "<option value=\"l\">Lehrkraft</option><option value=\"r\">Raum</option><option value=\"k\">Klassen</option><option value=\"s\">Stufen</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_art_l\"><th>Lehrkräfte:</th><td colspan=\"2\"><select id=\"cms_ausplanen_l\" name=\"cms_ausplanen_l\">";
        foreach ($LEHRER as $e) {
          $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_grund_l\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundl\" name=\"cms_ausplanen_grundl\">";
          $code .= "<option value=\"dv\">dienstlich verhindert</option><option value=\"k\">krank</option><option value=\"kk\">krankes Kind</option><option value=\"p\">bei Prüfung</option><option value=\"b\">beurlaubt</option><option value=\"ex\">auf Exkursion</option><option value=\"s\">sonstiges</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_art_r\" style=\"display:none\"><th>Räume:</th><td colspan=\"2\"><select id=\"cms_ausplanen_r\" name=\"cms_ausplanen_r\">";
        foreach ($RAEUME as $e) {
          $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_grund_r\" style=\"display:none\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundr\" name=\"cms_ausplanen_grundr\">";
          $code .= "<option value=\"b\">blockiert</option><option value=\"p\">durch Prüfung belegt</option><option value=\"k\">kaputt</option><option value=\"s\">sonstiges</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_art_k\" style=\"display:none\"><th>Klassen:</th><td id=\"cms_klassen_ausplanen\" colspan=\"2\"><select id=\"cms_ausplanen_k\" name=\"cms_ausplanen_k\">";
        foreach ($KLASSEN as $e) {
          $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_grund_k\" style=\"display:none\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundk\" name=\"cms_ausplanen_grundk\">";
          $code .= "<option value=\"ex\">auf Exkursion</option><option value=\"sh\">im Schullandheim</option><option value=\"p\">bei Prüfung</option><option value=\"bv\">bei Berufsorientierung</option><option value=\"k\">krank</option><option value=\"s\">sonstiges</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_art_s\" style=\"display:none\"><th>Stufen:</th><td id=\"cms_stufen_ausplanen\" colspan=\"2\"><select id=\"cms_ausplanen_s\" name=\"cms_ausplanen_s\">";
        foreach ($STUFEN as $e) {
          $code .= "<option value=\"".$e['id']."\">".$e['bez']."</option>";
        }
        $code .= "</select></td></tr>";
        $code .= "<tr id=\"cms_ausplanung_grund_s\" style=\"display:none\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grunds\" name=\"cms_ausplanen_grunds\">";
          $code .= "<option value=\"ex\">auf Exkursion</option><option value=\"sh\">im Schullandheim</option><option value=\"p\">bei Prüfung</option><option value=\"bv\">bei Berufsorientierung</option><option value=\"k\">krank</option><option value=\"s\">sonstiges</option>";
        $code .= "</select></td></tr>";
        $code .= "<tr><th>Zusatztext:</th><td colspan=\"2\"><input id=\"cms_ausplanen_zusatz\" name=\"cms_ausplanen_zusatz\" type=\"text\"></td></tr>";
        $code .= "<tr><th>Betroffene Stunden:</th><td colspan=\"2\"><select id=\"cms_ausplanen_folge\" name=\"cms_ausplanen_folge\"><option value=\"k\">als Konflikt anzeigen</option><option value=\"e\">als Entfall vormerken</option><option value=\"u\">als unsichtbaren Entfall vormerken</option></select></td></tr>";
        $code .= "</table>";
        $code .= "<p><span class=\"cms_button\" onclick=\"cms_ausplanung_speichern();\">Speichern</span></p>";
        $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_l', '');
        $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_r', '');
        $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_k', '');
        $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_s', 'cms_vplan_alles_neuladen(\'a\', \'s\');');
        $code .= "<input type=\"hidden\" name=\"cms_ausplanungen_ort\" id=\"cms_ausplanungen_ort\" value=\"v\">";
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_vplan_vormerkungen_uebernehmen()\">Änderungen übernehmen und veröffentlichen</span> <span class=\"cms_button\" onclick=\"cms_vplan_drucken()\">Drucken</span> <span class=\"cms_button\" onclick=\"cms_vplan_standardansicht()\">Standardansicht</span></p>";

      $code .= "<h2>Nur für Notfälle!!</h2>";
      $code .= "<p><span class=\"cms_button_nein\" onclick=\"cms_vplan_vormerkungen_loeschen_anzeigen()\">Alle Änderungen löschen</span> <span class=\"cms_button_nein\" onclick=\"cms_vplan_regelstundenplan_zueuecksetzen_anzeigen()\">Ganzen Tag auf Regelstundenplan zurücksetzen</span></p>";

      $code .= "<p><input type=\"hidden\" id=\"cms_vplan_stunde_markierteklasse\" name=\"cms_vplan_stunde_markierteklasse\" value=\"XXX\"></p>";

      $code .= "</div></div>";
      $code .= "<div class=\"cms_clear\"></div>";

      echo $code;

      ?>
      <script>
        var cms_groesseaendern_wochekonf = document.getElementById("cms_groesseaendern_wochekonf");
        var cms_groesseaendern_klasselehrer = document.getElementById("cms_groesseaendern_klasselehrer");
        var cms_groesseaendern_lehrerraum = document.getElementById("cms_groesseaendern_lehrerraum");
      	cms_groesseaendern_wochekonf.addEventListener("mousedown", function(e){
      		gedrueckte_xpos = e.x;
      		document.addEventListener("mousemove", cms_groesse_wochenkonf_aendern, false);
      	}, false);
      	cms_groesseaendern_klasselehrer.addEventListener("mousedown", function(e){
      		gedrueckte_xpos = e.x;
      		document.addEventListener("mousemove", cms_groesse_klasselehrer_aendern, false);
      	}, false);
      	cms_groesseaendern_lehrerraum.addEventListener("mousedown", function(e){
      		gedrueckte_xpos = e.x;
      		document.addEventListener("mousemove", cms_groesse_lehrerraum_aendern, false);
      	}, false);

      	document.addEventListener("mouseup", function(){
      	    document.removeEventListener("mousemove", cms_groesse_wochenkonf_aendern, false);
      	}, false);
      	document.addEventListener("mouseup", function(){
      	    document.removeEventListener("mousemove", cms_groesse_klasselehrer_aendern, false);
      	}, false);
      	document.addEventListener("mouseup", function(){
      	    document.removeEventListener("mousemove", cms_groesse_lehrerraum_aendern, false);
      	}, false);
      </script>
      <?php

      // VOLLBILD SCHLIESSEN
      $code = "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_clear\"></div>";
    }
    else {
      $code .= cms_meldung('info', '<h4>Kein Planungszeitraum</h4><p>Das eingegebene Datum gehört zu keinem Planungszeitraum!</p>');
    }
  }

}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
</div>
<div class="cms_clear"></div>
