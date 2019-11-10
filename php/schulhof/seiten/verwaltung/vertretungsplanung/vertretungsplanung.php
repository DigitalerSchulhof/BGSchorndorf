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
    $tag = date('d');
    $monat = date('m');
    $jahr = date('Y');
    $vollbildgewaehlt = 0;
    $hb = mktime(0,0,0, $monat, $tag, $jahr);
    $he = mktime(0,0,0,$monat, $tag+1, $jahr)-1;

    $wochentag = date('N', $hb);
    $datummo = mktime(0,0,0, $monat, $tag-$wochentag+1, $jahr);

    $ltag = $rtag = $ktag = date('d', $datummo);
    $lmonat = $rmonat = $kmonat = date('m', $datummo);
    $ljahr = $rjahr = $kjahr = date('Y', $datummo);
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

      // SCHULSTUNDEN
      $SCHULSTUNDEN = array();
      $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, beginns, beginnm FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY beginns, beginnm");
      $sql->bind_param("ii", $hb, $he);
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez, $ebeginns, $ebenginnm);
        while ($sql->fetch()) {
          $einzeln = array();
          $einzeln['id'] = $eid;
          $einzeln['bez'] = $ebez;
          array_push($SCHULSTUNDEN, $einzeln);
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
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_klasse\" name=\"cms_vplan_woche_klasse\" onchange=\"cms_vplan_klasse('j')\">";
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
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_lehrer\" name=\"cms_vplan_woche_lehrer\" onchange=\"cms_vplan_lehrer('j')\">";
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
      $code .= "<tr><td colspan=\"3\"><select id=\"cms_vplan_woche_raum\" name=\"cms_vplan_woche_raum\" onchange=\"cms_vplan_raum('j')\">";
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
        $code .= "<li><span id=\"cms_reiter_konflikte_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('konflikte', 0,2)\">Liste</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_1\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 1,2)\">Plan</span></li> ";
        $code .= "<li><span id=\"cms_reiter_konflikte_2\" class=\"cms_reiter\" onclick=\"cms_reiter('konflikte', 2,2)\">Stundendetails</span></li>";
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
        $code .= "<td>".cms_datum_eingabe('cms_vplankonflikte_zweitdatum', cms_fuehrendenull($tag-$wochentag+8), $monat, $jahr, 'cms_vplan_zweitkonflikte(\'j\');')."</td>";
        $code .= "<td><span class=\"cms_button\" onclick=\"cms_vplan_zweitkonflikte('+')\">»</span></td></tr>";
        $code .= "</table>";
        $code .= "</td></tr>";
        $code .= "</table>";
        $code .= cms_generiere_nachladen('cms_vplan_konflikte_plan', 'cms_vplan_alles_neuladen(\'a\', \'s\');');
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_konflikte_2\">";
        $code .= "<div class=\"cms_reitermenue_i\">";
        $code .= "<div id=\"cms_vplan_stundendetails\">";
        $code .= "<p class=\"cms_notiz\">Es wurde keine Stunde ausgewählt.</p>";
        $code .= "</div>";
        $code .= "</div>";
      $code .= "</div>";
      $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_vplan_vormerkungen_uebernehmen()\">Änderungen übernehmen und veröffentlichen</span> <span class=\"cms_button\" onclick=\"cms_vplan_drucken()\">Drucken</span> <span class=\"cms_button\" onclick=\"cms_vplan_standardansicht()\">Stadardansicht</span> ";

      if ($CMS_RECHTE['Planung']['Ausplanungen durchführen']) {
        $code .= "<a class=\"cms_button\" href=\"Schulhof/Verwaltung/Planung/Ausplanungen\">Ausplanungen</a>";
      }

      $code .= "</p>";

      $code .= "<h2>Anmerkungen zum Schultag</h2>";
      $code .= "<div id=\"cms_vplan_vertretungstext\">";
      $code .= "<table class=\"cms_formular\">";
      $code .= "<tr><th>Schüler:</th><td><textarea name=\"cms_vplan_vtext_schueler\" id=\"cms_vplan_vtext_schueler\">$VERTRETUNGSTEXTS</textarea></td></tr>";
      $code .= "<tr><th>Lehrer:</th><td><textarea name=\"cms_vplan_vtext_lehrer\" id=\"cms_vplan_vtext_lehrer\">$VERTRETUNGSTEXTL</textarea></td></tr>";
      $code .= "</table>";
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_vtexte_speichern()\">Vertretungstexte speichern</span></p>";
      $code .= "</div>";

      $code .= "<h2 draggable=\"true\">Nur für Notfälle!!</h2>";
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
