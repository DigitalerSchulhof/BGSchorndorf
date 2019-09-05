<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume rythmisieren'];
$code = "";
if ($zugriff) {
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
      $RYTHMEN = array();
      $sql = $dbs->prepare("SELECT jahr, kw, rythmus FROM rythmisierung WHERE zeitraum = ? ORDER BY jahr, kw");
      $sql->bind_param("i", $_SESSION['ZEITRAUMRYTHMISIEREN']);
      if ($sql->execute()) {
        $sql->bind_result($rjahr, $rkw, $rr);
        while ($sql->fetch()) {
          $RYTHMEN[$rjahr][$rkw] = $rr;
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
        $code .= "<tr><th>KW</th><th>Jahr</th><th>Wochenbeginn</th><th>Tage</th><th>Rythmisierung</th></tr>";
        $code .= "<tr><td>$kalenderwoche</td><td>$jahr</td><td>".cms_tagname($wochentag)." ".date("d.m.Y", $jetzt)."</td><td>";
        for ($i=1; $i<$wochentag; $i++) {$code .= cms_wochentagfeld(false, false, $TAGE);}
      while ($jetzt <= $zende) {
        if ($wochentag > 7) {
          if (isset($RYTHMEN[$jahr][$kalenderwoche])) {$opt = $OPTIONEN[$RYTHMEN[$jahr][$kalenderwoche]];} else {$opt = $OPTIONEN[1];}
          $code .= "</td><td><select name=\"cms_rythmus_$jahr"."_$kalenderwoche\" id=\"cms_rythmus_$jahr"."_$kalenderwoche\">$opt</select></td></tr>";
          $wochentag = 1;
          $kalenderwoche++;
          if ($kalenderwoche > 52) {
            $kalenderwoche = 1;
            $jahr++;
          }
          $code .= "<tr><td>$kalenderwoche</td><td>$jahr</td><td>".cms_tagname($wochentag)." ".date("d.m.Y", $jetzt)."</td><td>";
        }

        while (($jetzt > $fe) && ($fnid < $fanzahl)) {
          $fb = $FERIEN[$fnid]['b'];
          $fe = $FERIEN[$fnid]['e'];
          $fnid++;
        }
        if ($jetzt > $fe) {$ffertig = true;}
        if (($jetzt > $fb) && (!$ffertig)) {$geradef = true;} else {$geradef = false;}

        $code .= cms_wochentagfeld($wochentag, $geradef, $TAGE);
        $jetzt += $tag;
        $wochentag++;
      }
      if ($tag != 1) {
        if (isset($RYTHMEN[$jahr][$kalenderwoche])) {$opt = $OPTIONEN[$RYTHMEN[$jahr][$kalenderwoche]];} else {$opt = $OPTIONEN[1];}
        $code .= "</td><td><select name=\"cms_rythmus_$jahr"."_$kalenderwoche\" id=\"cms_rythmus_$jahr"."_$kalenderwoche\">$opt</select></td></tr>";
      }
      $code .= "</tr></table>";
      $code .= "<p><input type=\"hidden\" name=\"cms_rythmisierung_endejahr\" id=\"cms_rythmisierung_endejahr\" value=\"$jahr\"><input type=\"hidden\" name=\"cms_rythmisierung_endekw\" id=\"cms_rythmisierung_endekw\" value=\"$kalenderwoche\"></p>";


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