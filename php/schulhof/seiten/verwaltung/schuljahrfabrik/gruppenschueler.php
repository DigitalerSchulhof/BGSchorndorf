<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.fabrik")) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";


  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle Personen, die bereits Gruppen im Zielschuljahr zugeordnet wurden, werden mit dem Abschluss dieses Schrittes aus diesen Gruppen entfernt.</b></p>');

  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC");
  $buttonsa = "";
  $buttonsn = "";
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];}
  else {$SCHULJAHR = null;}
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$SCHULJAHRNEU = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];}
  else {$SCHULJAHRNEU = null;}
  if ($sql->execute()) {
    $sql->bind_result($id, $sjbez);
    while ($sql->fetch()) {
      if (($SCHULJAHR == $id) && ($SCHULJAHR != 'null')) {$buttonsa .= "<span class=\"cms_button_ja\"";}
      else {$buttonsa .= "<span class=\"cms_button\"";}
      $buttonsa .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$id', 'Schüler_in_Gruppen', '$SCHULJAHRNEU');\">".$sjbez."</span> ";
      if (($SCHULJAHRNEU == $id) && ($SCHULJAHRNEU != 'null')) {$buttonsn .= "<span class=\"cms_button_ja\"";}
      else {$buttonsn .= "<span class=\"cms_button\"";}
      $buttonsn .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$SCHULJAHR', 'Schüler_in_Gruppen', '$id');\">".$sjbez."</span> ";
    }
  }
  $sql->close();
  $code .= "</div><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Stammschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsa</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}
  $code .= "</div></div><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Zielschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsn</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}
  $code .= "</div></div><div class=\"cms_clear\"></div><div class=\"cms_spalte_i\">";

  // Prüfen, ob Stammschuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR']) || isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    if (!is_null($SCHULJAHRNEU)) {
      $sql->bind_param('i', $SCHULJAHRNEU);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $SCHULJAHRBEZ);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
    }
    if (!is_null($SCHULJAHR)) {
      $sql->bind_param('i', $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($anzahl, $SCHULJAHRBEZ);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
      $sql->close();
    }
  }

  $code .= "<h2>Teilschritte</h2>";
  $code .= "<p>";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_grundlagen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen\">Grundlagen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_profile\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\">Profile</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
  echo $code;

  if (is_null($SCHULJAHR) || is_null($SCHULJAHRNEU) ||$SCHULJAHR == 'null' || $SCHULJAHRNEU == 'null') {
    echo cms_meldung('info', '<h4>Informationen fehlen</h4><p>Bitte wählen Sie ein Stammschuljahr und ein Zielschuljahr aus.</p>');
    $sjfehler = true;
  }

  if (!$sjfehler) {
    if ($SCHULJAHR == $SCHULJAHRNEU) {
      echo cms_meldung('info', '<h4>Schuljahrauswahl ungültig</h4><p>Das Stammschuljahr und das Zielschuljahr sind identisch.</p>');
      $sjfehler = true;
    }
  }
  $code = "";

  if (!$sjfehler) {

    echo "<h2>Schüler in Gruppen einordnen</h2>";

    include_once('php/schulhof/seiten/personensuche/personensuche.php');

    // ALTE Gruppen laden
    $AEINZELN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS kid, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($aeid, $aebez, $aereihe);
      while ($sql->fetch()) {
        $ae = array();
        $ae['id'] = $aeid;
        $ae['bez'] = $aebez;
        $ae['schueler'] = "";
        array_push($AEINZELN, $ae);
      }
    }
    $sql->close();

    // Personen NEUER Gruppen laden
    $EINZELN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS kid, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
    $sql->bind_param("i", $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($eid, $ebez, $ereihe);
      while ($sql->fetch()) {
        $e = array();
        $e['id'] = $eid;
        $e['bez'] = $ebez;
        $e['schueler'] = "";
        array_push($EINZELN, $e);
      }
    }
    $sql->close();

    $SJF_GRUPPEN[0] = "Klassen";
    $SJF_GRUPPEN[1] = "Stufen";
    $SJF_GRUPPEN[2] = "Gremien";
    $SJF_GRUPPEN[3] = "Fachschaften";
    $SJF_GRUPPEN[4] = "Arbeitsgemeinschaften";
    $SJF_GRUPPEN[5] = "Arbeitskreise";
    $SJF_GRUPPEN[6] = "Fahrten";
    $SJF_GRUPPEN[7] = "Wettbewerbe";
    $SJF_GRUPPEN[8] = "Ereignisse";
    $SJF_GRUPPEN[9] = "Sonstige Gruppen";

    foreach ($SJF_GRUPPEN as $SJFG) {
      $kSJFG = cms_textzudb($SJFG);
      // ALTE Gruppen laden
      $AEINZELN = array();

      if ($kSJFG == 'klassen') {
        $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS kid, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
      }
      else if ($kSJFG == 'stufen') {
        $sql = $dbs->prepare("SELECT * FROM (SELECT stufen.id AS sid, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge, bez");
      }
      else  {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, sichtbar FROM ".$kSJFG." WHERE schuljahr = ?) AS x ORDER BY bez");
      }
      $sql->bind_param("i", $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($aeid, $aebez, $aereihe);
        while ($sql->fetch()) {
          $ae = array();
          $ae['id'] = $aeid;
          $ae['bez'] = $aebez;
          $ae['schueler'] = "";
          array_push($AEINZELN, $ae);
        }
      }
      $sql->close();

      // Personen NEUER Gruppen laden
      $EINZELN = array();
      $tanzahl = 0;
      $teilids = "";
      if ($kSJFG == 'klassen') {
        $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS kid, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
      }
      else if ($kSJFG == 'stufen') {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge, bez");
      }
      else {
        $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, sichtbar FROM ".$kSJFG." WHERE schuljahr = ?) AS x ORDER BY bez");
      }
      $sql->bind_param("i", $SCHULJAHRNEU);
      if ($sql->execute()) {
        $sql->bind_result($eid, $ebez, $ereihe);
        while ($sql->fetch()) {
          $e = array();
          $e['id'] = $eid;
          $e['bez'] = $ebez;
          $e['schueler'] = "";
          array_push($EINZELN, $e);
          $tanzahl ++;
          $teilids .= "|".$eid;
        }
      }
      $sql->close();

      $code = "<h3>$SJFG</h3>";
      $teilcode = "";
      // Klassen und Mitglieder laden
      $sql = $dbs->prepare("SELECT person FROM ".$kSJFG."mitglieder JOIN personen ON person = id WHERE gruppe = ?");
      for ($i=0; $i<count($EINZELN); $i++) {
        $sql->bind_param("i", $EINZELN[$i]['id']);
        if ($sql->execute()) {
          $sql->bind_result($epersid);
          while ($sql->fetch()) {
            $EINZELN[$i]['schueler'] .= "|".$epersid;
          }
          $teilcode .= "<h4>".$EINZELN[$i]['bez']."</h4>";
          $teilcode .= "<div id=\"cms_sjfabrik_personen_".$kSJFG."_".$EINZELN[$i]['id']."\">".cms_personensuche_personhinzu_generieren($dbs, "cms_sjfabrik_".$kSJFG."schueler_".$EINZELN[$i]['id'], 's', $EINZELN[$i]['schueler'])."</div>";
          $altgruppenmitgliedercode = "";
          foreach ($AEINZELN AS $ae) {
            $altgruppenmitgliedercode .= "<span class=\"cms_button\" onclick=\"cms_schuljahrfabrik_schueleruebernehmen('cms_sjfabrik_personen_".$kSJFG."_".$EINZELN[$i]['id']."', 'cms_sjfabrik_".$kSJFG."schueler_".$EINZELN[$i]['id']."', '".$kSJFG."', '".$ae['id']."')\">".$ae['bez']."</span> ";
          }

          if (strlen($altgruppenmitgliedercode) > 0) {$teilcode .= "<p>Hinzufügen aus $SCHULJAHRBEZ: $altgruppenmitgliedercode</p>";}
        }
      }
      if (strlen($teilcode) > 0) {$code .= $teilcode;}
      else {$code .= "<p class=\"cms_notiz\">keine $SJFG verfügbar</p>";}
      $code .= "<p><input type=\"hidden\" name=\"cms_sjfabrik_$kSJFG\" id=\"cms_sjfabrik_$kSJFG\" value=\"$teilids\"></p>";

      $sql->close();
      if ($SJFG == "Stufen") {
        $code .= cms_meldung('info', '<h4>Stufenzugehörigkeit der Klassen</h4><p>Alle Schüler, die einer Klasse hinzugefügt wurden, werden automatisch auch der zugehörigen Stufe zugewiesen.</p>');
      }
      $einblenden = 0;
      if ($tanzahl > 0) {$einblenden = 1;}
      echo cms_toggleeinblenden_generieren ('cms_sjfabrik_'.$kSJFG.'_felder', $SJFG.' einblenden', $SJFG.' ausblenden', $code, $einblenden);
    }


    // ALTE Gruppen laden
    $AEINZELN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT stufen.id AS sid, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge, bez");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($aeid, $aebez, $aereihe);
      while ($sql->fetch()) {
        $ae = array();
        $ae['id'] = $aeid;
        $ae['bez'] = $aebez;
        $ae['schueler'] = "";
        array_push($AEINZELN, $ae);
      }
    }
    $sql->close();

    // Personen NEUER Gruppen laden
    $code = "<h3>Personen löschen</h3>";

    $code .= "<div id=\"cms_sjfabrik_personen_loeschen\">".cms_personensuche_personhinzu_generieren($dbs, "cms_sjfabrik_loeschenpersonen", 'esvxl', '')."</div>";
    $altgruppenmitgliedercode = "";
    foreach ($AEINZELN AS $ae) {
      $altgruppenmitgliedercode .= "<span class=\"cms_button\" onclick=\"cms_schuljahrfabrik_schueleruebernehmen('cms_sjfabrik_personen_loeschen', 'cms_sjfabrik_loeschenpersonen', 'stufen', '".$ae['id']."')\">".$ae['bez']."</span> ";
    }
    if (strlen($altgruppenmitgliedercode) > 0) {$code .= "<p>Stufen hinzufügen aus $SCHULJAHRBEZ: $altgruppenmitgliedercode</p>";}
    echo cms_toggleeinblenden_generieren ('cms_sjfabrik_personenloeschen_felder', 'Personen löschen einblenden', 'Personen löschen ausblenden', $code, 1);

    $code = "<h2>Einordnen der Schüler in Gruppen abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle Personen, die bereits Gruppen im Zielschuljahr zugeordnet wurden, werden mit dem Abschluss dieses Schrittes aus diesen Gruppen entfernt.</b></p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_schueleringruppen();\">+ Schuljahrfabrik – Schüler in Gruppen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
