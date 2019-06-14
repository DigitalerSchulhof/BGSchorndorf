<?php
function cms_schulhof_download_ausgeben($e) {
  // Inaktiv für den Benutzer
  $code = "";
  $pfad = $e['pfad'];
  $titel = $e['titel'];
  $zusatzklasse = "";
  $beschreibung = $e['beschreibung'];
  $dateiname = $e['dateiname'];
  $dateigroesse = $e['dateigroesse'];
  $event = " onclick=\"cms_download('$pfad')\"";
  $aktiv = true;
  if (!is_file($pfad)) {$zusatzklasse = " cms_download_inaktiv"; $event = ""; $aktiv = false;}
  $dname = explode('/', $pfad);
  $dname = $dname[count($dname)-1];
  $endung = explode('.', $dname);
  $endung = $endung[count($endung)-1];
  $icon = cms_dateisystem_icon($endung);
  $code .= "<div class=\"cms_download_anzeige$zusatzklasse\" style=\"background-image: url('res/dateiicons/gross/".$icon."');\"$event>";
    $code .= "<h4>$titel</h4>";
    if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
    $info = "";
    if ($aktiv) {
      if ($dateiname == 1) {$info .= ' - '.$dname;}
      if ($dateigroesse == 1) {$info .= ' - '.cms_groesse_umrechnen(filesize($pfad));}
    }
    else {$info = "Die Datei existiert nicht mehr.";}
    if (strlen($info) > 0) {
      $info = substr($info, 3);
      $code .= "<p class=\"cms_notiz\">".$info."</p>";
    }
  $code .= "</div>";
  return $code;
}

function cms_schulhof_interndownload_ausgeben($e) {
  // Inaktiv für den Benutzer
  $code = "";
  $pfad = implode("/", array_slice(explode("/", $e['pfad']), 1));
  $titel = $e['titel'];
  $zusatzklasse = "";
  $beschreibung = $e['beschreibung'];
  $dateiname = $e['dateiname'];
  $dateigroesse = $e['dateigroesse'];
  $gruppenid = $e['gruppenid'];
  $event = " onclick=\"cms_herunterladen('s', 'schulhof', '$gruppenid', '$pfad')\"";
  $aktiv = true;
  if (!is_file('dateien/'.$pfad)) {$zusatzklasse = " cms_download_inaktiv"; $event = ""; $aktiv = false;}
  $dname = explode('/', $pfad);
  $dname = $dname[count($dname)-1];
  $endung = explode('.', $dname);
  $endung = $endung[count($endung)-1];
  $icon = cms_dateisystem_icon($endung);
  $code .= "<div class=\"cms_download_anzeige$zusatzklasse\" style=\"background-image: url('res/dateiicons/gross/".$icon."');\"$event>";
    $code .= "<h4>$titel</h4>";
    if (strlen($beschreibung) > 0) {$code .= "<p>$beschreibung</p>";}
    $info = "";
    if ($aktiv) {
      if ($dateiname == 1) {$info .= ' - '.$dname;}
      if ($dateigroesse == 1) {$info .= ' - '.cms_groesse_umrechnen(filesize('dateien/'.$pfad));}
    }
    else {$info = "Die Datei existiert nicht mehr.";}
    if (strlen($info) > 0) {
      $info = substr($info, 3);
      $code .= "<p class=\"cms_notiz\">".$info."</p>";
    }
  $code .= "</div>";
  return $code;
}
?>
