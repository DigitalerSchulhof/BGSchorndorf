<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Auszeichnungen</h1>

<?php
$bearbeiten = $CMS_RECHTE['Website']['Auszeichnungen bearbeiten'];
$loeschen = $CMS_RECHTE['Website']['Auszeichnungen löschen'];
$anzeigen = $bearbeiten || $loeschen;

if ($anzeigen) {
  $canlegen = '';
  $canzeigen = '';

  $spalten = 7;

  $canzeigen .= '<table class="cms_liste">';
  $canzeigen .= '<tr><th></th><th>Bezeichnung</th><th>Link</th><th>Ziel</th><th></th><th>Aktionen</th></tr>';
  $auszeichnungen = "";
  $sql = $dbs->prepare("SELECT id, bezeichnung, link, ziel, aktiv FROM auszeichnungen ORDER BY reihenfolge");
  if ($sql->execute()) {
    $sql->bind_result($aid, $abez, $alink, $aziel, $aaktiv);
    while ($sql->fetch()) {
      $auszeichnungen .= "<tr><td><img src=\"res/icons/klein/auszeichnungen.png\"></td>";
      $auszeichnungen .= "<td>$abez</td><td>$alink</td>";
      if ($aaktiv == '1') {$aaktivicon = "gruen.png";} else {$aaktivicon = "rot.png";}
      if ($aziel == '_blank') {$azieltext = "Neuer Tab";} else {$azieltext = "Dieser Tab";}
      $auszeichnungen .= "<td>$azieltext</td><td><img src=\"res/icons/klein/$aaktivicon\"></td><td>";
      if ($CMS_RECHTE['Website']['Auszeichnungen bearbeiten']) {$auszeichnungen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_auszeichnung_bearbeiten_vorbereiten('$aid')\"><span class=\"cms_hinweis\">Auszeichnung bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";}
      if ($CMS_RECHTE['Website']['Auszeichnungen löschen']) {$auszeichnungen .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_auszeichnung_loeschen_anzeigen('$aid')\"><span class=\"cms_hinweis\">Auszeichnung löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";}
      $auszeichnungen .= "</td></tr>";
    }
  }
  $sql->close();
  if (strlen($auszeichnungen) > 0) {$canzeigen .= $auszeichnungen;}
  else {$canzeigen .= "<tr><td class=\"cms_notiz\" colspan=\"6\">– Keine Auszeichnungen angelegt –</td></tr>";}
  $canzeigen .= '</table>';

  $code .= $canzeigen;
}
if ($CMS_RECHTE['Website']['Auszeichnungen anlegen']) {
  $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Website/Auszeichnungen/Auszeichnung_anlegen\">+ Neue Auszeichnung</a></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
