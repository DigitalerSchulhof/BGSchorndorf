<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zwang']))       {$zwang = $_POST['zwang'];}                     else {cms_anfrage_beenden(); exit;}
if (isset($_POST['uid']))         {$uid = $_POST['uid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kid']))         {$kid = $_POST['kid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag']))         {$tag = $_POST['tag'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat']))       {$monat = $_POST['monat'];}                     else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr']))        {$jahr = $_POST['jahr'];}                       else {cms_anfrage_beenden(); exit;}
if (isset($_POST['lehrer']))      {$lehrer = $_POST['lehrer'];}                   else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kurs']))        {$kurs = $_POST['kurs'];}                       else {cms_anfrage_beenden(); exit;}
if (isset($_POST['raum']))        {$raum = $_POST['raum'];}                       else {cms_anfrage_beenden(); exit;}
if (isset($_POST['stunde']))      {$stunde = $_POST['stunde'];}                   else {cms_anfrage_beenden(); exit;}
if (isset($_POST['bemerkung']))   {$bemerkung = cms_texttrafo_e_db($_POST['bemerkung']);} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['anzeigen']))    {$anzeigen = $_POST['anzeigen'];}               else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($lehrer,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kurs,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($raum,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($stunde,0) && ($stunde != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($uid,0) && ($uid != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kid,0) && ($kid != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_toggle($anzeigen)) {cms_anfrage_beenden(); exit;}
if (($uid == '-') && ($kid == '-')) {cms_anfrage_beenden(); exit;}
if ($kid != '-') {$id = $kid;} else {$id = $uid;}
if (($zwang != 'j') && ($zwang != 'n')) {cms_anfrage_beenden(); exit;}

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
  $dbs = cms_verbinden('s');
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $fehler = false;
  // Kursexistenz prüfen und Schuljahr laden
  $sql = $dbs->prepare("SELECT COUNT(*), schuljahr FROM kurse WHERE id = ?");
  $sql->bind_param("i", $kurs);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $SCHULJAHR);
    if ($sql->fetch()) {
      if ($anzahl != 1) {$fehler = true;}
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Unterrichtsexistenz prüfen
  if (!$fehler) {
    if ($kid != '-') {$tabelle = "unterrichtkonflikt";}
    else {$tabelle = "unterricht";}
    $sql = $dbs->prepare("SELECT COUNT(*) FROM $tabelle WHERE id = ?");
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Lehrerexistenz prüfen
  if (!$fehler) {
    $sql = $dbs->prepare("SELECT COUNT(*) FROM lehrer WHERE id = ?");
    $sql->bind_param("i", $lehrer);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Raumexistenz prüfen
  if (!$fehler) {
    $sql = $dbs->prepare("SELECT COUNT(*) FROM raeume WHERE id = ?");
    $sql->bind_param("i", $raum);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  // Schulstundenexistenz prüfen
  if ((!$fehler) && ($stunde != '-')) {
    $sql = $dbs->prepare("SELECT COUNT(*), beginns, beginnm, endes, endem FROM schulstunden WHERE id = ? AND zeitraum IN (SELECT id FROM zeitraeume WHERE schuljahr = ? AND beginn <= ? AND ende >= ?)");
    $sql->bind_param("iiii", $stunde, $SCHULJAHR, $hb, $hb);
    if ($sql->execute()) {
      $sql->bind_result($anzahl, $STDBEGINNS, $STDBEGINNM, $STDENDES, $STDENDEM);
      if ($sql->fetch()) {
        if ($anzahl != 1) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();
  }

  if ((!$fehler) && ($stunde == '-')) {
    $tbeginn = null;
    $tende = null;
    if ($kid != '-') {$sql = "SELECT tbeginn, tende FROM unterrichtkonflikt WHERE id = ?";}
    else {$sql = "SELECT tbeginn, tende FROM unterricht WHERE id = ?";}
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $id);
    if ($sql->execute()) {
      $sql->bind_result($tbeginn, $tende);
      $sql->fetch();
    }
    $sql->close();
    $art = 'e';
  }
  else if (!$fehler) {
    $tbeginn = mktime($STDBEGINNS, $STDBEGINNM, 0, $monat, $tag, $jahr);
    $tende = mktime($STDENDES, $STDENDEM, 0, $monat, $tag, $jahr)-1;
    $art = 'a';
  }

  $konflikt = false;
  $KONFLIKTZUSATZ = "";
  if (!$fehler && ($art != 'e')) {
    if (!$fehler && ($zwang == 'n')) {
      // Prüfen, ob die Stunde Konflikte auslöst
      // Prüfen, ob Ausplanungen betroffen sind
      $dbl = cms_verbinden('l');
      // Lehrer ausgeplant?
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanunglehrer WHERE von <= ? AND bis >= ? AND lehrer = ?");
      $sql->bind_param("iii", $tbeginn, $tbeginn, $lehrer);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'L';}
        }
      }
      $sql->close();
      // Raum ausgeplant?
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungraeume WHERE von <= ? AND bis >= ? AND raum = ?");
      $sql->bind_param("iii", $tbeginn, $tbeginn, $raum);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
        }
      }
      $sql->close();
      // Kurse der KLASSEN holen
      $KLASSEN = array();
      $sql = $dbs->prepare("SELECT DISTINCT klasse FROM kurseklassen WHERE kurs = ?");
      $sql->bind_param("i", $kurs);
      if ($sql->execute()) {
        $sql->bind_result($klassenid);
        while ($sql->fetch()) {
          array_push($KLASSEN, $klassenid);
        }
      }
      $sql->close();

      // Klasse ausgeplant?
      if (count($KLASSEN) > 0) {
        $klassenids = implode(',', $KLASSEN);
        $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungklassen WHERE von <= ? AND bis >= ? AND klasse IN ($klassenids)");
        $sql->bind_param("ii", $tbeginn, $tbeginn);
        if ($sql->execute()) {
          $sql->bind_result($anzahl);
          if ($sql->fetch()) {
            if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
          }
        }
        $sql->close();
      }
      // Stufen der Kurse holen
      $STUFEN = array();
      $sql = $dbs->prepare("SELECT DISTINCT stufe FROM kurse WHERE id = ?");
      $sql->bind_param("i", $kurs);
      if ($sql->execute()) {
        $sql->bind_result($stufenid);
        while ($sql->fetch()) {
          array_push($STUFEN, $stufenid);
        }
      }
      $sql->close();
      // Stufe ausgeplant?
      if (count($STUFEN) > 0) {
        $stufenids = implode(',', $STUFEN);
        $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungstufen WHERE von <= ? AND bis >= ? AND stufe IN ($stufenids)");
        $sql->bind_param("ii", $tbeginn, $tende);
        if ($sql->execute()) {
          $sql->bind_result($anzahl);
          if ($sql->fetch()) {
            if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'S';}
          }
        }
        $sql->close();
      }
      // Finden parallel andere Stunden in diesem Raum statt?
      if ($kid != '-') {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND id != ? AND traum = ? AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiii", $tbeginn, $raum, $tbeginn, $kid, $raum);
      }
      else {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND id != ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiii", $tbeginn, $uid, $raum, $tbeginn, $raum);
      }
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
        }
      }
      $sql->close();
      // Finden parallel andere Stunden mit dieser Klasse statt?
      if ($kid != '-') {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND id != ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiiiii", $tbeginn, $kurs, $kurs, $tbeginn, $kid, $kurs, $kurs);
      }
      else {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND id != ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiiiii", $tbeginn, $uid, $kurs, $kurs, $tbeginn, $kurs, $kurs);
      }
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
        }
      }
      $sql->close();
      // Finden parallel andere Stunden mit diesem Lehrer statt?
      if ($kid != '-') {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND id != ? AND tlehrer = ? AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiii", $tbeginn, $lehrer, $tbeginn, $kid, $lehrer);
      }
      else {
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND id != ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
        $sql->bind_param("iiiii", $tbeginn, $uid, $lehrer, $tbeginn, $lehrer);
      }

      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'L';}
        }
      }
      $sql->close();
      cms_trennen($dbl);
    }
  }



  if (!$fehler && !$konflikt) {
    // Stunde eintragen
    if ($kid != '-') {
      $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
      $sql->bind_param("iiiiiissi", $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $id);
      $sql->execute();
      $sql->close();
    }
    else {
      $kid = cms_generiere_kleinste_id('unterrichtkonflikt', 's');
      if ($art != 'e') {
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
        $sql->bind_param("iiiiiiissi", $id, $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $kid);
      }
      else {
        $sql = $dbs->prepare("UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplananzeigen = ?, vplanbemerkung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), vplanart = ? WHERE id = ?");
        $sql->bind_param("iiiiiiissi", $id, $kurs, $tbeginn, $tende, $lehrer, $raum, $anzeigen, $bemerkung, $art, $kid);
      }
      $sql->execute();
      $sql->close();
    }
    cms_lehrerdb_header(true);
    echo $kid."ERFOLG";
  }
  else if (!$fehler && $konflikt) {
    cms_lehrerdb_header(true);
    echo "KONFLIKT".$KONFLIKTZUSATZ;
  }
  else {
    cms_lehrerdb_header(true);
    echo "FEHLER";
  }

  cms_trennen($dbs);
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
