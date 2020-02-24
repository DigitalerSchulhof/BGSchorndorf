<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['uid']))         {$uid = $_POST['uid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kid']))         {$kid = $_POST['kid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['bem']))         {$bem = $_POST['bem'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['uids']))        {$uids = '('.$_POST['uids'].')';}               else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kids']))        {$kids = '('.$_POST['kids'].')';}               else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($uid,0) && ($uid != '')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kid,0) && ($kid != '')) {cms_anfrage_beenden(); exit;}
if (($kid == '') && ($uid == '')) {cms_anfrage_beenden(); exit;}
if (!cms_check_idliste($kids) && ($kids != '()')) {cms_anfrage_beenden(); exit;}
if (!cms_check_idliste($uids) && ($uids != '()')) {cms_anfrage_beenden(); exit;}
if (($bem != 'j') && ($bem != 'n')) {cms_anfrage_beenden(); exit;}

if ($bem == 'j') {$nvplantext = "Findet statt!"; $nvplanart = '-'; $nvplananzeigen = 1;}
else {$nvplantext = ""; $nvplanart = '-'; $nvplananzeigen = 0;}

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

  $vpkurs = $vpbeginn = $vpende = $vplehrer = $vpraum = null;
  // Diese Stunde laden
  if ($kid != '') {
    $sql = $dbs->prepare("SELECT pkurs, pbeginn, pende, plehrer, praum FROM unterrichtkonflikt LEFT JOIN unterricht ON altid = unterricht.id WHERE unterrichtkonflikt.id = ?");
    $sql->bind_param("i", $kid);
    if ($sql->execute()) {
      $sql->bind_result($vpkurs, $vpbeginn, $vpende, $vplehrer, $vpraum);
      $sql->fetch();
    }
    $sql->close();
  }
  else if ($uid != '') {
    $sql = $dbs->prepare("SELECT pkurs, pbeginn, pende, plehrer, praum FROM unterricht WHERE id = ?");
    $sql->bind_param("i", $uid);
    if ($sql->execute()) {
      $sql->bind_result($vpkurs, $vpbeginn, $vpende, $vplehrer, $vpraum);
      $sql->fetch();
    }
    $sql->close();
  }


  // Schulstunden laden
  if (($vpkurs !== null) && ($vpbeginn !== null) && ($vpende !== null) && ($vplehrer !== null) && ($vpraum !== null)) {
    $beginn = mktime(0,0,0,date('m', $vpbeginn), date('d', $vpbeginn), date('Y', $vpbeginn));
    $ende = mktime(0,0,0,date('m', $vpende), date('d', $vpende)+1, date('Y', $vpende))-1;
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

  $code = "";
  $neukids = array();
  $neuuids = array();
  if (!$fehler) {
    // Durch Rückabwicklung entstandene Überschneidung laden
    // Konflikte
    $zusatz = "";
    if ($kids != '()') {$zusatz = "AND k.id NOT IN $kids";}
    $sql = $dbs->prepare("SELECT u.id AS uid, k.id AS kid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, k.tbeginn, k.vplanart FROM unterricht AS u JOIN unterrichtkonflikt AS k ON u.id = k.altid LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON k.tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON k.tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON k.traum = traeume.id LEFT JOIN kurse AS tkurse ON k.tkurs = tkurse.id WHERE k.tbeginn = ? AND k.tende = ? AND (plehrer = ? OR praum = ? OR pkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?))) AND (plehrer != k.tlehrer OR praum != k.traum OR pkurs != k.tkurs OR pbeginn != k.tbeginn OR pende != k.tende) $zusatz ORDER BY pbeginn DESC");
    $sql->bind_param("iiiii", $vpbeginn, $vpende, $vplehrer, $vpraum, $vpkurs);
    if ($sql->execute()) {
      $sql->bind_result($nuid, $nkid, $plvor, $plnach, $pltit, $plkurz, $praum, $pkurs, $tlvor, $tlnach, $tltit, $tlkurz, $traum, $tkurs, $pbeginn, $tbeginn, $vplanart);
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
        $code .= $nuid.";".$nkid.";".$pzeit.";".$pkurs.";".$plehrer.";".$praum.";".$tzeit.";".$tkurs.";".$tlehrer.";".$traum.";".$vplanart."\n";

        array_push($neukids, $nkid);
      }
    }
    $sql->close();

    // Geplante Änderungen
    $zusatz = "";
    if ($uids != '()') {$zusatz = "AND u.id NOT IN $uids";}
    $sql = $dbs->prepare("SELECT u.id AS uid, AES_DECRYPT(ppers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ppers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(plehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(praeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(pkurse.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(tpers.titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(tlehr.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(traeume.bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(tkurse.bezeichnung, '$CMS_SCHLUESSEL'), pbeginn, tbeginn, vplanart FROM unterricht AS u LEFT JOIN personen AS ppers ON plehrer = ppers.id LEFT JOIN lehrer AS plehr ON plehrer = plehr.id LEFT JOIN raeume AS praeume ON praum = praeume.id LEFT JOIN kurse AS pkurse ON pkurs = pkurse.id LEFT JOIN personen AS tpers ON tlehrer = tpers.id LEFT JOIN lehrer AS tlehr ON tlehrer = tlehr.id LEFT JOIN raeume AS traeume ON traum = traeume.id LEFT JOIN kurse AS tkurse ON tkurs = tkurse.id WHERE tbeginn = ? AND tende = ? AND (plehrer = ? OR praum = ? OR pkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?))) AND (plehrer != tlehrer OR praum != traum OR pkurs != tkurs OR pbeginn != tbeginn OR pende != tende) $zusatz ORDER BY pbeginn DESC");
    $sql->bind_param("iiiii", $vpbeginn, $vpende, $vplehrer, $vpraum, $vpkurs);
    if ($sql->execute()) {
      $sql->bind_result($nuid, $plvor, $plnach, $pltit, $plkurz, $praum, $pkurs, $tlvor, $tlnach, $tltit, $tlkurz, $traum, $tkurs, $pbeginn, $tbeginn, $vplanart);
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
        $code .= $nuid.";;".$pzeit.";".$pkurs.";".$plehrer.";".$praum.";".$tzeit.";".$tkurs.";".$tlehrer.";".$traum.";".$vplanart."\n";

        array_push($neuuids, $nuid);
      }
    }
    $sql->close();


    // Diese Stunde zurücksetzen
    if ($kid != '') {
      $sql = $dbs->prepare("DELETE FROM unterrichtkonflikt WHERE id = ?");
      $sql->bind_param("i", $kid);
      $sql->execute();
      $sql->close();
    }
    else if ($uid != '') {
      $sql = $dbs->prepare("UPDATE unterricht SET tkurs = pkurs, tbeginn = pbeginn, tende = pende, tlehrer = plehrer, traum = praum, vplanart = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
      $sql->bind_param("sisi", $nvplanart, $nvplananzeigen, $nvplantext, $uid);
      $sql->execute();
      $sql->close();
    }
  }

  if ($fehler) {
    cms_lehrerdb_header(true);
    echo "FEHLER";
  }
  else {
    if (strlen($code) == 0) {$code = "\n";}
    cms_lehrerdb_header(true);
    echo $code."\n\n".implode(";", $neukids)."\n\n\n".implode(";", $neuuids);
  }

  cms_trennen($dbs);
  cms_trennen($dbl);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
