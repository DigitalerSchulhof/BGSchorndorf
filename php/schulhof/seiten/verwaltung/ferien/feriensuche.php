<?php
function cms_ferienverwaltung_suche($dbs, $jahr, $anzeigen, $bearbeiten, $loeschen) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID;

  if ($bearbeiten || $loeschen) {$aktionen = true;}

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $ferien = "";

  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $sql = "SELECT * FROM ferien WHERE (beginn BETWEEN $jahraktuellb AND $jahraktuelle OR ende BETWEEN $jahraktuellb AND $jahraktuellb) ORDER BY beginn DESC, ende DESC";

  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      if ($daten['art'] == 'f') {$icon = "ferien.png";}
      else if ($daten['art'] == 'b') {$icon = "beweglicherferientag.png";}
      else if ($daten['art'] == 't') {$icon = "feiertag.png";}
      else {$icon = "sonderereignis.png";}
      $ferien .= '<tr><td><img src="res/icons/oegruppen/'.$icon.'"></td><td>'.$daten['bezeichnung'].'</td>';
      if ($daten['art'] == 'f') {$art = "Ferien";}
      else if ($daten['art'] == 'b') {$art = "Beweglicher Ferientag";}
      else if ($daten['art'] == 't') {$art = "Feiertag";}
      else {$art = "Sonderereignis";}
      $ferien .= "<td>$art</td>";
      $ferien .= "<td>".cms_tagnamekomplett(date('w', $daten['beginn'])).", ".date('d.m.Y', $daten['beginn'])."</td>";
      $ferien .= "<td>".cms_tagnamekomplett(date('w', $daten['ende'])).", ".date('d.m.Y', $daten['ende'])."</td>";
      if ($aktionen) {
        $ferien .= "<td>";
        if ($bearbeiten) {
          $ferien .= "<span class=\"cms_aktion_klein\" onclick=\"cms_ferien_bearbeiten_vorbereiten('".$daten['id']."')\"><span class=\"cms_hinweis\">Ferien bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
        }
        if ($loeschen) {
          $ferien .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_ferien_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."')\"><span class=\"cms_hinweis\">Ferien löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
        }
        $ferien .= '</td>';
        $ferien .= '</tr>';
      }
    }
    $anfrage->free();
    if (strlen($ferien) == 0) {
      $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
    }
    else {$code .= $ferien;}
  }

  return $code;
}
?>
