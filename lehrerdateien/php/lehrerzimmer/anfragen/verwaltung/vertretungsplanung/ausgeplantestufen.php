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

// REIHENFOLGE WICHTIG!! NICHT ÄNDERN -->
include_once("../../lehrerzimmer/funktionen/entschluesseln.php");
include_once("../../lehrerzimmer/funktionen/sql.php");
include_once("../../lehrerzimmer/funktionen/meldungen.php");
include_once("../../lehrerzimmer/funktionen/generieren.php");
$angemeldet = cms_angemeldet();
$CMS_RECHTE = cms_rechte_laden();
// <-- NICHT ÄNDERN!! REIHENFOLGE WICHTIG

$zugriff = $CMS_RECHTE['Planung']['Ausplanungen durchführen'] || $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if ($angemeldet && $zugriff) {
  $code = "";
  $dbl = cms_verbinden('l');
  $AUSPLANUNGEN = array();
  $IDS = array();
  $hb = mktime(0,0,0,$monat, $tag, $jahr);
  $he = mktime(0,0,0,$monat, $tag+1, $jahr)-1;
  $sql = "SELECT id, stufe, grund, AES_DECRYPT(zusatz, '$CMS_SCHLUESSELL'), von, bis FROM ausplanungstufen WHERE (von BETWEEN ? AND ?) OR (bis BETWEEN ? AND ?) OR (von <= ? AND bis >= ?) ORDER BY grund ASC, von ASC, bis DESC";
  $sql = $dbl->prepare($sql);
  $sql->bind_param("iiiiii", $hb, $he, $hb, $he, $hb, $he);
  if ($sql->execute()) {
    $sql->bind_result($aid, $kid, $grund, $zusatz, $von, $bis);
    while ($sql->fetch()) {
      $a = array();
      $a['aid'] = $aid;
      $a['kid'] = $kid;
      $a['von'] = $von;
      $a['bis'] = $bis;
      $a['grund'] = $grund;
      $a['zusatz'] = $zusatz;
      array_push($AUSPLANUNGEN, $a);
      array_push($IDS, $kid);
    }
  }
  $sql->close();


  $dbs = cms_verbinden('s');
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

  // Lehrerinformationen laden
  if (count($IDS) > 0) {
    $vorgabe = implode(',', $IDS);
    $STUFEN = array();
    $sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM stufen WHERE id IN ($vorgabe)";
    $sql = $dbs->prepare($sql);
    if ($sql->execute()) {
      $sql->bind_result($kid, $bez);
      while ($sql->fetch()) {
        $STUFEN[$kid] = $bez;
      }
    }
    $sql->close();

    $auscode = "";
    $rueckabwicklung = "'n'";
    if ($CMS_RECHTE['Planung']['Stundenplanung durchführen']) {$rueckabwicklung = "'j'";}
    foreach ($AUSPLANUNGEN AS $a) {
      if ((date('d', $a['von']) == date('d', $a['bis'])) && (date('m', $a['von']) == date('m', $a['bis'])) &&
          (date('Y', $a['von']) == date('Y', $a['bis']))) {
        if (isset($SCHULSTUNDENBEGINN[date('H:i', $a['von'])]) && isset($SCHULSTUNDENENDE[date('H:i', $a['bis'])])) {
          $zeitvon = $SCHULSTUNDENBEGINN[date('H:i', $a['von'])];
          $zeitbis = $SCHULSTUNDENENDE[date('H:i', $a['bis'])];
        }
        else {
          $zeitvon = date('H:i', $a['von']);
          $zeitbis = date('H:i', $a['bis']);
        }
      }
      else {
        if (isset($SCHULSTUNDENBEGINN[date('H:i', $a['von'])]) && isset($SCHULSTUNDENENDE[date('H:i', $a['bis'])])) {
          $zeitvon = date('d.m.Y', $a['von'])." - ".$SCHULSTUNDENBEGINN[date('H:i', $a['von'])];
          $zeitbis = date('d.m.Y', $a['bis'])." - ".$SCHULSTUNDENENDE[date('H:i', $a['bis'])];
        }
        else {
          $zeitvon = date('d.m.Y H:i', $a['von']);
          $zeitbis = date('d.m.Y H:i', $a['bis']);
        }
      }

      $grund = $a['grund'];
      if (strlen($a['zusatz']) > 0) {$grund .= " (".$a['zusatz'].")";}
      $auscode .= "<tr><td>".$STUFEN[$a['kid']]."</td><td>$grund</td><td>$zeitvon</td><td>$zeitbis</td><td>";
        $auscode .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_ausplanung_loeschen_anzeigen('".$STUFEN[$a['kid']]."', ".$a['aid'].", 's', $rueckabwicklung)\"><span class=\"cms_hinweis\">Ausplanung löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
      $auscode .= "</td></tr>";
    }
    $code .= "<table class=\"cms_liste\"><tr><th>Stufen</th><th>Grund</th><th>von</th><th>bis</th><th>Aktionen</th></tr>$auscode</table>";
  }
  else {$code .= "<p class=\"cms_notiz\">Keine Stufen ausgeplant.</p>";}

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
