<?php
$code = "<h2>Probleme mit Geräten melden</h2>";
if (strpos($_SERVER['HTTP_USER_AGENT'], "dshApp") === false && strpos($_SERVER['HTTP_USER_AGENT'], "Android") !== false) {
  $code .= cms_meldung("fehler", "<h4>Veraltete App!</h4><p>Es ist eine neue Version der App des Digitalen Schulhof verfügbar!<br>Diese kann <a href=\"https://play.google.com/store/apps/details?id=de.dsh\">hier</a> heruntergeladen werden.<br>Die alte Version kann und sollte anschließend deinstalliert werden.</p>");
}

if (cms_r("schulhof.technik.geräte.probleme")) {
  $ausgaber = "";
  if (cms_r("schulhof.organisation.räume.sehen")) {
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;");
    if ($sql->execute()) {
      $sql->bind_result($rid, $rbez);
      while ($sql->fetch()) {
        $anzeigename = $rbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgaber .= "<a class=\"cms_button\" href=\"App/Probleme_melden/Räume/$anzeigenamelink\">".$anzeigename."</a> ";
      }
    }
    $sql->close();
    if (strlen($ausgaber) > 0) {$code .= "<h3>Probleme in Räumen</h3><p>$ausgaber</p>";}

  }
  $ausgabel = "";
  if (cms_r("schulhof.organisation.leihgeräte.sehen")) {
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihen WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;");
    if ($sql->execute()) {
      $sql->bind_result($lid, $lbez);
      while ($sql->fetch()) {
        $anzeigename = $lbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabel .= "<a class=\"cms_button\" href=\"App/Probleme_melden/Leihgeräte/$anzeigenamelink\">".$anzeigename."</a> ";
      }
    }
    $sql->close();
    if (strlen($ausgabel) > 0) {$code .= "<h3>Probleme mit Leihgeräten</h3><p>$ausgabel</p>";}
  }

  if ((strlen($ausgaber) == 0) && (strlen($ausgabel) == 0)) {
    $code .= "<p class=\"cms_notiz\">Keine Geräte verfügbar.</p>";
  }
}
else {
  $code .= cms_meldung_berechtigung();
}

echo "<div class=\"cms_spalte_i\">".$code."</div>";
?>
