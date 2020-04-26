<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

if ($angemeldet) {
  $code = "";
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  // Benutzerschuljahr laden
  $SCHULJAHR = null;
  $sql = $dbs->prepare("SELECT schuljahr FROM nutzerkonten JOIN personen ON nutzerkonten.id = personen.id WHERE personen.id = ? AND personen.art = AES_ENCRYPT('l', '$CMS_SCHLUESSEL')");
  $sql->bind_param("i", $CMS_BENUTZERID);
  if ($sql->execute()) {
    $sql->bind_result($SCHULJAHR);
    $sql->fetch();
  }
  $sql->close();

  if ($SCHULJAHR !== null) {
    $CMS_EINSTELLUNGEN = cms_einstellungen_laden();
    $jetzt = time();

    // Kurse des Nutzers laden
    $EINTRAEGE = array();
    $sql = $dbs->prepare("SELECT id, tbeginn, tende FROM unterricht WHERE tkurs IN (SELECT DISTINCT kurse.id FROM kurse JOIN stufen ON kurse.stufe = stufen.id JOIN unterricht ON unterricht.tkurs = kurse.id WHERE kurse.schuljahr = ? AND stufen.tagebuch = 1 AND tlehrer = ?)");
    $sql->bind_param("ii", $SCHULJAHR, $CMS_BENUTZERID);
    if ($sql->execute()) {
      $sql->bind_result($uid, $ubeginn, $uende);
      while ($sql->fetch()) {
        $e = array();
        $e['id'] = $uid;
        $e['beginn'] = $ubeginn;
        if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == 's') {$e['ziel'] = $uende;}
        else if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == 't') {$e['ziel'] = mktime(23,59,59,date('n', $uende),date('j', $uende),date('Y', $uende));}
        else if ($CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'] == '-') {$e['ziel'] = $jetzt + 1;}
        else {$e['ziel'] = mktime(23,59,59,date('n', $uende),date('j', $uende) + $CMS_EINSTELLUNGEN['Tagebuch Frist Inhalt'],date('Y', $uende));}
        array_push($EINTRAEGE, $e);
      }
    }
    $sql->close();

    if (count($EINTRAEGE) > 0) {
      // Einträge schließen, die über dem Zeitlimit liegen
      // Einträge zählen, die offen sind
      $SCHLIESSEN = array();
      $offen = 0;
      $sql = $dbl->prepare("SELECT id FROM tagebuch WHERE freigabe = 0 AND id = ?");
      foreach ($EINTRAEGE AS $e) {
        $sql->bind_param("i", $e['id']);
        if ($sql->execute()) {
          $sql->bind_result($tid);
          if ($sql->fetch()) {
            if (($e['beginn'] < $jetzt) && ($e['ziel'] > $jetzt)) {
              $offen++;
            }
            else if ($e['ziel'] < $jetzt) {
              array_push($SCHLIESSEN, $tid);
            }
          }
        }
      }
      $sql->close();

      // Schliessen ausführen
      if (count($SCHLIESSEN) > 0) {
        $schlusssql = implode(",", $SCHLIESSEN);
        $sql = $dbl->prepare("UPDATE tagebuch SET freigabe = 2 WHERE freigabe = 0 AND id IN ($schlusssql)");
        $sql->execute();
        $sql->close();
      }
    }

    if ($offen == 0) {$code = 'keine';}
    else if ($offen == 1) {$code = "1 offener Eintrag";}
    else {$code = $offen." offene Einträge";}
  }
  else {
    $code = 'keine';
  }

  cms_trennen($dbl);
  cms_trennen($dbs);
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
