<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag'])) 		    {$tag = $_POST['tag'];} 			                  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat'])) 		  {$monat = $_POST['monat'];} 			              else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr'])) 		    {$jahr = $_POST['jahr'];} 			                else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sortierung'])) 	{$sortierung = $_POST['sortierung'];} 			    else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sortierrichtung'])) 	{$sortierrichtung = $_POST['sortierrichtung'];} 			    else {cms_anfrage_beenden(); exit;}

if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}
if (!cms_check_toggle($sortierrichtung)) {cms_anfrage_beenden(); exit;}
if (($sortierung != 'k') && ($sortierung != 'l') && ($sortierung != 'r') && ($sortierung != 's')) {cms_anfrage_beenden(); exit;}

if ($sortierrichtung == '1') {$sortierungzusatz = "DESC";}
else {$sortierungzusatz = "ASC";}

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();

// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung");

if ($angemeldet && $zugriff) {
  $code = "";
  include_once("../../lehrerzimmer/seiten/verwaltung/vertretungsplanung/vplanunterrichtausgeben.php");
  // Ausgabezeiträume bestimmen
  $hb = mktime(0,0,0,$monat,$tag,$jahr);
  $he = mktime(0,0,0,$monat,$tag+1,$jahr)-1;
  $dbl = cms_verbinden('l');
  $dbs = cms_verbinden('s');

  // Zeitraum laden
  $ZEITRAUM = null;
  $sql = $dbs->prepare("SELECT id FROM zeitraeume WHERE (? BETWEEN beginn AND ende) OR (? BETWEEN beginn AND ende) OR (? <= beginn AND ? >= ende) ORDER BY beginn");
  $sql->bind_param("iiii", $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM);
    $sql->fetch();
  }
  $sql->close();

  if ($ZEITRAUM === null) {
    $code .= '<p class="cms_notiz">Für dieses Datum ist kein Planungszeitraum verfügbar.</p>';
  }
  else {
    // Schulstunden für die einzelnen Zeiträume laden
    $SCHULSTUNDEN = array();
    $SCHULSTUNDENOPTIONEN = "<option value=\"-\">Entfall</option>";
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
    $sql->bind_param("i", $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm);
      while ($sql->fetch()) {
        $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)]['id'] = $stdid;
        $SCHULSTUNDEN[cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm)]['bez'] = $stdbez;
        $SCHULSTUNDENOPTIONEN .= "<option value=\"$stdid\">$stdbez</option>";
      }
    }
    $sql->close();


    // Alle Lehrer laden
    $LEHREROPTIONEN = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT lehrer.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM lehrer JOIN personen ON lehrer.id = personen.id) AS x ORDER BY kuerzel, nachname, vorname");
    if ($sql->execute()) {
      $sql->bind_result($lid, $lvor, $lnach, $ltit, $lkurz);
      while ($sql->fetch()) {
        $text = cms_generiere_anzeigename($lvor, $lnach, $ltit);
        if (strlen($lkurz) > 0) {$text = $lkurz." - ".$text;}
        $LEHREROPTIONEN .= "<option value=\"$lid\">$text</option>";
      }
    }
    $sql->close();

    // Alle Kurse laden
    $KURSEOPTIONEN = "";
    $sql = $dbs->prepare("SELECT * FROM (SELECT kurse.id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, reihenfolge FROM kurse LEFT JOIN stufen ON kurse.stufe = stufen.id) AS x ORDER BY reihenfolge, bezeichnung");
    if ($sql->execute()) {
      $sql->bind_result($kursid, $kursbez, $kursreihe);
      while ($sql->fetch()) {
        $KURSEOPTIONEN .= "<option value=\"$kursid\">$kursbez</option>";
      }
    }
    $sql->close();

    // Alle Räume laden
    $RAEUMEOPTIONEN = "";
    $RAEUME = array();
    $sql = $dbs->prepare("SELECT * FROM (SELECT raeume.id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS rbez, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS kbez FROM raeume LEFT JOIN raeumestufen ON raeume.id = raeumestufen.raum LEFT JOIN stufen ON stufen.id = raeumestufen.stufe LEFT JOIN raeumeklassen ON raeume.id = raeumeklassen.raum LEFT JOIN klassen ON klassen.id = raeumeklassen.klasse GROUP BY raeume.id) AS x ORDER BY rbez");
    if ($sql->execute()) {
      $sql->bind_result($rid, $rbezeichnung, $sbez, $kbez);
      while ($sql->fetch()) {
        $text = $rbezeichnung;
        $zusatz = "";
        if ($kbez !== null) {$zusatz .= ", ".$kbez;}
        if ($sbez !== null) {$zusatz .= ", ".$sbez;}
        if (strlen($zusatz) > 0) {$text .= " » ".substr($zusatz, 2);}
        $RAEUMEOPTIONEN .= "<option value=\"$rid\">$text</option>";
      }
    }
    $sql->close();


    // AUSPLANUNGEN laden
    $AUSPLANUNGENL = array();
    $AUSPLANUNGENK = array();
    $AUSPLANUNGENR = array();
    $AUSPLANUNGENS = array();
    $sql = "SELECT id, lehrer, grund, von, bis FROM ausplanunglehrer WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
    $sql = $dbl->prepare($sql);
    $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($aid, $zid, $grund, $von, $bis);
      while ($sql->fetch()) {
        $a = array();
        $a['aid'] = $aid;
        $a['zid'] = $zid;
        $a['grund'] = $grund;
        $a['von'] = $von;
        $a['bis'] = $bis;
        array_push($AUSPLANUNGENL, $a);
      }
    }
    $sql->close();
    $sql = "SELECT id, raum, grund, von, bis FROM ausplanungraeume WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
    $sql = $dbl->prepare($sql);
    $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($aid, $zid, $grund, $von, $bis);
      while ($sql->fetch()) {
        $a = array();
        $a['aid'] = $aid;
        $a['zid'] = $zid;
        $a['grund'] = $grund;
        $a['von'] = $von;
        $a['bis'] = $bis;
        array_push($AUSPLANUNGENR, $a);
      }
    }
    $sql->close();
    $sql = "SELECT id, klasse, grund, von, bis FROM ausplanungklassen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
    $sql = $dbl->prepare($sql);
    $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($aid, $zid, $grund, $von, $bis);
      while ($sql->fetch()) {
        $a = array();
        $a['aid'] = $aid;
        $a['zid'] = $zid;
        $a['grund'] = $grund;
        $a['von'] = $von;
        $a['bis'] = $bis;
        array_push($AUSPLANUNGENK, $a);
      }
    }
    $sql->close();
    $sql = "SELECT id, stufe, grund, von, bis FROM ausplanungstufen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
    $sql = $dbl->prepare($sql);
    $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($aid, $zid, $grund, $von, $bis);
      while ($sql->fetch()) {
        $a = array();
        $a['aid'] = $aid;
        $a['zid'] = $zid;
        $a['grund'] = $grund;
        $a['von'] = $von;
        $a['bis'] = $bis;
        array_push($AUSPLANUNGENS, $a);
      }
    }
    $sql->close();

    // UNTERRICHT finden, der sich mit KONFLIKTLÖSUNGEN überschneidet
    // UNTERRICHTSKONFLIKTLÖSUNGEN LADEN
    $LOESUNGEN = array();
    $LOESUNGENLISTE = array();
    $LOESUNGENIDS = array();
    $sql = "SELECT altid AS uid, unterrichtkonflikt.id AS kid, tkurs, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), tbeginn, tende, tlehrer, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raum, klassen.id AS tklasse, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, stufen.id AS tstufe, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge, vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt LEFT JOIN kurse ON unterrichtkonflikt.tkurs = kurse.id LEFT JOIN raeume ON unterrichtkonflikt.traum = raeume.id LEFT JOIN lehrer ON unterrichtkonflikt.tlehrer = lehrer.id LEFT JOIN personen ON unterrichtkonflikt.tlehrer = personen.id LEFT JOIN stufen ON kurse.stufe = stufen.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id WHERE tbeginn >= ? AND tende <= ?";
    $sql = "SELECT * FROM ($sql) AS x ORDER BY ";
    if ($sortierung == 's') {$sql .= "tbeginn $sortierungzusatz, reihenfolge, klasse, kuerzel, nach, vor, tbeginn";}
    if ($sortierung == 'l') {$sql .= "kuerzel $sortierungzusatz, nach, vor, tbeginn, reihenfolge, klasse";}
    if ($sortierung == 'r') {$sql .= "raum $sortierungzusatz, tbeginn, reihenfolge, klasse";}
    if ($sortierung == 'k') {$sql .= "reihenfolge $sortierungzusatz, klasse, tbeginn, kuerzel, nach, vor, tbeginn";}
    $sql = $dbs->prepare($sql);
    $sql->bind_param("ii", $hb, $he);
    if ($sql -> execute()) {
      $sql -> bind_result($uid, $kid, $tkurs, $kurskurz, $kursbez, $tbeginn, $tende, $tlehrer, $lehrerkurz, $lehrervor, $lehrernach, $lehrertit, $traum, $raumbez, $tklasse, $klassenbez, $tstufe, $stufenbez, $reihenfolge, $vplananz, $vplanart, $vplanbem);
      while ($sql -> fetch()) {
        if (!in_array($kid, $LOESUNGENIDS)) {
          $l = array();
          $l['uid'] = $uid;
          $l['kid'] = $kid;
          $l['beginn'] = $tbeginn;
          $l['lehrer'] = $tlehrer;
          $l['raum'] = $traum;
          $l['kurs'] = $tkurs;
          $l['klasse'] = array();
          if (strlen($tklasse > 0)) {array_push($l['klasse'], $tklasse);}
          $LOESUNGEN[$kid] = $l;
          array_push($LOESUNGENIDS, $kid);
        }
        else {
          if (!in_array($tklasse, $LOESUNGEN[$kid]['klasse']) && strlen($tklasse > 0)) {
            array_push($LOESUNGEN[$kid]['klasse'], $tklasse);
          }
        }
        if ($kid === null) {$kid = '-';}
        if ($uid === null) {$uid = '-';}
        if (strlen($klassenbez) > 0) {$klassentext = $klassenbez;}
        else if (strlen($stufenbez) > 0) {$klassentext = $stufenbez;}
        else {$klassentext = "-";}
        $lehrertext = cms_generiere_anzeigename($lehrervor, $lehrernach, $lehrertit);
        if (strlen($lehrerkurz) > 0) {$lehrertext = $lehrerkurz." - ".$lehrertext;}
        if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$schulstd = $SCHULSTUNDEN[date("H:i", $tbeginn)]['id'];}
        else {$schulstd = "";}
        if ($tklasse !== null) {$kauswahl = 'k'.$tklasse;}
        else if ($tstufe !== null) {$kauswahl = 's'.$tstufe;}
        else {$kauswahl = '';}
        $l = array();
        $l['uid'] = $uid;
        $l['kid'] = $kid;
        $l['tklasse'] = $tklasse;
        $l['klassenid'] = $kauswahl;
        $l['klassentext'] = $klassentext;
        $l['tkurs'] = $tkurs;
        $l['kursbez'] = $kursbez;
        $l['beginn'] = $tbeginn;
        $l['ende'] = $tende;
        $l['tlehrer'] = $tlehrer;
        $l['tstufe'] = $tstufe;
        $l['lehrertext'] = $lehrertext;
        $l['traum'] = $traum;
        $l['raumbez'] = $raumbez;
        $l['schulstd'] = $schulstd;
        $l['reihenfolge'] = $reihenfolge;
        $l['vplananz'] = $vplananz;
        $l['vplanart'] = $vplanart;
        $l['vplanbem'] = $vplanbem;
        array_push($LOESUNGENLISTE, $l);
      }
    }
    $sql->close();


    // ÜBERSCHNEIDUNGEN SUCHEN
    // Enthält alle Konfliktlösungsids die sich mit anderem Unterricht überschneiden
    $UEBERL = array();
    $UEBERR = array();
    $UEBERK = array();
    foreach ($LOESUNGEN AS $l) {
      $sql1 = "SELECT COUNT(*) as anzahl FROM unterricht WHERE tbeginn = ? AND tlehrer = ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e'";
      $sql2 = "SELECT COUNT(*) as anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tlehrer = ? AND id != ? AND vplanart != 'e'";
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM (($sql1) UNION ($sql2)) AS x");
      $sql->bind_param("iiiii", $l['beginn'], $l['lehrer'], $l['beginn'], $l['lehrer'], $l['kid']);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        while ($sql->fetch()) {
          if ($anzahl > 0) {
            array_push($UEBERL, $l['kid']);
          }
        }
      }
      $sql->close();
      $sql1 = "SELECT COUNT(*) as anzahl FROM unterricht WHERE tbeginn = ? AND traum = ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e'";
      $sql2 = "SELECT COUNT(*) as anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND traum = ? AND id != ? AND vplanart != 'e'";
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM (($sql1) UNION ($sql2)) AS x");
      $sql->bind_param("iiiii", $l['beginn'], $l['raum'], $l['beginn'], $l['raum'], $l['kid']);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        while ($sql->fetch()) {
          if ($anzahl > 0) {
            array_push($UEBERR, $l['kid']);
          }
        }
      }
      $sql->close();
      if (count($l['klasse']) > 0) {
        $lklasse = implode(',', $l['klasse']);
        $sql1 = "SELECT COUNT(*) as anzahl FROM unterricht WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN ($lklasse)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e'";
        $sql2 = "SELECT COUNT(*) as anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN ($lklasse)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)) AND id != ? AND vplanart != 'e'";
        $sql = $dbs->prepare("SELECT SUM(anzahl) FROM (($sql1) UNION ($sql2)) AS x");
        $sql->bind_param("iiiii", $l['beginn'], $l['kurs'], $l['beginn'], $l['kurs'], $l['kid']);
        if ($sql->execute()) {
          $sql->bind_result($anzahl);
          while ($sql->fetch()) {
            if ($anzahl > 0) {
              array_push($UEBERK, $l['kid']);
            }
          }
        }
        $sql->close();
      }
    }


    // KONFLIKTE SUCHEN
    // Konflikte suchen
    $KONFLIKTEU = array();
    $KONFLIKTEK = array();
    foreach ($AUSPLANUNGENL AS $ap) {
      $sql = "SELECT DISTINCT uid, kid FROM ((SELECT id AS uid, '-' AS kid FROM unterricht WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tlehrer = ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e') UNION (SELECT altid AS uid, id AS kid FROM unterrichtkonflikt WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tlehrer = ? AND vplanart != 'e')) AS x";
      $sql = $dbs->prepare($sql);
      $apvon = max($hb, $ap['von']);
      $apbis = min($he, $ap['bis']);
      $sql->bind_param("iiiiiiiiiiiiii", $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid'], $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid']);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid);
        while ($sql->fetch()) {
          if ($kid == '-') {if (!in_array($uid, $KONFLIKTEU)) {array_push($KONFLIKTEU, $uid);}}
          else {if (!in_array($kid, $KONFLIKTEK)) {array_push($KONFLIKTEK, $kid);}}
        }
      }
      $sql->close();
    }
    foreach ($AUSPLANUNGENR AS $ap) {
      $sql = "SELECT DISTINCT uid, kid FROM ((SELECT id AS uid, '-' AS kid FROM unterricht WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND traum = ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e') UNION (SELECT altid AS uid, id AS kid FROM unterrichtkonflikt WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND traum = ? AND vplanart != 'e')) AS x";
      $sql = $dbs->prepare($sql);
      $apvon = max($hb, $ap['von']);
      $apbis = min($he, $ap['bis']);
      $sql->bind_param("iiiiiiiiiiiiii", $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid'], $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid']);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid);
        while ($sql->fetch()) {
          if ($kid == '-') {if (!in_array($uid, $KONFLIKTEU)) {array_push($KONFLIKTEU, $uid);}}
          else {if (!in_array($kid, $KONFLIKTEK)) {array_push($KONFLIKTEK, $kid);}}
        }
      }
      $sql->close();
    }
    foreach ($AUSPLANUNGENK AS $ap) {
      $sql = "SELECT DISTINCT uid, kid FROM ((SELECT id AS uid, '-' AS kid FROM unterricht WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e') UNION (SELECT altid AS uid, id AS kid FROM unterrichtkonflikt WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse = ?) AND vplanart != 'e')) AS x";
      $sql = $dbs->prepare($sql);
      $apvon = max($hb, $ap['von']);
      $apbis = min($he, $ap['bis']);
      $sql->bind_param("iiiiiiiiiiiiii", $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid'], $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid']);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid);
        while ($sql->fetch()) {
          if ($kid == '-') {if (!in_array($uid, $KONFLIKTEU)) {array_push($KONFLIKTEU, $uid);}}
          else {if (!in_array($kid, $KONFLIKTEK)) {array_push($KONFLIKTEK, $kid);}}
        }
      }
      $sql->close();
    }
    foreach ($AUSPLANUNGENS AS $ap) {
      $sql = "SELECT DISTINCT uid, kid FROM ((SELECT id AS uid, '-' AS kid FROM unterricht WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tkurs IN (SELECT id FROM kurse WHERE stufe = ?) AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e') UNION (SELECT altid AS uid, id AS kid FROM unterrichtkonflikt WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tkurs IN (SELECT id FROM kurse WHERE stufe = ?) AND vplanart != 'e')) AS x";
      $sql = $dbs->prepare($sql);
      $apvon = max($hb, $ap['von']);
      $apbis = min($he, $ap['bis']);
      $sql->bind_param("iiiiiiiiiiiiii", $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid'], $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid']);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid);
        while ($sql->fetch()) {
          if ($kid == '-') {if (!in_array($uid, $KONFLIKTEU)) {array_push($KONFLIKTEU, $uid);}}
          else {if (!in_array($kid, $KONFLIKTEK)) {array_push($KONFLIKTEK, $kid);}}
        }
      }
      $sql->close();
    }

    $zeilennr = 0;

    if ((count($KONFLIKTEU) > 0) || (count($KONFLIKTEK) > 0)) {
      // KONFLIKTUNTERRICHT LADEN
      if (count($KONFLIKTEU) > 0) {
        $uids = implode(",", $KONFLIKTEU);
        $sql1 = "SELECT unterricht.id AS uid, null AS kid, tkurs, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), tbeginn, tende, tlehrer, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raum, klassen.id AS tklasse, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, stufen.id AS tstufe, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge, vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterricht LEFT JOIN kurse ON unterricht.tkurs = kurse.id LEFT JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN lehrer ON unterricht.tlehrer = lehrer.id LEFT JOIN personen ON unterricht.tlehrer = personen.id LEFT JOIN stufen ON kurse.stufe = stufen.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id WHERE unterricht.id IN ($uids)";
      }
      if (count($KONFLIKTEK) > 0) {
        $kids = implode(",", $KONFLIKTEK);
        $sql2 = "SELECT altid AS uid, unterrichtkonflikt.id AS kid, tkurs, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL'), tbeginn, tende, tlehrer, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vor, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nach, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS raum, klassen.id AS tklasse, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klasse, stufen.id AS tstufe, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe, reihenfolge, vplananzeigen, vplanart, AES_DECRYPT(vplanbemerkung, '$CMS_SCHLUESSEL') FROM unterrichtkonflikt LEFT JOIN kurse ON unterrichtkonflikt.tkurs = kurse.id LEFT JOIN raeume ON unterrichtkonflikt.traum = raeume.id LEFT JOIN lehrer ON unterrichtkonflikt.tlehrer = lehrer.id LEFT JOIN personen ON unterrichtkonflikt.tlehrer = personen.id LEFT JOIN stufen ON kurse.stufe = stufen.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs LEFT JOIN klassen ON kurseklassen.klasse = klassen.id WHERE unterrichtkonflikt.id IN ($kids)";
      }

      if (count($KONFLIKTEK) == 0) {$sql = "SELECT * FROM ($sql1) AS x ORDER BY ";}
      else if (count($KONFLIKTEU) == 0) {$sql = "SELECT * FROM ($sql2) AS x ORDER BY ";}
      else {$sql = "SELECT * FROM (($sql1) UNION ($sql2)) AS x ORDER BY ";}
      if ($sortierung == 's') {$sql .= "tbeginn $sortierungzusatz, reihenfolge, klasse, kuerzel, nach, vor, tbeginn";}
      if ($sortierung == 'l') {$sql .= "kuerzel $sortierungzusatz, nach, vor, tbeginn, reihenfolge, klasse";}
      if ($sortierung == 'r') {$sql .= "raum $sortierungzusatz, tbeginn, reihenfolge, klasse";}
      if ($sortierung == 'k') {$sql .= "reihenfolge $sortierungzusatz, klasse, tbeginn, kuerzel, nach, vor, tbeginn";}

      $listencode = "<h4>Offene Konflikte</h4>";
      $listencode .= "<div class=\"cms_konflikte_liste_menue\" id=\"cms_konflikte_liste_menue_k\" onmousemove=\"cms_vplan_schnellmenue('k', 'j')\"  onmouseout=\"cms_vplan_schnellmenue('k', 'n')\"><span class=\"cms_aktion_klein\" onclick=\"cms_vplan_auswahl_aendern('k', 'sichtbarkeit', '1');\"><span class=\"cms_hinweis\">Sichtbar</span><img src=\"res/icons/klein/sichtbar.png\"></span> <span class=\"cms_aktion_klein\" onclick=\"cms_vplan_auswahl_aendern('k', 'sichtbarkeit', '0');\"><span class=\"cms_hinweis\">Unsichtbar</span><img src=\"res/icons/klein/unsichtbar.png\"></span> <span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('k', 'entfall', '1');\"><span class=\"cms_hinweis\">Entfall</span><img src=\"res/icons/klein/entfall.png\"></span> <span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('k', 'entfall', '0');\"><span class=\"cms_hinweis\">Unsichtbarer Entfall</span><img src=\"res/icons/klein/uentfall.png\"></span> <span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('k', 'regelstundenplan');\"><span class=\"cms_hinweis\">Auf Regelstundenplan zurücksetzen</span><img src=\"res/icons/klein/zuruecksetzen.png\"></span></div>";
      $listencode .= "<table class=\"cms_liste\">";
      $listencode .= "<tr>";
      if ($sortierung == 'k') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '$sortwert');\"></th>";
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '$sortwert');\">Kurs</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '0');\"></th>";
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '0');\">Kurs</th>";
      }
      if ($sortierung == 'l') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('l', '$sortwert');\">Lehrer</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('l', '0');\">Lehrer</th>";
      }
      if ($sortierung == 'r') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('r', '$sortwert');\">Raum</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('r', '0');\">Raum</th>";
      }
      if ($sortierung == 's') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('s', '$sortwert');\">Stunde</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('s', '0');\">Stunde</th>";
      }

      $listencode .= "<th>Bemerkung</th><th></th></tr>";
      $konfliktstunden = "";
      $sql = $dbs->prepare($sql);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid, $tkurs, $kurskurz, $kursbez, $tbeginn, $tende, $tlehrer, $lehrerkurz, $lehrervor, $lehrernach, $lehrertit, $traum, $raumbez, $tklasse, $klassenbez, $tstufe, $stufenbez, $reihenfolge, $vplananzeigen, $vplanart, $vplanbem);
        while ($sql->fetch()) {
          if ($kid === null) {$kid = '-';}
          if ($uid === null) {$uid = '-';}
          if (strlen($klassenbez) > 0) {$klassentext = $klassenbez;}
          else if (strlen($stufenbez) > 0) {$klassentext = $stufenbez;}
          else {$klassentext = "-";}
          $lehrertext = cms_generiere_anzeigename($lehrervor, $lehrernach, $lehrertit);
          if (strlen($lehrerkurz) > 0) {$lehrertext = $lehrerkurz." - ".$lehrertext;}
          if (isset($SCHULSTUNDEN[date("H:i", $tbeginn)])) {$schulstd = $SCHULSTUNDEN[date("H:i", $tbeginn)]['id'];}
          else {$schulstd = "";}

          if ($tklasse !== null) {$kauswahl = 'k'.$tklasse;}
          else if ($tstufe !== null) {$kauswahl = 's'.$tstufe;}
          else {$kauswahl = '';}

          if ($vplanart != 'e') {
            $konfliktgrund = cms_finde_konflikt_detail($tlehrer, $traum, $tklasse, $tstufe, $tbeginn, $tende, $AUSPLANUNGENL, $AUSPLANUNGENR, $AUSPLANUNGENK, $AUSPLANUNGENS);
            $ueberschneidungsgrund = cms_finde_ueberschneidung_detail($kid, $UEBERL, $UEBERR, $UEBERK);
            $zeilenklasse = "";
          }
          else {$konfliktgrund = ""; $ueberschneidungsgrund = ""; $zeilenklasse = "cms_vplan_entfall";}
          $planladenevent = "onmousedown=\"cms_vplan_wochenplan_neuladen('a', '".$tlehrer."', '".$traum."', '$kauswahl', '".date('d.m.Y', $tbeginn)."');cms_vplan_freieressourcen_laden('k', '$uid', '$kid', '_$zeilennr');\"";
          $planladeneventohne = "onmousedown=\"cms_vplan_wochenplan_neuladen('a', '".$tlehrer."', '".$traum."', '$kauswahl', '".date('d.m.Y', $tbeginn)."');\"";
          $aendereventfkt = "cms_vplan_stunde_aendern('$uid', '$kid', 'k', '', 'n', 'j', '_$zeilennr');";
          $aenderevent = " onchange=\"$aendereventfkt\"";
          $selektionevent = " onclick=\"cms_vplan_stunde_auswaehlen('$uid', '$kid', 'k'); cms_vplan_wochenplan_neuladen('a', '".$tlehrer."', '".$traum."', '$kauswahl', '".date('d.m.Y', $tbeginn)."');\"";

          if (preg_match("/k/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
          else if (preg_match("/k/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
          else {$styleklasse = "";}
          $listencode .= "<tr class=\"$zeilenklasse cms_zeilek_$uid"."_"."$kid\">";
          $listencode .= "<td class=\"$styleklasse cms_auswaehlen\"$selektionevent onmousemove=\"cms_vplan_schnellmenue('k', 'j', '$zeilennr')\" onmouseout=\"cms_vplan_schnellmenue('k', 'n')\" id=\"cms_vplan_konflikteliste_zeile_$zeilennr\">$klassentext</td>";
          $listencode .= "<td><select$aenderevent $planladeneventohne id=\"cms_kursk_$uid"."_"."$kid"."_$zeilennr\" name=\"cms_kursk_$uid"."_"."$kid"."_$zeilennr\" class=\"cms_kursk_$uid"."_"."$kid\">";
            $listencode .= str_replace("<option value=\"$tkurs\"", "<option value=\"$tkurs\" selected=\"selected\"", $KURSEOPTIONEN);
          $listencode .= "</select></td>";
          if (preg_match("/l/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
          else if (preg_match("/l/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
          else {$styleklasse = "";}
          $listencode .= "<td class=\"$styleklasse\"><select$aenderevent $planladeneventohne id=\"cms_lehrerk_$uid"."_"."$kid"."_$zeilennr\" name=\"cms_lehrerk_$uid"."_"."$kid"."_$zeilennr\" class=\"cms_lehrerk_$uid"."_"."$kid\">";
            $listencode .= str_replace("<option value=\"$tlehrer\"", "<option value=\"$tlehrer\" selected=\"selected\"", $LEHREROPTIONEN);
          $listencode .= "</select></td>";
          if (preg_match("/r/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
          else if (preg_match("/r/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
          else {$styleklasse = "";}
          $listencode .= "<td class=\"$styleklasse\"><select$aenderevent $planladeneventohne id=\"cms_raumk_$uid"."_"."$kid"."_$zeilennr\" name=\"cms_raumk_$uid"."_"."$kid"."_$zeilennr\" class=\"cms_raumk_$uid"."_"."$kid\">";
            $listencode .= str_replace("<option value=\"$traum\"", "<option value=\"$traum\" selected=\"selected\"", $RAEUMEOPTIONEN);
          $listencode .= "</select></td>";
          $listencode .= "<td><select$aenderevent $planladenevent id=\"cms_stdk_$uid"."_"."$kid"."_$zeilennr\" name=\"cms_stdk_$uid"."_"."$kid"."_$zeilennr\">";
          if ($vplanart != 'e') {
            $listencode .= str_replace("<option value=\"$schulstd\"", "<option value=\"$schulstd\" selected=\"selected\"", $SCHULSTUNDENOPTIONEN);
          }
          else {
            $listencode .= $SCHULSTUNDENOPTIONEN;
          }
          $listencode .= "</select></td>";
          $listencode .= "<td><input$aenderevent $planladenevent type=\"cms_text\" name=\"cms_vtextk_$uid"."_"."$kid"."_$zeilennr\" id=\"cms_vtextk_$uid"."_"."$kid"."_$zeilennr\" value=\"$vplanbem\"></td><td>".cms_generiere_schieber("vanzk_$uid"."_".$kid."_$zeilennr", '1', $aendereventfkt)."</td></tr>";
          $zeilennr ++;
        }
      }
      $sql->close();
      $listencode .= "</table>";
      $listencode .= "<p><input type=\"hidden\" id=\"cms_stundek_gewaehlt\" name=\"cms_stundek_gewaehlt\" value=\"\"></p>";
      $listencode .= "<p>Ausgewählte Stunden: <span class=\"cms_button\" onclick=\"cms_vplan_auswahl_aendern('k', 'sichtbarkeit', '1');\">Sichtbar</span></span> <span class=\"cms_button\" onclick=\"cms_vplan_auswahl_aendern('k', 'sichtbarkeit', '0');\">Unsichtbar</span></span> <span class=\"cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('k', 'entfall', '1');\">Entfall</span> <span class=\"cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('k', 'entfall', '0');\">Unsichtbarer Entfall</span> <span class=\"cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('k', 'regelstundenplan');\">Auf Regelstundenplan zurücksetzen</span></p>";

      // Liste ausgeben
      $code .= $listencode;
    }
    else {
      $code .= "<p class=\"cms_notiz cms_vplan_geloest\">Keine Konflikte vorhanden.</p>";
    }


    if (count($LOESUNGENLISTE) > 0) {
      $listencode = "<h4>Vorgemerkte Änderungen</h4>";
      $listencode .= "<div class=\"cms_konflikte_liste_menue\" id=\"cms_konflikte_liste_menue_l\" onmousemove=\"cms_vplan_schnellmenue('l', 'j')\" onmouseout=\"cms_vplan_schnellmenue('l', 'n')\"><span class=\"cms_aktion_klein\" onclick=\"cms_vplan_auswahl_aendern('l', 'sichtbarkeit', '1');\"><span class=\"cms_hinweis\">Sichtbar</span><img src=\"res/icons/klein/sichtbar.png\"></span> <span class=\"cms_aktion_klein\" onclick=\"cms_vplan_auswahl_aendern('l', 'sichtbarkeit', '0');\"><span class=\"cms_hinweis\">Unsichtbar</span><img src=\"res/icons/klein/unsichtbar.png\"></span> <span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('l', 'entfall', '1');\"><span class=\"cms_hinweis\">Entfall</span><img src=\"res/icons/klein/entfall.png\"></span> <span class=\"cms_aktion_klein cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('l', 'entfall', '0');\"><span class=\"cms_hinweis\">Unsichtbarer Entfall</span><img src=\"res/icons/klein/uentfall.png\"></span> <span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('l', 'aenderung');\"><span class=\"cms_hinweis\">Änderungen löschen</span><img src=\"res/icons/klein/aenderungloeschen.png\"></span> <span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('l', 'regelstundenplan');\"><span class=\"cms_hinweis\">Auf Regelstundenplan zurücksetzen</span><img src=\"res/icons/klein/zuruecksetzen.png\"></span></div>";
      $listencode .= "<table class=\"cms_liste\">";
      $listencode .= "<tr>";
      if ($sortierung == 'k') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '$sortwert');\"></th>";
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '$sortwert');\">Kurs</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '0');\"></th>";
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('k', '0');\">Kurs</th>";
      }
      if ($sortierung == 'l') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('l', '$sortwert');\">Lehrer</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('l', '0');\">Lehrer</th>";
      }
      if ($sortierung == 'r') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('r', '$sortwert');\">Raum</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('r', '0');\">Raum</th>";
      }
      if ($sortierung == 's') {
        if ($sortierrichtung == '1') {$sortwert = '0';} else {$sortwert = '1';}
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('s', '$sortwert');\">Stunde</th>";
      }
      else {
        $listencode .= "<th class=\"cms_sortieren\" onclick=\"cms_vplan_konflikte_liste('s', '0');\">Stunde</th>";
      }
      $listencode .= "<th>Bemerkung</th><th></th></tr>";

      foreach ($LOESUNGENLISTE AS $L) {
        if ($L['vplanart'] != 'e') {
          $konfliktgrund = cms_finde_konflikt_detail($L['tlehrer'], $L['traum'], $L['tklasse'], $L['tstufe'], $L['beginn'], $L['ende'], $AUSPLANUNGENL, $AUSPLANUNGENR, $AUSPLANUNGENK, $AUSPLANUNGENS);
          $ueberschneidungsgrund = cms_finde_ueberschneidung_detail($L['kid'], $UEBERL, $UEBERR, $UEBERK);
          $zeilenklasse = "";
        }
        else {$konfliktgrund = ""; $ueberschneidungsgrund = ""; $zeilenklasse = "cms_vplan_entfall";}
        $planladenevent = "onmousedown=\"cms_vplan_wochenplan_neuladen('a', '".$L['tlehrer']."', '".$L['traum']."', '".$L['klassenid']."', '".date('d.m.Y', $L['beginn'])."');  cms_vplan_freieressourcen_laden('l', '".$L['uid']."', '".$L['kid']."', '_$zeilennr');\"";
        $planladeneventohne = "onmousedown=\"cms_vplan_wochenplan_neuladen('a', '".$L['tlehrer']."', '".$L['traum']."', '".$L['klassenid']."', '".date('d.m.Y', $L['beginn'])."');\"";
        $aendereventfkt = "cms_vplan_stunde_aendern('".$L['uid']."', '".$L['kid']."', 'l', '', 'n', 'j', '_$zeilennr');";
        $aenderevent = " onchange=\"$aendereventfkt\"";
        $selektionevent = " onclick=\"cms_vplan_stunde_auswaehlen('".$L['uid']."', '".$L['kid']."', 'l'); cms_vplan_wochenplan_neuladen('a', '".$L['tlehrer']."', '".$L['traum']."', '".$L['klassenid']."', '".date('d.m.Y', $L['beginn'])."');\"";

        if (preg_match("/k/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
        else if (preg_match("/k/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
        else {$styleklasse = "";}
        $listencode .= "<tr class=\"$zeilenklasse cms_zeilel_".$L['uid']."_".$L['kid']."\">";
        $listencode .= "<td class=\"$styleklasse cms_auswaehlen\"$selektionevent onmousemove=\"cms_vplan_schnellmenue('l', 'j', '$zeilennr')\" onmouseout=\"cms_vplan_schnellmenue('l', 'n')\" id=\"cms_vplan_konflikteliste_zeile_$zeilennr\">".$L['klassentext']."</td>";
        $listencode .= "<td><select$aenderevent $planladeneventohne id=\"cms_kursl_".$L['uid']."_".$L['kid']."_$zeilennr\" name=\"cms_kursl_".$L['uid']."_".$L['kid']."_$zeilennr\" class=\"cms_kursl_".$L['uid']."_".$L['kid']."\">";
          $listencode .= str_replace("<option value=\"".$L['tkurs']."\"", "<option value=\"".$L['tkurs']."\" selected=\"selected\"", $KURSEOPTIONEN);
        $listencode .= "</select></td>";
        if (preg_match("/l/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
        else if (preg_match("/l/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
        else {$styleklasse = "";}
        $listencode .= "<td class=\"$styleklasse\"><select$aenderevent $planladeneventohne id=\"cms_lehrerl_".$L['uid']."_".$L['kid']."_$zeilennr\" name=\"cms_lehrerl_".$L['uid']."_".$L['kid']."\" class=\"cms_lehrerl_".$L['uid']."_".$L['kid']."_$zeilennr\">";
          $listencode .= str_replace("<option value=\"".$L['tlehrer']."\"", "<option value=\"".$L['tlehrer']."\" selected=\"selected\"", $LEHREROPTIONEN);
        $listencode .= "</select></td>";
        if (preg_match("/r/", $konfliktgrund)) {$styleklasse = "cms_vplan_konfliktgrund";}
        else if (preg_match("/r/", $ueberschneidungsgrund)) {$styleklasse = "cms_vplan_konfliktwarnung";}
        else {$styleklasse = "";}
        $listencode .= "<td class=\"$styleklasse\"><select$aenderevent $planladeneventohne id=\"cms_rauml_".$L['uid']."_".$L['kid']."_$zeilennr\" name=\"cms_rauml_".$L['uid']."_".$L['kid']."\" class=\"cms_rauml_".$L['uid']."_".$L['kid']."_$zeilennr\">";
          $listencode .= str_replace("<option value=\"".$L['traum']."\"", "<option value=\"".$L['traum']."\" selected=\"selected\"", $RAEUMEOPTIONEN);
        $listencode .= "</select></td>";
        $listencode .= "<td><select$aenderevent $planladenevent id=\"cms_stdl_".$L['uid']."_".$L['kid']."_$zeilennr\" name=\"cms_stdl_".$L['uid']."_".$L['kid']."_$zeilennr\">";
        if ($L['vplanart'] != 'e') {
          $listencode .= str_replace("<option value=\"".$L['schulstd']."\"", "<option value=\"".$L['schulstd']."\" selected=\"selected\"", $SCHULSTUNDENOPTIONEN);
        }
        else {
          $listencode .= $SCHULSTUNDENOPTIONEN;
        }
        $listencode .= "</select></td>";
        $listencode .= "<td><input$aenderevent $planladenevent type=\"cms_text\" name=\"cms_vtextl_".$L['uid']."_".$L['kid']."_$zeilennr\" id=\"cms_vtextl_".$L['uid']."_".$L['kid']."_$zeilennr\" value=\"".$L['vplanbem']."\"></td><td>".cms_generiere_schieber("vanzl_".$L['uid']."_".$L['kid']."_$zeilennr", $L['vplananz'], $aendereventfkt)."</td></tr>";
        $zeilennr ++;
      }

      $listencode .= "</table>";
      $listencode .= "<p><input type=\"hidden\" id=\"cms_stundel_gewaehlt\" name=\"cms_stundel_gewaehlt\" value=\"\"></p>";
      $listencode .= "<p>Ausgewählte Stunden: <span class=\"cms_button\" onclick=\"cms_vplan_auswahl_aendern('l', 'sichtbarkeit', '1');\">Sichtbar</span> <span class=\"cms_button\" onclick=\"cms_vplan_auswahl_aendern('l', 'sichtbarkeit', '0');\">Unsichtbar</span> <span class=\"cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('l', 'entfall', '1');\">Entfall</span> <span class=\"cms_button_wichtig\" onclick=\"cms_vplan_auswahl_aendern('l', 'entfall', '0');\">Unsichtbarer Entfall</span> <span class=\"cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('l', 'aenderung');\">Änderungen löschen</span> <span class=\"cms_button_nein\" onclick=\"cms_vplan_auswahl_aendern_anzeigen('l', 'regelstundenplan');\">Auf Regelstundenplan zurücksetzen</span></p>";

      $code .= $listencode;
    }
  }

  $code .= "<p><input type=\"hidden\" id=\"cms_vplankonflikte_sortierung\" name=\"cms_vplankonflikte_sortierung\" value=\"$sortierung\"> <input type=\"hidden\" id=\"cms_vplankonflikte_sortierung\" name=\"cms_vplankonflikte_sortierung\" value=\"$sortierrichtung\"></p>";



  cms_trennen($dbs);
  cms_trennen($dbl);
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
?>
