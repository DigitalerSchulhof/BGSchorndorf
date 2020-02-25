<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['ausgangsstundeu'])) {$ausgangsstundeu = $_POST['ausgangsstundeu'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['ausgangsstundek'])) {$ausgangsstundek = $_POST['ausgangsstundek'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zielstundeu'])) {$zielstundeu = $_POST['zielstundeu'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zielstundek'])) {$zielstundek = $_POST['zielstundek'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zielzeit'])) {$zielzeit = $_POST['zielzeit'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zwang'])) {$zwang = $_POST['zwang'];} else {cms_anfrage_beenden(); exit;}
if (isset($_POST['zusatzbem'])) {$zusatzbem = $_POST['zusatzbem'];} else {cms_anfrage_beenden(); exit;}

if ((!cms_check_ganzzahl($ausgangsstundeu,0)) && ($ausgangsstundeu !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($ausgangsstundek,0)) && ($ausgangsstundek !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($zielstundeu,0)) && ($zielstundeu !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($zielstundek,0)) && ($zielstundek !== '')) {cms_anfrage_beenden(); exit;}
$tag = substr($zielzeit,0,2);
$monat = substr($zielzeit,3,2);
$jahr = substr($zielzeit,6,4);
$stunde = substr($zielzeit,11,2);
$minute = substr($zielzeit,14,2);
if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($stunde,0,23)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($minute,0,59)) {cms_anfrage_beenden(); exit;}
if (($zwang != 'j') && ($zwang != 'n')) {cms_anfrage_beenden(); exit;}
$beginnzeit = mktime($stunde, $minute, 0, $monat, $tag, $jahr);



// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');
  $fehler = false;

  // Informationen der Ausgangsstunde laden
  if ($ausgangsstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($ausanzahl, $auskurs, $ausbeginn, $ausende, $auslehrer, $ausraum, $ausbem);
    $sql->fetch();
    if ($ausanzahl != 1) {$fehler = true; echo 1;}
  } else {$fehler = true; echo 2;}
  $sql->close();

  // Informationen der Zielstunde laden
  if ($zielstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($zieanzahl, $ziekurs, $ziebeginn, $zieende, $zielehrer, $zieraum, $ziebem);
    $sql->fetch();
    if ($zieanzahl != 1) {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Zeit suchen
  if (!$fehler) {
    $sql = "SELECT COUNT(*), beginns, beginnm, endes, endem FROM schulstunden JOIN zeitraeume ON schulstunden.zeitraum = zeitraeume.id WHERE zeitraeume.beginn <= ? AND zeitraeume.ende >= ? AND beginns = ? AND beginnm = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("iiii", $beginnzeit, $beginnzeit, $stunde, $minute);
    if ($sql->execute()) {
      $sql->bind_result($stdanzahl, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
      $sql->fetch();
      if ($stdanzahl != 1) {$fehler = true; echo 5;}
      else {
        $neubeginn = mktime($stdbeginns, $stdbeginnm, 0, $monat, $tag, $jahr);
        $neuende = mktime($stdendes, $stdendem, 0, $monat, $tag, $jahr);
      }
    } else {$fehler = true; echo 6;}
    $sql->close();
  }

  // Prüfen, ob mögliche Konflikte entstehen
  $konflikt = false;
  $KONFLIKTZUSATZ = "";
  if (!$fehler && ($zwang == 'n')) {
    // Prüfen, ob die Stunde Konflikte auslöst
    // Prüfen, ob Ausplanungen betroffen sind
    $dbl = cms_verbinden('l');
    // Lehrer ausgeplant?
    $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanunglehrer WHERE von <= ? AND bis >= ? AND lehrer = ?");
    $sql->bind_param("iii", $neubeginn, $neubeginn, $auslehrer);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'L';}
      }
    }
    $sql->close();
    // Raum ausgeplant?
    $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungraeume WHERE von <= ? AND bis >= ? AND raum = ?");
    $sql->bind_param("iii", $neubeginn, $neubeginn, $ausraum);
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
    $sql->bind_param("i", $auskurs);
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
      $sql->bind_param("ii", $neubeginn, $neubeginn);
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
    $sql->bind_param("i", $auskurs);
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
      $sql->bind_param("ii", $neubeginn, $neubeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'S';}
        }
      }
      $sql->close();
    }
    // Finden parallel andere Stunden in diesem Raum statt?
    if (($zielstundeu != '') && ($zielstundek != '')) {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiiii", $neubeginn, $ausraum, $zielstundek, $zielstundeu, $neubeginn, $ausraum, $zielstundek);
    }
    else if ($zielstundeu != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $neubeginn, $ausraum, $zielstundeu, $neubeginn, $ausraum);
    }
    else if ($zielstundek != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiii", $neubeginn, $ausraum, $zielstundek, $neubeginn, $ausraum, $zielstundek);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiii", $neubeginn, $ausraum, $neubeginn, $ausraum);
    }

    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit dieser Klasse statt?
    if (($zielstundeu != '') && ($zielstundek != '')) {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiiiiii", $neubeginn, $auskurs, $auskurs, $zielstundek, $zielstundeu, $neubeginn, $auskurs, $auskurs, $zielstundek);
    }
    else if ($zielstundeu != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiiii", $neubeginn, $auskurs, $auskurs, $zielstundeu, $neubeginn, $auskurs, $auskurs);
    }
    else if ($zielstundek != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiiiii", $neubeginn, $auskurs, $auskurs, $zielstundek, $neubeginn, $auskurs, $auskurs, $zielstundek);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiii", $neubeginn, $auskurs, $auskurs, $neubeginn, $auskurs, $auskurs);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit diesem Lehrer statt?
    if (($zielstundeu != '') && ($zielstundek != '')) {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiiii", $neubeginn, $auslehrer, $zielstundek, $zielstundeu, $neubeginn, $auslehrer, $zielstundek);
    }
    else if ($zielstundeu != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL) AND id != ?) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $neubeginn, $auslehrer, $zielstundeu, $neubeginn, $auslehrer);
    }
    else if ($zielstundek != '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL AND id != ?)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id != ?)) AS x");
      $sql->bind_param("iiiiii", $neubeginn, $auslehrer, $zielstundek, $neubeginn, $auslehrer, $zielstundek);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiii", $neubeginn, $auslehrer, $neubeginn, $auslehrer);
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

  if (!$fehler && !$konflikt) {
    if (strlen($zusatzbem) > 0) {
      $ausbem = $zusatzbem;
    }

    if ($zielstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = 'a', vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiii", $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $zielstundek);
      $sql->execute();
    }
    else {
      $neuid = cms_generiere_kleinste_id("unterrichtkonflikt", "s");
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = 'a', vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiiii", $zielstundeu, $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $neuid);
      $sql->execute();
    }

    cms_lehrerdb_header(true);
    echo "ERFOLG";
  }
  else if (!$fehler && $konflikt) {
    cms_lehrerdb_header(true);
    echo "KONFLIKT".$KONFLIKTZUSATZ;
  }
  else {
    cms_lehrerdb_header(true);
    echo "FEHLER";
  }

}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
