<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.fabrik"))) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";


  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Kurse im Zielschuljahr, die keiner Klasse zugeordnet sind, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');

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
      $buttonsa .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$id', 'Stufenkurse', '$SCHULJAHRNEU');\">".$sjbez."</span> ";
      if (($SCHULJAHRNEU == $id) && ($SCHULJAHRNEU != 'null')) {$buttonsn .= "<span class=\"cms_button_ja\"";}
      else {$buttonsn .= "<span class=\"cms_button\"";}
      $buttonsn .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$SCHULJAHR', 'Stufenkurse', '$id');\">".$sjbez."</span> ";
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
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
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

    // ALTE STUFEN LADEN
    $ALTESTUFEN = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, reihenfolge, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM stufen WHERE schuljahr = ?) AS x ORDER BY reihenfolge, bezeichnung");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($sid, $sreihe, $sbez);
      while ($sql->fetch()) {
        $stufe = array();
        $stufe['id']    = $sid;
        $stufe['reihe'] = $sreihe;
        $stufe['bez']   = $sbez;
        array_push($ALTESTUFEN, $stufe);
      }
    }
    $sql->close();

    $stufenfaecher = "";
    $faecherids = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM faecher WHERE schuljahr = ?) AS x ORDER BY bezeichnung");
    $sql->bind_param('i', $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($fid, $fbez);
      while ($sql->fetch()) {
        $stufenfaecher .= "<tr><td>$fbez</td>";
        $stufenfaecher .= "<td><input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl1\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl1\" value=\"0\" min=\"0\" step=\"1\" type=\"number\"> <input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz1\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz1\" value=\"LK\" type=\"text\"></td>";
        $stufenfaecher .= "<td><input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl2\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl2\" value=\"0\" min=\"0\" step=\"1\" type=\"number\"> <input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz2\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz2\" value=\"GK\" type=\"text\"></td>";
        $stufenfaecher .= "<td><input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl3\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_anzahl3\" value=\"0\" min=\"0\" step=\"1\" type=\"number\"> <input class=\"cms_klein\" name=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz3\" id=\"cms_sjfabrik_kurse_stufen_STUFENID_".$fid."_zusatz3\" value=\"\" type=\"text\"></td>";
        $stufenfaecher .= "</tr>";
        $faecherids .= "|".$fid;
      }
    }
    $sql->close();

    echo "<h2>Kurse für Stufen anlegen</h2>";

    if ((count($STUFEN) > 0) && (count($ALTESTUFEN) > 0)) {
      echo "<h3>Kurse aus Stufen übertragen</h2>";
      $code = cms_meldung('info', '<h4>Übernahme von Kursen in neue Stufen</h4><p>Bei der Übernahme der Kurse in neue Stufen bleiben alle Personen wie bisher zugeordnet. Die Bezeichnung des Kurses wird je nach neuer Stufe neu angepasst. Wenn ein Fach im Zielschuljahr nicht mehr besteht, dem aber ein Kurs im Stammschuljahr zugeordnet war, dann wird dieser Kurs nicht übernommen.</p>');
      $code .= "<table class=\"cms_liste\">";
      $anzahl = 0;
      foreach ($ALTESTUFEN as $a) {
        if ($anzahl != 0) {$code .= "<tr>";}
        foreach ($STUFEN as $s) {$code .= "<th>".cms_togglebutton_generieren ('cms_sjfabrik_kurse_stufenuebertragen_'.$a['id'].'_'.$s['id'], $a['bez']." » ".$s['bez'], 0)."</th>";}
        $code .= "</tr>";
        $anzahl ++;
      }
      $code .= "</table>";
      echo $code;
    }

    echo "<h3>Kurse nach Stufen anlegen</h2>";
    $code = cms_meldung('info', '<h4>Neue Kurse nach Stufen</h4><p>Diese neuen Kurse werden ohne Mitglieder erstellt. Diese müssen manuell hinzugefügt werden.</p>');
    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th>Fächer</th><th>Kursart 1</th><th>Kursart 2</th><th>Kursart 3</th></tr>";
    foreach ($STUFEN as $s) {
      $teilcode = "<tr><th colspan=\"4\" class=\"cms_zwischenueberschrift\">".$s['bez']."</th></tr>";
      $sf = str_replace("id=\"cms_sjfabrik_kurse_stufen_STUFENID_", "id=\"cms_sjfabrik_kurse_stufen_".$s['id']."_", $stufenfaecher);
      $sf = str_replace("name=\"cms_sjfabrik_kurse_stufen_STUFENID_", "name=\"cms_sjfabrik_kurse_stufen_".$s['id']."_", $sf);
      $teilcode .= $sf;
      if (strlen($teilcode) > 0) {$code .= $teilcode;}
    }
    $code .= "</table>";
    echo $code;

    $stufenids = "";
    $altestufenids = "";

    foreach ($ALTESTUFEN as $a) {$altestufenids .= "|".$a['id'];}
    foreach ($STUFEN as $s) {$stufenids .= "|".$s['id'];}

    echo "<p><input type=\"hidden\" id=\"cms_sjfabrik_stufen\" name=\"cms_sjfabrik_stufen\" value=\"$stufenids\"><input type=\"hidden\" id=\"cms_sjfabrik_altestufen\" name=\"cms_sjfabrik_altestufen\" value=\"$altestufenids\"><input type=\"hidden\" id=\"cms_sjfabrik_faecher\" name=\"cms_sjfabrik_faecher\" value=\"$faecherids\"></p>";

    $code = "<h2>Anlegen der Kurse für Stufen abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Kurse im Zielschuljahr, die keiner Klasse zugeordnet sind, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_stufenkurse();\">+ Schuljahrfabrik – Kurse für Stufen anlegen</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
