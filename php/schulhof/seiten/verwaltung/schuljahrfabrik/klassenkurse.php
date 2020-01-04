<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (r("schulhof.planung.schuljahre.fabrik")) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";


  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Kurse im Zielschuljahr, die Klassen zugeordnet sind, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');

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
      $buttonsa .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$id', 'Klassenkurse', '$SCHULJAHRNEU');\">".$sjbez."</span> ";
      if (($SCHULJAHRNEU == $id) && ($SCHULJAHRNEU != 'null')) {$buttonsn .= "<span class=\"cms_button_ja\"";}
      else {$buttonsn .= "<span class=\"cms_button\"";}
      $buttonsn .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$SCHULJAHR', 'Klassenkurse', '$id');\">".$sjbez."</span> ";
    }
  }
  $sql->close();

  $code .= "<h2>Zielschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsn</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}

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
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
  echo $code;

  if (is_null($SCHULJAHRNEU) || $SCHULJAHRNEU == 'null') {
    echo cms_meldung('info', '<h4>Informationen fehlen</h4><p>Bitte wählen Sie ein Zielschuljahr aus.</p>');
    $sjfehler = true;
  }
  $code = "";

  if (!$sjfehler) {

    $STUFEN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, reihenfolge, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge, bezeichnung");
    $sql->bind_param('i', $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($sid, $sreihe, $sbez);
      while ($sql->fetch()) {
        $stufe = array();
        $stufe['id']    = $sid;
        $stufe['reihe'] = $sreihe;
        $stufe['bez']   = $sbez;
        $stufe['klassen'] = array();
        array_push($STUFEN, $stufe);
      }
    }
    $sql->close();

    $KLASSEN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id, reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, stufe FROM stufen JOIN klassen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ?) AS x ORDER BY bezeichnung");
    $sql->bind_param('i', $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($kid, $kreihe, $kbez, $kstufe);
      while ($sql->fetch()) {
        for ($s=0; $s<count($STUFEN); $s++) {
          if ($STUFEN[$s]['id'] == $kstufe) {
            $klasse = array();
            $klasse['id']    = $kid;
            $klasse['bez']   = $kbez;
            array_push($STUFEN[$s]['klassen'], $klasse);
          }
        }
      }
    }
    $sql->close();

    $klassenfaecher = "";
    $stufenfaecher = "";
    $faecherids = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM faecher WHERE schuljahr = ?) AS x ORDER BY bezeichnung");
    $sql->bind_param('i', $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($fid, $fbez);
      while ($sql->fetch()) {
        $klassenfaecher .= cms_togglebutton_generieren ('cms_sjfabrik_kurse_klassen_KLASSENID_'.$fid, $fbez, 0)." ";
        $faecherids .= "|".$fid;
      }
    }
    $sql->close();

    echo "<h2>Kurse für Klassen anlegen</h2>";
    $code = cms_meldung('info', '<h4>Neue Kurse nach Klassen</h4><p>Den neuen Kursen werden die Mitglieder der jeweiligen Klassen zugeteilt. Klassenübergreifende Kurse müssen manuell angelegt werden.</p>');

    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th>Klasse</th><th>Fächer</th></tr>";
    foreach ($STUFEN as $s) {
      $teilcode = "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">".$s['bez']."</th></tr>";
      foreach ($s['klassen'] as $k) {
        $kf = str_replace("id=\"cms_sjfabrik_kurse_klassen_KLASSENID_", "id=\"cms_sjfabrik_kurse_klassen_".$k['id']."_", $klassenfaecher);
        $kf = str_replace("name=\"cms_sjfabrik_kurse_klassen_KLASSENID_", "name=\"cms_sjfabrik_kurse_klassen_".$k['id']."_", $kf);
        $kf = str_replace("onclick=\"cms_togglebutton('cms_sjfabrik_kurse_klassen_KLASSENID_", "onclick=\"cms_togglebutton('cms_sjfabrik_kurse_klassen_".$k['id']."_", $kf);
        $teilcode .= "<tr><td>".$k['bez']."</td><td>$kf</td></tr>";
      }
      if (strlen($teilcode) > 0) {$code .= $teilcode;}
    }
    $code .= "</table>";
    echo $code;

    $stufenids = "";
    $klassenhidden = "";

    foreach ($STUFEN as $s) {
      $stufenids .= "|".$s['id'];
      $klassenids = "";
      foreach ($s['klassen'] as $k) {
        $klassenids .= "|".$k['id'];
      }
      $klassenhidden .= "<input type=\"hidden\" id=\"cms_sjfabrik_stufen_".$s['id']."_klassen\" name=\"cms_sjfabrik_stufen_".$s['id']."_klassen\" value=\"$klassenids\">";
    }

    echo "<p><input type=\"hidden\" id=\"cms_sjfabrik_stufen\" name=\"cms_sjfabrik_stufen\" value=\"$stufenids\"><input type=\"hidden\" id=\"cms_sjfabrik_faecher\" name=\"cms_sjfabrik_faecher\" value=\"$faecherids\">$klassenhidden</p>";

    $code = "<h2>Anlegen der Kurse für Klassen abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Kurse im Zielschuljahr, die Klassen zugeordnet sind, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_klassenkurse();\">+ Schuljahrfabrik – Kurse für Klassen anlegen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
