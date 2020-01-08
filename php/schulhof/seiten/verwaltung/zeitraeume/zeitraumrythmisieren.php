<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.planungszeiträume.rythmisieren"))) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['ZEITRAUMRYTHMISIEREN'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT(zeitraeume.bezeichnung, '$CMS_SCHLUESSEL') AS zbez, zeitraeume.beginn, zeitraeume.ende, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?");
    $sql->bind_param('i', $_SESSION['ZEITRAUMRYTHMISIEREN']);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez, $zbez, $zbeginn, $zende, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    $code .= "<h1>Zeitraum »".$zbez."« bearbeiten im Schuljahr $sjbez</h1>";

    if ($rythmen > 1) {
      $FERIEN = array();
      $sql = $dbs->prepare("SELECT beginn, ende FROM ferien WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn <= ? AND ende >= ?) ORDER BY beginn ASC, ende DESC");
      $sql->bind_param("iiiiii", $zbeginn, $zende, $zbeginn, $zende, $zbeginn, $zende);
      if ($sql->execute()) {
        $sql->bind_result($fbeginn, $fende);
        while ($sql->fetch()) {
          $f = array();
          $f['b'] = $fbeginn;
          $f['e'] = $fende;
          array_push($FERIEN, $f);
        }
      }
      $sql->close();

      // Rythmen laden
      $RYTH = array();
      $sql = $dbs->prepare("SELECT beginn, kw, rythmus FROM rythmisierung WHERE zeitraum = ? ORDER BY beginn, kw");
      $sql->bind_param("i", $_SESSION['ZEITRAUMRYTHMISIEREN']);
      if ($sql->execute()) {
        $sql->bind_result($rbeginn, $rkw, $rr);
        while ($sql->fetch()) {
          $RYTH[$rbeginn][$rkw] = $rr;
        }
      }
      $sql->close();

      $TAGE = array();
      $TAGE[1] = $mo;
      $TAGE[2] = $di;
      $TAGE[3] = $mi;
      $TAGE[4] = $do;
      $TAGE[5] = $fr;
      $TAGE[6] = $sa;
      $TAGE[7] = $so;

      function cms_wochentagfeld($wochentag, $ferien, $TAGE) {
        if (!$wochentag) {return "<span class=\"cms_wochentag_rythmus_leer\"></span> ";}
        else if (!$TAGE[$wochentag]) {$klasse = "cms_wochentag_rythmus_schulfrei";}
        else if ($ferien) {$klasse = "cms_wochentag_rythmus_ferien";}
        else {$klasse = "cms_wochentag_rythmus";}
        return "<span class=\"$klasse\">".cms_tagname($wochentag)."</span> ";
      }

      $jahr = date('Y', $zbeginn);
      $wochentag = date('N', $zbeginn);
      $kalenderwoche = date('W', $zbeginn);
      $jetzt = $zbeginn;
      $tag = 60*60*24;

      if (count($FERIEN) > 0) {
        $fb = $FERIEN[0]['b'];
        $fe = $FERIEN[0]['e'];
      }
      else {
        $fb = 0;
        $fe = 0;
      }
      $fnid = 1;
      $geradef = false;
      $ffertig = false;
      $fanzahl = COUNT($FERIEN);

      while (($jetzt > $fe) && ($fnid < $fanzahl)) {
        $fb = $FERIEN[$fnid]['b'];
        $fe = $FERIEN[$fnid]['e'];
        $fnid++;
      }
      if ($jetzt > $fe) {$ffertig = true;}
      if (($jetzt > $fb) && (!$ffertig)) {$geradef = true;} else {$geradef = false;}

      $code .= "<p><input type=\"hidden\" name=\"cms_rythmisierung_beginnjahr\" id=\"cms_rythmisierung_beginnjahr\" value=\"$jahr\"><input type=\"hidden\" name=\"cms_rythmisierung_beginnkw\" id=\"cms_rythmisierung_beginnkw\" value=\"$kalenderwoche\"></p>";

      $OPTIONEN = array();
      for ($r=1; $r<=$rythmen; $r++) {$OPTIONEN[$r] = "";}
      for ($r=1; $r<=$rythmen; $r++) {
        for ($ri=1; $ri<=$rythmen; $ri++) {
          if ($r == $ri) {$OPTIONEN[$ri] .= "<option value=\"$r\" selected=\"selected\">".chr(64+$r)."</option>";}
          else {$OPTIONEN[$ri] .= "<option value=\"$r\">".chr(64+$r)."</option>";}
        }
      }

      $code .= "<table class=\"cms_formular\">";
        $code .= "<tr><th>KW</th><th>Wochenbeginn</th><th>Tage</th><th>Rythmisierung</th></tr>";
        $code .= "<tr><td>$kalenderwoche</td><td>".cms_tagname($wochentag)." ".date("d.m.Y", $jetzt)."</td><td>";
        for ($i=1; $i<$wochentag; $i++) {$code .= cms_wochentagfeld(false, false, $TAGE);}
      $woche = 0;

      $wochenbeginn = $zbeginn;
      while ($jetzt <= $zende) {
        if ($wochentag > 7) {
          $woche++;
          if (isset($RYTH[$wochenbeginn][$kalenderwoche])) {$opt = $OPTIONEN[$RYTH[$wochenbeginn][$kalenderwoche]];}
          else {$opt = $OPTIONEN[1];}
          $code .= "</td><td><select name=\"cms_rythmus_$woche\" id=\"cms_rythmus_$woche\">$opt</select></td></tr>";
          $wochentag = 1;
          $kalenderwoche++;
          if ($kalenderwoche > 52) {
            $kalenderwoche = 1;
          }
          $wochenbeginn = mktime(0,0,0,date('m',$wochenbeginn), date('d', $wochenbeginn)+(7-date('N', $wochenbeginn)+1), date('Y', $wochenbeginn));
          $code .= "<tr><td>$kalenderwoche</td><td>".cms_tagname($wochentag)." ".date("d.m.Y", $jetzt)."</td><td>";
        }

        while (($jetzt > $fe) && ($fnid < $fanzahl)) {
          $fb = $FERIEN[$fnid]['b'];
          $fe = $FERIEN[$fnid]['e'];
          $fnid++;
        }
        if ($jetzt > $fe) {$ffertig = true;}
        if (($jetzt >= $fb) && (!$ffertig)) {$geradef = true;} else {$geradef = false;}

        $code .= cms_wochentagfeld($wochentag, $geradef, $TAGE);
        $jetzt = mktime(0,0,0,date('m', $jetzt), date('d', $jetzt)+1, date('Y',$jetzt));
        $wochentag++;
      }
      if ($tag != 1) {
        $woche++;
        if (isset($RYTH[$wochenbeginn][$kalenderwoche])) {$opt = $OPTIONEN[$RYTH[$wochenbeginn][$kalenderwoche]];} else {$opt = $OPTIONEN[1];}
        $code .= "</td><td><select name=\"cms_rythmus_$woche\" id=\"cms_rythmus_$woche\">$opt</select></td></tr>";
      }
      $code .= "</tr></table>";
      $code .= "<p><input type=\"hidden\" name=\"cms_rythmisierung_wochenzahl\" id=\"cms_rythmisierung_wochenzahl\" value=\"$woche\"></p>";


  		$code .= "<p><span class=\"cms_button\" onclick=\"cms_zeitraeume_rythmisierung_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Zeiträume\">Abbrechen</a></p>";
    }
    else {
      $code .= cms_meldung('info', '<h4>Keine Rythmisierung gewählt</h4><p>Es wurde keine Rythmisierung ausgewählt, folglich können auch keine Wochen verteilt werden.</p>');
    }
  }
  else {$code .= "<h1>Zeitraum rythmisieren</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Zeitraum rythmisieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
