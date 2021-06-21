<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Conoatest</h1>";


$zugriff = (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')); // Mitgliedschaft oder nach außen sichtbar

if ($zugriff) {
  $code .= "<h2>Meine Gruppen</h2>";
  foreach ($CMS_GRUPPEN as $g) {
    $kg = strtolower(str_replace(" ", "", $g));
    $gcode = "";

    $heute = time();

    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM $kg WHERE id IN (SELECT DISTINCT gruppe FROM $kg"."mitglieder WHERE person = ?) AND schuljahr IN (SELECT id FROM schuljahre WHERE ? BETWEEN beginn AND ende)) AS x ORDER BY bez ASC");

    $sql->bind_param("ii", $CMS_BENUTZERID, $heute);
	  if ($sql->execute()) {
	    $sql->bind_result($gid, $gbez);
	    while ($sql->fetch()) {
        $gcode .= "<span class=\"cms_button\" onclick=\"cms_coronatest_vorbereiten('$g', '$gid')\">$gbez</span> ";
      }
	  }
	  $sql->close();

    if (strlen($gcode) > 0) {
      $code .= "<h3>$g</h3><p>".$gcode."</p>";
    }
  }

  $code .= "<h2>Sonstige Gruppen</h2>";

  foreach ($CMS_GRUPPEN as $g) {
    $kg = strtolower(str_replace(" ", "", $g));
    $gcode = "";

    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM $kg WHERE id NOT IN (SELECT DISTINCT gruppe FROM $kg"."mitglieder WHERE person = ?) AND schuljahr IN (SELECT id FROM schuljahre WHERE ? BETWEEN beginn AND ende)) AS x ORDER BY bez ASC");
    $sql->bind_param("ii", $CMS_BENUTZERID, $heute);
	  if ($sql->execute()) {
	    $sql->bind_result($gid, $gbez);
	    while ($sql->fetch()) {
        $gcode .= "<span class=\"cms_button\" onclick=\"cms_coronatest_vorbereiten('$g', '$gid')\">$gbez</span> ";
      }
	  }
	  $sql->close();

    if (strlen($gcode) > 0) {
      $code .= "<h3>$g</h3><p>".$gcode."</p>";
    }
  }


  $code .= "<h2>Statistik getesteter Schüler in den letzten 10 Tagen</h2>";
  // Stufen laden
  $STATISTIK = array();
  $htag = date("d", $heute);
  $hmonat = date("m", $heute);
  $hjahr = date("Y", $heute);
  $stufenreihenfolge = array();
  $sql = $dbs->prepare("SELECT stufen.id, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL'), stufen.reihenfolge, COUNT(*) as anzahl FROM coronagetestet JOIN coronatest ON coronagetestet.test = coronatest.id JOIN personen ON coronagetestet.person = personen.id LEFT JOIN stufenmitglieder ON coronagetestet.person = stufenmitglieder.person JOIN stufen ON stufen.id = stufenmitglieder.gruppe WHERE coronagetestet.art = AES_ENCRYPT('t', '$CMS_SCHLUESSEL') AND (zeit BETWEEN ? AND ?) AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND stufen.schuljahr IN (SELECT id FROM schuljahre WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR ((beginn < ?) AND (ende > ?))) GROUP BY stufen.id ORDER BY stufen.reihenfolge ASC");
  $von = mktime(0,0,0,$hmonat,$htag-9,$hjahr);
  $bis = mktime(23,59,59,$hmonat,$htag,$hjahr);

  $sql->bind_param("iiiiiiii", $von, $bis, $von, $bis, $von, $bis, $von, $bis);
  $nr = 0;
  if ($sql->execute()) {
    $sql->bind_result($sid, $sbez, $reihenfolge, $sanzahl);
    while ($sql->fetch()) {
      $stufenreihenfolge[$nr] = $sid;
      $nr++;
      $STATISTIK[$sid] = array();
      $STATISTIK[$sid]['bez'] = $sbez;
    }
  }

  $TAGE = array();

  for ($tag = 0; $tag <= 9; $tag++) {
    $von = mktime(0,0,0,$hmonat,$htag-9+$tag,$hjahr);
    $TAGE[$tag] = date("d.m.Y", $von);
    $bis = mktime(23,59,59,$hmonat,$htag-9+$tag,$hjahr);
    $sql->bind_param("iiiiiiii", $von, $bis, $von, $bis, $von, $bis, $von, $bis);
    if ($sql->execute()) {
      $sql->bind_result($sid, $sbez, $reihenfolge, $sanzahl);
      while ($sql->fetch()) {
        $STATISTIK[$sid]['anzahlen'][$tag] = $sanzahl;
      }
    }
  }

  $sql->close();


  // Nicht zugeordnete Schüler finden
  if (count($stufenreihenfolge) > 0) {
    $stufenausschluss = implode(",", $stufenreihenfolge);
    $sql = $dbs->prepare("SELECT COUNT(*) FROM coronagetestet JOIN coronatest ON coronagetestet.test = coronatest.id JOIN personen ON coronagetestet.person = personen.id WHERE coronagetestet.art = AES_ENCRYPT('t', '$CMS_SCHLUESSEL') AND (zeit BETWEEN ? AND ?) AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL') AND personen.id NOT IN (SELECT DISTINCT person FROM stufenmitglieder WHERE gruppe IN ($stufenausschluss))");
  } else {
    $sql = $dbs->prepare("SELECT COUNT(*) FROM coronagetestet JOIN coronatest ON coronagetestet.test = coronatest.id JOIN personen ON coronagetestet.person = personen.id WHERE coronagetestet.art = AES_ENCRYPT('t', '$CMS_SCHLUESSEL') AND (zeit BETWEEN ? AND ?) AND personen.art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
  }

  $stufenreihenfolge[$nr] = "-";
  $STATISTIK['-']['bez'] = "<i>nicht zugeordnet</i>";

  for ($tag = 0; $tag <= 9; $tag++) {
    $nr = 0;
    $von = mktime(0,0,0,$hmonat,$htag-9+$tag,$hjahr);
    $TAGE[$tag] = date("d.m.Y", $von);
    $bis = mktime(23,59,59,$hmonat,$htag-9+$tag,$hjahr);
    $sql->bind_param("ii", $von, $bis);
    if ($sql->execute()) {
      $sql->bind_result($sanzahl);
      if ($sql->fetch()) {
        $STATISTIK['-']['anzahlen'][$tag] = $sanzahl;
      }
    }
  }

  $sql->close();


  // Ausgabe

  $code .= "<table class=\"cms_liste\">";

    $code .= "<tr><th>Stufe</th>";
    foreach ($TAGE as $t) {
      $code .= "<th>$t</th>";
    }
    $code .= "</tr>";

    foreach ($stufenreihenfolge AS $sr) {
      $code .= "<tr>";
      $code .= "<th>".$STATISTIK[$sr]['bez']."</th>";
      for ($i=0; $i<=9; $i++) {
        if (isset($STATISTIK[$sr]['anzahlen'][$i])) {
          $code .= "<td>".$STATISTIK[$sr]['anzahlen'][$i]."</td>";
        } else {
          $code .= "<td>0</td>";
        }
      }
      $code .= "</tr>";
    }
    // Gesamtzahlen
    $code .= "<tr>";
    $code .= "<th>Gesamt</th>";
    for ($i=0; $i<=9; $i++) {
      $taggesammt = 0;
      foreach ($stufenreihenfolge AS $sr) {
        if (isset($STATISTIK[$sr]['anzahlen'][$i])) {
          $taggesammt += $STATISTIK[$sr]['anzahlen'][$i];
        }
      }
      $code .= "<td>$taggesammt</td>";
    }
    $code .= "</tr>";
  $code .= "</table>";
}
else {
  $code .= cms_meldung_berechtigung();
}



$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
