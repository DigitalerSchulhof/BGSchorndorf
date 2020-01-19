<?php

function cms_maxpos_spalte($dbs, $spalte) {
  $max = 0;
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter');
  foreach ($elemente as $e) {
    $sql = $dbs->prepare("SELECT MAX(position) AS max FROM $e WHERE spalte = ?");
    $sql->bind_param("i", $spalte);
    if ($sql->execute()) {
      $sql->bind_result($max);
      $sql->fetch();
    }
    $sql->close();
  }
  return $max;
}

function cms_elemente_verschieben_einfuegen($dbs, $spalte, $position) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter');
  foreach ($elemente as $e) {
    $sql = $dbs->prepare("UPDATE $e SET position = position + 1 WHERE spalte = ? AND position >= ?");
    $sql->bind_param("ii", $spalte, $position);
    $sql->execute();
    $sql->close();
  }
}

function cms_elemente_verschieben_loeschen($dbs, $spalte, $position) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter');
  foreach ($elemente as $e) {
    $sql = $dbs->prepare("UPDATE $e SET position = position - 1 WHERE spalte = ? AND position >= ?");
    $sql->bind_param("ii", $spalte, $position);
    $sql->execute();
    $sql->close();
  }
}

function cms_elemente_verschieben_aendern($dbs, $spalte, $altpos, $neupos) {
  $elemente = array('editoren', 'downloads', 'boxenaussen', 'eventuebersichten', 'kontaktformulare', 'wnewsletter');

  if ($altpos < $neupos) {
    // Vorgängerelemente nachrutschen lassen
    foreach ($elemente as $e) {
      $sql = $dbs->prepare("UPDATE $e SET position = position - 1 WHERE spalte = ? AND position > ? AND position <= ?");
      $sql->bind_param("iii", $spalte, $altpos, $neupos);
      $sql->execute();
      $sql->close();
    }
  }
  else if ($altpos > $neupos) {
    // Nachfolgende Elemente aufrutschen lassen
    foreach ($elemente as $e) {
      $sql = $dbs->prepare("UPDATE $e SET position = position + 1 WHERE spalte = ? AND position >= ? AND position < ?");
      $sql->bind_param("iii", $spalte, $neupos, $altpos);
      $sql->execute();
      $sql->close();
    }
  }
}

?>
