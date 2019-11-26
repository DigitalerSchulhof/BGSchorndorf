<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Hausmeisteraufträge</h1>

<?php

if ($CMS_RECHTE['Technik']['Hausmeisteraufträge sehen']) {
  $code = "<table class=\"cms_liste\">";
    $code .= "<tr><th></th><th></th><th>Titel</th><th>Erstellt</th><th>Ziel</th><th>Ersteller</th><th>Aktionen</th></tr>";
    $eintraege = "";

    include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
    $CMS_EMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);

    $sqlfelder = "hausmeisterauftraege.id AS id, status, AES_DECRYPT(hausmeisterauftraege.titel, '$CMS_SCHLUESSEL') AS titel, start, ziel, hausmeisterauftraege.idvon AS ersteller, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS pvorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS pnachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, erstellt";
    $sql = "SELECT $sqlfelder FROM hausmeisterauftraege LEFT JOIN personen ON personen.id = hausmeisterauftraege.idvon LEFT JOIN nutzerkonten ON nutzerkonten.id = hausmeisterauftraege.idvon ORDER BY status DESC, ziel ASC, erstellt ASC";

    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      while ($daten = $anfrage->fetch_assoc()) {
        $eintraege .= "<tr>";
          $eintraege .= "<td><img src=\"res/icons/klein/hausmeister.png\"></td>";
          if ($daten['status'] == 'e') {$icon = "gruen"; $hinweis = "erledigt";} else {$icon = "rot"; $hinweis = "offen";}
          $eintraege .= "<td>".cms_generiere_hinweisicon($icon, $hinweis)."</td>";
          $eintraege .= "<td>".$daten['titel']."</td>";
          $eintraege .= "<td>".date("d.m.Y", $daten['start'])."</td>";
          $eintraege .= "<td>".date("d.m.Y", $daten['ziel'])."</td>";
          if (!is_null($daten['pvorname']) && ($daten['erstellt'] < $daten['start'])) {
            $anzeigename = cms_generiere_anzeigename($daten['pvorname'], $daten['pnachname'], $daten['ptitel']);
            if (in_array($daten['ersteller'], $CMS_EMPFAENGERPOOL)) {
              $eintraege .= "<td><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$daten['ersteller'].")\">$anzeigename</span></td>";
            }
            else {
              $eintraege .= "<td><span class=\"cms_button_passiv\">$anzeigename</span></td>";
            }
          }
          else {$eintraege .= "<td><i>existiert nicht mehr</i></td>";}
          $eintraege .= "<td>";
          if ($CMS_RECHTE['Technik']['Hausmeisteraufträge markieren']) {
            $eintraege .= "<span class=\"cms_aktion_klein\" onclick=\"cms_hausmeisterauftrag_lesen(".$daten['id'].")\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/auftrag.png\"></span> ";
            if ($daten['status'] == 'e') {
              $eintraege .= "<span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_hausmeisterauftrag_markieren('n', ".$daten['id'].")\"><span class=\"cms_hinweis\">als ausstehend markieren</span><img src=\"res/icons/klein/ausstehend.png\"></span> ";
            }
            else {
              $eintraege .= "<span class=\"cms_aktion_klein cms_button_ja\" onclick=\"cms_hausmeisterauftrag_markieren('e', ".$daten['id'].")\"><span class=\"cms_hinweis\">als erledigt markieren</span><img src=\"res/icons/klein/erledigt.png\"></span> ";
            }
          }
          if ($CMS_RECHTE['Technik']['Hausmeisteraufträge löschen']) {
            $eintraege .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_hausmeisterauftrag_loeschen_anzeigen(".$daten['id'].")\"><span class=\"cms_hinweis\">löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
          }
          $eintraege .= "</td>";

        $eintraege .= "</tr>";
      }
      $anfrage->free();
    }

    if (strlen($eintraege) == 0) {
      $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">– keine Datensätze gefunden –</td></tr>";
    }
    else {
      $code .= $eintraege;
      $code .= cms_meldung('info', '<h4>Markierungen</h4><p>Mit Markierungen erhält der Ersteller des Auftrags eine Rückmeldung darüber, dass der Auftrag erfüllt wurde.</p>');
    }
  $code .= "</table>";
  echo $code;
}
else {
  echo cms_meldung_berechtigung();
}

?>
</div>
<div class="cms_clear"></div>
