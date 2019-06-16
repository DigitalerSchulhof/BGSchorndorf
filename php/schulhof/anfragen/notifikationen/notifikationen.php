<?php
/* Eintrag ist ein Array bestehend aus:
   $eintrag['gruppe']    -- Termine, Blogeinträge, Galerien, Hausmeister oder Gruppentitel (Gremien, Fachschaften, etc.)
   $eintrag['gruppenid'] -- Welche der jeweiligen Gruppen
                         -- bei Terminen, Blogeinträgen und Galerien wird der Öffentlichkeitsgrad übergeben
                         -- bei Terminen, Blogeinträgen und Galerien anschließend 0 eingetragen
                         -- bei Hausmeister wird 0 übergeben
   $eintrag['zielid']    -- Ziel der Notifikation - ID des Termins, des Blogeintrags, der Galerie, des Hausmeister
                         -- Bei Löschungen null
   $eintrag['status']    -- [n]eu, [b]earbeitet, ge[l]öscht, [g]enehmigt, [a]bgelehnt, [e]rledigt, [w]iedereröffnet
   $eintrag['art']       -- [t]ermin, [b]log, [g]alerie, [a]uftrag
   $eintrag['titel']     -- Angezeigter Titel in der Notifikation
   $eintrag['vorschau']  -- Angezeigte Vorschau in der Notifikation
   $eintrag['link']      -- Link der zur Volldarstellung führt
*/
function cms_notifikation_senden($dbs, $eintrag, $ausnahme) {
  global $CMS_SCHLUESSEL, $CMS_SCHULE, $CMS_ORT, $CMS_MAILZ, $CMS_MAILSIGNATUR, $CMS_RECHTE, $CMS_GRUPPEN;
  $gruppek = cms_textzudb($eintrag['gruppe']);

  // ALTE NOTIFIKATION ZU DIESEM THEMA LÖSCHEN
  $loeschgruppenid = $eintrag['gruppenid'];
  if (($gruppek == 'termine') || ($gruppek == 'blogeinträge') || ($gruppek == 'blogeintraege') || ($gruppek == 'galerien') || ($gruppek == 'hausmeister')) {
    $loeschgruppenid = 0;
  }
  $sql = $dbs->prepare("DELETE FROM notifikationen WHERE gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND gruppenid = ? AND zielid = ?");
  $sql->bind_param("sii", $eintrag['gruppe'], $loeschgruppenid, $eintrag['zielid']);
  $sql->execute();
  $sql->close();

  // Alle Empfänger von Notifikationen suchen
  $empfaenger = array();
  $spalten = "letztenotifikation, AES_DECRYPT(notifikationsmail, '$CMS_SCHLUESSEL') AS notifikationsmail, letzteanmeldung, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email";

  if (($gruppek == "termine") || ($gruppek == "blogeinträge") || ($gruppek == "blogeintraege") || ($gruppek == "galerien")) {
    $sql = $dbs->prepare(cms_notifikationsempfaenger_oeffentlich($dbs, $eintrag, $ausnahme, $spalten));
    $eintrag['gruppenid'] = 0;
    $sql->bind_param("i", $ausnahme);
  }
  else if (in_array($eintrag['gruppe'], $CMS_GRUPPEN)) {
    $sql = $dbs->prepare("SELECT $gruppek"."notifikationsabo.person AS id, $spalten FROM $gruppek"."notifikationsabo JOIN nutzerkonten ON nutzerkonten.id = $gruppek"."notifikationsabo.person JOIN personen_einstellungen ON personen_einstellungen.person = nutzerkonten.id JOIN personen ON personen.id = nutzerkonten.id WHERE gruppe = ? AND personen.id != ?");
    $sql->bind_param("ii", $eintrag['gruppenid'], $ausnahme);
  }
  else if ($gruppek == "hausmeister") {
    $sql = $dbs->prepare("SELECT hausmeisterauftraege.idvon AS id, $spalten FROM hausmeisterauftraege JOIN nutzerkonten ON nutzerkonten.id = hausmeisterauftraege.idvon JOIN personen_einstellungen ON personen_einstellungen.person = nutzerkonten.id JOIN personen ON personen.id = nutzerkonten.id");
  }

  if ($sql->execute()) {
    $empfaenger = array();
    $sql->bind_result($sid, $sletztenotifikation, $snotifikationsmail, $sletzteanmeldung, $svorname, $snachname, $stitel, $sart, $sgeschlecht, $semail);
    while($sql->fetch()) {
      $e['id'] = $sid;
      $e['notifikationsmail'] = $snotifikationsmail;
      $e['letzteanmeldung'] = $sletzteanmeldung;
      $e['letztenotifikation'] = $sletztenotifikation;
      $e['vorname'] = $svorname;
      $e['nachname'] = $snachname;
      $e['titel'] = $stitel;
      $e['art'] = $sart;
      $e['geschlecht'] = $sgeschlecht;
      $e['email'] = $semail;
      array_push($empfaenger, $e);
    }
    $sql->close();
    $jetzt = time();
    // Nach dem Löschen gibt es keine Zielid mehr
    if (($eintrag['art'] == 'l') || ($eintrag['art'] == 'a')) {$eintrag['zielid'] = 'NULL';}

    $sqlnot = $dbs->prepare("UPDATE notifikationen SET person = ?, zeit = ?, gruppe = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), gruppenid = ?, zielid = ?, status = ?, art = ?, titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vorschau = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), link = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
    $sqlnut = $dbs->prepare("UPDATE nutzerkonten SET letztenotifikation = ? WHERE id = ?");

    foreach ($empfaenger as $e) {
      // Notifikation eintragen
      $id = cms_generiere_kleinste_id('notifikationen');
      $sqlnot->bind_param("iisiisssssi", $e['id'], $jetzt, $eintrag['gruppe'], $eintrag['gruppenid'], $eintrag['zielid'], $eintrag['status'], $eintrag['art'], $eintrag['titel'], $eintrag['vorschau'], $eintrag['link'], $id);
      $sqlnot->execute();

      $sqlnut->bind_param("ii", $jetzt, $e['id']);
      $sqlnut->execute();

      // MAILBENACHRICHTIGUNG
      if (($e['notifikationsmail'] == 1) && ($e['letzteanmeldung'] > $e['letztenotifikation'])) {
        $betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Es gibt was Neues!';

        $anrede = cms_mail_anrede($e['titel'], $e['vorname'], $e['nachname'], $e['art'], $e['geschlecht']);
        $text;
        for ($i=0; $i<2; $i++) {
          $text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
          $text[$i] = $text[$i].'Im Schulhof haben sich Inhalte geändert. Wo genau, muss aus Datenschutzgründen den Neuigkeiten auf der Anmeldeseite entnommen werden.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
          $text[$i] = $text[$i].'Mails wie diese können in den Einstellungen des Nutzerkontos deaktiviert werden.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
          $text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
        }

        // Mail verschicken:
        $mailerfolg = cms_mailsenden(cms_generiere_anzeigename($e['vorname'], $e['nachname'], $e['titel']), $e['email'], $betreff, $text[1], $text[0]);
      }
    }
    $sqlnot->close();
    $sqlnut->close();
  }

}


function cms_notifikationsempfaenger_oeffentlich($dbs, $eintrag, $ausnahme, $spalten) {
  global $CMS_SCHLUESSEL, $CMS_GRUPPEN;
  $gruppek = cms_textzudb($eintrag['gruppe']);
  $einstellungsspalte = "";
  if ($gruppek = "termine") {$einstellungsspalte = "oeffentlichertermin";}
  else if ($gruppek = "blogeintraege") {$einstellungsspalte = "oeffentlicherblog";}
  else if ($gruppek = "galerien") {$einstellungsspalte = "oeffentlichegalerie";}
  else {return "";}

  // sql zusammenbauen
  // Tabellen
  $rsql = "SELECT nutzerkonten.id AS id, $spalten FROM personen_einstellungen JOIN nutzerkonten ON personen_einstellungen.person = nutzerkonten.id JOIN personen ON personen.id = nutzerkonten.id WHERE $einstellungsspalte = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND nutzerkonten.id != ?";

  $hinzufuegen = "";
  if ($eintrag['gruppenid'] < 3) {
    // Zugrordnete Gruppen des Termins ermitteln
    $sql = "";
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT gruppe AS gruppenid, '$gk' AS gruppe FROM $gk"."termine WHERE termin = ".$eintrag['zielid'].")";
    }
    $sql = substr($sql, 7);
    $sql = "SELECT DISTINCT * FROM ($sql) AS x";
    $beteiligtegruppen = array();
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        array_push($beteiligtegruppen, $daten);
      }
      $anfrage->free();
    }

    if (count($beteiligtegruppen) > 0) {
      // Mitglieder ermitteln
      $sql = "";
      foreach ($beteiligtegruppen AS $b) {
        $sql = " UNION (SELECT person AS id FROM ".$b['gruppe']."mitglieder WHERE gruppe = ".$b['gruppenid'].") UNION (SELECT person AS id FROM ".$b['gruppe']."aufsicht WHERE gruppe = ".$b['gruppenid'].")";
      }
      $sql = substr($sql, 7);
      // Gruppenmitglieder bestimmen
      $sql = "SELECT DISTINCT id FROM ($sql) AS x";

      $erlaubtepersonen = "";
      if ($anfrage = $dbs->query($sql)) {
        while ($daten = $anfrage->fetch_assoc()) {
          $erlaubtepersonen .= ",".$daten['id'];
        }
        $anfrage->free();
      }
      $hinzufuegen = " OR nutzerkonten.id IN (".substr($erlaubtepersonen, 1).")";
    }

    if ($eintrag['gruppenid'] > 1) {$hinzufuegen .= " OR art = AES_ENCRYPT('v', '$CMS_SCHLUESSEL')";}
    if ($eintrag['gruppenid'] > 0) {$hinzufuegen .= " OR art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')";}

    if (strlen($hinzufuegen) > 0) {
      $hinzufuegen = substr($hinzufuegen, 4);
      $rsql .= " AND ($hinzufuegen)";
    }
  }

  return $rsql;
}
?>
