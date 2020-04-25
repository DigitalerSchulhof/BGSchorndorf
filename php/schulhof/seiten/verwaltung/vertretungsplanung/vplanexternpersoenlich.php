<?php
function cms_vertretungsplan_extern_persoenlich() {
  global $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;

  $code = "";

  if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'e') || ($CMS_BENUTZERART == 'x')) {
    return $code;
  }

  $kuerzel = null;
  $klassen = array();

  // Art des Benutzers ermitteln
  $dbs = cms_verbinden('s');
  if ($CMS_BENUTZERART == 'l') {
    $sql = $dbs->prepare("SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer WHERE id = ?");
    $sql->bind_param("i", $CMS_BENUTZERID);
    if ($sql->execute()) {
      $sql->bind_result($kuerzel);
      $sql->fetch();
    }
    $sql->close();
  }
  else if ($CMS_BENUTZERART == 's') {
    $jetzt = time();
    // HEUTIGE KLASSE SUCHEN
    $sql = $dbs->prepare("SELECT id from schuljahre WHERE beginn <= ? AND ende >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    $schuljahr = "";
    if ($sql->execute()) {
      $sql->bind_result($schuljahr);
      $sql->fetch();
    }
    $sql->close();

    if ($schuljahr != "") {
      $sql = $dbs->prepare("SELECT * FROM (SELECT klassen.id AS id, AES_DECRYPT(klassenbezextern, '$CMS_SCHLUESSEL') AS klasse, AES_DECRYPT(stufenbezextern, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenmitglieder ON klassen.id = klassenmitglieder.gruppe WHERE schuljahr = ? AND person = ?) AS x ORDER BY stufe ASC, klasse ASC");
      $sql->bind_param("ii", $schuljahr, $CMS_BENUTZERID);
      if ($sql->execute()) {
        $sql->bind_result($kid, $kbez, $sbez);
        while ($sql->fetch()) {
          $D = array();
          $D['id'] = $kid;
          $D['klasse'] = $kbez;
          $D['stufe'] = $sbez;
          array_push($klassen, $D);
        }
      }
      $sql->close();
    }
  }
  cms_trennen($dbs);

  if ((is_file($CMS_EINSTELLUNGEN['Vertretungsplan Schüler aktuell']) && is_file($CMS_EINSTELLUNGEN['Vertretungsplan Schüler Folgetag']) && is_file($CMS_EINSTELLUNGEN['Vertretungsplan Lehrer aktuell']) && is_file($CMS_EINSTELLUNGEN['Vertretungsplan Lehrer Folgetag'])) && (!is_null($kuerzel) || (count($klassen) > 0))) {

    $gesetzt = false;

    if (($CMS_BENUTZERART == 'l') && !is_null($kuerzel)) {
      $heute = file_get_contents_utf8($CMS_EINSTELLUNGEN['Vertretungsplan Lehrer aktuell']);
      $datumheute = cms_vertretungsplan_extern_datum_auslesen($heute);
      $infoheute = cms_vertretungsplan_extern_information_auslesen($heute);
      $infoheute = $infoheute['info'];

      $morgen = file_get_contents_utf8($CMS_EINSTELLUNGEN['Vertretungsplan Lehrer Folgetag']);
      $datummorgen = cms_vertretungsplan_extern_datum_auslesen($morgen);
      $infomorgen = cms_vertretungsplan_extern_information_auslesen($morgen);
      $infomorgen = $infomorgen['info'];

      $planpersoenlichheute = cms_vertretungsplan_extern_plan_persoenlich_lehrer($heute, $kuerzel);
      $planpersoenlichmorgen = cms_vertretungsplan_extern_plan_persoenlich_lehrer($morgen, $kuerzel);
      $gesetzt = true;
    }
    /*else if ($CMS_BENUTZERART == 's') {
      $planpersoenlichheute = cms_meldung('warnung', '<h4>Im Moment wird leider ein alter Vertretungsplan ausgelesen!! Ab Montag sollte es wieder funktionieren.</p>');
      $infoheute = "";
      $planpersoenlichmorgen = cms_meldung('warnung', '<h4>Im Moment wird leider ein alter Vertretungsplan ausgelesen!! Ab Montag sollte es wieder funktionieren.</p>');
      $infomorgen = "";
      $gesetzt = true;
    }*/
    else if (($CMS_BENUTZERART == 's') && (count($klassen) > 0)) {
      $heute = file_get_contents_utf8($CMS_EINSTELLUNGEN['Vertretungsplan Schüler aktuell']);
      $datumheute = cms_vertretungsplan_extern_datum_auslesen($heute);
      $infoheute = cms_vertretungsplan_extern_information_auslesen($heute);
      $infoheute = $infoheute['info'];

      $morgen = file_get_contents_utf8($CMS_EINSTELLUNGEN['Vertretungsplan Schüler Folgetag']);
      $datummorgen = cms_vertretungsplan_extern_datum_auslesen($morgen);
      $infomorgen = cms_vertretungsplan_extern_information_auslesen($morgen);
      $infomorgen = $infomorgen['info'];

      $planpersoenlichheute = cms_vertretungsplan_extern_plan_persoenlich_schueler($heute, $klassen);
      $planpersoenlichmorgen = cms_vertretungsplan_extern_plan_persoenlich_schueler($morgen, $klassen);
      $gesetzt = true;
    }

    if ($gesetzt) {
      if ((strlen($infoheute) > 0) || (strlen($planpersoenlichheute) > 0)) {
        $code .= "<h3>".$datumheute."</h3>";
        $code .= $infoheute;
        $code .= $planpersoenlichheute;
      }

      if ((strlen($infomorgen) > 0) || (strlen($planpersoenlichmorgen) > 0)) {
        $code .= "<h3>".$datummorgen."</h3>";
        $code .= $infomorgen;
        $code .= $planpersoenlichmorgen;
      }
    }
  }
  return $code;
}
?>
