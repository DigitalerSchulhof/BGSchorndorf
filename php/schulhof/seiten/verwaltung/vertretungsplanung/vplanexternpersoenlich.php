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
    $sql = "SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer WHERE id = $CMS_BENUTZERID";
    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      if ($daten = $anfrage->fetch_assoc()) {
        $kuerzel = $daten['kuerzel'];
      }
      $anfrage->free();
    }
  }
  else if ($CMS_BENUTZERART == 's') {
    $jetzt = time();
    // HEUTIGE KLASSE SUCHEN
    $sql = "SELECT id from schuljahre WHERE beginn <= $jetzt AND ende >= $jetzt";
    $schuljahr = "";
    if ($anfrage = $dbs->query($sql)) { // Safe weil keine Eingabe
      if ($daten = $anfrage->fetch_assoc()) {
        $schuljahr = $daten['id'];
      }
      $anfrage->free();
    }

    if ($schuljahr != "") {
      $sql = "SELECT * FROM (SELECT klassen.id AS id, AES_DECRYPT(klassenbezextern, '$CMS_SCHLUESSEL') AS klasse, AES_DECRYPT(stufenbezextern, '$CMS_SCHLUESSEL') AS stufe FROM klassen JOIN klassenmitglieder ON klassen.id = klassenmitglieder.gruppe WHERE schuljahr = $schuljahr AND person = $CMS_BENUTZERID) AS x ORDER BY stufe ASC, klasse ASC";
      if ($anfrage = $dbs->query($sql)) { // Safe weil interne ID
        while ($daten = $anfrage->fetch_assoc()) {
          array_push($klassen, $daten);
        }
        $anfrage->free();
      }
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
