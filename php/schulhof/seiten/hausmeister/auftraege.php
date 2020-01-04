<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Hausmeisteraufträge</h1>

<?php

if (r("schulhof.technik.hausmeisteraufträge.sehen")) {
  $code = "<table class=\"cms_liste\">";
    $code .= "<tr><th></th><th></th><th>Titel</th><th>Erstellt</th><th>Ziel</th><th>Ersteller</th><th>Aktionen</th></tr>";
    $eintraege = "";

    include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
    $CMS_EMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);

    $sqlfelder = "hausmeisterauftraege.id AS id, status, AES_DECRYPT(hausmeisterauftraege.titel, '$CMS_SCHLUESSEL') AS titel, start, ziel, hausmeisterauftraege.idvon AS ersteller, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS pvorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS pnachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, erstellt";
    $sql = $dbs->prepare("SELECT $sqlfelder FROM hausmeisterauftraege LEFT JOIN personen ON personen.id = hausmeisterauftraege.idvon LEFT JOIN nutzerkonten ON nutzerkonten.id = hausmeisterauftraege.idvon ORDER BY status DESC, ziel ASC, erstellt ASC");

    if ($sql->execute()) {
      $sql->bind_result($hid, $hstat, $htit, $hstart, $hziel, $hersteller, $hpvor, $hpnach, $hptit, $herstellt);
      while ($sql->fetch()) {
        $eintraege .= "<tr>";
          $eintraege .= "<td><img src=\"res/icons/klein/hausmeister.png\"></td>";
          if ($hstat == 'e') {$icon = "gruen"; $hinweis = "erledigt";} else {$icon = "rot"; $hinweis = "offen";}
          $eintraege .= "<td>".cms_generiere_hinweisicon($icon, $hinweis)."</td>";
          $eintraege .= "<td>$htit</td>";
          $eintraege .= "<td>".date("d.m.Y", $hstart)."</td>";
          $eintraege .= "<td>".date("d.m.Y", $hziel)." um ".date("H:i", $hziel)." Uhr</td>";
          if (!is_null($hpvor) && ($herstellt < $hstart)) {
            $anzeigename = cms_generiere_anzeigename($hpvor, $hpnach, $hptit);
            if (in_array($hersteller, $CMS_EMPFAENGERPOOL)) {
              $eintraege .= "<td><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $hersteller)\">$anzeigename</span></td>";
            }
            else {
              $eintraege .= "<td><span class=\"cms_button_passiv\">$anzeigename</span></td>";
            }
          }
          else {$eintraege .= "<td><i>existiert nicht mehr</i></td>";}
          $eintraege .= "<td>";
          if (r("schulhof.technik.hausmeisteraufträge.markieren")) {
            $eintraege .= "<span class=\"cms_aktion_klein\" onclick=\"cms_hausmeisterauftrag_lesen($hid)\"><span class=\"cms_hinweis\">Details anzeigen</span><img src=\"res/icons/klein/auftrag.png\"></span> ";
            if ($hstat == 'e') {
              $eintraege .= "<span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_hausmeisterauftrag_markieren('n', $hid)\"><span class=\"cms_hinweis\">als ausstehend markieren</span><img src=\"res/icons/klein/ausstehend.png\"></span> ";
            }
            else {
              $eintraege .= "<span class=\"cms_aktion_klein cms_button_ja\" onclick=\"cms_hausmeisterauftrag_markieren('e', $hid)\"><span class=\"cms_hinweis\">als erledigt markieren</span><img src=\"res/icons/klein/erledigt.png\"></span> ";
            }
          }
          if (r("schulhof.technik.hausmeisteraufträge.löschen")) {
            $eintraege .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_hausmeisterauftrag_loeschen_anzeigen($hid)\"><span class=\"cms_hinweis\">löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
          }
          $eintraege .= "</td>";

        $eintraege .= "</tr>";
      }
    }
    $sql->close();

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
