<?php
function cms_notifikationen_ausgeben($dbs, $person) {
  global $CMS_SCHLUESSEL;
  $neuigkeiten = "";
  $sql = "SELECT id, AES_DECRYPT(gruppe, '$CMS_SCHLUESSEL') AS gruppe, gruppenid, status, art, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link FROM notifikationen WHERE person = $person ORDER BY zeit DESC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      if (($daten['status'] == 'l') || ($daten['status'] == 'a')) {$event = " onclick=\"cms_neuigkeit_schliessen('".$daten['id']."')\"";}
      else {$event = " onclick=\"cms_link('".$daten['link']."')\"";}
      $neuigkeiten .= "<li class=\"cms_neuigkeit\">";
        $gruppe = cms_notifikation_gruppendetails($dbs, $daten['gruppe'], $daten['gruppenid']);
        $neuigkeiten .= "<span class=\"cms_neuigkeit_icon\"><img src=\"".$gruppe['icon']."\"></span> ";
        $art = cms_notifikation_art_ermitteln($daten['status'],$daten['art']);
        $neuigkeiten .= "<span class=\"cms_neuigkeit_inhalt\"><h4>".$gruppe['bezeichnung']."<br>$art</h4></p>";
        $neuigkeiten .= "<p>".$daten['titel']."</p><p class=\"cms_neuigkeit_vorschau\">".$daten['vorschau']."</p></span>";
        $neuigkeiten .= "<span class=\"cms_neuigkeit_schliessen cms_button_nein\" onclick=\"cms_neuigkeit_schliessen('".$daten['id']."')\"><span class=\"cms_hinweis\">Neuigkeit schließen</span>&times;</span>";
        if (($daten['status'] != 'l') && ($daten['status'] != 'a')) {
          $neuigkeiten .= "<span class=\"cms_neuigkeit_oeffnen cms_button_ja\" onclick=\"cms_link('".$daten['link']."')\"><span class=\"cms_hinweis\"> Neuigkeit öffnen</span>»</span>";
        }
        $neuigkeiten .= "</li>";
    }
    $anfrage->free();
  }

  return $neuigkeiten;
}


function cms_notifikation_art_ermitteln($status, $art) {
  $rart = "";
  if ($status == 'n') {
    if ($art == 'b') {$rart = 'Neuer Blogeintrag';}
    else if ($art == 't') {$rart = 'Neuer Termin';}
    else if ($art == 'g') {$rart = 'Neue Galerie';}
  }
  else if ($status == 'l') {
    if ($art == 'b') {$rart = 'Blogeintrag gelöscht';}
    else if ($art == 't') {$rart = 'Termin gelöscht';}
    else if ($art == 'g') {$rart = 'Galerie gelöscht';}
  }
  else if ($status == 'b') {
    if ($art == 'b') {$rart = 'Blogeintrag bearbeitet';}
    else if ($art == 't') {$rart = 'Termin bearbeitet';}
    else if ($art == 'g') {$rart = 'Galerie bearbeitet';}
  }
  else if ($status == 'g') {
    if ($art == 'b') {$rart = 'Blogeintrag genehmigt';}
    else if ($art == 't') {$rart = 'Termin genehmigt';}
    else if ($art == 'g') {$rart = 'Galerie genehmigt';}
  }
  else if ($status == 'a') {
    if ($art == 'b') {$rart = 'Blogeintrag abgelehnt';}
    else if ($art == 't') {$rart = 'Termin abgelehnt';}
    else if ($art == 'g') {$rart = 'Galerie abgelehnt';}
  }
  return $rart;
}

function cms_notifikation_gruppendetails($dbs, $gruppe, $gruppenid) {
  global $CMS_SCHLUESSEL;
  $rueckgabe['icon'] = "res/icons/gross/weiss.png";
  $rueckgabe['bezeichnung'] = "Unbekannte Gruppe";
  if ($gruppe == 'Termine') {
    $rueckgabe['icon'] = "res/icons/gross/termine.png";
    $rueckgabe['bezeichnung'] = "Öffentliche Termine";
  }
  else if ($gruppe == 'Blogeinträge') {
    $rueckgabe['icon'] = "res/icons/gross/blog.png";
    $rueckgabe['bezeichnung'] = "Öffentliche Blogeinträge";
  }
  else if ($gruppe == 'Galerien') {
    $rueckgabe['icon'] = "res/icons/gross/galerien.png";
    $rueckgabe['bezeichnung'] = "Galerien";
  }
  else {
    $gk = cms_textzudb($gruppe);
    $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk WHERE id = $gruppenid";
    if ($anfrage = $dbs->query($sql)) {
      if ($daten = $anfrage->fetch_assoc()) {
        $rueckgabe['icon'] = "res/gruppen/gross/".$daten['icon'];
        $rueckgabe['bezeichnung'] = $daten['bezeichnung'];
      }
    }
  }
  return $rueckgabe;
}
?>
