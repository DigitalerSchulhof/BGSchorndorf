<?php
function cms_zugehoerig_ausgeben ($dbs, $gruppe, $gruppenname, $gruppenicon, $gruppenid, $jahr) {
  global $CMS_SCHLUESSEL;
  $code = "";
  $code .= "<p id=\"cms_zugehoerig_anfang\"><span id=\"cms_zugrhoerigknopf\" class=\"cms_button\" onclick=\"cms_togglebutton_anzeigen('cms_zugehoerig', 'cms_zugrhoerigknopf', 'Zugehörige Inhalte anzeigen', 'Zugehörige Inhalte ausblenden')\">Zugehörige Inhalte anzeigen</span></p>";

  $code .= "<div id=\"cms_zugehoerig\" style=\"display: none;\">";
  $code .= "<h3>$gruppe » $gruppenname</h3>";
  $code .= "<img id=\"cms_zugehoerig_icon\" src=\"res/ereignisse/gross/$gruppenicon\">";

  // JAHRE
  $minjahr = $jahr;
  $maxjahr = $jahr;
  $sqltermine = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";
  $sqlblog = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM blogeintraege WHERE aktiv = '1'";
  $sqlgalerie = "SELECT MIN(datumaktuell) AS beginn, MAX(datumaktuell) AS ende FROM galerien WHERE aktiv = '1'";
  $sql = "SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM (($sqltermine) UNION ($sqlblog) UNION ($sqlgalerie)) AS x";
  if ($anfrage = $dbs->query($sql)) {
    if ($daten = $anfrage->fetch_assoc()) {
      if (date('Y', $daten['beginn']) < $minjahr) {$minjahr = date('Y', $daten['beginn']);}
      if (date('Y', $daten['ende']) > $maxjahr) {$maxjahr = date('Y', $daten['ende']);}
    }
    $anfrage->free();
  }

  $code .= "<p>";
  for ($i = $maxjahr; $i>=$minjahr; $i--) {
    if ($i == $jahr) {$zusatz = "_aktiv";} else {$zusatz = "";}
    $code .= "<span id=\"cms_zugehoerig_jahr_$i\" class=\"cms_toggle$zusatz\" onclick=\"cms_zugehoerig_laden('$i', '$minjahr', '$maxjahr', '$gruppe', '$gruppenid');\">$i</span> ";
  }
  $code .= "</p>";

  $code .= "<div id=\"cms_zugehoerig_jahr\">";
  $code .= cms_zugehoerig_jahr_ausgeben ($dbs, $gruppe, $gruppenid, $jahr);
  $code .= "</div>";

  $code .= "</div>";

  return $code;
}


function cms_zugehoerig_jahr_ausgeben ($dbs, $gruppe, $gruppenid, $jahr) {
  global $CMS_SCHLUESSEL, $CMS_TRENNUNG;
  $code = "";

  $jetzt = time();
  $jahrbeginn = mktime(0,0,0,1,1,$jahr);
  $jahrende = mktime(0,0,0,1,1,$jahr+1)-1;

  $tabcode = "";
  $termincode = "";
  $atermincode = "";
  $vtermincode = "";
  $blogcode = "";
  $galeriecode = "";
  // Termine dieses Jahres laden
  $sql = "SELECT beginn, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM termine WHERE gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL') AND gruppenid = '$gruppenid' AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND ende >= $jetzt";
  $sql .= " AND ((beginn BETWEEN $jahrbeginn AND $jahrende) OR (ende BETWEEN $jahrbeginn AND $jahrende)) ORDER BY beginn ASC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $bezlink = str_replace(' ', '_', $daten['bezeichnung']);
      $terminjahr = date('Y', $daten['beginn']);
      $atermincode .= "<li><a href=\"Website/Termine/$terminjahr/".cms_monatsnamekomplett(date('n', $daten['beginn'])).$CMS_TRENNUNG.$bezlink."\">".date('d.m.Y', $daten['beginn'])." ".$daten['bezeichnung']."</a></li>";
    }
    $anfrage->free();
  }
  $sql = "SELECT beginn, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM termine WHERE gruppe = AES_ENCRYPT('$gruppe', '$CMS_SCHLUESSEL') AND gruppenid = '$gruppenid' AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND oeffentlicht = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND ende < $jetzt";
  $sql .= " AND ((beginn BETWEEN $jahrbeginn AND $jahrende) OR (ende BETWEEN $jahrbeginn AND $jahrende)) ORDER BY beginn ASC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $bezlink = str_replace(' ', '_', $daten['bezeichnung']);
      $vtermincode .= "<li><a href=\"Website/Termine/$jahr/".cms_monatsnamekomplett(date('n', $daten['beginn'])).$CMS_TRENNUNG.$bezlink."\">".date('d.m.Y', $daten['beginn'])." ".$daten['bezeichnung']."</a></li>";
    }
    $anfrage->free();
  }

  $spalten = 0;


  if (strlen($atermincode) > 0) {$termincode .= "<h4>Anstehende Termine</h4><ul>".$atermincode."</ul>";}
  if (strlen($vtermincode) > 0) {$termincode .= "<h4>Vergangene Termine</h4><ul>".$vtermincode."</ul>";}
  if (strlen($termincode) > 0) {$spalten++;}

  // Blogeinträge dieses Jahres laden
  $sql = "SELECT datumaktuell AS datum, titelaktuell AS bezeichnung FROM blogeintraege WHERE gruppe = '$gruppe' AND gruppenid = '$gruppenid' AND aktiv = '1' AND (datumaktuell BETWEEN $jahrbeginn AND $jahrende) ORDER BY datum ASC, bezeichnung ASC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $bezlink = str_replace(' ', '_', $daten['bezeichnung']);
      $blogcode .= "<li><a href=\"Website/Blog/$jahr/".cms_monatsnamekomplett(date('n', $daten['datum'])).$CMS_TRENNUNG.$bezlink."\">".date('d.m.Y', $daten['datum'])." ".$daten['bezeichnung']."</a></li>";
    }
    $anfrage->free();
  }
  if (strlen($blogcode) > 0) {$spalten++;}

  // Blogeinträge dieses Jahres laden
  $sql = "SELECT datumaktuell AS datum, titelaktuell AS bezeichnung FROM galerien WHERE gruppe = '$gruppe' AND gruppenid = '$gruppenid' AND aktiv = '1' AND (datumaktuell BETWEEN $jahrbeginn AND $jahrende) ORDER BY datum ASC, bezeichnung ASC";
  if ($anfrage = $dbs->query($sql)) {
    while ($daten = $anfrage->fetch_assoc()) {
      $bezlink = str_replace(' ', '_', $daten['bezeichnung']);
      $blogcode .= "<li><a href=\"Website/Galerien/$jahr/".cms_monatsnamekomplett(date('n', $daten['datum'])).$CMS_TRENNUNG.$bezlink."\">".date('d.m.Y', $daten['datum'])." ".$daten['bezeichnung']."</a></li>";
    }
    $anfrage->free();
  }
  if (strlen($galeriecode) > 0) {$spalten++;}

  if (strlen($termincode) > 0) { $tabcode .= "<div class=\"cms_zugehoerig_spalte_a$spalten\"><div class=\"cms_zugehoerig_spalte_i\">".$termincode."</div></div>";}
  if (strlen($blogcode) > 0) {$tabcode .= "<div class=\"cms_zugehoerig_spalte_a$spalten\"><div class=\"cms_zugehoerig_spalte_i\"><h4>Blogeinträge</h4><ul>".$blogcode."</ul></div></div>";}
    if (strlen($galeriecode) > 0) {$tabcode .= "<div class=\"cms_zugehoerig_spalte_a$spalten\"><div class=\"cms_zugehoerig_spalte_i\"><ul>".$galeriecode."</ul></div></div>";}

  if (strlen($tabcode) > 0) {$code .= $tabcode;}
  else {
    $code .= "<div class=\"cms_zugehoerig_spalte_a1\"><div class=\"cms_zugehoerig_spalte_i\"><p class=\"cms_notiz\">In diesem sind Jahr keine zugehörigen Inhalte vorhanden.</p></div></div>";
  }

  $code .= "<div class=\"cms_clear\"></div>";

  return $code;
}

?>
