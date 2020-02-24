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
if (isset($_POST['zwang'])) {$zwang = $_POST['zwang'];} else {cms_anfrage_beenden(); exit;}

if ((!cms_check_ganzzahl($ausgangsstundeu,0)) && ($ausgangsstundeu !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($ausgangsstundek,0)) && ($ausgangsstundek !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($zielstundeu,0)) && ($zielstundeu !== '')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($zielstundek,0)) && ($zielstundek !== '')) {cms_anfrage_beenden(); exit;}
if (($zwang != 'j') && ($zwang != 'n')) {cms_anfrage_beenden(); exit;}
if (($ausgangsstundeu == $zielstundeu) && ($ausgangsstundek == $zielstundek)) {cms_anfrage_beenden(); exit;}

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
  $fehler = false;

  // Informationen der Ausgangsstunde laden
  if ($ausgangsstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ausgangsstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($ausanzahl, $auskurs, $ausbeginn, $ausende, $auslehrer, $ausraum);
    $sql->fetch();
    if ($ausanzahl != 1) {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();


  // Informationen der Zielstunde laden
  if ($zielstundek == '') {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterricht WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundeu);
  }
  else {
    $sql = "SELECT COUNT(*), tkurs, tbeginn, tende, tlehrer, traum FROM unterrichtkonflikt WHERE id = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $zielstundek);
  }
  if ($sql->execute()) {
    $sql->bind_result($zieanzahl, $ziekurs, $ziebeginn, $zieende, $zielehrer, $zieraum);
    $sql->fetch();
    if ($zieanzahl != 1) {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

  // Prüfen, ob mögliche Konflikte entstehen
  $konflikt = false;
  $KONFLIKTZUSATZ = "";
  if (!$fehler && ($zwang == 'n')) {
    // Prüfen, ob die Stunde Konflikte auslöst
    // Prüfen, ob Ausplanungen betroffen sind
    $dbl = cms_verbinden('l');
    // Lehrer ausgeplant?
    $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanunglehrer WHERE (von <= ? AND bis >= ? AND lehrer = ?) OR (von <= ? AND bis >= ? AND lehrer = ?)");
    $sql->bind_param("iiiiii", $ziebeginn, $ziebeginn, $auslehrer, $ausbeginn, $ausbeginn, $zielehrer);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'L';}
      }
    }
    $sql->close();
    // Raum ausgeplant?
    $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungraeume WHERE (von <= ? AND bis >= ? AND raum = ?) OR (von <= ? AND bis >= ? AND raum = ?)");
    $sql->bind_param("iiiiii", $ziebeginn, $ziebeginn, $ausraum, $ausbeginn, $ausbeginn, $zieraum);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
      }
    }
    $sql->close();
    // Kurse der KLASSEN holen
    $AUSKLASSEN = array();
    $sql = $dbs->prepare("SELECT DISTINCT klasse FROM kurseklassen WHERE kurs = ?");
    $sql->bind_param("i", $auskurs);
    if ($sql->execute()) {
      $sql->bind_result($klassenid);
      while ($sql->fetch()) {
        array_push($AUSKLASSEN, $klassenid);
      }
    }
    $sql->close();
    $ZIEKLASSEN = array();
    $sql = $dbs->prepare("SELECT DISTINCT klasse FROM kurseklassen WHERE kurs = ?");
    $sql->bind_param("i", $ziekurs);
    if ($sql->execute()) {
      $sql->bind_result($klassenid);
      while ($sql->fetch()) {
        array_push($ZIEKLASSEN, $klassenid);
      }
    }
    $sql->close();
    // Klasse ausgeplant?
    if ((count($AUSKLASSEN) > 0) && (count($ZIEKLASSEN) > 0)) {
      $ausklassenids = implode(',', $AUSKLASSEN);
      $zieklassenids = implode(',', $ZIEKLASSEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungklassen WHERE (von <= ? AND bis >= ? AND klasse IN ($ausklassenids)) OR (von <= ? AND bis >= ? AND klasse IN ($zieklassenids))");
      $sql->bind_param("iiii", $ziebeginn, $ziebeginn, $ausbeginn, $ausbeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
        }
      }
      $sql->close();
    }
    else if (count($AUSKLASSEN) > 0) {
      $ausklassenids = implode(',', $AUSKLASSEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungklassen WHERE von <= ? AND bis >= ? AND klasse IN ($ausklassenids)");
      $sql->bind_param("ii", $ziebeginn, $ziebeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
        }
      }
      $sql->close();
    }
    else if (count($ZIEKLASSEN) > 0) {
      $zieklassenids = implode(',', $ZIEKLASSEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungklassen WHERE von <= ? AND bis >= ? AND klasse IN ($zieklassenids)");
      $sql->bind_param("ii", $ausbeginn, $ausbeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
        }
      }
      $sql->close();
    }

    // Stufen der Kurse holen
    $AUSSTUFEN = array();
    $sql = $dbs->prepare("SELECT DISTINCT stufe FROM kurse WHERE id = ?");
    $sql->bind_param("i", $auskurs);
    if ($sql->execute()) {
      $sql->bind_result($stufenids);
      while ($sql->fetch()) {
        array_push($AUSSTUFEN, $stufenids);
      }
    }
    $sql->close();
    $ZIESTUFEN = array();
    $sql = $dbs->prepare("SELECT DISTINCT stufe FROM kurse WHERE id = ?");
    $sql->bind_param("i", $ziekurs);
    if ($sql->execute()) {
      $sql->bind_result($stufenids);
      while ($sql->fetch()) {
        array_push($ZIESTUFEN, $stufenids);
      }
    }
    $sql->close();
    // Klasse ausgeplant?
    if ((count($AUSSTUFEN) > 0) && (count($ZIESTUFEN) > 0)) {
      $ausstufenids = implode(',', $AUSSTUFEN);
      $ziestufenids = implode(',', $ZIESTUFEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungstufen WHERE (von <= ? AND bis >= ? AND stufe IN ($ausstufenids)) OR (von <= ? AND bis >= ? AND stufe IN ($ziestufenids))");
      $sql->bind_param("iiii", $ziebeginn, $ziebeginn, $ausbeginn, $ausbeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'S';}
        }
      }
      $sql->close();
    }
    else if (count($AUSSTUFEN) > 0) {
      $ausstufenids = implode(',', $AUSSTUFEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungstufen WHERE von <= ? AND bis >= ? AND stufe IN ($ausstufenids)");
      $sql->bind_param("ii", $ziebeginn, $ziebeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'S';}
        }
      }
      $sql->close();
    }
    else if (count($ZIESTUFEN) > 0) {
      $ziestufenids = implode(',', $ZIESTUFEN);
      $sql = $dbl->prepare("SELECT COUNT(*) FROM ausplanungstufen WHERE von <= ? AND bis >= ? AND stufe IN ($ziestufenids)");
      $sql->bind_param("ii", $ausbeginn, $ausbeginn);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {
          if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'S';}
        }
      }
      $sql->close();
    }

    // Finden parallel andere Stunden in diesem Raum statt? ZIELZEIT
    if ($zielstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $zielstundeu, $ziebeginn, $ausraum, $ziebeginn, $ausraum);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ziebeginn, $ausraum, $zielstundek, $ziebeginn, $ausraum);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden in diesem Raum statt? AUSGANGSZEIT
    if ($ausgangsstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ausgangsstundeu, $ausbeginn, $zieraum, $ausbeginn, $zieraum);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND traum = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ausbeginn, $zieraum, $ausgangsstundek, $ausbeginn, $zieraum);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'R';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit dieser Klasse statt? ZIELZEIT
    if ($zielstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiiii", $zielstundeu, $ziebeginn, $auskurs, $auskurs, $ziebeginn, $auskurs, $auskurs);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiiii", $ziebeginn, $auskurs, $auskurs, $zielstundek, $ziebeginn, $auskurs, $auskurs);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit dieser Klasse statt? AUSGANGSZEIT
    if ($ausgangsstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiiii", $ausgangsstundeu, $ausbeginn, $ziekurs, $ziekurs, $ausbeginn, $ziekurs, $ziekurs);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs = ?)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiiiii", $ausbeginn, $ziekurs, $ziekurs, $ausgangsstundek, $ausbeginn, $ziekurs, $ziekurs);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'K';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit diesem Lehrer statt? ZIELZEIT
    if ($zielstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $zielstundeu, $ziebeginn, $auslehrer, $ziebeginn, $auslehrer);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ziebeginn, $auslehrer, $zielstundek, $ziebeginn, $auslehrer);
    }
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
        if ($anzahl > 0) {$konflikt = true; $KONFLIKTZUSATZ .= 'L';}
      }
    }
    $sql->close();
    // Finden parallel andere Stunden mit diesem Lehrer statt? AUSGANGSZEIT
    if ($ausgangsstundek == '') {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE id != ? AND tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ausgangsstundeu, $ausbeginn, $zielehrer, $ausbeginn, $zielehrer);
    }
    else {
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM ((SELECT COUNT(*) AS anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND vplanart != 'e' AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) UNION (SELECT COUNT(*) AS anzahl FROM unterrichtkonflikt WHERE id != ? AND tbeginn = ? AND tlehrer = ? AND vplanart != 'e')) AS x");
      $sql->bind_param("iiiii", $ausbeginn, $zielehrer, $ausgangsstundek, $ausbeginn, $zielehrer);
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
    if (date('d.m.Y', $ausbeginn) == date('d.m.Y', $ziebeginn)) {
      $art = 'a';
    }
    else {
      $art = 'v';
    }

    // Zielstunde verlegen
    if ($zielstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1' WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiisi", $ziekurs, $ausbeginn, $ausende, $zielehrer, $zieraum, $art, $zielstundek);
      $sql->execute();
    }
    else {
      $neuid = cms_generiere_kleinste_id("unterrichtkonflikt", "s");
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiiisi", $zielstundeu, $ziekurs, $ausbeginn, $ausende, $zielehrer, $zieraum, $art, $neuid);
      $sql->execute();
    }
    $sql->close();

    // Ausgangsstunde verlegen
    if ($ausgangsstundek !== '') {
      $sql = "UPDATE unterrichtkonflikt SET tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1' WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiisi", $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $art, $ausgangsstundek);
      $sql->execute();
    }
    else {
      $neuid2 = cms_generiere_kleinste_id("unterrichtkonflikt", "s", '-', '1');
      $sql = "UPDATE unterrichtkonflikt SET altid = ?, tkurs = ?, tbeginn = ?, tende = ?, tlehrer = ?, traum = ?, vplanart = ?, vplananzeigen = '1', vplanbemerkung = AES_ENCRYPT('', '$CMS_SCHLUESSEL') WHERE id = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("iiiiiisi", $ausgangsstundeu, $auskurs, $ziebeginn, $zieende, $auslehrer, $ausraum, $art, $neuid2);
      $sql->execute();
    }
    $sql->close();
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
