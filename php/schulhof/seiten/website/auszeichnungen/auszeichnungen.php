<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Auszeichnungen</h1>

<?php
$bearbeiten = cms_r("website.auszeichnungen.bearbeiten");
$loeschen   = cms_r("website.auszeichnungen.löschen");
$anzeigen = $bearbeiten || $loeschen;

if ($anzeigen) {
  $canlegen = '';
  $canzeigen = '';

  $canzeigen .= '<table class="cms_liste">';
  $canzeigen .= '<tr><th></th><th>Bezeichnung</th><th>Link</th><th>Ziel</th><th></th><th>Aktionen</th></tr>';
  $auszeichnungen = "";
  $sql = $dbs->prepare("SELECT id, bezeichnung, link, ziel, aktiv FROM auszeichnungen ORDER BY reihenfolge");
  if ($sql->execute()) {
    $sql->bind_result($aid, $abez, $alink, $aziel, $aaktiv);
    while ($sql->fetch()) {
      $hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"$aid\">";

      $auszeichnungen .= "<tr><td class=\"cms_multiselect\">$hmeta<img src=\"res/icons/klein/auszeichnungen.png\"></td>";
      $auszeichnungen .= "<td>$abez</td><td>$alink</td>";
      if ($aaktiv == '1') {$aaktivicon = "gruen.png";} else {$aaktivicon = "rot.png";}
      if ($aziel == '_blank') {$azieltext = "Neuer Tab";} else {$azieltext = "Dieser Tab";}
      $auszeichnungen .= "<td>$azieltext</td><td><img src=\"res/icons/klein/$aaktivicon\"></td><td>";
      if (cms_r("website.auszeichnungen.bearbeiten")) {$auszeichnungen .= "<span class=\"cms_aktion_klein\" onclick=\"cms_auszeichnung_bearbeiten_vorbereiten('$aid')\"><span class=\"cms_hinweis\">Auszeichnung bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";}
      if (cms_r("website.auszeichnungen.löschen")) {$auszeichnungen .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_auszeichnung_loeschen_anzeigen('$aid')\"><span class=\"cms_hinweis\">Auszeichnung löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";}
      $auszeichnungen .= "</td></tr>";
    }
  }
  $sql->close();
  if (strlen($auszeichnungen) > 0) {
    $canzeigen .= $auszeichnungen;
    $canzeigen .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"6\">";
    if (cms_r("website.auszeichnungen.löschen")) {
      $canzeigen .= "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_multiselect_auszeichnungen_loeschen_anzeigen()\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
    }
    $canzeigen .= "</tr>";
  }
  else {$canzeigen .= "<tr><td class=\"cms_notiz\" colspan=\"6\">– Keine Auszeichnungen angelegt –</td></tr>";}
  $canzeigen .= '</table>';

  $code .= $canzeigen;
}
if (cms_r("website.auszeichnungen.anlegen")) {
  $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Website/Auszeichnungen/Auszeichnung_anlegen\">+ Neue Auszeichnung</a></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
