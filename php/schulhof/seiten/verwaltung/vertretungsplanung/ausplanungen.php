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

    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Datum:</th><td>".cms_datum_eingabe('cms_ausplanung_datum', $tag, $monat, $jahr, 'cms_vplan_klassen_laden(\'ausplanung\');')."</td>";
    $code .= "</table>";

    // LEHRER LADEN
    $LEHRER = "";
    $sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id) AS x ORDER BY nachname, vorname, kuerzel ASC";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($id, $vorname, $nachname, $titel, $kuerzel);
      while ($sql->fetch()) {
        $bez = "";
        if (strlen($kuerzel) > 0) {$bez = "$kuerzel - ";}
        $bez .= cms_generiere_anzeigename($vorname, $nachname, $titel);
        $LEHRER .= "<option value=\"$id\">$bez</option>";
      }
    }
    $sql->close();

    // RAEUME LADEN
    $RAEUME = "";
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume) AS x ORDER BY bezeichnung ASC";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez);
      while ($sql->fetch()) {
        $RAEUME .= "<option value=\"$id\">$bez</option>";
      }
    }
    $sql->close();

    // KLASSEN LADEN
    $jetzt = mktime(0,0,0,$monat, $tag, $jahr);
    $KLASSEN = "";
    $sql = "SELECT * FROM (SELECT klassen.id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM klassen JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr IN (SELECT id FROM schuljahre WHERE beginn <= ? AND ende >= ?)) AS x ORDER BY reihenfolge, bezeichnung ASC";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $jetzt, $jetzt);
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
    $code .= "<tr><th>Art:</th><td><select id=\"cms_ausplanen_art\" name=\"cms_ausplanen_art\" onmouseup=\"cms_ausplanung_art_aendern()\" onchange=\"cms_ausplanung_art_aendern()\">";
      $code .= "<option value=\"l\">Lehrkraft</option><option value=\"r\">Raum</option><option value=\"k\">Klassen</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_l\"><th>Lehrkräfte:</th><td><select id=\"cms_ausplanen_l\" name=\"cms_ausplanen_l\">";
      $code .= $LEHRER;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_l\"><th>Grund:</th><td><select id=\"cms_ausplanen_grundl\" name=\"cms_ausplanen_grundl\">";
      $code .= "<option value=\"dv\">dienstlich verhindert</option><option value=\"k\">krank</option><option value=\"b\">beurlaubt</option><option value=\"s\">sontiges</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_r\" style=\"display:none\"><th>Räume:</th><td><select id=\"cms_ausplanen_r\" name=\"cms_ausplanen_r\">";
      $code .= $RAEUME;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_r\" style=\"display:none\"><th>Grund:</th><td><select id=\"cms_ausplanen_grundr\" name=\"cms_ausplanen_grundr\">";
      $code .= "<option value=\"b\">blockiert</option><option value=\"k\">kaputt</option><option value=\"s\">sontiges</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_art_k\" style=\"display:none\"><th>Klassen:</th><td id=\"cms_klassen_ausplanen\"><select id=\"cms_ausplanen_k\" name=\"cms_ausplanen_k\">";
      $code .= $KLASSEN;
    $code .= "</select></td></tr>";
    $code .= "<tr id=\"cms_ausplanung_grund_k\" style=\"display:none\"><th>Grund:</th><td><select id=\"cms_ausplanen_grundk\" name=\"cms_ausplanen_grundk\">";
      $code .= "<option value=\"ex\">auf Exkursion</option><option value=\"sh\">im Schullandheim</option><option value=\"k\">krank</option><option value=\"s\">sontiges</option>";
    $code .= "</select></td></tr>";
    $code .= "<tr><th>von:</th><td>".cms_uhrzeit_eingabe('cms_ausplanung_von', '00','00')."</td></tr>";
    $code .= "<tr><th>bis:</th><td>".cms_uhrzeit_eingabe('cms_ausplanung_bis', '23','59')."</td></tr>";
    $code .= "</table>";
    $code .= "<p><span class=\"cms_button\" onclick=\"cms_ausplanung_speichern();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Planung\">Abbrechen</a></p>";
    $code .= "</div></div>";
    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Ausgeplant</h2>";
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
