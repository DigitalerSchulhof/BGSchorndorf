<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

$code = "";
if (r("schulhof.planung.schuljahre.profile.*")) {
  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['PROFILSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['PROFILSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Profile</h1>";

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_profile_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeilen = "";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th></th><th>Bezeichnung</th><th>Klassenstufe</th><th>Wahlfächer</th><th>Aktionen</th></tr>";



      $sql = "SELECT * FROM (SELECT profile.id AS pid, AES_DECRYPT(profile.bezeichnung, '$CMS_SCHLUESSEL') AS pbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, reihenfolge, profile.art AS art FROM profile JOIN stufen ON profile.stufe = stufen.id WHERE profile.schuljahr = $SCHULJAHR) AS x ORDER BY reihenfolge ASC, pbez ASC";

      if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
        while ($daten = $anfrage->fetch_assoc()) {
          $pid = $daten['pid'];
          $pbez = $daten['pbez'];
          $sbez = $daten['sbez'];
          $part = $daten['art'];

          $zeilen .= "<tr>";
            $zeilen .= "<td><img src=\"res/icons/klein/profile.png\"></td>";
            if ($part == 'p') {$zeilen .= "<td>".cms_generiere_hinweisicon("pflichtprofil", "Pflichtprofil")."</td>";}
            else {$zeilen .= "<td>".cms_generiere_hinweisicon("wahlprofil", "Wahlprofil")."</td>";}
            $zeilen .= "<td>$pbez</td>";
            $zeilen .= "<td>$sbez</td>";
            $faecher = "";

            $sqlfaecher = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS fbez FROM profilfaecher JOIN faecher ON profilfaecher.fach = faecher.id WHERE profilfaecher.profil = $pid";
            if ($anfrage2 = $dbs->query($sqlfaecher)) { // Safe weil interne ID
              while ($daten2 = $anfrage2->fetch_assoc()) {
                $faecher .= ", ".$daten2['fbez'];
              }
              $anfrage2->free();
            }

            if (strlen($faecher) > 0) {$faecher = substr($faecher, 2);}
            $zeilen .= "<td>".$faecher."</td>";
            $zeilen .= "<td>";
            if (r("schulhof.planung.schuljahre.profile.bearbeiten")) {
              $zeilen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_profile_bearbeiten_vorbereiten($pid);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
            }
            if (r("schulhof.planung.schuljahre.profile.löschen")) {
              $zeilen .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_profile_loeschen_anzeigen('$pbez', $pid);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
            }
            $zeilen .= "</td>";
          $zeilen .= "</tr>";
        }

        if (strlen($zeilen) > 0) {$code .= $zeilen;}
        else {
          $code .= "<tr><td class=\"cms_notiz\" colspan=\"7\">- keine Datensätze gefunden -</td></tr>";
        }
        $anfrage->free();
      }

    $code .= "</table>";

    if (r("schulhof.planung.schuljahre.profile.anlegen")) {
      $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Profile/Neues_Profil_anlegen\">+ Neues Profil anlegen</a></p>";
    }


  }
  else {$code .= "<h1>Profile</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Profile</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
