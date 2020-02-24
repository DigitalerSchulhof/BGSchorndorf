<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['id']))          {$id = $_POST['id'];}                           else {cms_anfrage_beenden(); exit;}
if (isset($_POST['art']))         {$art = $_POST['art'];}                         else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($id,0)) {cms_anfrage_beenden(); exit;}
if (($art != 'l') && ($art != 'r') && ($art != 'k') && ($art != 's')) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if ($angemeldet && $zugriff) {
  $dbl = cms_verbinden('l');
  $dbs = cms_verbinden('s');
  $fehler = false;
  // Diese Ausplanung laden
  $beginn = null;
  $ende = null;
  $ziel = null;
  if ($art == 'l') {$sql = $dbl->prepare("SELECT von, bis, lehrer FROM ausplanunglehrer WHERE id = ?");}
  if ($art == 'r') {$sql = $dbl->prepare("SELECT von, bis, raum FROM ausplanungraeume WHERE id = ?");}
  if ($art == 'k') {$sql = $dbl->prepare("SELECT von, bis, klasse FROM ausplanungklassen WHERE id = ?");}
  if ($art == 's') {$sql = $dbl->prepare("SELECT von, bis, stufe FROM ausplanungstufen WHERE id = ?");}
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($beginn, $ende, $ziel);
    $sql->fetch();
  }
  $sql->close();

  $code = "";

  // Schulstunden laden
  if (($beginn !== null) && ($ende !== null) && ($ziel !== null) && (!$fehler)) {
    $SCHULSTUNDEN = array();
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM schulstunden WHERE zeitraum IN (SELECT id FROM zeitraeume WHERE (? BETWEEN beginn AND ende) OR (? BETWEEN beginn AND ende) OR (beginn <= ? AND ende >= ?))");
    $sql->bind_param("iiii", $beginn, $ende, $beginn, $ende);
    if ($sql->execute()) {
      $sql->bind_result($stdbez, $stdbeginns, $stdbeginnm);
      while ($sql->fetch()) {
        $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)] = $stdbez;
      }
    }
    else {$fehler = true;}
    $sql->close();
  }

  if (($beginn !== null) && ($ende !== null) && ($ziel !== null) && (!$fehler)) {
    $kids = array();
    $uids = array();
    // Suche Stunden, mit vorgemerkten Änderungen, die rückabgewickelt werden müssen
    if ($art == 'l') {
      $sql = $dbs->prepare("SELECT u.id AS uid, k.id AS kid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, k.tbeginn, k.vplanart FROM unterrichtkonflikt AS k LEFT JOIN unterricht AS u ON u.id = k.altid LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON k.tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON k.tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON k.traum = traeume.id LEFT JOIN kurse AS tkurse ON k.tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND plehrer = ? ORDER BY pbeginn DESC");
    }
    if ($art == 'r') {
      $sql = $dbs->prepare("SELECT u.id AS uid, k.id AS kid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, k.tbeginn, k.vplanart FROM unterrichtkonflikt AS k LEFT JOIN unterricht AS u ON u.id = k.altid LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON k.tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON k.tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON k.traum = traeume.id LEFT JOIN kurse AS tkurse ON k.tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND praum = ? ORDER BY pbeginn DESC");
    }
    if ($art == 'k') {
      $sql = $dbs->prepare("SELECT u.id AS uid, k.id AS kid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, k.tbeginn, k.vplanart FROM unterrichtkonflikt AS k LEFT JOIN unterricht AS u ON u.id = k.altid LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON k.tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON k.tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON k.traum = traeume.id LEFT JOIN kurse AS tkurse ON k.tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND pkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) ORDER BY pbeginn DESC");
    }
    if ($art == 'k') {
      $sql = $dbs->prepare("SELECT u.id AS uid, k.id AS kid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, k.tbeginn, k.vplanart FROM unterrichtkonflikt AS k LEFT JOIN unterricht AS u ON u.id = k.altid LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON k.tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON k.tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON k.traum = traeume.id LEFT JOIN kurse AS tkurse ON k.tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND pkurs IN (SELECT id FROM kurse WHERE stufe = ?) ORDER BY pbeginn DESC");
    }
    $sql->bind_param("iiiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende, $ziel);
    if ($sql->execute()) {
      $sql->bind_result($uid, $kid, $plvor, $plnach, $pltit, $plkurz, $praum, $pkurs, $tlvor, $tlnach, $tltit, $tlkurz, $traum, $tkurs, $pbeginn, $tbeginn, $vplanart);
      while ($sql->fetch()) {
        $plehrer = cms_generiere_anzeigename($plvor, $plnach, $pltit);
        if (strlen($plkurz) > 0) {$plehrer = "$plkurz - ".$plehrer;}
        if (isset($SCHULSTUNDEN[date('H:i', $pbeginn)])) {$pzeit = $SCHULSTUNDEN[date('H:i', $pbeginn)];}
        else {$pzeit = date('H:i', $pbeginn);}
        $tlehrer = cms_generiere_anzeigename($tlvor, $tlnach, $tltit);
        if (strlen($tlkurz) > 0) {$tlehrer = "$tlkurz - ".$tlehrer;}
        if (isset($SCHULSTUNDEN[date('H:i', $tbeginn)])) {$tzeit = $SCHULSTUNDEN[date('H:i', $tbeginn)];}
        else {$tzeit = date('H:i', $tbeginn);}
        if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {
          $tzeit = date("d.m.Y", $tbeginn)." - ".$tzeit;
        }
        $code .= $uid.";".$kid.";".$pzeit.";".$pkurs.";".$plehrer.";".$praum.";".$tzeit.";".$tkurs.";".$tlehrer.";".$traum.";".$vplanart."\n";

        array_push($kids, $kid);
      }
    }
    $sql->close();


    // Suche Stunden, mit gespeicherten Änderungen, die rückabgewickelt werden müssen
    if ($art == 'l') {
      $sql = $dbs->prepare("SELECT u.id AS uid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, tbeginn, vplanart FROM unterricht AS u LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND plehrer = ? AND u.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) ORDER BY pbeginn DESC");
    }
    if ($art == 'r') {
      $sql = $dbs->prepare("SELECT u.id AS uid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, tbeginn, vplanart FROM unterricht AS u LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND praum = ? AND u.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) ORDER BY pbeginn DESC");
    }
    if ($art == 'k') {
      $sql = $dbs->prepare("SELECT u.id AS uid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, tbeginn, vplanart FROM unterricht AS u LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND pkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND u.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) ORDER BY pbeginn DESC");
    }
    if ($art == 'k') {
      $sql = $dbs->prepare("SELECT u.id AS uid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, tbeginn, vplanart FROM unterricht AS u LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id WHERE ((pbeginn BETWEEN ? AND ?) OR (pende BETWEEN ? AND ?) OR (pbeginn <= ? AND pende >= ?)) AND pkurs IN (SELECT id FROM kurse WHERE stufe = ?) AND u.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) ORDER BY pbeginn DESC");
    }
    $sql->bind_param("iiiiiii", $beginn, $ende, $beginn, $ende, $beginn, $ende, $ziel);
    if ($sql->execute()) {
      $sql->bind_result($uid, $plvor, $plnach, $pltit, $plkurz, $praum, $pkurs, $tlvor, $tlnach, $tltit, $tlkurz, $traum, $tkurs, $pbeginn, $tbeginn, $vplanart);
      while ($sql->fetch()) {
        $plehrer = cms_generiere_anzeigename($plvor, $plnach, $pltit);
        if (strlen($plkurz) > 0) {$plehrer = "$plkurz - ".$plehrer;}
        if (isset($SCHULSTUNDEN[date('H:i', $pbeginn)])) {$pzeit = $SCHULSTUNDEN[date('H:i', $pbeginn)];}
        else {$pzeit = date('H:i', $pbeginn);}
        $tlehrer = cms_generiere_anzeigename($tlvor, $tlnach, $tltit);
        if (strlen($tlkurz) > 0) {$tlehrer = "$tlkurz - ".$tlehrer;}
        if (isset($SCHULSTUNDEN[date('H:i', $tbeginn)])) {$tzeit = $SCHULSTUNDEN[date('H:i', $tbeginn)];}
        else {$tzeit = date('H:i', $tbeginn);}
        if (date("d.m.Y", $tbeginn) != date("d.m.Y", $pbeginn)) {
          $tzeit = date("d.m.Y", $tbeginn)." - ".$tzeit;
        }
        $code .= $uid.";;".$pzeit.";".$pkurs.";".$plehrer.";".$praum.";".$tzeit.";".$tkurs.";".$tlehrer.";".$traum.";".$vplanart."\n";

        array_push($uids, $uid);
      }
    }
    $sql->close();
  }
  else {$fehler = true;}

  if ($fehler) {
    cms_lehrerdb_header(true);
    echo "FEHLER";
  }
  else {
    if ($art == 'l') {
      $sql = "DELETE FROM ausplanunglehrer WHERE id = ?";
    }
    else if ($art == 'r') {
      $sql = "DELETE FROM ausplanungraeume WHERE id = ?";
    }
    else if ($art == 'k') {
      $sql = "DELETE FROM ausplanungklassen WHERE id = ?";
    }
    else if ($art == 's') {
      $sql = "DELETE FROM ausplanungstufen WHERE id = ?";
    }
    $sql = $dbl->prepare($sql);
    $sql->bind_param("i", $id);
    $sql->execute();
    $sql->close();

    cms_lehrerdb_header(true);
    if (strlen($code) == 0) {$code = "\n";}
    echo $code."\n\n".implode(";", $kids)."\n\n\n".implode(";", $uids);
  }

  cms_trennen($dbs);
  cms_trennen($dbl);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
