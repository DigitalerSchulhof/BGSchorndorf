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
if (isset($_POST['tag']))         {$tag = $_POST['tag'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat']))       {$monat = $_POST['monat'];}                     else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr']))        {$jahr = $_POST['jahr'];}                       else {cms_anfrage_beenden(); exit;}
if (isset($_POST['std']))         {$std = $_POST['std'];}                         else {cms_anfrage_beenden(); exit;}
if (isset($_POST['min']))         {$min = $_POST['min'];}                         else {cms_anfrage_beenden(); exit;}

if ((!cms_check_ganzzahl($uid,0)) && ($uid != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($kid,0)) && ($kid != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($tag,1,31)) && ($tag != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($monat,1,12)) && ($monat != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($jahr,0)) && ($jahr != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($std,0,23)) && ($std != '-')) {cms_anfrage_beenden(); exit;}
if ((!cms_check_ganzzahl($min,0,59)) && ($min != '-')) {cms_anfrage_beenden(); exit;}
if (($uid == '-') && ($kid == '-')) {
  if (($tag == '-') || ($monat == '-') || ($jahr == '-') || ($std == '-') || ($min == '-')) {$fehler = true;}
  $jetzt = mktime($std, $min, 0, $monat, $tag, $jahr);
  $tag = date("d", $jetzt);
  $monat = date("m", $jetzt);
  $jahr = date("Y", $jetzt);
  $std = date("H", $jetzt);
  $min = date("i", $jetzt);
}



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
  $dbl = cms_verbinden('l');

  // Lade Schulstunde
  $vplanbem = "";
  $vplanart = "s";
  $vplananz = 0;
  $DETAILS = array();
  if (($uid != '-') || ($kid != '-')) {
    if ($uid != '-') {
      $sql = $dbs->prepare("SELECT unterricht.id AS uid, unterrichtkonflikt.id AS kid, pkurs, pbeginn, pende, plehrer, praum, unterricht.tkurs, unterricht.tbeginn, unterricht.tende, unterricht.tlehrer, unterricht.traum, unterricht.vplananzeigen, unterricht.vplanart, AES_DECRYPT(unterricht.vplanbemerkung, '$CMS_SCHLUESSEL'), unterrichtkonflikt.tkurs, unterrichtkonflikt.tbeginn, unterrichtkonflikt.tende, unterrichtkonflikt.tlehrer, unterrichtkonflikt.traum, unterrichtkonflikt.vplananzeigen, unterrichtkonflikt.vplanart, AES_DECRYPT(unterrichtkonflikt.vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterricht LEFT JOIN unterrichtkonflikt ON unterricht.id = unterrichtkonflikt.altid WHERE unterricht.id = ?");
      $sql->bind_param("i", $uid);
    }
    else {
      $sql = $dbs->prepare("SELECT unterricht.id AS uid, unterrichtkonflikt.id AS kid, pkurs, pbeginn, pende, plehrer, praum, unterricht.tkurs, unterricht.tbeginn, unterricht.tende, unterricht.tlehrer, unterricht.traum, unterricht.vplananzeigen, unterricht.vplanart, AES_DECRYPT(unterricht.vplanbemerkung, '$CMS_SCHLUESSEL'), unterrichtkonflikt.tkurs, unterrichtkonflikt.tbeginn, unterrichtkonflikt.tende, unterrichtkonflikt.tlehrer, unterrichtkonflikt.traum, unterrichtkonflikt.vplananzeigen, unterrichtkonflikt.vplanart, AES_DECRYPT(unterrichtkonflikt.vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt LEFT JOIN unterricht ON unterricht.id = unterrichtkonflikt.altid WHERE unterrichtkonflikt.id = ?");
      $sql->bind_param("i", $kid);
    }
    if ($sql->execute()) {
      $sql->bind_result($DETAILS['uid'], $DETAILS['kid'], $DETAILS['regelkurs'], $DETAILS['regelbeginn'], $DETAILS['regelende'], $DETAILS['regellehrer'], $DETAILS['regelraum'], $DETAILS['aktkurs'], $DETAILS['aktbeginn'], $DETAILS['aktende'], $DETAILS['aktlehrer'], $DETAILS['aktraum'], $vplananz, $vplanart, $vplanbem, $DETAILS['neukurs'], $DETAILS['neubeginn'], $DETAILS['neuende'], $DETAILS['neulehrer'], $DETAILS['neuraum'], $DETAILS['neuvplan'], $DETAILS['neuvart'], $DETAILS['neuvtext']);
      $sql->fetch();
      if (($kid != '-')) {
        $tag = date("d", $DETAILS['neubeginn']);
        $monat = date("m", $DETAILS['neubeginn']);
        $jahr = date("Y", $DETAILS['neubeginn']);
        $std = date("H", $DETAILS['neubeginn']);
        $min = date("i", $DETAILS['neubeginn']);
      }
      else {
        $tag = date("d", $DETAILS['aktbeginn']);
        $monat = date("m", $DETAILS['aktbeginn']);
        $jahr = date("Y", $DETAILS['aktbeginn']);
        $std = date("H", $DETAILS['aktbeginn']);
        $min = date("i", $DETAILS['aktbeginn']);
      }
      $jetzt = mktime($std, $min, 0, $monat, $tag, $jahr);
    }
    $sql->close();
  }

  // suche zugehörigen ZEITRAUM und dazu gehörige SCHULSTUNDEN
  $SCHULJAHR = null;
  $ZEITRAUM = null;
  $SCHULSTUNDEN = array();
  $SCHULSTUNDENOPTIONEN = "<option value=\"-\">Entfall</option>";
  $sql = $dbs->prepare("SELECT id, beginn, ende FROM schuljahre WHERE beginn <= ? AND ende >= ?");
  $sql->bind_param("ii", $jetzt, $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($SCHULJAHR, $SJBEGINN, $SJENDE);
    $sql->fetch();
  }
  $sql->close();

  $sql = $dbs->prepare("SELECT zeitraeume.id, schulstunden.id, AES_DECRYPT(schulstunden.bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM zeitraeume LEFT JOIN schulstunden ON zeitraeume.id = schulstunden.zeitraum WHERE beginn <= ? AND ende >= ? ORDER BY beginns, beginnm");
  $sql->bind_param("ii", $jetzt, $jetzt);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM, $stdid, $stdbez, $stdbeginns, $stdbeginnm);
    while ($sql->fetch()) {
      if ($stdid !== null) {
        $zeit = cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm);
        $SCHULSTUNDEN[$zeit] = array();
        $SCHULSTUNDEN[$zeit]['id'] = $stdid;
        $SCHULSTUNDEN[$zeit]['bez'] = $stdbez;
        $SCHULSTUNDENOPTIONEN .= "<option value=\"$stdid\">$stdbez</option>";
      }
    }
  }
  $sql->close();


  $code = "";
  if (($SCHULJAHR === null) || ($ZEITRAUM === null) || (count($SCHULSTUNDEN) == 0)) {
    $code .= '<p class="cms_notiz">Für dieses Datum ist kein Planungszeitraum verfügbar.</p>';
  }
  else {
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

    $heute = mktime(0,0,0, $monat, $tag, $jahr);
    $heutemittag = mktime(12,59,59, $monat, $tag, $jahr);
    $heuteende = mktime(0,0,0, $monat, $tag+1, $jahr)-1;

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
    else if ($uid != '-') {
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

    $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Zeit:</th><th>Details:</th><th>Bemerkung:</th></tr>";

    $code .= "<tr><td>".cms_datum_eingabe ('cms_stundendetails_datum', $tag, $monat, $jahr, 'cms_vplan_stundendetails_stunden_laden(\''.$uid.'\', \''.$kid.'\')')."</td><td><select id=\"cms_stundendetails_lehrer\" name=\"cms_stundendetails_lehrer\" onchange=\"cms_vplan_wochenplan_l('d')\">";
    if ($kid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['neulehrer']."\"", "<option value=\"".$DETAILS['neulehrer']."\" selected=\"selected\"", $LEHREROPTIONEN);
    }
    else if ($uid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['aktlehrer']."\"", "<option value=\"".$DETAILS['aktlehrer']."\" selected=\"selected\"", $LEHREROPTIONEN);
    }
    else {$code .= $LEHREROPTIONEN;}
    $code .= "</select></td><td><input type=\"text\" id=\"cms_stundendetails_vbem\" name=\"cms_stundendetails_vbem\" value=\"";
    if ($kid != '-') {
      $code .= $DETAILS['neuvtext'];
    }
    else if ($uid != '-') {
      $code .= $vplanbem;
    }
    $code .= "\"></td></tr>";


    $code .= "<tr><td><select id=\"cms_stundendetails_std\" name=\"cms_stundendetails_std\" onchange=\"cms_vplan_freieressourcen_laden('d', '$uid', '$kid');\">";
    if ($kid != '-') {
      if ($DETAILS['neuvart'] != 'e') {
        $code .= str_replace("<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\"", "<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\" selected=\"selected\"", $SCHULSTUNDENOPTIONEN);
      }
      else {
        $code .= $SCHULSTUNDENOPTIONEN;
      }
    }
    else if ($uid != '-') {
      if ($vplanart != 'e') {
        $code .= str_replace("<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\"", "<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\" selected=\"selected\"", $SCHULSTUNDENOPTIONEN);
      }
      else {
        $code .= $SCHULSTUNDENOPTIONEN;
      }
    }
    else {$code .= str_replace("<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\"", "<option value=\"".$SCHULSTUNDEN[$std.":".$min]['id']."\" selected=\"selected\"", substr($SCHULSTUNDENOPTIONEN, 34));}
    $code .= "</select></td><td><select id=\"cms_stundendetails_raum\" name=\"cms_stundendetails_raum\" onchange=\"cms_vplan_wochenplan_r('d')\">";
    if ($kid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['neuraum']."\"", "<option value=\"".$DETAILS['neuraum']."\" selected=\"selected\"", $RAEUMEOPTIONEN);
    }
    else if ($uid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['aktraum']."\"", "<option value=\"".$DETAILS['aktraum']."\" selected=\"selected\"", $RAEUMEOPTIONEN);
    }
    else {$code .= $RAEUMEOPTIONEN;}
    $code .= "</select></td><th>Sichtbar:</th></tr>";

    $code .= "<tr><td class=\"cms_notiz\">";
    if ($kid != '-') {
      if ($DETAILS['neuvplan'] == 0) {$code .= "aktuell unsichtbar";}
    }
    else if ($uid != '-') {
      if ($vplananz == 0) {$code .= "aktuell unsichtbar";}
    }
    $code .= "</td><td><select id=\"cms_stundendetails_kurs\" name=\"cms_stundendetails_kurs\">";
    if ($kid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['neukurs']."\"", "<option value=\"".$DETAILS['neukurs']."\" selected=\"selected\"", $KURSEOPTIONEN);
    }
    else if ($uid != '-') {
      $code .= str_replace("<option value=\"".$DETAILS['aktkurs']."\"", "<option value=\"".$DETAILS['aktkurs']."\" selected=\"selected\"", $KURSEOPTIONEN);
    }
    else {$code .= $KURSEOPTIONEN;}
    $code .= "</select></td><td>";
    if ($kid != '-') {
      $code .= cms_generiere_schieber('stundendetails_vanz',1);
    }
    else if ($uid != '-') {
      $code .= cms_generiere_schieber('stundendetails_vanz',1);
    }
    else {$code .= cms_generiere_schieber('stundendetails_vanz',1);}
    $code .= "</td></tr>";
    $code .= "</table>";




    if (($uid != '-') || ($kid != '-')) {
      $zusatzinfo = "";
      if ($DETAILS['regelbeginn'] !== null) {
        $zusatzinfo .= "<p class=\"cms_notiz\"><b>Regelstundenplan:</b> ".date("d.m.Y", $DETAILS['regelbeginn'])." - ".$SCHULSTUNDEN[date("H:i", $DETAILS['regelbeginn'])]['bez'].": ";
        $zusatzinfo .= $KURSE[$DETAILS['regelkurs']]['text']." bei ".$LEHRER[$DETAILS['regellehrer']]['text']." in ".$RAEUME[$DETAILS['regelraum']]['text']."</p>";
      }
      else {$zusatzinfo .= "<p class=\"cms_notiz\"><b>Regelstundenplan:</b> nicht vorhanden - Zusatzstunde</p>";}
      if ($DETAILS['aktbeginn'] !== null) {
        $zusatzinfo .= "<p class=\"cms_notiz\"><b>Aktuell:</b> ";
        if ($vplanart == 'e') {
          $zusatzinfo .= "Entfall</p>";
        }
        else {
          $zusatzinfo .= date("d.m.Y", $DETAILS['aktbeginn'])." - ".$SCHULSTUNDEN[date("H:i", $DETAILS['aktbeginn'])]['bez'].": ";
          $zusatzinfo .= $KURSE[$DETAILS['aktkurs']]['text']." bei ".$LEHRER[$DETAILS['aktlehrer']]['text']." in ".$RAEUME[$DETAILS['aktraum']]['text']."</p>";
        }
      }
      else {$zusatzinfo .= "<p class=\"cms_notiz\"><b>Aktuell:</b> nicht vorhanden - nur vorgemerkt</p>";}
      if ($DETAILS['neubeginn'] !== null) {
        $zusatzinfo .= "<p class=\"cms_notiz\"><b>Vorgemerkt:</b> ";
        if ($DETAILS['neuvart'] == 'e') {
          $zusatzinfo .= "Entfall";
        }
        else {
          $zusatzinfo .= date("d.m.Y", $DETAILS['neubeginn'])." - ".$SCHULSTUNDEN[date("H:i", $DETAILS['neubeginn'])]['bez'].": ";
          $zusatzinfo .= $KURSE[$DETAILS['neukurs']]['text']." bei ".$LEHRER[$DETAILS['neulehrer']]['text']." in ".$RAEUME[$DETAILS['neuraum']]['text']."</p>";
        }
      }
      else {$zusatzinfo .= "<p class=\"cms_notiz\"><b>Vorgemerkt:</b> keine Änderung vorgemerkt</p>";}
      $code .= $zusatzinfo;
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_stunde_aendern('$uid', '$kid', 'd')\">Änderung übernehmen</span> <span class=\"cms_button\" onclick=\"cms_vplan_stunde_zusatzstunde()\">Als Zusatzstunde speichern</span> ";
      if ((($kid != '-') && ($DETAILS['neuvart'] != 'e')) || (($kid == '-') && ($uid != '-') && ($vplanart != 'e'))) {
        $code .= "<span class=\"cms_button_wichtig\" onclick=\"cms_vplan_stunde_entfall ('$uid', '$kid')\">Entfall</span> ";
        $code .= "<span class=\"cms_button_wichtig\" onclick=\"cms_vplan_stunde_entfall ('$uid', '$kid', '0')\">Unsichtbarer Entfall</span> ";
      }

      if ($kid != '-') {
        $code .= "<span class=\"cms_button_nein\" onclick=\"cms_vplan_stunde_aenderungenzurueck_anzeigen ('$uid', '$kid')\">Änderung löschen</span>";
      }
      $gerade = time();
      if (($DETAILS['regelbeginn'] >= $gerade) && ($DETAILS['aktbeginn'] >= $gerade)) {
        $code .= " <span class=\"cms_button_nein\" onclick=\"cms_vplan_stunde_regelstundenplan_anzeigen ('$uid', '$kid')\">Auf Regelstundenplan zurücksetzen</span>";
      }
      $code .= "<input type=\"hidden\" name=\"cms_stundendetails_zusatz\" id=\"cms_stundendetails_zusatz\" value=\"0\"></p>";
    }
    else {
      $code .= "<p><span class=\"cms_button\" onclick=\"cms_vplan_stunde_zusatzstunde()\">Als Zusatzstunde speichern</span> <input type=\"hidden\" name=\"cms_stundendetails_zusatz\" id=\"cms_stundendetails_zusatz\" value=\"1\"></p>";
    }
    $code .= "<p><input type=\"hidden\" id=\"cms_stundendetails_uid\" name=\"cms_stundendetails_uid\" value=\"$uid\"><input type=\"hidden\" id=\"cms_stundendetails_kid\" name=\"cms_stundendetails_kid\" value=\"$kid\"></p>";
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
