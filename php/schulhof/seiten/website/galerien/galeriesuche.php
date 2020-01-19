<?php
function cms_galerieverwaltung_suche($dbs, $jahr, $anzeigen, $bearbeiten, $loeschen) {
  global $CMS_SCHLUESSEL, $CMS_RECHTE, $CMS_BENUTZERID, $CMS_GRUPPEN;

  $genehmigen = $CMS_RECHTE['Organisation']['Galerien genehmigen'];

  if ($bearbeiten || $loeschen || $genehmigen) {$aktionen = true;}

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $schulhofgalerien = "";

  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $GALERIEN = array();
  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, genehmigt, aktiv, oeffentlichkeit, datum, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM galerien WHERE (datum BETWEEN ? AND ?) ORDER BY datum DESC");
  $sql->bind_param("ii", $jahraktuellb, $jahraktuelle);

  if ($sql->execute()) {
    $sql->bind_result($gid, $gbez, $ggen, $gakt, $goeff, $gdat, $gaut);
    while ($sql->fetch()) {
      $G = array();
      $G['id'] = $gid;
      $G['bezeichnung'] = $gbez;
      $G['genehmigt'] = $ggen;
      $G['aktiv'] = $gakt;
      $G['oeffentlichkeit'] = $goeff;
      $G['datum'] = $gdat;
      $G['autor'] = $gaut;
      array_push($GALERIEN, $G);
    }
  }
  $sql->close();

  foreach ($GALERIEN as $daten) {
    if ($daten['genehmigt'] == 1) {$klasse = '';} else {$klasse = ' class="cms_vorlaeufig"';}
    $schulhofgalerien .= '<tr'.$klasse.'><td><img src="res/icons/klein/galerie.png"></td><td>'.$daten['bezeichnung'].'</td>';
    $zuordnungen = "";

    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."galerien JOIN $gk ON gruppe = id WHERE galerie = ?) AS x ORDER BY bezeichnung ASC");
      $sql->bind_param("i", $daten['id']);
      if ($sql->execute()) {
        $sql->bind_result($zbez, $zicon);
        while ($sql->fetch()) {
          $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> "; ;
        }
      }
      $sql->close();
    }
    $schulhofgalerien .= "<td>$zuordnungen</td>";
    $schulhofgalerien .= "<td>".cms_tagname(date('w', $daten['datum']))." ".date('d.m.Y', $daten['datum'])."</td>";
    $schulhofgalerien .= "<td>".$daten['autor']."</td>";
    if ($daten['oeffentlichkeit'] == 0) {$icon = "rot"; $oeffentlichkeit = 'Mitglieder der zugeordneten Gruppen';}
    if ($daten['oeffentlichkeit'] == 1) {$icon = "orange"; $oeffentlichkeit = 'Lehrer';}
    if ($daten['oeffentlichkeit'] == 2) {$icon = "gelb"; $oeffentlichkeit = 'Lehrer und Verwaltung';}
    if ($daten['oeffentlichkeit'] == 3) {$icon = "blau"; $oeffentlichkeit = 'Gesamter Schulhof';}
    if ($daten['oeffentlichkeit'] == 4) {$icon = "gruen"; $oeffentlichkeit = 'Auf der Website und im Schulhof';}
    $schulhofgalerien .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$oeffentlichkeit</span><img src=\"res/icons/klein/".$icon.".png\"></span> ";
    if ($daten['aktiv'] == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
    if ($daten['aktiv'] == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
    $schulhofgalerien .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
    $schulhofgalerien .= '<td>';
    if ($genehmigen && ($daten['genehmigt'] != '1')) {
      $schulhofgalerien .= "<a class=\"cms_aktion_klein\" href=\"Schulhof/Aufgaben/Galerien_genehmigen\"><span class=\"cms_hinweis\">Zum Genehmigungscenter</span><img src=\"res/icons/klein/akzeptieren.png\"></a> ";
    }
    if ($bearbeiten) {
      $schulhofgalerien .= "<span class=\"cms_aktion_klein\" onclick=\"cms_galerie_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Website/Galerien')\"><span class=\"cms_hinweis\">Galerie bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
    }
    if ($loeschen) {
      $schulhofgalerien .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_galerie_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."', 'Schulhof/Website/Galerien')\"><span class=\"cms_hinweis\">Galerie löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
    }
    $schulhofgalerien .= '</td>';
    $schulhofgalerien .= '</tr>';
  }

  if (strlen($schulhofgalerien) == 0) {
    $code .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Galerien vorhanden --</td></tr>";
  }
  else {$code .= $schulhofgalerien;}

  return $code;
}
?>
