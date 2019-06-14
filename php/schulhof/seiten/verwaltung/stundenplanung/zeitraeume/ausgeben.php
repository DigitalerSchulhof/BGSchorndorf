<?php
function cms_stundenplanung_zeitraeume_ausgeben($dbs, $schuljahr) {
  global $CMS_SCHLUESSEL, $CMS_RECHTE;

  $code = "";
  $bestehend = "";
  $aktionen = false;
  if ($CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume löschen'] ||
      $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen']) {$aktionen = true;}

  // Bestehende Zeiträume in diesem Schuljahr laden
  $sql = "SELECT id, aktiv, beginn, ende FROM zeitraeume WHERE schuljahr = $schuljahr ORDER BY beginn DESC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $bestehend .= "<tr><td><img src=\"res/icons/klein/zeitraeume.png\"></td>";
      $bestehend .= "<td>".cms_tagname(date('w', $daten['beginn']))." ".date('d', $daten['beginn']).".".date('m', $daten['beginn']).".".date('Y', $daten['beginn'])." bis ";
      $bestehend .= cms_tagname(date('w', $daten['ende']))." ".date('d', $daten['ende']).".".date('m', $daten['ende']).".".date('Y', $daten['ende'])."</td>";
      if ($daten['aktiv'] == 1) {$icon = 'gruen.png';} else {$icon = 'rot.png';}
      $bestehend .= "<td><img src=\"res/icons/klein/$icon\"></td>";
      if ($aktionen) {
        $bestehend .= "<td>";
        if ($CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten']) {$bestehend .= "<span class=\"cms_aktion_klein\" onclick=\"cms_stundenplanung_zeitraeume_bearbeiten_vorbereiten('".$daten['id']."')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";}
        if ($CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen']) {$bestehend .= "<span class=\"cms_aktion_klein\" onclick=\"cms_stundenplanung_stundenplaene_bearbeiten_vorbereiten('".$daten['id']."')\"><span class=\"cms_hinweis\">Stundenpläne in diesem Zeitraum anlegen, bearbeiten, löschen</span><img src=\"res/icons/klein/stundenplan.png\"></span> ";}
        if ($CMS_RECHTE['Planung']['Stundenplanzeiträume löschen']) {$bestehend .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_stundenplanung_zeitraeume_loeschen_vorbereiten('".$daten['id']."')\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";}
        $bestehend .= "</td>";
      }
      $bestehend .= "</tr>";
    }
    $anfrage->free();
  }

  if (strlen($bestehend) == 0) {
    if ($aktionen) {$spalten = 4;} else {$spalten = 3;}
    $bestehend = "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">-- keine Zeiträume angelegt --</td></tr>";
  }

  $code .= $bestehend;

  return $code;
}
?>
