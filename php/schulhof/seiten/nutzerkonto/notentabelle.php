<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php

  echo "<h1>Notentabelle</h1>";

  $notenWidth = 30;
  $kurse = array();
  $halbjahre = array(); // Max Noten pro HJ für cellspan

  for($i = 0; $i < 2; $i++) {
    $halbjahre[$i] = array(
      "max"           => 1    // Max noten pro HJ
    );
  }


  $sql = "SELECT AES_DECRYPT(faecher.bezeichnung, '$CMS_SCHLUESSEL'), kurse.id, AES_DECRYPT(notentabelle_struktur.bereiche, '$CMS_SCHLUESSEL'), AES_DECRYPT(notentabelle_struktur.noten, '$CMS_SCHLUESSEL') FROM faecher JOIN kurse ON faecher.id = kurse.fach JOIN kursemitglieder on kurse.id = kursemitglieder.gruppe LEFT JOIN notentabelle_struktur ON notentabelle_struktur.kurs = kurse.id AND notentabelle_struktur.person = kursemitglieder.person WHERE kursemitglieder.person = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("i", $CMS_BENUTZERID);
  $sql->bind_result($kursbez, $kursid, $bereiche, $noten);
  $sql->execute();
  while($sql->fetch()) {
    $b = json_decode($bereiche, true);
    $n = json_decode($noten, true);

    if($b === null) {
      $b = array(array("1", "Mündlich"), array("2", "Schriftlich"));
    }
    if($n === null) {
      $n = array(array(2, 2), array(2, 2));
    }
    $bereiche = array();
    foreach($b as $bereich) {
      $bereiche[] = array(
        "gewichtung"  => $bereich[0],
        "bezeichnung" => $bereich[1]
      );
    }

    $noten = array();
    foreach($n as $j => $hj) {
      $h = array();
      foreach($hj as $bereich) {
        $halbjahre[$j]["max"] = max($halbjahre[$j]["max"], $bereich);
        $b = array();
        for($i = 0; $i < $bereich; $i++) {
          $b[] = array("note" => "", "datum" => "");
        }
        $h[] = $b;
      }
      $noten[] = $h;
    }

    // TODO: Notenwerte speichern und laden

    $kurse[] = array(
      "bezeichnung" => $kursbez,
      "kid"         => $kursid,
      "bereiche"    => $bereiche,
      "noten"       => $noten,
    );
  }

  $tabelle = "";



  function bereiche_ausgeben($bereiche) {
    $r = "";
    foreach($bereiche as $bereich) {
      $r .= "<div class=\"bereich\"><div class=\"loeschen\" onclick=\"cms_notentabelle_bereich_loeschen(this)\"></div><div class=\"gewichtung\">{$bereich['gewichtung']}</div><div class=\"bez\">{$bereich['bezeichnung']}</div></div>";
    }

    return $r;
  }

  function noten_ausgeben($noten, $hj) {
    $r = "";
    foreach($noten[$hj] as $bereich) {
      $r .= "<div class=\"bereich\">";
      foreach($bereich as $note) {
        $r .= "<div class=\"note\">{$note['note']}</div>";
      }
      $r .= "</div>";
    }

    return $r;
  }

  function avg_ausgeben($noten, $hj) {
    $r = "";
    foreach($noten[$hj] as $bereich) {
      $r .= "<div class=\"bereich\">...";
      $r .= "</div>";
    }

    return $r;
  }

  $hell = false;
  foreach($kurse as $kurs) {

    $kursname     = $kurs["bezeichnung"];
    $kursid       = $kurs["kid"];
    $kursbereiche = $kurs["bereiche"];
    $kursnoten    = $kurs["noten"];

    if(count($kursbereiche) == 0) {
      // Keine Bereiche eingetragen
    } else {
      $bg = $hell ? "#eeeeee" : "#dddddd";

      // Zeile mit Kurs und rowspan
      $tabelle .= "<tr class=\"kurs\" id=\"kurs_$kursid\">";
      $tabelle .= "<td class=\"fach\" style=\"background-color: $bg;\">$kursname</td>";
      $tabelle .= "<td class=\"bereiche\"><div>".bereiche_ausgeben($kursbereiche)."</div></td>";
      foreach($halbjahre as $hj => $d) {
        $tabelle .= "<td class=\"noten hj_$hj\"><div>".noten_ausgeben($kursnoten, $hj)."</div></td>";
        $tabelle .= "<td class=\"avg hj_$hj\"><div>".avg_ausgeben($kursnoten, $hj)."</div></td>";
      }
      $tabelle .= "<td class=\"einstellungen\" style=\"background-color: $bg; width: 28px;\">";
      $tabelle .= "<span class=\"cms_aktion_klein cms_aktion bearbeiten\" onclick=\"cms_notentabelle_einstellungen($kursid)\"><span class=\"cms_hinweis\">Optionen</span><img src=\"res/icons/klein/einstellungen.png\"><br></span>";
      $tabelle .= "<span class=\"cms_aktion_klein cms_aktion abbrechen\" onclick=\"cms_notentabelle_einstellungen_abbrechen($kursid)\"><span class=\"cms_hinweis\">Änderungen verwerfen</span><img src=\"res/icons/klein/abbrechen.png\"><br></span>";
      $tabelle .= "<span class=\"cms_aktion_klein cms_aktion speichern\" onclick=\"cms_notentabelle_einstellungen_speichern($kursid)\"><span class=\"cms_hinweis\">Änderungen speichern</span><img src=\"res/icons/klein/richtig.png\"><br></span>";
      $tabelle .= "</td></tr>";

      $hell = !$hell;
    }
  }


  echo "<table id=\"notentabelle\">";
    echo "<thead>";
    echo "<tr><th>Kurs</th><th id=\"bereich\"><div><div></div><div title=\"Gewichtung\" id=\"gew\">Gew.</div><div>Bereich</div></div></th>";
    foreach($halbjahre as $hj => $daten) {
      $max = $daten["max"];
      $n = $hj+1;

      $width = max(1, $max) * $notenWidth;
      echo "<th class=\"hj\" title=\"Halbjahr $n\" id=\"hj_$hj\" style=\"width: {$width}px\">HJ $n</th>";
      echo "<th class=\"avg\" style=\"width: {$notenWidth}px;\" id=\"avg_$hj\">&Oslash;</th>";
    }
    echo "<th style=\"width: 28px;\"></th></tr>";
    echo "</thead><tbody>";
    echo $tabelle;
    echo "</tbody>";
  echo "</table>";
?>
</div>
