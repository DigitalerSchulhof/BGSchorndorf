<?php
include_once("../../lehrerzimmer/funktionen/config.php");
include_once("../../lehrerzimmer/funktionen/texttrafo.php");
include_once("../../lehrerzimmer/funktionen/check.php");

// Variablen einlesen, falls übergeben
if (isset($_POST['zeit'])) 		    {$zeit = $_POST['zeit'];} 			                else {cms_anfrage_beenden(); exit;}
if (isset($_POST['kennung'])) 		{$kennung = $_POST['kennung'];} 			          else {cms_anfrage_beenden(); exit;}
if (isset($_POST['art'])) 		    {$art = $_POST['art'];} 			                  else {cms_anfrage_beenden(); exit;}
if (isset($_POST['nutzerid'])) 		{$nutzerid = $_POST['nutzerid'];} 			        else {cms_anfrage_beenden(); exit;}
if (isset($_POST['sessionid'])) 	{$sessionid = $_POST['sessionid'];} 		        else {cms_anfrage_beenden(); exit;}
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
if (!cms_check_ganzzahl($zeit,0)) {cms_anfrage_beenden(); exit;}
if ($art != 'a') {cms_anfrage_beenden(); exit;}

$zugriff = false;
$angemeldet = false;

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$dbs = cms_verbinden('s');

$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Lehrerstundenpläne sehen'];

if ($angemeldet && $zugriff) {
  $code = "";

  $dbl = cms_verbinden('l');
  $hb = mktime(0,0,0,date('m', $zeit), date('d', $zeit), date('Y', $zeit));;
  $he = mktime(0,0,0,date('m', $hb), date('d', $hb)+1, date('Y', $hb))-1;
  $AUSPLANUNGENL = array();
  $LIDS = array();
  $sql = "SELECT id, lehrer, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanunglehrer WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENL[$eid]['aid'] = $aid;
      $AUSPLANUNGENL[$eid]['von'] = $von;
      $AUSPLANUNGENL[$eid]['bis'] = $bis;
      $AUSPLANUNGENL[$eid]['grund'] = $grund;
      $AUSPLANUNGENL[$eid]['zusatz'] = $zusatz;
      array_push($LIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENR = array();
  $RIDS = array();
  $sql = "SELECT id, raum, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungraeume WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENR[$eid]['aid'] = $aid;
      $AUSPLANUNGENR[$eid]['von'] = $von;
      $AUSPLANUNGENR[$eid]['bis'] = $bis;
      $AUSPLANUNGENR[$eid]['grund'] = $grund;
      $AUSPLANUNGENR[$eid]['zusatz'] = $zusatz;
      array_push($RIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENK = array();
  $KIDS = array();
  $sql = "SELECT id, klasse, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungklassen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENK[$eid]['aid'] = $aid;
      $AUSPLANUNGENK[$eid]['von'] = $von;
      $AUSPLANUNGENK[$eid]['bis'] = $bis;
      $AUSPLANUNGENK[$eid]['grund'] = $grund;
      $AUSPLANUNGENK[$eid]['zusatz'] = $zusatz;
      array_push($KIDS, $eid);
    }
  }
  $sql->close();

  $AUSPLANUNGENS = array();
  $SIDS = array();
  $sql = "SELECT id, stufe, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungstufen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $eid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $AUSPLANUNGENS[$eid]['aid'] = $aid;
      $AUSPLANUNGENS[$eid]['von'] = $von;
      $AUSPLANUNGENS[$eid]['bis'] = $bis;
      $AUSPLANUNGENS[$eid]['grund'] = $grund;
      $AUSPLANUNGENS[$eid]['zusatz'] = $zusatz;
      array_push($SIDS, $eid);
    }
  }
  $sql->close();

  // Aktuellen Zeitraum laden
  $ZEITRAUM = null;
  $sql = "SELECT id FROM zeitraeume WHERE beginn <= ? AND ende >= ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ii", $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($ZEITRAUM);
    $sql->fetch();
  }
  $sql->close();

  // Schulstundeninformationen laden
  $SCHULSTUNDENBEGINN = array();
  $SCHULSTUNDENENDE = array();
  if ($ZEITRAUM !== null) {
    $sql = "SELECT beginns, beginnm, endes, endem, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schulstunden WHERE zeitraum = ?";
    $sql = $dbs->prepare($sql);
    $sql->bind_param("i", $ZEITRAUM);
    if ($sql->execute()) {
      $sql->bind_result($stdbs, $stdbm, $stdes, $stdem, $stdbez);
      while ($sql->fetch()) {
        $SCHULSTUNDENBEGINN[cms_fuehrendenull($stdbs).":".cms_fuehrendenull($stdbm)] = $stdbez;
        $SCHULSTUNDENENDE[cms_fuehrendenull($stdes).":".cms_fuehrendenull($stdem)] = $stdbez;
      }
    }
    $sql->close();
  }

  $LEHRER = array();
  // Lehrerinformationen laden
  if (count($LIDS) > 0) {
    $vorgabe = implode(',', $LIDS);
    $sql = "SELECT * FROM (SELECT personen.id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.id IN ($vorgabe)) AS x ORDER BY kuerzel, vorname, nachname, titel";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $vorname, $nachname, $titel, $kuerzel);
      while ($sql->fetch()) {
        if (strlen($kuerzel) > 0) {$bez = $kuerzel;}
        else {$bez = cms_generiere_anzeigename($vorname, $nachname, $titel);}
        $neu = $bez." (".$AUSPLANUNGENL[$eid]['grund'];
        if (strlen($AUSPLANUNGENL[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENL[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENL[$eid]['von'];
        $bis = $AUSPLANUNGENL[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($LEHRER, $neu);
      }
    }
    $sql->close();
  }

  $RAEUME = array();
  // Rauminformationen laden
  if (count($RIDS) > 0) {
    $vorgabe = implode(',', $RIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENR[$eid]['grund'];
        if (strlen($AUSPLANUNGENR[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENR[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENR[$eid]['von'];
        $bis = $AUSPLANUNGENR[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($RAEUME, $neu);
      }
    }
    $sql->close();
  }

  $STUFEN = array();
  // Rauminformationen laden
  if (count($SIDS) > 0) {
    $vorgabe = implode(',', $SIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM stufen WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENS[$eid]['grund'];
        if (strlen($AUSPLANUNGENS[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENS[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENS[$eid]['von'];
        $bis = $AUSPLANUNGENS[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($STUFEN, $neu);
      }
    }
    $sql->close();
  }

  $KLASSEN = array();
  // Klasseninformationen laden
  if (count($KIDS) > 0) {
    $vorgabe = implode(',', $KIDS);
    $sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM klassen WHERE id IN ($vorgabe)) AS x ORDER BY bezeichnung";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($eid, $bez);
      while ($sql->fetch()) {
        $neu = $bez." (".$AUSPLANUNGENK[$eid]['grund'];
        if (strlen($AUSPLANUNGENK[$eid]['zusatz']) > 0) {
          $neu .= " - ".$AUSPLANUNGENK[$eid]['zusatz'];
        }
        $neu .= ": ";
        $von = $AUSPLANUNGENK[$eid]['von'];
        $bis = $AUSPLANUNGENK[$eid]['bis'];
        if ((date('d.m.Y', $von) == date('d.m.Y', $bis))) {
          if (isset($SCHULSTUNDENBEGINN[date('H:i', $von)]) && isset($SCHULSTUNDENENDE[date('H:i', $bis)])) {
            $neu .= $SCHULSTUNDENBEGINN[date('H:i', $von)]."-".$SCHULSTUNDENENDE[date('H:i', $bis)].")";
          }
          else {$neu .= date('H:i', $von)."-".date('H:i', $bis).")";}
        }
        else {$neu .= 'ganztags)';}
        array_push($KLASSEN, $neu);
      }
    }
    $sql->close();
  }

  $auscode = "";
  if (count($LEHRER) > 0) {$auscode .= "<p><b>Lehrer:</b> ".implode(", ", $LEHRER)."</p>";}
  if (count($RAEUME) > 0) {$auscode .= "<p><b>Räume:</b> ".implode(", ", $RAEUME)."</p>";}
  if (count($KLASSEN) > 0) {$auscode .= "<p><b>Klassen:</b> ".implode(", ", $KLASSEN)."</p>";}
  if (count($STUFEN) > 0) {$auscode .= "<p><b>Stufen:</b> ".implode(", ", $STUFEN)."</p>";}

  if (strlen($auscode) > 0) {
    $code .= cms_meldung('vplan', $auscode);
  }

  cms_trennen($dbl);
	cms_lehrerdb_header(true);
  echo $code;
}
else {
  cms_lehrerdb_header(false);
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
