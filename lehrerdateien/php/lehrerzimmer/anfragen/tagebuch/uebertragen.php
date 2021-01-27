<?php
// LADEN
// Tagebücher
$TAGEBUCH = [];
$TIDS = [];
$sql = $dbs->prepare("SELECT id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL'), AES_DECRYPT(hausaufgabe, '$CMS_SCHLUESSEL'), leistungsmessung, urheber, eintragsdatum FROM tagebuch WHERE freigabe = 1");
if ($sql->execute()) {
  $sql->bind_result($tid, $tinhalt, $thausi, $tleistung, $turheber, $teintrag);
  while ($sql->fetch()) {
    $t = [];
    $t['id'] = $tid;
    $t['inhalt'] = $tinhalt;
    $t['hausi'] = $thausi;
    $t['leistung'] = $tleistung;
    $t['urheber'] = $turheber;
    $t['eintrag'] = $teintrag;
    array_push($TIDS, $tid);
    array_push($TAGEBUCH, $t);
  }
}
$sql->close();

// Lob und Tadel
$LOBTADEL = [];
$LTIDS = [];
$sql = $dbs->prepare("SELECT id, eintrag, person, art, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL'), urheber, eintragszeit FROM lobtadel WHERE eintrag IN (SELECT id FROM tagebuch WHERE freigabe = 1)");
if ($sql->execute()) {
  $sql->bind_result($ltid, $lteintrag, $ltperson, $ltart, $ltbem, $lturheber, $lteintragszeit);
  while ($sql->fetch()) {
    $lt = [];
    $lt['id'] = $ltid;
    $lt['eintrag'] = $lteintrag;
    $lt['person'] = $ltperson;
    $lt['art'] = $ltart;
    $lt['bem'] = $ltbem;
    $lt['urheber'] = $lturheber;
    $lt['eintragszeit'] = $lteintragszeit;
    array_push($LTIDS, $ltid);
    array_push($LOBTADEL, $lt);
  }
}
$sql->close();

// Fehlzeiten
// Fehlzeiten
$FEHLZEITEN = [];
$FZIDS = [];
$sql = $dbs->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSEL'), urheber, eintragszeit FROM fehlzeiten");
if ($sql->execute()) {
  $sql->bind_result($fzid, $fzperson, $fzvon, $fzbis, $fzbem, $fzurheber, $fzeintragszeit);
  while ($sql->fetch()) {
    $fz = [];
    $fz['id'] = $fzid;
    $fz['person'] = $fzperson;
    $fz['von'] = $fzvon;
    $fz['bis'] = $fzbis;
    $fz['bemerkung'] = $fzbem;
    $fz['urheber'] = $fzurheber;
    $fz['eintragszeit'] = $fzeintragszeit;
    array_push($FZIDS, $fzid);
    array_push($FEHLZEITEN, $fz);
  }
}
$sql->close();

// Falls Tagebucheinträge übernommen werden müssen
if (count($TIDS) > 0) {
  // Tagebucheinträge löschen
  $TIDS = "(".implode(",", $TIDS).")";
  $sql = $dbs->prepare("DELETE FROM tagebuch WHERE id IN $TIDS");
  $sql->execute();
  $sql->close();

  // Lob und Tadel löschen
  $sql = $dbs->prepare("DELETE FROM lobtadel WHERE eintrag IN $TIDS");
  $sql->execute();
  $sql->close();
  if (count($LTIDS) > 0) {
    $LTIDS = "(".implode(",", $LTIDS).")";
    $sql = $dbl->prepare("DELETE FROM lobtadel WHERE id IN $LTIDS");
    $sql->execute();
    $sql->close();
  }

  // Fehlzeiten löschen
  if (count($FZIDS) > 0) {
    $FZIDS = "(".implode(",", $FZIDS).")";
    $sql = $dbs->prepare("DELETE FROM fehlzeiten WHERE id IN $FZIDS");
    $sql->execute();
    $sql->close();
  }

  foreach ($TAGEBUCH as $t) {
    $kurs = null;
    $sql = $dbs->prepare("SELECT tkurs, tbeginn, tende FROM unterricht WHERE id = ?");
    $sql->bind_param("i", $t['id']);
    if ($sql->execute()) {
      $sql->bind_result($kurs, $beginn, $ende);
      $sql->fetch();
    }
    $sql->close();
    $nbis = $beginn-1;
    $nvon = $ende+1;

    if ($kurs !== null) {
      // Einträge übernehmen
      $anfrage = "INSERT INTO tagebuch (id, inhalt, hausaufgabe, leistungsmessung, urheber, eintragsdatum, freigabe)";
      $anfrage .= " VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), ?, ?, ?, 1)";
      $sql = $dbl->prepare($anfrage);
      $sql->bind_param("issiii", $t['id'], $t['inhalt'], $t['hausi'], $t['leistung'], $t['urheber'], $t['eintrag']);
      $sql->execute();
      $sql->close();

      // Lob und Tadel übernehmen
      $anfrage = "INSERT INTO lobtadel (id, eintrag, person, art, bemerkung, urheber, eintragszeit)";
      $anfrage .= " VALUES (?, ?, ?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), ?, ?)";
      $sql = $dbl->prepare($anfrage);
      foreach ($LOBTADEL as $lt) {
        if ($lt['eintrag'] == $t['id']) {
          $sql->bind_param("iiissii", $lt['id'], $lt['eintrag'], $lt['person'], $lt['art'], $lt['bem'], $lt['urheber'], $lt['eintragszeit']);
          $sql->execute();
        }
      }
      $sql->close();

      // Fehlzeiten der aktuellen Stunde entfernen
      // Mitglieder des Kurses laden
      $kurspersonen = [];
      $sql = $dbs->prepare("SELECT person FROM kursemitglieder WHERE gruppe = ?");
      $sql->bind_param("i", $kurs);
      if ($sql->execute()) {
        $sql->bind_result($kpers);
        while ($sql->fetch()) {
          array_push($kurspersonen, $kpers);
        }
      }
      $sql->close();

      // Überschneidungsfehlzeiten entfernen
      if (count($kurspersonen) > 0) {
        $anpassungfz = [];
        $kurspersonen = "(".implode(",", $kurspersonen).")";
        $sql = $dbl->prepare("SELECT id, person, von, bis, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL'), entschuldigt, urheber, eintragszeit FROM fehlzeiten WHERE ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?)) AND person IN $kurspersonen");
        $sql->bind_param("iiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende);
        if ($sql->execute()) {
          $sql->bind_result($aid, $apers, $avon, $abis, $abem, $aent, $aurheber, $aeintragszeit);
          while ($sql->fetch()) {
            $a = [];
            $a['id'] = $aid;
            $a['person'] = $apers;
            $a['von'] = $avon;
            $a['bis'] = $abis;
            $a['bemerkung'] = $abem;
            $a['entschuldigt'] = $aent;
            $a['urheber'] = $aurheber;
            $a['eintragszeit'] = $aeintragszeit;
            array_push($anpassungfz, $a);
          }
        }
        $sql->close();

        foreach ($anpassungfz AS $a) {
          // Überschreitet die Fehlzeit die ganze Stunde? -> Fehlzeit teilen
          if (($a['von'] < $beginn) && ($a['bis'] > $ende)) {
            // Fehlzeit-Ende auf Anfang der Stunde -1 setzen
            $sql = $dbl->prepare("UPDATE fehlzeiten SET bis = ? WHERE id = ?");
            $sql->bind_param("ii", $nbis, $a['id']);
            $sql->execute();
            $sql->close();
            // Neue Fehlzeit für die Restzeit einfügen
            $id = cms_generiere_kleinste_id("fehlzeiten", "l");
            $sql = $dbl->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), entschuldigt = ?, urheber = ?, eintragszeit = ? WHERE id = ?");
            $sql->bind_param("ii", $a['person'], $nvon, $a['bis'], $a['bemerkung'], $a['entschudligt'], $a['urheber'], $a['eintragszeit'], $id);
            $sql->execute();
            $sql->close();
          }
          // Ragt Fehlzeit in die Stunde hinein -> kürzen
          else if ($a['bis'] >= $beginn) {
            $sql = $dbl->prepare("UPDATE fehlzeiten SET bis = ? WHERE id = ?");
            $sql->bind_param("ii", $nbis, $a['id']);
            $sql->execute();
            $sql->close();
          } else if ($a['von'] <= $ende) {
            $sql = $dbl->prepare("UPDATE fehlzeiten SET von = ? WHERE id = ?");
            $sql->bind_param("ii", $nvon, $a['id']);
            $sql->execute();
            $sql->close();
          }
        }
      }

      // Fehlzeiten übernehmen
      // Prüfen, ob eine größere Fehlzeit bereits besteht
      foreach ($FEHLZEITEN as $fz) {
        if ($fz['von'] >= $beginn && $fz['bis'] <= $ende) {
          // Fehlzeiten mit direktem Anknüpfungspunkt vorne
          $fnbis = $fz['von']-1;
          $fnvon = $fz['bis']+1;
          $fzv = null;
          $fzvbem = null;
          $fzh = null;
          $fzhbem = null;
          // Fehlzeit endet mit Beginn der neuen Fehlzeit
          $sql = $dbl->prepare("SELECT id, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM fehlzeiten WHERE person = ? AND bis = ?");
          $sql->bind_param("ii", $fz['person'], $fnbis);
          if ($sql->execute()) {
            $sql->bind_result($fzv, $fzvbem);
            $sql->fetch();
          }
          $sql->close();
          // Fehlzeit beginnt mit Ende der neuen Fehlzeit
          $sql = $dbl->prepare("SELECT id, AES_DECRYPT(bemerkung, '$CMS_SCHLUESSELL') FROM fehlzeiten WHERE person = ? AND von = ?");
          $sql->bind_param("ii", $fz['person'], $fnvon);
          if ($sql->execute()) {
            $sql->bind_result($fzh, $fzhbem);
            $sql->fetch();
          }
          $sql->close();

          // Fehlzeit einpassen
          if (($fzv !== null) && ($fzh !== null)) {
            $sql = $dbl->prepare("UPDATE fehlzeiten SET bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), entschuldigt = 0, urheber = ?, eintragszeit = ? WHERE id = ?");
            $neuebemerkung = $fzvbem." – ".$fz['bemerkung'];
            $sql->bind_param("isiii", $fz['bis'], $neuebemerkung, $fz['urheber'], $fz['eintragszeit'], $fzv);
            $sql->execute();
            $sql->close();

            $sql = $dbl->prepare("DELETE FROM fehlzeiten WHERE id = ?");
            $sql->bind_param("i", $fzh);
            $sql->execute();
            $sql->close();
          } else if ($fzv !== null) { // Alte Fehlzeit später beenden
            $sql = $dbl->prepare("UPDATE fehlzeiten SET bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), entschuldigt = 0, urheber = ?, eintragszeit = ? WHERE id = ?");
            $neuebemerkung = $fzvbem." – ".$fz['bemerkung'];
            $sql->bind_param("isiii", $fz['bis'], $neuebemerkung, $fz['urheber'], $fz['eintragszeit'], $fzvon);
            $sql->execute();
            $sql->close();
          } else if ($fzh !== null) { // Alte Fehlzeit früher beginnen
            $sql = $dbl->prepare("UPDATE fehlzeiten SET von = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), entschuldigt = 0, urheber = ?, eintragszeit = ? WHERE id = ?");
            $neuebemerkung = $fz['bemerkung']." – ".$fzhbem;
            $sql->bind_param("isiii", $fz['von'], $neuebemerkung, $fz['urheber'], $fz['eintragszeit'], $fzbis);
          } else {  // Neue Fehlzeit eintragen
            $id = cms_generiere_kleinste_id("fehlzeiten", "l");
            $sql = $dbl->prepare("UPDATE fehlzeiten SET person = ?, von = ?, bis = ?, bemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSELL'), entschuldigt = 0, urheber = ?, eintragszeit = ? WHERE id = ?");
            $sql->bind_param("iiisiii", $fz['person'], $fz['von'], $fz['bis'], $fz['bemerkung'], $fz['urheber'], $a['eintragszeit'], $id);
            $sql->execute();
            $sql->close();
          }
        }
      }
    }
  }
}
?>
