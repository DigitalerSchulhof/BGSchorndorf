<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (cms_r("schulhof.planung.schuljahre.verantwortlichkeiten")) {
	// Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['VERANTWORTLICHKEITENSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['VERANTWORTLICHKEITENSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $sjbez);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }

  if (!$sjfehler) {
    // Lehrer laden
    $LEHRER = "<option value=\"-\">keine</option>";
    $sql = $dbs->prepare("SELECT * FROM (SELECT lehrer.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kurz FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY kurz, nach, vor, titel");
    if ($sql->execute()) {
      $sql->bind_result($id, $vor, $nach, $titel, $kurz);
      while ($sql->fetch()) {
        if (strlen($kurz) > 0) {$anzeige = $kurz." - ";} else {$anzeige = "";}
        $anzeige .= cms_generiere_anzeigename($vor, $nach, $titel);
        $LEHRER .= "<option value=\"$id\">$anzeige</option>";
      }
    }
    $sql->close();
    $RAEUME = "<option value=\"-\">keines</option>";
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM raeume) AS x ORDER BY bez");
    if ($sql->execute()) {
      $sql->bind_result($id, $bez);
      while ($sql->fetch()) {
        $RAEUME .= "<option value=\"$id\">$bez</option>";
      }
    }
    $sql->close();


    $KLASSEN = array();
    $KLASSENIDS = "";
    $sql = $dbs->prepare("SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL'), klassenlehrer.lehrer, klassenlehrerstellvertreter.lehrer, raeumeklassen.raum FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id LEFT JOIN raeumeklassen ON klassen.id = raeumeklassen.klasse LEFT JOIN klassenlehrer ON klassen.id = klassenlehrer.klasse LEFT JOIN klassenlehrerstellvertreter ON klassen.id = klassenlehrerstellvertreter.klasse WHERE klassen.schuljahr = ? ORDER BY reihenfolge");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $lehrer, $stellvertreter, $raum);
      while ($sql->fetch()) {
        $E = array();
        $E['id'] = $id;
        $E['bez'] = $bez;
        $E['lehrer'] = $lehrer;
        $E['stellvertreter'] = $stellvertreter;
        $E['raum'] = $raum;
        array_push($KLASSEN, $E);
        $KLASSENIDS .= "|".$id;
      }
    }
    $sql->close();

    $STUFEN = array();
    $STUFENIDS = "";
    $sql = $dbs->prepare("SELECT stufen.id, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL'), stufenlehrer.lehrer, stufenlehrerstellvertreter.lehrer, raeumestufen.raum FROM stufen LEFT JOIN raeumestufen ON stufen.id = raeumestufen.stufe LEFT JOIN stufenlehrer ON stufen.id = stufenlehrer.stufe LEFT JOIN stufenlehrerstellvertreter ON stufen.id = stufenlehrerstellvertreter.stufe WHERE stufen.schuljahr = ? ORDER BY reihenfolge");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $lehrer, $stellvertreter, $raum);
      while ($sql->fetch()) {
        $E = array();
        $E['id'] = $id;
        $E['bez'] = $bez;
        $E['lehrer'] = $lehrer;
        $E['stellvertreter'] = $stellvertreter;
        $E['raum'] = $raum;
        array_push($STUFEN, $E);
        $STUFENIDS .= "|".$id;
      }
    }
    $sql->close();


    $code .= "<h1>Verantwortlichkeiten für das Schuljahr »".$sjbez."« festlegen</h1>";
    $code .= "<h2>Klassen</h2>";
    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th></th><th>Klasse</th><th>Klassenlehrkraft</th><th>Stellvertretung</th><th>Klassenzimmer</th></tr>";
    foreach ($KLASSEN as $E) {
      $code .= "<tr><td><img src=\"res/icons/klein/klassen.png\"></td><td>".$E['bez']."</td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_kl_".$E['id']."\">".str_replace("<option value=\"".$E['lehrer']."\">", "<option value=\"".$E['lehrer']."\" selected=\"selected\">",$LEHRER)."</select></td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_ks_".$E['id']."\">".str_replace("<option value=\"".$E['stellvertreter']."\">", "<option value=\"".$E['stellvertreter']."\" selected=\"selected\">",$LEHRER)."</select></td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_kr_".$E['id']."\">".str_replace("<option value=\"".$E['raum']."\">", "<option value=\"".$E['raum']."\" selected=\"selected\">",$RAEUME)."</select></td></tr>";
    }
    $code .= "</table>";
    $code .= "<p><input type=\"hidden\" name=\"cms_verantwortlichkeiten_klassen\" id=\"cms_verantwortlichkeiten_klassen\" value=\"$KLASSENIDS\"></p>";
    $code .= "<h2>Stufen</h2>";
    $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><td></th><th>Stufe</th><th>Stufenlehrkraft</th><th>Stellvertretung</th><th>Stufenzimmer</th></tr>";
    foreach ($STUFEN as $E) {
      $code .= "<tr><td><img src=\"res/icons/klein/klassenstufe.png\"></td><td>".$E['bez']."</td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_sl_".$E['id']."\">".str_replace("<option value=\"".$E['lehrer']."\">", "<option value=\"".$E['lehrer']."\" selected=\"selected\">",$LEHRER)."</select></td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_ss_".$E['id']."\">".str_replace("<option value=\"".$E['stellvertreter']."\">", "<option value=\"".$E['stellvertreter']."\" selected=\"selected\">",$LEHRER)."</select></td>";
      $code .= "<td><select id=\"cms_verantwortlichkeiten_sr_".$E['id']."\">".str_replace("<option value=\"".$E['raum']."\">", "<option value=\"".$E['raum']."\" selected=\"selected\">",$RAEUME)."</select></td></tr>";
    }
    $code .= "</table>";
    $code .= "<p><input type=\"hidden\" name=\"cms_verantwortlichkeiten_stufen\" id=\"cms_verantwortlichkeiten_stufen\" value=\"$STUFENIDS\"></p>";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_verantwlortlichkeiten_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung/Schuljahre\">Abbrechen</a></p>";
  }
  else {$code .= "<h1>Stundenplanung in Zeitraum importieren</h1>".cms_meldung_bastler();}
}
else {
	$code .= "<h1>Stundenplanung in Zeitraum importieren</h1>".cms_meldung_berechtigung();
}

echo $code;
?>

</div>

<div class="cms_clear"></div>
