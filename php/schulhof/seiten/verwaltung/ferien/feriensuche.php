<?php
function cms_ferienverwaltung_suche($dbs, $jahr, $anzeigen, $bearbeiten, $loeschen) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID;

  if ($bearbeiten || $loeschen) {$aktionen = true;}

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $ferien = "";

  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $sql = $dbs->prepare("SELECT * FROM ferien WHERE (beginn BETWEEN $jahraktuellb AND $jahraktuelle OR ende BETWEEN $jahraktuellb AND $jahraktuellb) ORDER BY beginn DESC, ende DESC");

  if ($sql->execute()) {
    $sql->bind_result($fid, $fbezeichnung, $fart, $fbeginn, $fende, $fmehrtaegigt, $fidvon, $fidzeit);
    while ($sql->fetch()) {
      $hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"$fid\">";

      if ($fart == 'f') {$icon = "ferien.png";$art = "Ferien";}
      else if ($fart == 'b') {$icon = "beweglicherferientag.png";$art = "Beweglicher Ferientag";}
      else if ($fart == 't') {$icon = "feiertag.png";$art = "Feiertag";}
      else {$icon = "sonderereignis.png";$art = "Sonderereignis";}
      $ferien .= '<tr><td class="cms_multiselect">'.$hmeta.'<img src="res/icons/oegruppen/'.$icon.'"></td><td>'.$fbezeichnung.'</td>';
      $ferien .= "<td>$art</td>";
      $ferien .= "<td>".cms_tagnamekomplett(date('w', $fbeginn)).", ".date('d.m.Y', $fbeginn)."</td>";
      $ferien .= "<td>".cms_tagnamekomplett(date('w', $fende)).", ".date('d.m.Y', $fende)."</td>";
      if ($aktionen) {
        $ferien .= "<td>";
        if ($bearbeiten) {
          $ferien .= "<span class=\"cms_aktion_klein\" onclick=\"cms_ferien_bearbeiten_vorbereiten('$fid')\"><span class=\"cms_hinweis\">Ferien bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
        }
        if ($loeschen) {
          $ferien .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_ferien_loeschen_vorbereiten('$fid', '$fbezeichnung')\"><span class=\"cms_hinweis\">Ferien löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
        }
        $ferien .= '</td>';
        $ferien .= '</tr>';
      }
    }
    if (strlen($ferien) == 0) {
      $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
    }
    else {
      $code .= $ferien;
      $code .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"7\">";
      if ($loeschen) {
        $code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_ferien_loeschen_anzeigen()\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
      }
      $code .= "</tr>";
    }
  }
  $sql->close();

  return $code;
}
?>
