<?php
function cms_zugehoerig_jahr_ausgeben ($dbs, $feldid, $gruppe, $gruppenid, $jahr, $url) {
  global $CMS_SCHLUESSEL;

  $anzeigegruppe = strtoupper(substr($gruppe,0,1)).substr($gruppe,1);
  if ($anzeigegruppe == "Sonstigegruppen") {$anzeigegruppe = "Sontige Gruppen";}

  $fehler = false;
  if (!cms_valide_kgruppe($gruppe)) {$fehler = true; echo 1;}
  if (!cms_check_ganzzahl($jahr)) {$fehler = true; echo 2;}

  $oeffentlichkeit = 4;
  $CMS_ANGEMELDET = cms_angemeldet();

  if ($CMS_ANGEMELDET && isset($_SESSION['BENUTZERART'])) {
    if ($_SESSION['BENUTZERART'] == 'l') {$oeffentlichkeit = 1;}
    if ($_SESSION['BENUTZERART'] == 'v') {$oeffentlichkeit = 2;}
    else {$oeffentlichkeit = 3;}
  }

  $sql = $dbs->prepare("SELECT COUNT(*) as anzahl, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gruppe WHERE id = ?");
  $sql->bind_param("i", $gruppenid);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $gruppenname, $gruppenicon);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true; echo 3;}}
    else {$fehler = true; echo 4;}
  } else {$fehler = true; echo 5;}
  $sql->close();

  if (!$fehler) {
    $jbeginn = mktime (0, 0, 0, 1, 1, $jahr);
    $jende = mktime (0, 0, 0, 1, 1, $jahr+1)-1;
    // Blogeinträge laden
    $BLOGEINTRAEGE = array();
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), datum FROM blogeintraege WHERE oeffentlichkeit > ? AND id IN (SELECT blogeintrag FROM $gruppe"."blogeintraege WHERE gruppe = ?) AND datum BETWEEN ? AND ? ORDER BY datum DESC");
    $sql->bind_param("iiii", $oeffentlichkeit, $gruppenid, $jbeginn, $jende);
    if ($sql->execute()) {
      $sql->bind_result($ebez, $edatum);
      while ($sql->fetch()) {
        $einzeln = array();
        $einzeln['bez'] = $ebez;
        $einzeln['dat'] = $edatum;
        array_push($BLOGEINTRAEGE, $einzeln);
      }
    }
    $sql->close();

    // Termine laden
    $jetzt = time();
    $TERMINENEU = array();
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginn FROM termine WHERE oeffentlichkeit > ? AND id IN (SELECT termin FROM $gruppe"."termine WHERE gruppe = ?) AND ende > ? AND ((beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn <= ? AND ende >= ?)) ORDER BY beginn ASC");
    $sql->bind_param("iiiiiiiii", $oeffentlichkeit, $gruppenid, $jetzt, $jbeginn, $jende, $jbeginn, $jende, $jbeginn, $jende);
    if ($sql->execute()) {
      $sql->bind_result($ebez, $edatum);
      while ($sql->fetch()) {
        $einzeln = array();
        $einzeln['bez'] = $ebez;
        $einzeln['dat'] = $edatum;
        array_push($TERMINENEU, $einzeln);
      }
    }
    $sql->close();
    $jetzt = time();
    $TERMINEALT = array();
    $sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), beginn FROM termine WHERE oeffentlichkeit > ? AND id IN (SELECT termin FROM $gruppe"."termine WHERE gruppe = ?) AND ende <= ? AND ((beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?) OR (beginn <= ? AND ende >= ?)) ORDER BY beginn DESC");
    $sql->bind_param("iiiiiiiii", $oeffentlichkeit, $gruppenid, $jetzt, $jbeginn, $jende, $jbeginn, $jende, $jbeginn, $jende);
    if ($sql->execute()) {
      $sql->bind_result($ebez, $edatum);
      while ($sql->fetch()) {
        $einzeln = array();
        $einzeln['bez'] = $ebez;
        $einzeln['dat'] = $edatum;
        array_push($TERMINEALT, $einzeln);
      }
    }
    $sql->close();

    // Galerien laden
    $GALERIEN = array();
    /*$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), datum FROM galerien WHERE oeffentlichkeit > ? AND id IN (SELECT galerie FROM $gruppe"."galerien WHERE gruppe = ?) AND datum BETWEEN ? AND ? ORDER BY datum DESC");
    $sql->bind_param("iiii", $oeffentlichkeit, $gruppenid, $jbeginn, $jende);
    if ($sql->execute()) {
      $sql->bind_result($ebez, $edatum);
      while ($sql->fetch()) {
        $einzeln = array();
        $einzeln['bez'] = $ebez;
        $einzeln['dat'] = $edatum;
        array_push($TERMINE, $einzeln);
      }
    }
    $sql->close();*/

    $code = "<h3><img id=\"cms_zugehoerig_icon\" src=\"res/gruppen/klein/$gruppenicon\"> $anzeigegruppe » $gruppenname</h3>";
    $code .= "<table><tr><td><span class=\"cms_button\" onclick=\"cms_zugehoerig_laden('$feldid', '".($jahr-1)."', '$gruppe', '$gruppenid')\"\">«</span></td>";
    $code .= "<td>$jahr</td>";
    $code .= "<td><span class=\"cms_button\" onclick=\"cms_zugehoerig_laden('$feldid', '".($jahr+1)."', '$gruppe', '$gruppenid')\"\">»</span></td></tr></table>";

    $blogcode = "";
    foreach ($BLOGEINTRAEGE as $e) {
      $link = $url.'/Blog/'.date('Y', $e['dat']).'/'.cms_monatsnamekomplett(date('m', $e['dat'])).'/'.date('d', $e['dat']).'/'.cms_textzulink($e['bez']);
      $blogcode .= cms_generiere_zugehoerigbutton ($link, $e);
    }
    $terminncode = "";
    foreach ($TERMINENEU as $e) {
      $link = $url.'/Termine/'.date('Y', $e['dat']).'/'.cms_monatsnamekomplett(date('m', $e['dat'])).'/'.date('d', $e['dat']).'/'.cms_textzulink($e['bez']);
      $terminncode .= cms_generiere_zugehoerigbutton ($link, $e);
    }
    $terminacode = "";
    foreach ($TERMINEALT as $e) {
      $link = $url.'/Termine/'.date('Y', $e['dat']).'/'.cms_monatsnamekomplett(date('m', $e['dat'])).'/'.date('d', $e['dat']).'/'.cms_textzulink($e['bez']);
      $terminacode .= cms_generiere_zugehoerigbutton ($link, $e);
    }
    $galeriecode = "";
    foreach ($GALERIEN as $e) {
      $link = $url.'/Galerien/'.date('Y', $e['dat']).'/'.cms_monatsnamekomplett(date('m', $e['dat'])).'/'.date('d', $e['dat']).'/'.cms_textzulink($e['bez']);
      $galeriecode .= cms_generiere_zugehoerigbutton ($link, $e);
    }

    if (strlen($blogcode) > 0) {$code .= "<h4>Blogeinträge</h4><ul>".$blogcode."</ul>";}
    if (strlen($terminncode) > 0) {$code .= "<h4>Anstehende Termine</h4><ul>".$terminncode."</ul>";}
    if (strlen($terminacode) > 0) {$code .= "<h4>Vergangene Termine</h4><ul>".$terminacode."</ul>";}
    if (strlen($galeriecode) > 0) {$code .= "<h4>Galerien</h4><ul>".$galeriecode."</ul>";}
    return $code;
  }
  return "<p class=\"cms_notiz\">– ungültige Anfrage –</p>";
}

function cms_generiere_zugehoerigbutton ($link, $e) {
  $code = "<li><a href=\"$link\">";
  $code .= "<span class=\"cms_zugehoerig_datum\">".cms_tagname(date('N', $e['dat']))." ".date('d.', $e['dat'])." ".cms_monatsnamekomplett(date('m', $e['dat']))."</span><br>";
  $code .= "<span class=\"cms_zugehoerig_text\">".$e['bez']."</span></a></li>";
  return $code;
}

?>
