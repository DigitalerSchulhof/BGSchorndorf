<?php
function cms_vplan_laden($dbs, $art) {
  global $CMS_SCHLUESSEL;
  $code = "";
  if ($art == 's') {
    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= cms_vertretungsplan_komplettansicht_heute($dbs, 's');
    $code .= "</div></div>";
    $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, 's');
    $code .= "</div></div>";
    $code .= "<div class=\"cms_clear\"></div>";
  }
  else if ($art == 'l') {
    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
    $code .= cms_vertretungsplan_komplettansicht_heute($dbs, 'l');
    $code .= "</div></div>";
    $code .= "<div class=\"cms_spalte_20\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Gerätezustände</h2>";

    $raeume = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(raeumegeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC");
    if ($sql->execute()) {
      $sql->bind_result($rbez, $gbez, $gstat, $gmel, $gkom, $gzei);
      while ($sql->fetch()) {
        $icon = "blau";
        if ($gstat == 1) {$icon = "blau";};
        if ($gstat == 2) {$icon = "gelb";};
        if ($gstat == 3) {$icon = "rot";};
        if ($gstat == 4) {$icon = "schwarz";};
        if ($gstat == 5) {$icon = "weiss";};
        $raeume .= "<tr><td><p><b>$rbez – $gbez</b></p><p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
      }
    }
    $sql->close();


    $leihen = "";
    $sql = "SELECT * FROM (SELECT AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(leihengeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        $code .= "<div class=\"cms_problemmeldung cms_problem".$daten['gstat']."\">";
          $code .= "<div class=\"cms_problemmeldunginnen\">";
          $code .= "<span class=\"cms_problemmeldung_standort\">".$daten['rbez']." – ".$daten['gbez']."</span>";
          $code .= "<span class=\"cms_problemmeldung_meldung\">".$daten['gmel']."</span>";
          $code .= "<span class=\"cms_problemmeldung_kommentar\">".$daten['gkom']."</span>";
          $code .= "<span class=\"cms_problemmeldung_zeit\">".date("d.m.Y H:i", $daten['gzei'])."</span>";
          $code .= "</div>";
        $code .= "</div>";
      }
      $anfrage->free();
    }

    if (strlen($raeume) > 0) {
      $code .= "<h3>Räume</h3>";
      $code .= "<table class=\"cms_liste\">";
      $code .= $raeume;
      $code .= "</table>";
    }

    if (strlen($leihen) > 0) {
      $code .= "<h3>Leihgeräte</h3>";
      $code .= "<table class=\"cms_liste\">";
      $code .= $leihen;
      $code .= "</table>";
    }

    if ((strlen($leihen) == 0) && (strlen($raeume) == 0)) {
      $code .= "<p class=\"cms_notiz\">Alle Geräte sind in einwandfreiem Zustand.</p>";
    }

    $code .= "</div></div>";
    $code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
    $code .= cms_vertretungsplan_komplettansicht_naechsterschultag($dbs, 'l');
    $code .= "</div></div>";
    $code .= "<div class=\"cms_clear\"></div>";
  }
  return $code;
}
 ?>
