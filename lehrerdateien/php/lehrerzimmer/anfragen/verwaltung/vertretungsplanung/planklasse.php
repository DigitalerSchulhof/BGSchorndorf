<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['tag'])) 		    {$tag = $_POST['tag'];} 			                  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['monat'])) 		  {$monat = $_POST['monat'];} 			              else {cms_anfrage_beenden(); exit;}
if (isset($_POST['jahr'])) 		    {$jahr = $_POST['jahr'];} 			                else {cms_anfrage_beenden(); exit;}
if (isset($_POST['klasse'])) 		  {$klasse = $_POST['klasse'];} 			            else {cms_anfrage_beenden(); exit;}
$planziel = substr($klasse,1);
$planart = substr($klasse,0,1);

if (!cms_check_ganzzahl($tag,1,31)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($monat,1,12)) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($jahr,0)) {cms_anfrage_beenden(); exit;}
if (($planart != 's') && ($planart != 'k')) {cms_anfrage_beenden(); exit;}
if (!cms_check_ganzzahl($planziel,0) && ($planziel != '-')) {cms_anfrage_beenden(); exit;}

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

  if ($planziel == '-') {
    $code .= '<p class="cms_notiz">Es wurde kein gültiger Stundenplan ausgewählt.</p>';
  }
  else {
    include_once("../../lehrerzimmer/seiten/verwaltung/vertretungsplanung/vplanunterrichtausgeben.php");
    // Ausgabezeiträume bestimmen
    $hb = mktime(0,0,0,$monat,$tag,$jahr);
    $wochentag = date("N", $hb);
    $hb = mktime(0,0,0,$monat,$tag-$wochentag+1,$jahr);
    $he = mktime(0,0,0,$monat,$tag-$wochentag+8,$jahr)-1;
    $dbl = cms_verbinden('l');
    $dbs = cms_verbinden('s');

    // Zeiträume laden
    $ZEITRAEUME = array();
    $sql = $dbs->prepare("SELECT id, beginn, ende, mo, di, mi, do, fr, sa, so, rythmen FROM zeitraeume WHERE (? BETWEEN beginn AND ende) OR (? BETWEEN beginn AND ende) OR (? <= beginn AND ? >= ende) ORDER BY beginn");
    $sql->bind_param("iiii", $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($zid, $zbeginn, $zende, $zmo, $zdi, $zmi, $zdo, $zfr, $zsa, $zso, $zrythmen);
      while ($sql->fetch()) {
        $Z = array();
        $Z['id'] = $zid;
        $Z['beginn'] = $zbeginn;
        $Z['ende'] = $zende;
        $Z['mo'] = $zmo;
        $Z['di'] = $zdi;
        $Z['mi'] = $zmi;
        $Z['do'] = $zdo;
        $Z['fr'] = $zfr;
        $Z['sa'] = $zsa;
        $Z['so'] = $zso;
        $Z['tage'] = "|";
        $Z['tageanzahl'] = $zmo+$zdi+$zmi+$zdo+$zfr+$zsa+$zso;
        $Z['rythmen'] = $zrythmen;
        if ($Z['mo'] == 1) {$Z['tage'] .= "1|";}
        if ($Z['di'] == 1) {$Z['tage'] .= "2|";}
        if ($Z['mi'] == 1) {$Z['tage'] .= "3|";}
        if ($Z['do'] == 1) {$Z['tage'] .= "4|";}
        if ($Z['fr'] == 1) {$Z['tage'] .= "5|";}
        if ($Z['sa'] == 1) {$Z['tage'] .= "6|";}
        if ($Z['so'] == 1) {$Z['tage'] .= "7|";}
        $Z['schulstunden'] = array();
        array_push($ZEITRAEUME, $Z);
      }
    }
    $sql->close();

    // Schulstunden für die einzelnen Zeiträume laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ? ORDER BY beginns, beginnm");
    for ($z=0; $z < count($ZEITRAEUME); $z++) {
      $sql->bind_param("i", $ZEITRAEUME[$z]['id']);
      if ($sql->execute()) {
        $sql->bind_result($stdid, $stdbez, $stdbeginns, $stdbeginnm, $stdendes, $stdendem);
        while ($sql->fetch()) {
          $S = array();
          $S['id'] = $stdid;
          $S['bez'] = $stdbez;
          $S['beginn'] = cms_fuehrendenull($stdbeginns).":".cms_fuehrendenull($stdbeginnm);
          $S['ende'] = cms_fuehrendenull($stdendes).":".cms_fuehrendenull($stdendem);
          array_push($ZEITRAEUME[$z]['schulstunden'], $S);
        }
      }
    }
    $sql->close();

    $AUSPLANUNGENSTD = array();
    if ($planart == 's') {$sql = $dbl->prepare("SELECT von, bis FROM ausplanungstufen WHERE stufe = ? AND ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?))");}
    else if ($planart == 'k') {$sql = $dbl->prepare("SELECT von, bis FROM ausplanungklassen WHERE klasse = ? AND ((von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?))");}
    $sql->bind_param("iiiiiii", $planziel, $hb, $he, $hb, $he, $hb, $he);
    if ($sql->execute()) {
      $sql->bind_result($avon, $abis);
      while ($sql->fetch()) {
        $A = array();
        $A['von'] = $avon;
        $A['bis'] = $abis;
        array_push($AUSPLANUNGENSTD, $A);
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

    // PLAN VORBEREITEN
    $PLAN = array();
    $tag = $hb;
    while ($tag <= $he) {
      $tagzeitraum = cms_finde_zeitraum($tag, $ZEITRAEUME);
      if (preg_match("|".date('N', $tag)."|", $tagzeitraum['tage']) == 1) {
        $datum = date("d.m.Y", $tag);
        $PLAN[$datum] = array();
        $PLAN[$datum]['zeitraum'] = $tagzeitraum['id'];
        $PLAN[$datum]['datum'] = $datum;
        $tagesinfo = cms_erstelle_tagesinfo($tagzeitraum['schulstunden']);
        $PLAN[$datum]['tagbeginn'] = $tagesinfo['beginn'];
        $PLAN[$datum]['tagende'] = $tagesinfo['ende'];
        $PLAN[$datum]['tagdauer'] = $tagesinfo['dauer'];
        $PLAN[$datum]['wochentag'] = cms_tagname(date('N', $tag))." <span class=\"cms_notiz\">".date('d.m.', $tag)."</span>";
        $PLAN[$datum]['schulstunden'] = $tagesinfo['schulstunden'];
      }
      $tag = mktime(0,0,0,date('m', $tag),date('d', $tag)+1,date('Y', $tag));
    }

    // UNTERRICHT finden, der sich mit KONFLIKTLÖSUNGEN überschneidet
    // UNTERRICHTSKONFLIKTLÖSUNGEN LADEN
    $LOESUNGEN = array();
    $LOESUNGENIDS = array();
    $sql = $dbs->prepare("SELECT id AS kid, altid AS uid, tbeginn, tlehrer, traum, tkurs, klasse FROM unterrichtkonflikt LEFT JOIN kurseklassen ON unterrichtkonflikt.tkurs = kurseklassen.kurs AND (tbeginn BETWEEN ? AND ?)");
    $sql->bind_param("ii", $hb, $he);
    if ($sql -> execute()) {
      $sql -> bind_result($kid, $uid, $ubeginn, $ulehrer, $uraum, $ukurs, $uklasse);
      while ($sql -> fetch()) {
        if (!in_array($kid, $LOESUNGENIDS)) {
          $l = array();
          $l['uid'] = $uid;
          $l['kid'] = $kid;
          $l['beginn'] = $ubeginn;
          $l['lehrer'] = $ulehrer;
          $l['raum'] = $uraum;
          $l['kurs'] = $ukurs;
          $l['klasse'] = array();
          if (strlen($uklasse > 0)) {array_push($l['klasse'], $uklasse);}
          $LOESUNGEN[$kid] = $l;
          array_push($LOESUNGENIDS, $kid);
        }
        else {
          if (!in_array($uklasse, $LOESUNGEN[$kid]['klasse']) && strlen($uklasse > 0)) {
            array_push($LOESUNGEN[$kid]['klasse'], $uklasse);
          }
        }
      }
    }
    $sql->close();


    // ÜBERSCHNEIDUNGEN SUCHEN
    // Enthält alle Konfliktlösungsids die sich mit anderem Unterricht überschneiden
    $UEBER = array();
    foreach ($LOESUNGEN AS $l) {
      if (count($l['klasse']) > 0) {$lklasse = implode(',', $l['klasse']);}
      $sql1 = "SELECT COUNT(*) as anzahl FROM unterricht WHERE tbeginn = ? AND (tlehrer = ? OR traum = ?";
      if (count($l['klasse']) > 0) {$sql1 .= " OR (tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN ($lklasse)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)))";}
      $sql1 .= ") AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e'";
      $sql2 = "SELECT COUNT(*) as anzahl FROM unterrichtkonflikt WHERE tbeginn = ? AND (tlehrer = ? OR traum = ?";
      if (count($l['klasse']) > 0) {$sql2 .= " OR (tkurs IN (SELECT kurs FROM kurseklassen WHERE klasse IN ($lklasse)) AND tkurs NOT IN (SELECT kurs FROM schienenkurse WHERE schiene IN (SELECT schiene FROM schienenkurse WHERE kurs = ?)))";}
      $sql2 .= ") AND id != ? AND vplanart != 'e'";
      $sql = $dbs->prepare("SELECT SUM(anzahl) FROM (($sql1) UNION ($sql2)) AS x");
      if (count($l['klasse']) > 0) {
        $sql->bind_param("iiiiiiiii", $l['beginn'], $l['lehrer'], $l['raum'], $l['kurs'], $l['beginn'], $l['lehrer'], $l['raum'], $l['kurs'], $l['kid']);
      }
      else {
        $sql->bind_param("iiiiiii", $l['beginn'], $l['lehrer'], $l['raum'], $l['beginn'], $l['lehrer'], $l['raum'], $l['kid']);
      }
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        while ($sql->fetch()) {
          if ($anzahl > 0) {
            array_push($UEBER, $l['kid']);
          }
        }
      }
      $sql->close();
    }


    // KONFLIKTE SUCHEN
    // Konflikte suchen
    $KONFLIKTE = array();
    foreach ($AUSPLANUNGENL AS $ap) {
      $sql = "SELECT DISTINCT uid, kid FROM ((SELECT id AS uid, '-' AS kid FROM unterricht WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tlehrer = ? AND id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND vplanart != 'e') UNION (SELECT altid AS uid, id AS kid FROM unterrichtkonflikt WHERE ((tbeginn BETWEEN ? AND ?) OR (tende BETWEEN ? AND ?) OR (tbeginn <= ? AND tende >= ?)) AND tlehrer = ? AND vplanart != 'e')) AS x";
      $sql = $dbs->prepare($sql);
      $apvon = max($hb, $ap['von']);
      $apbis = min($he, $ap['bis']);
      $sql->bind_param("iiiiiiiiiiiiii", $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid'], $apvon, $apbis, $apvon, $apbis, $apvon, $apbis, $ap['zid']);
      if ($sql->execute()) {
        $sql->bind_result($uid, $kid);
        while ($sql->fetch()) {
          if (!in_array($uid."|".$kid, $KONFLIKTE)) {array_push($KONFLIKTE, $uid."|".$kid);}
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
          if (!in_array($uid."|".$kid, $KONFLIKTE)) {array_push($KONFLIKTE, $uid."|".$kid);}
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
          if (!in_array($uid."|".$kid, $KONFLIKTE)) {array_push($KONFLIKTE, $uid."|".$kid);}
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
          if (!in_array($uid."|".$kid, $KONFLIKTE)) {array_push($KONFLIKTE, $uid."|".$kid);}
        }
      }
      $sql->close();
    }


    // UNTERRICHT und UNTERRICHTSKONFLIKTLÖSUNGEN laden
    $sql1 = "SELECT unterricht.id AS uid, null AS kid, tkurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurskurzbez, tbeginn, tende, tlehrer, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), stufen.id AS tstufe, kurseklassen.klasse AS tklasse, vplanart, pkurs, pbeginn, pende, plehrer, praum ";
    $sql1 .= "FROM unterricht LEFT JOIN kurse ON unterricht.tkurs = kurse.id LEFT JOIN raeume ON unterricht.traum = raeume.id LEFT JOIN lehrer ON unterricht.tlehrer = lehrer.id LEFT JOIN personen ON unterricht.tlehrer = personen.id LEFT JOIN stufen ON kurse.stufe = stufen.id LEFT JOIN kurseklassen ON unterricht.tkurs = kurseklassen.kurs ";
    $sql1 .= "WHERE unterricht.id NOT IN (SELECT altid FROM unterrichtkonflikt WHERE altid IS NOT null) AND tbeginn >= ? AND tende <= ?";
    if ($planart == 'k') {$sql1 .= " AND klasse = ?";}
    if ($planart == 's') {$sql1 .= " AND stufe = ?";}
    $sql2 = "SELECT unterrichtkonflikt.altid AS uid, unterrichtkonflikt.id AS kid, unterrichtkonflikt.tkurs AS tkurs, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS kursbez, AES_DECRYPT(kurse.kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurskurzbez, unterrichtkonflikt.tbeginn AS tbeginn, unterrichtkonflikt.tende AS tende, unterrichtkonflikt.tlehrer AS tlehrer, AES_DECRYPT(lehrer.kuerzel, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL'), unterrichtkonflikt.traum AS traum, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), stufen.id AS tstufe, kurseklassen.klasse AS tklasse, unterrichtkonflikt.vplanart, pkurs, pbeginn, pende, plehrer, praum ";
    $sql2 .= "FROM unterrichtkonflikt LEFT JOIN kurse ON unterrichtkonflikt.tkurs = kurse.id LEFT JOIN raeume ON unterrichtkonflikt.traum = raeume.id LEFT JOIN lehrer ON unterrichtkonflikt.tlehrer = lehrer.id LEFT JOIN personen ON unterrichtkonflikt.tlehrer = personen.id LEFT JOIN stufen ON kurse.stufe = stufen.id LEFT JOIN kurseklassen ON unterrichtkonflikt.tkurs = kurseklassen.kurs LEFT JOIN unterricht ON unterrichtkonflikt.altid = unterricht.id ";
    $sql2 .= "WHERE unterrichtkonflikt.tbeginn >= ? AND unterrichtkonflikt.tende <= ?";
    if ($planart == 'k') {$sql2 .= " AND klasse = ?";}
    if ($planart == 's') {$sql2 .= " AND stufe = ?";}
    $sql = $dbs->prepare("SELECT * FROM ((".$sql1.") UNION (".$sql2.")) AS x GROUP BY uid, kid ORDER BY tbeginn, kurskurzbez, kursbez");
    $sql->bind_param("iiiiii", $hb, $he, $planziel, $hb, $he, $planziel);
    if ($sql->execute()) {
      $sql->bind_result($uid, $kid, $kursid, $kursbez, $kurskurz, $ubeginn, $uende, $lehrerid, $lehrerkurz, $lehrervor, $lehrernach, $lehrertit, $raumid, $raumbez, $stufeid, $klasseid, $vplanart, $vkurs, $vbeginn, $vende, $vlehrer, $vraum);
      while ($sql->fetch()) {
        $tag = date("d.m.Y", $ubeginn);
        if (isset($PLAN[$tag])) {
          $zeit = date("H:i", $ubeginn);
          if (isset($PLAN[$tag]['schulstunden'][$zeit]['stunden'])) {
            $u = array();
            $u['uid'] = $uid;
            $u['kid'] = $kid;
            $u['beginn'] = $ubeginn;
            $u['stufeid'] = $stufeid;
            $u['klasseid'] = $klasseid;
            $u['kursid'] = $kursid;
            if (strlen($kurskurz) > 0) {$u['kurs'] = $kurskurz;}
            else {$u['kurs'] = $kursbez;}
            $u['lehrerid'] = $lehrerid;
            if (strlen($lehrerkurz) > 0) {$u['lehrer'] = $lehrerkurz;}
            else {$u['lehrer'] = cms_generiere_anzeigename($lehrervor, $lehrernach, $lehrertit);}
            $u['raumid'] = $raumid;
            $u['raum'] = $raumbez;
            $u['vplanart'] = $vplanart;
            $u['konflikt'] = cms_finde_konflikt($uid, $kid, $KONFLIKTE);
            $u['ueberschneidung'] = cms_finde_ueberschneidung($uid, $kid, $UEBER);
            $u['geaendert'] = ($kursid != $vkurs) || ($ubeginn != $vbeginn) || ($uende != $vende) || ($lehrerid != $vlehrer) || ($raumid != $vraum);
            array_push($PLAN[$tag]['schulstunden'][$zeit]['stunden'], $u);
          }
        }
      }
    }
    $sql->close();
  }

  // Plan ausgeben
  $code .= cms_vplan_ausgeben($PLAN, $planart, $AUSPLANUNGENSTD);

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
