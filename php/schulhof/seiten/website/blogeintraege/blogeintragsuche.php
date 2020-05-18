<?php
function cms_blogeintragverwaltung_suche($dbs, $jahr) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_GRUPPEN;

  $genehmigen = cms_r("artikel.genehmigen.blogeinträge");

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $schulhofblogeintraege = "";

  $EINTRAEGE = array();
  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, genehmigt, aktiv, oeffentlichkeit, datum, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM blogeintraege WHERE (datum BETWEEN ? AND ?) ORDER BY datum DESC");
  $sql->bind_param("ii", $jahraktuellb, $jahraktuelle);
  if ($sql->execute()) {
    $sql->bind_result($eid, $ebez, $egen, $eakt, $eoef, $edat, $eaut);
    while ($sql->fetch()) {
      $D = array();
      $D['id'] = $eid;
      $D['bezeichnung'] = $ebez;
      $D['genehmigt'] = $egen;
      $D['aktiv'] = $eakt;
      $D['oeffentlichkeit'] = $eoef;
      $D['datum'] = $edat;
      $D['autor'] = $eaut;
      array_push($EINTRAEGE, $D);
    }
  }
  $sql->close();

  foreach ($EINTRAEGE AS $daten) {
    $hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"{$daten['id']}\">";

    if ($daten['genehmigt'] == 1) {$klasse = '';} else {$klasse = ' class="cms_vorlaeufig"';}
    $schulhofblogeintraege .= '<tr'.$klasse.'><td class="cms_multiselect">'.$hmeta.'<img src="res/icons/klein/blog.png"></td><td>'.$daten['bezeichnung'].'</td>';
    $zuordnungen = "";
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."blogeintraege JOIN $gk ON gruppe = id WHERE blogeintrag = ?) AS x ORDER BY bezeichnung ASC");
      $sql->bind_param("i", $daten['id']);
      if ($sql->execute()) {
        $sql->bind_result($zbez, $zicon);
        while ($sql->fetch()) {
          $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> "; ;
        }
      }
      $sql->close();
    }
    $schulhofblogeintraege .= "<td>$zuordnungen</td>";
    $schulhofblogeintraege .= "<td>".cms_tagname(date('w', $daten['datum']))." ".date('d.m.Y', $daten['datum'])."</td>";
    $schulhofblogeintraege .= "<td>".$daten['autor']."</td>";
    if ($daten['oeffentlichkeit'] == 0) {$icon = "rot"; $oeffentlichkeit = 'Mitglieder der zugeordneten Gruppen';}
    if ($daten['oeffentlichkeit'] == 1) {$icon = "orange"; $oeffentlichkeit = 'Lehrer';}
    if ($daten['oeffentlichkeit'] == 2) {$icon = "gelb"; $oeffentlichkeit = 'Lehrer und Verwaltung';}
    if ($daten['oeffentlichkeit'] == 3) {$icon = "blau"; $oeffentlichkeit = 'Gesamter Schulhof';}
    if ($daten['oeffentlichkeit'] == 4) {$icon = "gruen"; $oeffentlichkeit = 'Auf der Website und im Schulhof';}
    $schulhofblogeintraege .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$oeffentlichkeit</span><img src=\"res/icons/klein/".$icon.".png\"></span> ";
    if ($daten['aktiv'] == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
    if ($daten['aktiv'] == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
    $schulhofblogeintraege .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
    $schulhofblogeintraege .= '<td>';
    if ($genehmigen && ($daten['genehmigt'] != '1')) {
      $schulhofblogeintraege .= "<a class=\"cms_aktion_klein\" href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\"><span class=\"cms_hinweis\">zum Genehmigungscenter</span><img src=\"res/icons/klein/akzeptieren.png\"></a> ";
    }
    if (cms_r("artikel.{$daten['oeffentlichkeit']}.blogeinträge.bearbeiten")) {
      $schulhofblogeintraege .= "<span class=\"cms_aktion_klein\" onclick=\"cms_blogeintraege_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Website/Blogeinträge')\"><span class=\"cms_hinweis\">Blogeintrag bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
    }
    if (cms_r("artikel.{$daten['oeffentlichkeit']}.blogeinträge.löschen")) {
      $schulhofblogeintraege .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_blogeintraege_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."', 'Schulhof/Website/Blogeinträge')\"><span class=\"cms_hinweis\">Blogeintrag löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
    }
    $schulhofblogeintraege .= '</td>';
    $schulhofblogeintraege .= '</tr>';
  }

  if (strlen($schulhofblogeintraege) == 0) {
    $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Blogeinträge vorhanden --</td></tr>";
  }
  else {
    $code .= $schulhofblogeintraege;
    $code .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"7\">";
    if (cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.löschen")) {
      $code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_blogeintraege_loeschen_anzeigen('Schulhof/Website/Blogeinträge')\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
    }
    $code .= "</tr>";
  }

  return $code;
}
?>
