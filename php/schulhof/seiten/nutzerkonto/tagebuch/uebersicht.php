<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
if ($CMS_BENUTZERART == 'l') {
  $code = "<h1>Tagebuch</h1>";

  $code .= "<div class=\"cms_meldung cms_meldung_info\"><h3>Testbetrieb</h3><p>Über eine Weiterverwendung nach den Corona-Einschränkungen muss noch abgestimmt werden.</p></div>";

  $eintraege = "";
  $jetzt = time();
  $sql = $dbs->prepare("SELECT tagebuch.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.icon, '$CMS_SCHLUESSEL'), tbeginn, tende FROM tagebuch JOIN unterricht ON tagebuch.id = unterricht.id LEFT JOIN kurse ON tkurs = kurse.id WHERE tbeginn < ? AND tlehrer = ? AND freigabe != 1 ORDER BY tbeginn ASC");
  $sql->bind_param("ii", $jetzt, $CMS_BENUTZERID);
  if ($sql->execute()) {
  	$sql->bind_result($uid, $bezeichnung, $icon, $beginn, $ende);
    while ($sql->fetch()) {
      $eintraege .= "<li class=\"cms_neuigkeit cms_neuigkeit_ganz\" id=\"cms_tagebuchneuigkeit\" onclick=\"cms_tagebuch_eintragen('$uid')\"><span class=\"cms_neuigkeit_icon\"><img src=\"res/gruppen/gross/$icon\"></span>";
      $eintraege .= "<span class=\"cms_neuigkeit_inhalt\"><h4>$bezeichnung</h4>";
      $zeit = cms_tagname(date('N', $beginn)).", ".date("d.m.Y H:i", $beginn)." – ".date("H:i", $ende+1);
      $eintraege .= "<p>$zeit</p>";
      $eintraege .= "</span></li>";
    }
  }

  $code .= "<h2>Offene Tagebucheinträge</h2>";
  if (strlen($eintraege) > 0) {
    $code .= "<ul class=\"cms_neuigkeiten\">$eintraege</ul>";
  } else {
    $code .= "<p class=\"cms_notiz\">Aktuell sind keine offenen Tagebucheitnräge vorhanden.</p>";
  }

  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $klassentagebuecher = "";
  $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS id, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS bez, stufen.reihenfolge AS reihe FROM klassen LEFT JOIN stufen ON klassen.stufe = stufen.id WHERE klassen.schuljahr = ? AND klassen.id IN (SELECT gruppe FROM klassenvorsitz WHERE person = ?)) AS x ORDER BY x.reihe, x.bez");
  $sql->bind_param("ii", $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($klaid, $klabez, $klareihe);
    while ($sql->fetch()) {
      $klassentagebuecher .= "<span class=\"cms_button\" onclick=\"cms_tagebuch_einsehen('$klaid', 'klasse')\">$klabez</span>";
    }
  }
  $sql->close();

  $kurstagebuecher = "";
  $sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id AS id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, stufen.reihenfolge AS reihe FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ? AND kurse.id IN (SELECT gruppe FROM kursevorsitz WHERE person = ?)) AS x ORDER BY x.reihe, x.bez");
  $sql->bind_param("ii", $CMS_BENUTZERSCHULJAHR, $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($kurid, $kurbez, $kurreihe);
    while ($sql->fetch()) {
      $kurstagebuecher .= "<span class=\"cms_button\" onclick=\"cms_tagebuch_einsehen('$kurid', 'kurs')\">$kurbez</span>";
    }
  }
  $sql->close();

  if ((strlen($klassentagebuecher) > 0) || (strlen($kurstagebuecher) > 0)) {
    $code .= "<h2 style=\"padding-top:30px\">Tagebücher einsehen</h2>";

    $code .= "<div class=\"cms_meldung cms_meldung_warnung\"><h4>Ansicht</h4><p>Die Tagebuchansicht wird so bald wie möglich nachgeliefert.</p></div>";

    /*if (strlen($klassentagebuecher) > 0) {
      $code .= "<h3>Klassentagebücher</h3><p>$klassentagebuecher</p>";
    }
    if (strlen($kurstagebuecher) > 0) {
      $code .= "<h3>Kurstagebücher</h3><p>$kurstagebuecher</p>";
    }*/
  }

  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2 style=\"padding-top:30px\">Entschuldigungswesen</h2>";
  $code .= "<div class=\"cms_meldung cms_meldung_warnung\"><h4>Entschuldigungsmodus</h4><p>Der Entschuldigungsmodus wird so bald wie möglich nachgeliefert.</p></div>";

  $code .= "</div></div>";
  $code .= "<div class=\"cms_clear\">";

  echo $code;
}
else {
  echo cms_meldung_berechtigung();
}
?>
</div>
