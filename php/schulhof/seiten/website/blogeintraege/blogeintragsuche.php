<?php
function cms_blogeintragverwaltung_suche($dbs, $jahr, $bearbeiten, $loeschen) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_GRUPPEN;

  $genehmigen = r("artikel.genehmigen.blogeinträge");

  if ($bearbeiten || $loeschen || $genehmigen) {$aktionen = true;}

  $gruppen = cms_gruppeninfos_generieren($dbs);

  $code = "";
  $schulhofblogeintraege = "";

  $jahraktuellb = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;
  $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, genehmigt, aktiv, oeffentlichkeit, datum, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM blogeintraege WHERE (datum BETWEEN $jahraktuellb AND $jahraktuelle) ORDER BY datum DESC";

  if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
    while ($daten = $anfrage->fetch_assoc()) {
      if ($daten['genehmigt'] == 1) {$klasse = '';} else {$klasse = ' class="cms_vorlaeufig"';}
      $schulhofblogeintraege .= '<tr'.$klasse.'><td><img src="res/icons/klein/blog.png"></td><td>'.$daten['bezeichnung'].'</td>';
      $zuordnungen = "";
      foreach ($CMS_GRUPPEN as $g) {
        $gk = cms_textzudb($g);
        $sql = "SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."blogeintraege JOIN $gk ON gruppe = id WHERE blogeintrag = ".$daten['id'].") AS x ORDER BY bezeichnung ASC";
        if ($anfrage2 = $dbs->query($sql)) {  // Safe weil interne ID
          while ($z = $anfrage2->fetch_assoc()) {
            $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$z['bezeichnung']."</span><img src=\"res/gruppen/klein/".$z['icon']."\"></span> "; ;
          }
          $anfrage2->free();
        }
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
      if ($bearbeiten) {
        $schulhofblogeintraege .= "<span class=\"cms_aktion_klein\" onclick=\"cms_blogeintraege_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Website/Blogeinträge')\"><span class=\"cms_hinweis\">Blogeintrag bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
      }
      if ($loeschen) {
        $schulhofblogeintraege .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_blogeintraege_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."', 'Schulhof/Website/Blogeinträge')\"><span class=\"cms_hinweis\">Blogeintrag löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
      }
      $schulhofblogeintraege .= '</td>';
      $schulhofblogeintraege .= '</tr>';
    }
    $anfrage->free();
    if (strlen($schulhofblogeintraege) == 0) {
      $spalten = 6;
      if ($aktionen) {$spalten++;}
      $code .= "<tr><td colspan=\"$spalten\" class=\"cms_notiz\">-- keine Blogeinträge vorhanden --</td></tr>";
    }
    else {$code .= $schulhofblogeintraege;}
  }

  return $code;
}
?>
