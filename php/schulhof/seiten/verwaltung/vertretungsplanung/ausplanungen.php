<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Ausplanungen</h1>
<?php
$zugriff = $CMS_RECHTE['Planung']['Ausplanungen durchführen'];

$code = "";
if ($zugriff) {
  if (!$CMS_IMLN) {
    $code .= cms_meldung_firewall();
  }
  else {
    if (isset($_SESSION['AUSPLANUNGTAG']) && cms_check_ganzzahl($_SESSION['AUSPLANUNGTAG'],1,31)) {$tag = $_SESSION['AUSPLANUNGTAG'];} else {$tag = date('d');}
    if (isset($_SESSION['AUSPLANUNGMONAT']) && cms_check_ganzzahl($_SESSION['AUSPLANUNGMONAT'],1,12)) {$monat = $_SESSION['AUSPLANUNGMONAT'];} else {$monat = date('m');}
    if (isset($_SESSION['AUSPLANUNGJAHR']) && cms_check_ganzzahl($_SESSION['AUSPLANUNGJAHR'],0)) {$jahr = $_SESSION['AUSPLANUNGJAHR'];} else {$jahr = date('Y');}
    $_SESSION['AUSPLANUNGTAG'] = $tag;
    $_SESSION['AUSPLANUNGMONAT'] = $monat;
    $_SESSION['AUSPLANUNGJAHR'] = $jahr;
    $heute = mktime(0,0,0, $monat, $tag, $jahr);

    // LEHRER LADEN
    $LEHRER = "";
    $sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY nachname, vorname, kuerzel ASC";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($id, $vorname, $nachname, $titel, $kuerzel);
      while ($sql->fetch()) {
        $bez = cms_generiere_anzeigename($vorname, $nachname, $titel);
        if (strlen($kuerzel) > 0) {$bez = $kuerzel." - ".$bez;}
        $LEHRER .= "<option value=\"$id\">$bez</option>";
      }
    }
    $sql->close();

    // RAEUME LADEN
    $RAEUME = "";
    $sql = "SELECT DISTINCT * FROM ((SELECT raeume.id AS id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzk, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS zusatzs FROM raeume LEFT JOIN raeumeklassen ON raeume.id = raeumeklassen.raum LEFT JOIN klassen ON raeumeklassen.klasse = klassen.id LEFT JOIN raeumestufen ON raeume.id = raeumestufen.raum LEFT JOIN stufen ON raeumestufen.stufe = stufen.id)) AS x GROUP BY id ORDER BY bezeichnung ASC";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $zusatzk, $zusatzs);
      while ($sql->fetch()) {
        $raumbez = $bez;
        $zusatz = "";
        if ($zusatzk !== null) {$zusatz .= ", ".$zusatzk;}
        if ($zusatzs !== null) {$zusatz .= ", ".$zusatzs;}
        if (strlen($zusatz) > 0) {$raumbez .= " » ".substr($zusatz, 2);}
        $RAEUME .= "<option value=\"$id\">$raumbez</option>";
      }
    }
    $sql->close();

    // SCHULSTUNDEN LADEN
    $SCHULSTUNDEN = array();
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY beginns, beginnm ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $heute, $heute);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $beginns, $beginnm, $endes, $endem);
      while ($sql->fetch()) {
        $s = array();
        $s['beginn'] = $beginns.":".$beginnm;
        $s['ende'] = $endes.":".$endem;
        $s['bez'] = $bez;
        array_push($SCHULSTUNDEN, $s);
      }
    }
    $sql->close();

    // KLASSEN LADEN
    $KLASSEN = "";
    $sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr IN (SELECT id FROM schuljahre WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY reihenfolge, bezeichnung ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $heute, $heute);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $r);
      while ($sql->fetch()) {
        $KLASSEN .= "<option value=\"$id\">$bez</option>";
      }
    }
    $sql->close();

    $code .= "</div>";
    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Ausplanen</h2>";
    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>von:</th><td>".cms_datum_eingabe('cms_ausplanung_datum_von', $tag, $monat, $jahr, 'cms_vplan_schulstunden_laden(\'von\');')."</td><td id=\"cms_ausplanung_schulstunden_von\"><select id=\"cms_ausplanung_std_von\" bis=\"cms_ausplanung_std_von\">";
    for ($s=0; $s<count($SCHULSTUNDEN); $s++) {
      $code .= "<option value=\"".$SCHULSTUNDEN[$s]['beginn']."\">".$SCHULSTUNDEN[$s]['bez']."</option>";
    }
    $code .= "</select></td></tr>";
    $code .= "<tr><th>bis:</th><td>".cms_datum_eingabe('cms_ausplanung_datum_bis', $tag, $monat, $jahr, 'cms_vplan_schulstunden_laden(\'bis\');')."</td><td id=\"cms_ausplanung_schulstunden_bis\"><select id=\"cms_ausplanung_std_bis\" name=\"cms_ausplanung_std_bis\">";
    for ($s=0; $s<count($SCHULSTUNDEN)-1; $s++) {
      $code .= "<option value=\"".$SCHULSTUNDEN[$s]['ende']."\">".$SCHULSTUNDEN[$s]['bez']."</option>";
    }
    $code .= "<option value=\"".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['ende']."\" selected=\"selected\">".$SCHULSTUNDEN[count($SCHULSTUNDEN)-1]['bez']."</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr><th>Art:</th><td colspan=\"2\"><select id=\"cms_ausplanen_art\" name=\"cms_ausplanen_art\" onmouseup=\"cms_ausplanung_art_aendern()\" onchange=\"cms_ausplanung_art_aendern()\">";
      $code .= "<option value=\"l\">Lehrkraft</option><option value=\"r\">Raum</option><option value=\"k\">Klassen</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_l\"><th>Lehrkräfte:</th><td colspan=\"2\"><select id=\"cms_ausplanen_l\" name=\"cms_ausplanen_l\">";
      $code .= $LEHRER;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_l\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundl\" name=\"cms_ausplanen_grundl\">";
      $code .= "<option value=\"dv\">dienstlich verhindert</option><option value=\"k\">krank</option><option value=\"kk\">krankes Kind</option><option value=\"p\">bei Prüfung</option><option value=\"b\">beurlaubt</option><option value=\"ex\">auf Exkursion</option><option value=\"s\">sonstiges</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_r\" style=\"display:none\"><th>Räume:</th><td colspan=\"2\"><select id=\"cms_ausplanen_r\" name=\"cms_ausplanen_r\">";
      $code .= $RAEUME;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_r\" style=\"display:none\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundr\" name=\"cms_ausplanen_grundr\">";
      $code .= "<option value=\"b\">blockiert</option><option value=\"p\">durch Prüfung belegt</option><option value=\"k\">kaputt</option><option value=\"s\">sonstiges</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_k\" style=\"display:none\"><th>Klassen:</th><td id=\"cms_klassen_ausplanen\" colspan=\"2\"><select id=\"cms_ausplanen_k\" name=\"cms_ausplanen_k\">";
      $code .= $KLASSEN;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_k\" style=\"display:none\"><th>Grund:</th><td colspan=\"2\"><select id=\"cms_ausplanen_grundk\" name=\"cms_ausplanen_grundk\">";
      $code .= "<option value=\"ex\">auf Exkursion</option><option value=\"sh\">im Schullandheim</option><option value=\"p\">bei Prüfung</option><option value=\"bv\">bei Berufsorientierung</option><option value=\"k\">krank</option><option value=\"s\">sonstiges</option>";
    $code .= "</select></td></tr>";
    $code .= "</table>";
    $code .= "<p class=\"cms_notiz\">Schulstunden jeweils einschließlich.</p>";
    $code .= "<p><span class=\"cms_button\" onclick=\"cms_ausplanung_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung\">Abbrechen</a></p>";
    $code .= "</div></div>";

    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Ausgeplant</h2>";
    $code .= "<table class=\"cms_zeitwahl\">";
    $code .= "<tr><td><span class=\"cms_button\" onclick=\"cms_vplan_ausgeplant_laden('-')\">«</span></td><td>".cms_datum_eingabe('cms_ausplanung_datum', $tag, $monat, $jahr, 'cms_vplan_ausgeplant_laden(\'j\');')."</td><td><span class=\"cms_button\" onclick=\"cms_vplan_ausgeplant_laden('+')\">»</span></td></tr>";
    $code .= "</table>";
    $code .= "<h3>Lehrkräfte</h3>";
    $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_l', '');
    $code .= "<h3>Räume</h3>";
    $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_r', '');
    $code .= "<h3>Klassen</h3>";
    $code .= cms_generiere_nachladen('cms_ausplanung_ausgeplant_k', 'cms_ausplanen_lausgeplant();');
    $code .= "</div></div>";
    $code .= "<div class=\"cms_clear\"></div><div>";
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
