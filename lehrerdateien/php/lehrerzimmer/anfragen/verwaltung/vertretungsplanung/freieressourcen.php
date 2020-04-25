<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag']))         {$tag = $_POST['tag'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat']))       {$monat = $_POST['monat'];}                     else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr']))        {$jahr = $_POST['jahr'];}                       else {cms_anfrage_beenden(); exit;}
if (isset($_POST['std']))         {$std = $_POST['std'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['uid']))         {$uid = $_POST['uid'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kid']))         {$kid = $_POST['kid'];}                         else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($std,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($uid,0) && ($uid != '-')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($kid,0) && ($kid != '-')) {cms_anfrage_beenden(); exit;}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG
$zugriff = cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen");

if ($angemeldet && $zugriff) {
  $dbs = cms_verbinden('s');
  $dbl = cms_verbinden('l');

  $heute = mktime(0,0,0, $monat, $tag, $jahr);
  $heutemittag = mktime(12,59,59, $monat, $tag, $jahr);
  $heuteende = mktime(0,0,0, $monat, $tag+1, $jahr)-1;

  $RAUMID = null;
  $KURSID = null;
  $LEHRERID = null;
  if (($kid != '-') || ($uid != '-')) {
    if ($kid != '-') {
      $sql = $dbs->prepare("SELECT tlehrer, traum, tkurs FROM unterrichtkonflikt WHERE id = ?");
      $sql->bind_param("i", $kid);
    }
    else {
      $sql = $dbs->prepare("SELECT tlehrer, traum, tkurs FROM unterricht WHERE id = ?");
      $sql->bind_param("i", $uid);
    }
    $sql->execute();
    $sql->bind_result($LEHRERID, $RAUMID, $KURSID);
    $sql->fetch();
    $sql->close();
  }


  // suche zugehörigen ZEITRAUM und dazu gehörige SCHULSTUNDEN
  $SCHULJAHR = null;
  $ZEITRAUM = null;
  $beginns = null;
  $beginnm = null;
  $sql = $dbs->prepare("SELECT zeitraeume.id, schuljahr, schuljahre.beginn, schuljahre.ende FROM zeitraeume JOIN schuljahre ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.beginn <= ? AND zeitraeume.ende >= ?");
  $sql->bind_param("ii", $heute, $heute);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM, $SCHULJAHR, $SJBEGINN, $SJENDE);
    $sql->fetch();
  }
  $sql->close();

  if ($ZEITRAUM != null) {
    $sql = $dbs->prepare("SELECT beginns, beginnm FROM schulstunden WHERE id = ? AND zeitraum = ? ORDER BY beginns, beginnm");
    $sql->bind_param("ii", $std, $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($beginns, $beginnm);
      $sql->fetch();
    }
    $sql->close();
  }

  $code = "";
  if (($SCHULJAHR !== null) && ($ZEITRAUM !== null) && ($beginns !== null) && ($beginnm !== null)) {
    $jetzt = mktime($beginns,$beginnm,0, $monat, $tag, $jahr);
    // LEHRER SUCHEN
    $LEHRER = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT lehrer.id, IFNULL(zusatz, 0) AS summe, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer LEFT JOIN (SELECT lehrer.id AS lid, COUNT(zusatz.id) AS zusatz FROM lehrer LEFT JOIN unterricht AS zusatz ON lehrer.id = zusatz.tlehrer WHERE zusatz.tlehrer != zusatz.plehrer AND tbeginn >= ? AND tende <= ? AND vplanart != 'e' GROUP BY lid) AS y ON lehrer.id = y.lid JOIN personen ON lehrer.id = personen.id) AS z ORDER BY summe, kuerzel, nachname, vorname");
    $sql->bind_param("ii", $SJBEGINN, $SJENDE);
    if ($sql->execute()) {
      $sql->bind_result($id, $summe, $vorname, $nachname, $titel, $kuerzel);
      while ($sql->fetch()) {
        $LEHRER[$id]['id'] = $id;
        $text = cms_generiere_anzeigename($vorname, $nachname, $titel);
        if (strlen($kuerzel) > 0) {$text = $kuerzel." - ".$text;}
        $LEHRER[$id]['text'] = $text." ($summe)";
        $LEHRER[$id]['status'] = "";
      }
    }
    $sql->close();


    // Kollegen suchen, die heute keinen Unterricht haben
    $sql = $dbs->prepare("SELECT lehrer.id FROM lehrer WHERE id NOT IN (SELECT DISTINCT tlehrer FROM unterricht WHERE tbeginn >= ? AND tende <= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) AND id NOT IN (SELECT DISTINCT tlehrer FROM unterrichtkonflikt WHERE tbeginn >= ? AND tende <= ?)");
    $sql->bind_param("iiii", $heute, $heuteende, $heute, $heuteende);
    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $LEHRER[$lid]['status'] .= "g";
      }
    }
    $sql->close();

    // Kollegen suchen, die heute vormittag keinen Unterricht haben
    $sql = $dbs->prepare("SELECT lehrer.id FROM lehrer WHERE id NOT IN (SELECT DISTINCT tlehrer FROM unterricht WHERE tbeginn >= ? AND tende <= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT NULL)) AND id NOT IN (SELECT DISTINCT tlehrer FROM unterrichtkonflikt WHERE tbeginn >= ? AND tende <= ?)");
    $sql->bind_param("iiii", $heute, $heutemittag, $heute, $heutemittag);
    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $LEHRER[$lid]['status'] .= "m";
      }
    }
    $sql->close();


    // Ausgeplante LEHRER suchen
    $sql = $dbl->prepare("SELECT lehrer FROM ausplanunglehrer WHERE von <= ? AND bis >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $LEHRER[$lid]['status'] .= "a";
      }
    }
    $sql->close();

    // Anderweitig verplante LEHRER suchen
    if ($kid != '-') {
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT tlehrer FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT tlehrer FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ? AND id != ?)) AS x");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $jetzt, $jetzt, $kid);
    }
    else if ($uid != '-') {
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT tlehrer FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt) AND id != ?) UNION (SELECT tlehrer FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $uid, $jetzt, $jetzt);
    }
    else {
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT tlehrer FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT tlehrer FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x");
      $sql->bind_param("iiii", $jetzt, $jetzt, $jetzt, $jetzt);
    }

    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $LEHRER[$lid]['status'] .= "v";
      }
    }
    $sql->close();

    // RÄUME SUCHEN
    $RAEUME = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT raeume.id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez FROM raeume LEFT JOIN raeumestufen ON raeume.id = raeumestufen.raum LEFT JOIN stufen ON stufen.id = raeumestufen.stufe LEFT JOIN raeumeklassen ON raeume.id = raeumeklassen.raum LEFT JOIN klassen ON klassen.id = raeumeklassen.klasse GROUP BY raeume.id) AS x ORDER BY rbez");
    if ($sql->execute()) {
      $sql->bind_result($id, $bezeichnung, $sbez, $kbez);
      while ($sql->fetch()) {
        $RAEUME[$id]['id'] = $id;
        $text = $bezeichnung;
        $zusatz = "";
        if ($kbez !== null) {$zusatz .= ", ".$kbez;}
        if ($sbez !== null) {$zusatz .= ", ".$sbez;}
        if (strlen($zusatz) > 0) {$text .= " » ".substr($zusatz, 2);}
        $RAEUME[$id]['text'] = $text;
        $RAEUME[$id]['status'] = "";
      }
    }
    $sql->close();

    // Ausgeplante RÄUME suchen
    $sql = $dbl->prepare("SELECT raum FROM ausplanungraeume WHERE von <= ? AND bis >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $RAEUME[$lid]['status'] .= "a";
      }
    }
    $sql->close();

    // Anderweitig verplante RÄUME suchen
    if ($kid != '-') {
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT traum FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT traum FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ? AND id != ?)) AS x");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $jetzt, $jetzt, $kid);
    }
    else if ($uid != '-'){
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT traum FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt) AND id != ?) UNION (SELECT traum FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $uid, $jetzt, $jetzt);
    }
    else {
      $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT traum FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT traum FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x");
      $sql->bind_param("iiii", $jetzt, $jetzt, $jetzt, $jetzt);
    }

    if ($sql->execute()) {
      $sql->bind_result($lid);
      while ($sql->fetch()) {
        $RAEUME[$lid]['status'] .= "v";
      }
    }
    $sql->close();

    // KURSE laden
    $KURSEOPTIONEN = "";
    $KURSE = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bez, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id WHERE kurse.schuljahr = ?) AS x ORDER BY reihenfolge, bez");
    $sql->bind_param("i", $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($id, $bez, $reihe);
      while ($sql->fetch()) {
        $KURSE[$id]['id'] = $id;
        $KURSE[$id]['text'] = $bez;
        $KURSE[$id]['status'] = "";
      }
    }
    $sql->close();

    // Ausgeplante KLASSEN suchen
    $KLASSENRAUS = array();
    $sql = $dbl->prepare("SELECT klasse FROM ausplanungklassen WHERE von <= ? AND bis >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($klassenid);
      while ($sql->fetch()) {
        array_push($KLASSENRAUS, $klassenid);
      }
    }
    $sql->close();

    // Ausgeplante STUFEN suchen
    $STUFENRAUS = array();
    $sql = $dbl->prepare("SELECT stufe FROM ausplanungstufen WHERE von <= ? AND bis >= ?");
    $sql->bind_param("ii", $jetzt, $jetzt);
    if ($sql->execute()) {
      $sql->bind_result($stufenid);
      while ($sql->fetch()) {
        array_push($STUFENRAUS, $stufenid);
      }
    }
    $sql->close();

    // Ausgeplante KURSE suchen
    if ((count($KLASSENRAUS) > 0) || (count($STUFENRAUS) > 0)) {
      if ((count($KLASSENRAUS) > 0) && (count($STUFENRAUS) == 0)) {
        $klassenr = implode(',', $KLASSENRAUS);
        $sql = $dbs->prepare("SELECT kurs FROM kurseklassen WHERE klasse IN ($klassenr)");
      }
      else if ((count($KLASSENRAUS) == 0) && (count($STUFENRAUS) > 0)) {
        $stufenr = implode(',', $STUFENRAUS);
        $sql = $dbs->prepare("SELECT id FROM kurse WHERE stufe IN ($stufenr)");
      }
      else if ((count($KLASSENRAUS) > 0) && (count($STUFENRAUS) > 0)) {
        $klassenr = implode(',', $KLASSENRAUS);
        $stufenr = implode(',', $STUFENRAUS);
        $sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT kurs FROM kurseklassen WHERE klasse IN ($klassenr)) UNION (SELECT id AS kurs FROM kurse WHERE stufe IN ($stufenr))) AS x");
      }
      if ($sql->execute()) {
        $sql->bind_result($kursid);
        while ($sql->fetch()) {
          $KURSE[$kursid]['status'] .= "a";
        }
      }
      $sql->close();
    }

    // Anderweitig verplante KURSE suchen
    if ($kid != '-') {
      $sql = ")";
      $sql = $dbs->prepare("SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs IN (SELECT DISTINCT * FROM ((SELECT tkurs FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT tkurs FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ? AND id != ?)) AS x))");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $jetzt, $jetzt, $kid);
    }
    else if ($uid != '-'){
      $sql = $dbs->prepare("SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs IN (SELECT DISTINCT * FROM ((SELECT tkurs FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt) AND id != ?) UNION (SELECT tkurs FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x))");
      $sql->bind_param("iiiii", $jetzt, $jetzt, $uid, $jetzt, $jetzt);
    }
    else {
      $sql = $dbs->prepare("SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT klasse FROM kurseklassen WHERE kurs IN (SELECT DISTINCT * FROM ((SELECT tkurs FROM unterricht WHERE tbeginn <= ? AND tende >= ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt)) UNION (SELECT tkurs FROM unterrichtkonflikt WHERE tbeginn <= ? AND tende >= ?)) AS x))");
      $sql->bind_param("iiii", $jetzt, $jetzt, $jetzt, $jetzt);
    }
    if ($sql->execute()) {
      $sql->bind_result($kursid);
      while ($sql->fetch()) {
        $KURSE[$kursid]['status'] .= "v";
      }
    }
    $sql->close();

    $KURSEOPTIONENF = "";
    $KURSEOPTIONENV = "";
    $KURSEOPTIONENA = "";
    foreach ($KURSE AS $E) {
      if (preg_match("/a/", $E['status'])) {$KURSEOPTIONENA .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else if (preg_match("/v/", $E['status'])) {$KURSEOPTIONENV .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else {$KURSEOPTIONENF .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
    }
    $KURSEOPTIONEN = "<optgroup label=\"Freie Kurse\">$KURSEOPTIONENF</optgroup>";
    $KURSEOPTIONEN .= "<optgroup label=\"Verplante Kurse\">$KURSEOPTIONENV</optgroup>";
    $KURSEOPTIONEN .= "<optgroup label=\"Ausgeplante Kurse\">$KURSEOPTIONENA</optgroup>";

    $LEHREROPTIONENF = "";
    $LEHREROPTIONENV = "";
    $LEHREROPTIONENG = "";
    $LEHREROPTIONENM = "";
    $LEHREROPTIONENA = "";
    foreach ($LEHRER AS $E) {
      if (preg_match("/a/", $E['status'])) {$LEHREROPTIONENA .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else if (preg_match("/v/", $E['status'])) {$LEHREROPTIONENV .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else if (preg_match("/g/", $E['status'])) {$LEHREROPTIONENG .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else if (preg_match("/m/", $E['status'])) {$LEHREROPTIONENM .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else {$LEHREROPTIONENF .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
    }
    $LEHREROPTIONEN = "<optgroup label=\"Freie Lehrkräfte\">$LEHREROPTIONENF</optgroup>";
    $LEHREROPTIONEN .= "<optgroup label=\"Lehrkräfte mit freiem Vormittag\">$LEHREROPTIONENM</optgroup>";
    $LEHREROPTIONEN .= "<optgroup label=\"Lehrkräfte mit freiem Tag\">$LEHREROPTIONENG</optgroup>";
    $LEHREROPTIONEN .= "<optgroup label=\"Verplante Lehrkräfte\">$LEHREROPTIONENV</optgroup>";
    $LEHREROPTIONEN .= "<optgroup label=\"Ausgeplante Lehrkräfte\">$LEHREROPTIONENA</optgroup>";

    $RAEUMEOPTIONENF = "";
    $RAEUMEOPTIONENV = "";
    $RAEUMEOPTIONENA = "";
    foreach ($RAEUME AS $E) {
      if (preg_match("/a/", $E['status'])) {$RAEUMEOPTIONENA .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else if (preg_match("/v/", $E['status'])) {$RAEUMEOPTIONENV .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
      else {$RAEUMEOPTIONENF .= "<option value=\"".$E['id']."\">".$E['text']."</option>";}
    }
    $RAEUMEOPTIONEN = "<optgroup label=\"Freie Räume\">$RAEUMEOPTIONENF</optgroup>";
    $RAEUMEOPTIONEN .= "<optgroup label=\"Verplante Räume\">$RAEUMEOPTIONENV</optgroup>";
    $RAEUMEOPTIONEN .= "<optgroup label=\"Ausgeplante Räume\">$RAEUMEOPTIONENA</optgroup>";

    $code = str_replace("<option value=\"$KURSID\"", "<option value=\"$KURSID\" selected=\"selected\"", $KURSEOPTIONEN)."|||";
    $code .= str_replace("<option value=\"$LEHRERID\"", "<option value=\"$LEHRERID\" selected=\"selected\"", $LEHREROPTIONEN)."|||";
    $code .= str_replace("<option value=\"$RAUMID\"", "<option value=\"$RAUMID\" selected=\"selected\"", $RAEUMEOPTIONEN);

  }

  cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbl);
cms_trennen($dbs);
?>
