<?php
$code = "<h2>Probleme mit Geräten melden</h2>";

if ($CMS_RECHTE['Technik']['Geräte-Probleme melden']) {
  $ausgaber = "";
  if ($CMS_RECHTE['Planung']['Räume sehen']) {
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
  if ($CMS_RECHTE['Planung']['Leihgeräte sehen']) {
    $sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihen WHERE verfuegbar = 1) AS x ORDER BY bezeichnung ASC;");
    if ($sql->execute()) {
      $sql->bind_result($lid, $lbez);
      while ($sql->fetch()) {
        $anzeigename = $lbez;
        $anzeigenamelink = cms_textzulink($anzeigename);
        $ausgabel .= "<li><a class=\"cms_button\" href=\"App/Probleme_melden/Leihgeräte/$anzeigenamelink\">".$anzeigename."</a></li> ";
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
