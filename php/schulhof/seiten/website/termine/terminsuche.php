<?php
function cms_terminverwaltung_suche($dbs, $jahr) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_GRUPPEN;

  $genehmigen = cms_r("artikel.genehmigen.termine");

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $schulhoftermine = "";

  $TERMINE = array();
  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, genehmigt, aktiv, oeffentlichkeit, beginn, ende FROM termine WHERE (beginn BETWEEN ? AND ? OR ende BETWEEN ? AND ?) ORDER BY beginn DESC, ende DESC");
  $sql->bind_param("iiii", $jahraktuellb, $jahraktuelle, $jahraktuellb, $jahraktuelle);
  if ($sql->execute()) {
    $sql->bind_result($tid, $tbez, $tgen, $takt, $toeff, $tb, $te);
    while ($sql->fetch()) {
      $T = array();
      $T['id'] = $tid;
      $T['bezeichnung'] = $tbez;
      $T['genehmigt'] = $tgen;
      $T['aktiv'] = $takt;
      $T['oeffentlichkeit'] = $toeff;
      $T['beginn'] = $tb;
      $T['ende'] = $te;
      array_push($TERMINE, $T);
    }
  }
  $sql->close();

  foreach ($TERMINE as $daten) {
    if ($daten['genehmigt'] == 1) {$klasse = '';} else {$klasse = ' class="cms_vorlaeufig"';}
    $schulhoftermine .= '<tr'.$klasse.'><td><img src="res/icons/klein/termine.png"></td><td>'.$daten['bezeichnung'].'</td>';
    $zuordnungen = "";
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."termine JOIN $gk ON gruppe = id WHERE termin = ?) AS x ORDER BY bezeichnung ASC");
      $sql->bind_param("i", $daten['id']);
      if ($sql->execute()) {
        $sql->bind_result($zbez, $zicon);
        while ($sql->fetch()) {
          $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> "; ;
        }
      }
      $sql->close();
    }
    $schulhoftermine .= "<td>$zuordnungen</td>";
    $schulhoftermine .= "<td>".date('d.m.Y H:i', $daten['beginn'])."</td>";
    $schulhoftermine .= "<td>".date('d.m.Y H:i', $daten['ende'])."</td>";
    if ($daten['oeffentlichkeit'] == 0) {$icon = "rot"; $oeffentlichkeit = 'Mitglieder der zugeordneten Gruppen';}
    if ($daten['oeffentlichkeit'] == 1) {$icon = "orange"; $oeffentlichkeit = 'Lehrer';}
    if ($daten['oeffentlichkeit'] == 2) {$icon = "gelb"; $oeffentlichkeit = 'Lehrer und Verwaltung';}
    if ($daten['oeffentlichkeit'] == 3) {$icon = "blau"; $oeffentlichkeit = 'Gesamter Schulhof';}
    if ($daten['oeffentlichkeit'] == 4) {$icon = "gruen"; $oeffentlichkeit = 'Auf der Website und im Schulhof';}
    $schulhoftermine .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$oeffentlichkeit</span><img src=\"res/icons/klein/".$icon.".png\"></span> ";
    if ($daten['aktiv'] == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
    if ($daten['aktiv'] == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
    $schulhoftermine .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
    $schulhoftermine .= '<td>';
    if ($genehmigen && ($daten['genehmigt'] != '1')) {
      $schulhoftermine .= "<a class=\"cms_aktion_klein\" href=\"Schulhof/Aufgaben/Termine_genehmigen\"><span class=\"cms_hinweis\">zum Genehmigungscenter</span><img src=\"res/icons/klein/akzeptieren.png\"></a> ";
    }
    if (cms_r("artikel.{$daten['oeffentlichkeit']}.termine.bearbeiten")) {
      $schulhoftermine .= "<span class=\"cms_aktion_klein\" onclick=\"cms_termine_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Website/Termine')\"><span class=\"cms_hinweis\">Termin bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
    }
    if (cms_r("artikel.{$daten['oeffentlichkeit']}.termine.löschen")) {
      $schulhoftermine .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_termine_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."', 'Schulhof/Website/Termine')\"><span class=\"cms_hinweis\">Termin löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
    }
    $schulhoftermine .= '</td>';
    $schulhoftermine .= '</tr>';
  }

  if (strlen($schulhoftermine) == 0) {
    $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
  }
  else {$code .= $schulhoftermine;}

  return $code;
}
?>
