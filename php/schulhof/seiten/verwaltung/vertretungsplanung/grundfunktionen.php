<?php
function cms_vertretungsplan_stunde_einsortieren($alt, $neu) {
  $spalte = 0;
  $einsortiert = false;
  while (!$einsortiert) {
    if (isset($alt[$spalte])) {
      if ($alt[$spalte][count($alt[$spalte])-1]['ende'] < $neu['beginn']) {
        $alt[$spalte][count($alt[$spalte])] = $neu;
        $einsortiert = true;
      }
    }
    else {
      // Spalte existiert nicht, anlegen und als ersten Eintrag eintragen
      $alt[$spalte][0] = $neu;
      $einsortiert = true;
    }
    $spalte++;
  }
  return $alt;
}

function cms_vertretungsplan_tag_minmax($stunden) {
  $daten['min'] = null;
  $daten['max'] = null;
  foreach ($stunden AS $spalte) {
    if (is_null($daten['min'])) {
      $daten['min'] = $spalte[0]['beginn'];
      $daten['max'] = $spalte[0]['ende'];
    }
    foreach ($spalte AS $std) {
      $daten['min'] = min($daten['min'], $std['beginn']);
      $daten['max'] = max($daten['max'], $std['ende']);
    }
  }
  return $daten;
}
?>
