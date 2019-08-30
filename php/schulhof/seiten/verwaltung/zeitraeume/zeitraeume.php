<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume löschen'];

$code = "";
if ($zugriff) {
  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['ZEITRAUMSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['ZEITRAUMSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Stundenplanzeiträume</h1>";

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_stundenplanzeitraeume_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeilen = "";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th>Bezeichnung</th><th>Beginn</th><th>Ende</th><th>Schultage</th><th>Rythmen</th><th>Aktionen</th></tr>";
      $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginn, ende, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE schuljahr = ? ORDER BY beginn ASC");
      $sql->bind_param('i', $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($zid, $zbez, $zbeginn, $zende, $mo, $di, $mi, $do, $fr, $sa, $so, $rythmen);
        while ($sql->fetch()) {
          $zeilen .= "<tr>";
            $zeilen .= "<td><img src=\"res/icons/klein/stundenplanzeitraeume.png\"></td>";
            $zeilen .= "<td>$zbez</td>";
            $zeilen .= "<td>".date('d.m.Y', $zbeginn)."</td>";
            $zeilen .= "<td>".date('d.m.Y', $zende)."</td>";
            $schultage = "";
            if ($mo == 1) {$schultage .= ", MO";}
            if ($di == 1) {$schultage .= ", DI";}
            if ($mi == 1) {$schultage .= ", MI";}
            if ($do == 1) {$schultage .= ", DO";}
            if ($fr == 1) {$schultage .= ", FR";}
            if ($sa == 1) {$schultage .= ", SA";}
            if ($so == 1) {$schultage .= ", SO";}
            if (strlen($schultage) > 0) {$schultage = substr($schultage, 2);}
            $zeilen .= "<td>".$schultage."</td>";
            $zeilen .= "<td>".$rythmen."</td>";
            $zeilen .= "<td>";
            if ($CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten']) {
              $zeilen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_zeitraeume_bearbeiten_vorbereiten($zid);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
            }
            if (($CMS_RECHTE['Planung']['Stundenplanzeiträume rythmisieren'] && ($rythmen > 1))) {
              $zeilen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_zeitraeume_rythmisieren_vorbereiten($zid);\"><span class=\"cms_hinweis\">Zeitraum rythmisieren</span><img src=\"res/icons/klein/zeitraumrythmen.png\"></span> ";
            }
            if ($CMS_RECHTE['Planung']['Stundenplanzeiträume löschen']) {
              $zeilen .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_zeitraeume_loeschen_anzeigen('$zbez', $zid);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
            }
            $zeilen .= "</td>";
          $zeilen .= "</tr>";
        }

        if (strlen($zeilen) > 0) {$code .= $zeilen;}
        else {
          $code .= "<tr><td class=\"cms_notiz\" colspan=\"7\">- keine Datensätze gefunden -</td></tr>";
        }
      }
      $sql->close();

    $code .= "</table>";

    if ($CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen']) {
      $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Zeiträume/Neuen_Zeitraum_anlegen\">+ Neuen Zeitraum anlegen</a></p>";
    }


  }
  else {$code .= "<h1>Stundenplanzeiträume</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Stundenplanzeiträume</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
