<?php

function cms_postfach_empfaengerpool_generieren($dbs) {
  global $CMS_EINSTELLUNGEN, $CMS_BENUTZERID, $CMS_BENUTZERART, $CMS_SCHLUESSEL, $CMS_GRUPPEN;
  $empfaengerpool = array();
  $sql = "";
  $limit = 4;

  if ($CMS_BENUTZERART == 'l') {
    $benutzergruppe = 'Lehrer';
    $limit = 1;
  }
  else if ($CMS_BENUTZERART == 'v') {
    $benutzergruppe = 'Verwaltungsangestellte';
    $limit = 2;
  }
  else if ($CMS_BENUTZERART == 's') {
    $benutzergruppe = 'Schüler';
    $limit = 3;
  }
  else if ($CMS_BENUTZERART == 'e') {
    $benutzergruppe = 'Eltern';
    $limit = 3;
  }
  else if ($CMS_BENUTZERART == 'x') {
    $benutzergruppe = 'Externe';
    $limit = 4;
  }

  if ($CMS_EINSTELLUNGEN['Postfach - '.$benutzergruppe.' dürfen Lehrer schreiben'] == 1) {$sql .= " UNION (SELECT id FROM personen WHERE art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL'))";}
  if ($CMS_EINSTELLUNGEN['Postfach - '.$benutzergruppe.' dürfen Verwaltungsangestellte schreiben'] == 1) {$sql .= " UNION (SELECT id FROM personen WHERE art = AES_ENCRYPT('v', '$CMS_SCHLUESSEL'))";}
  if ($CMS_EINSTELLUNGEN['Postfach - '.$benutzergruppe.' dürfen Schüler schreiben'] == 1) {$sql .= " UNION (SELECT id FROM personen WHERE art = AES_ENCRYPT('s', '$CMS_SCHLUESSEL'))";}
  if ($CMS_EINSTELLUNGEN['Postfach - '.$benutzergruppe.' dürfen Eltern schreiben'] == 1) {$sql .= " UNION (SELECT id FROM personen WHERE art = AES_ENCRYPT('e', '$CMS_SCHLUESSEL'))";}
  if ($CMS_EINSTELLUNGEN['Postfach - '.$benutzergruppe.' dürfen Externe schreiben'] == 1) {$sql .= " UNION (SELECT id FROM personen WHERE art = AES_ENCRYPT('x', '$CMS_SCHLUESSEL'))";}

  foreach ($CMS_GRUPPEN AS $g) {
    if ($CMS_EINSTELLUNGEN["Postfach - $benutzergruppe dürfen $g Mitglieder schreiben"] == 1) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT person AS id FROM $gk"."mitglieder WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID))";
    }
  }
  foreach ($CMS_GRUPPEN AS $g) {
    if ($CMS_EINSTELLUNGEN["Postfach - $benutzergruppe dürfen $g Vorsitzende schreiben"] == 1) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT person AS id FROM $gk"."vorsitz WHERE (gruppe IN (SELECT id AS gruppe FROM $gk WHERE sichtbar >= $limit) OR gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID)))";
    }
  }
  if ($CMS_EINSTELLUNGEN["Postfach - $benutzergruppe dürfen $g Aufsicht schreiben"] == 1) {
    foreach ($CMS_GRUPPEN AS $g) {
      $gk = cms_textzudb($g);
      $sql .= " UNION (SELECT person AS id FROM $gk"."aufsicht WHERE (gruppe IN (SELECT id AS gruppe FROM $gk WHERE sichtbar >= $limit) OR gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID)))";
    }
  }

  if (strlen($sql) > 0) {
    $sql = "(".substr($sql,7).")";

    $sql = "SELECT DISTINCT x.id AS id FROM ($sql) AS x JOIN nutzerkonten ON x.id = nutzerkonten.id WHERE x.id != $CMS_BENUTZERID";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        array_push($empfaengerpool, $daten['id']);
      }
      $anfrage->free();
    }
    return $empfaengerpool;
  }
  else {return $empfaengerpool;}
}

?>
