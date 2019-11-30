<?php

function cms_maxpos_spalte($dbs, $spalte) {
  $max = 0;
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare');
  foreach ($elemente as $e) {
    $sql = "SELECT MAX(position) AS max FROM $e WHERE spalte = $spalte";
    if ($anfrage = $dbs->query($sql)) { // TODO: Irgendwie safe machen
      if ($daten = $anfrage->fetch_assoc()) {
        if ($daten['max'] > $max) {$max = $daten['max'];}
      }
      $anfrage->free();
    }
  }
  return $max;
}

function cms_elemente_verschieben_einfuegen($dbs, $spalte, $position) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare');
  foreach ($elemente as $e) {
    $sql = "UPDATE $e SET position = position + 1 WHERE spalte = $spalte AND position >= $position";
    $dbs->query($sql);  // TODO: Irgendwie safe machen
  }
}

function cms_elemente_verschieben_loeschen($dbs, $spalte, $position) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare');
  foreach ($elemente as $e) {
    $sql = "UPDATE $e SET position = position - 1 WHERE spalte = $spalte AND position >= $position";
    $dbs->query($sql);  // TODO: Irgendwie safe machen
  }
}

function cms_elemente_verschieben_aendern($dbs, $spalte, $altpos, $neupos) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare');

  if ($altpos < $neupos) {
    // Vorgängerelemente nachrutschen lassen
    foreach ($elemente as $e) {
      $sql = "UPDATE $e SET position = position - 1 WHERE spalte = '$spalte' AND position > $altpos AND position <= $neupos";
      $dbs->query($sql);  // TODO: Irgendwie safe machen
    }
  }
  else if ($altpos > $neupos) {
    // Nachfolgende Elemente aufrutschen lassen
    foreach ($elemente as $e) {
      $sql = "UPDATE $e SET position = position + 1 WHERE spalte = '$spalte' AND position >= $neupos AND position < $altpos";
      $dbs->query($sql);  // TODO: Irgendwie safe machen
    }
  }
}

?>
