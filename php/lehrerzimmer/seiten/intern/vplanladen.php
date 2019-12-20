<?php
function cms_vplan_laden($dbs, $art, $nachladen) {
  global $CMS_SCHLUESSEL;
  $code = "";
  if ($art == 's') {
    $code .= "<div id=\"cms_svplan_heute\">";
    $code .= "</div>";
    $code .= "<div id=\"cms_svplan_morgen\">";
    $code .= "</div>";
    $code .= "<script>cms_intern_vplan_laden('s');</script>";
    $code .= "<div class=\"cms_clear\"></div>";
  }
  else if ($art == 'l') {
    $code .= "<div id=\"cms_lvplan_heute\">";
    $code .= "</div>";
    $code .= "<div id=\"cms_lvplan_geraete\">";
    $code .= "<h2>Gerätezustände</h2>";

    $raeume = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(raeumegeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, rbez ASC, gbez ASC");
    if ($sql->execute()) {
      $sql->bind_result($rbez, $gbez, $gstat, $gmel, $gkom, $gzei);
      while ($sql->fetch()) {
        $icon = "blau";
        if ($gstat == 1) {$icon = "blau";}
        if ($gstat == 2) {$icon = "gelb";}
        if ($gstat == 3) {$icon = "rot";}
        if ($gstat == 4) {$icon = "schwarz";}
        if ($gstat == 5) {$icon = "weiss";}

        $raeume .= "<tr><td><b>$rbez – $gbez</b>";
        //"<p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td>";
        $raeume .= "</td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
        //$raeume .= "<tr><td><p><b>$rbez – $gbez</b></p><p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
      }
    }
    $sql->close();


    $leihen = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT(leihengeraete.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, statusnr AS gstat, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS gmel, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS gkom, zeit AS gzei FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0) AS x ORDER BY gstat DESC, sbez ASC, gbez ASC");
    if ($sql->execute()) {
      $sql->bind_result($sbez, $gbez, $gstat, $gmel, $gkom, $gzei);
      while ($sql->fetch()) {
        $icon = "blau";
        if ($gstat == 1) {$icon = "blau";}
        if ($gstat == 2) {$icon = "gelb";}
        if ($gstat == 3) {$icon = "rot";}
        if ($gstat == 4) {$icon = "schwarz";}
        if ($gstat == 5) {$icon = "weiss";}
        $leihen .= "<tr><td><b>$sbez – $gbez</b>";
        //"<p class=\"cms_notiz\">$gmel<br>$gkom</p><p class=\"cms_notiz\">(gemeldet am ".date("d.m.Y H:i", $gzei).")</p></td>";
        $leihen .= "</td><td><img src=\"res/icons/gross/$icon.png\"></td></tr>";
      }
    }
    $sql->close();

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

    $code .= "</div>";
    $code .= "<div id=\"cms_lvplan_morgen\">";
    $code .= "</div>";
    $code .= "<script>cms_intern_vplan_laden('l');</script>";
    $code .= "<div class=\"cms_clear\"></div>";
  }
  return $code;
}
 ?>
